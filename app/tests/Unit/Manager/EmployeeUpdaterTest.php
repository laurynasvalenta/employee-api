<?php

namespace App\Tests\Unit\Manager;

use App\Entity\Employee as EmployeeEntity;
use App\Exception\NotFoundException;
use App\Exception\ValidationException;
use App\Manager\EmployeeUpdater;
use App\Manager\EmployeeUpdaterInterface;
use App\Manager\EmployeeWriterInterface;
use App\Repository\EmployeeRepository;
use Package\EmployeeDto\Employee as EmployeeDto;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class EmployeeUpdaterTest extends TestCase
{
    private const VALUE_ID = 'test-id';

    /**
     * @var EmployeeWriterInterface|MockObject
     */
    private $writer;

    /**
     * @var EmployeeRepository|MockObject
     */
    private $repository;

    /**
     * @var EmployeeUpdaterInterface
     *
     */
    private $updater;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->writer = $this->getMockBuilder(EmployeeWriterInterface::class)->getMock();
        $this->repository = $this->getMockBuilder(EmployeeRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->updater = new EmployeeUpdater($this->writer, $this->repository);
    }

    /**
     * @test
     */
    public function entityIsPassedToWriter(): void
    {
        $employeeEntity = new EmployeeEntity();
        $employeeDto = new EmployeeDto();
        $employeeDto->setId(self::VALUE_ID);

        $this->repository->expects($this->once())
            ->method('findEmployeeById')
            ->with(self::VALUE_ID)
            ->willReturn($employeeEntity);

        $this->writer->expects($this->once())
            ->method('persistEmployee')
            ->with($employeeDto, $employeeEntity)
            ->willReturn($employeeDto);

        $result = $this->updater->updateEmployee($employeeDto);

        $this->assertEquals($employeeDto, $result);
    }

    /**
     * @test
     */
    public function validationExceptionIsRaised(): void
    {
        $this->expectException(ValidationException::class);

        $employeeEntity = new EmployeeEntity();
        $employeeDto = new EmployeeDto();

        $this->repository->expects($this->once())
            ->method('findEmployeeById')
            ->with('')
            ->willReturn($employeeEntity);

        $this->writer->expects($this->once())
            ->method('persistEmployee')
            ->with($employeeDto, $employeeEntity)
            ->willThrowException(new ValidationException());

        $this->updater->updateEmployee($employeeDto);
    }

    /**
     * @test
     */
    public function notFoundExceptionIsRaised(): void
    {
        $this->expectException(NotFoundException::class);

        $employeeDto = new EmployeeDto();

        $this->repository->expects($this->once())
            ->method('findEmployeeById')
            ->with('')
            ->willReturn(null);

        $this->writer->expects($this->never())->method('persistEmployee');

        $this->updater->updateEmployee($employeeDto);
    }
}
