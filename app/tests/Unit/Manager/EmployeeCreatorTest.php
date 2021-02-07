<?php

namespace App\Tests\Unit\Manager;

use App\Entity\Employee as EmployeeEntity;
use App\Exception\ValidationException;
use App\Factory\EmployeeEntityFactoryInterface;
use App\Manager\EmployeeCreator;
use App\Manager\EmployeeCreatorInterface;
use App\Manager\EmployeeWriterInterface;
use Package\EmployeeDto\Employee as EmployeeDto;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class EmployeeCreatorTest extends TestCase
{
    /**
     * @var EmployeeWriterInterface|MockObject
     */
    private $writer;

    /**
     * @var EmployeeEntityFactoryInterface|MockObject
     */
    private $entityFactory;

    /**
     * @var EmployeeCreatorInterface
     */
    private $creator;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->writer = $this->getMockBuilder(EmployeeWriterInterface::class)->getMock();
        $this->entityFactory = $this->getMockBuilder(EmployeeEntityFactoryInterface::class)->getMock();

        $this->creator = new EmployeeCreator($this->writer, $this->entityFactory);
    }

    /**
     * @test
     */
    public function newEntityIsPassedToWriter(): void
    {
        $employeeEntity = new EmployeeEntity();
        $employeeDto = new EmployeeDto();

        $this->entityFactory->expects($this->once())
            ->method('create')
            ->willReturn($employeeEntity);

        $this->writer->expects($this->once())
            ->method('persistEmployee')
            ->with($employeeDto, $employeeEntity)
            ->willReturn($employeeDto);

        $result = $this->creator->createEmployee($employeeDto);

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

        $this->entityFactory->expects($this->once())
            ->method('create')
            ->willReturn($employeeEntity);

        $this->writer->expects($this->once())
            ->method('persistEmployee')
            ->with($employeeDto, $employeeEntity)
            ->willThrowException(new ValidationException());

        $this->creator->createEmployee($employeeDto);
    }
}
