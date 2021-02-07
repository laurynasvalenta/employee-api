<?php

namespace App\Tests\Unit\Manager;

use App\Converter\Employee\DtoToEntityConverterInterface;
use App\Converter\Employee\EntityToDtoConverterInterface;
use App\Entity\Employee;
use App\Exception\ValidationException;
use App\Manager\EmployeeWriter;
use Doctrine\ORM\EntityManagerInterface;
use Package\EmployeeDto\Employee as EmployeeDto;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EmployeeWriterTest extends TestCase
{
    private const EXCEPTION_MESSAGE = 'message';
    private const MARKER = 'marker';

    /**
     * @var ValidatorInterface|MockObject
     */
    private $validator;

    /**
     * @var EntityToDtoConverterInterface|MockObject
     */
    private $entityConverter;

    /**
     * @var DtoToEntityConverterInterface|MockObject
     */
    private $dtoConverter;

    /**
     * @var EntityManagerInterface|MockObject
     */
    private $entityManager;

    /**
     * @var EmployeeWriter
     */
    private $writer;

    public function setUp(): void
    {
        parent::setUp();

        $this->validator = $this->getMockBuilder(ValidatorInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->entityConverter = $this->getMockBuilder(EntityToDtoConverterInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->dtoConverter = $this->getMockBuilder(DtoToEntityConverterInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->entityManager = $this->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->writer = new EmployeeWriter(
            $this->validator,
            $this->dtoConverter,
            $this->entityConverter,
            $this->entityManager
        );
    }

    /**
     * @test
     */
    public function dtoIsValidated(): void
    {
        $dto = new EmployeeDto();

        $this->validator->expects($this->once())
            ->method('validate')
            ->with($dto)
            ->willReturn(new ConstraintViolationList());

        $this->writer->persistEmployee($dto, new Employee());
    }

    /**
     * @test
     */
    public function exceptionIsRaisedIfValidationFails(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectErrorMessage(self::EXCEPTION_MESSAGE);

        $dto = new EmployeeDto();

        $violations = new ConstraintViolationList();
        $violations->add(new ConstraintViolation(self::EXCEPTION_MESSAGE, null, [], '', null, ''));

        $this->validator->expects($this->once())
            ->method('validate')
            ->with($dto)
            ->willReturn($violations);

        $this->entityManager->expects($this->never())
            ->method('persist');

        $this->entityManager->expects($this->never())
            ->method('flush');

        $this->writer->persistEmployee($dto, new Employee());
    }

    /**
     * @test
     */
    public function dtoIsConvertedAndPersisted(): void
    {
        $dto = new EmployeeDto();
        $entity = new Employee();

        $this->validator->expects($this->once())
            ->method('validate')
            ->with($dto)
            ->willReturn(new ConstraintViolationList());

        $this->dtoConverter->expects($this->once())
            ->method('convertToEntity')
            ->with($dto, $entity)
            ->willReturnCallback(function (EmployeeDto $passedDto, Employee $entity) use ($dto) {
                $this->assertSame($dto, $passedDto);

                $entity->setId(self::MARKER);
            });

        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($this->callback(function (Employee $employee) {
                return $employee->getId() === self::MARKER;
            }));

        $this->entityManager->expects($this->once())
            ->method('flush');

        $this->writer->persistEmployee($dto, $entity);
    }

    /**
     * @test
     */
    public function persistedEntityIsConvertedBackToDto(): void
    {
        $dto = new EmployeeDto();
        $entity = new Employee();

        $this->validator->expects($this->once())
            ->method('validate')
            ->with($dto)
            ->willReturn(new ConstraintViolationList());

        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($entity)
            ->willReturnCallback(function (Employee $employee) {
                $employee->setId(self::MARKER);
            });

        $this->entityConverter->expects($this->once())
            ->method('convertToDto')
            ->with($this->callback(function (Employee $employee) {
                return $employee->getId() === self::MARKER;
            }), $dto);

        $resultingDto = $this->writer->persistEmployee($dto, $entity);

        $this->assertSame($resultingDto, $dto);
    }
}
