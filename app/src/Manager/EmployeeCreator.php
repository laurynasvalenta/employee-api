<?php

namespace App\Manager;

use App\Factory\EmployeeEntityFactoryInterface;
use Package\EmployeeDto\Employee;

class EmployeeCreator implements EmployeeCreatorInterface
{
    /**
     * @var EmployeeWriterInterface
     */
    private $writer;

    /**
     * @var EmployeeEntityFactoryInterface
     */
    private $entityFactory;

    /**
     * @param EmployeeWriterInterface $writer
     * @param EmployeeEntityFactoryInterface $entityFactory
     */
    public function __construct(EmployeeWriterInterface $writer, EmployeeEntityFactoryInterface $entityFactory)
    {
        $this->writer = $writer;
        $this->entityFactory = $entityFactory;
    }

    /**
     * @inheritDoc
     */
    public function createEmployee(Employee $employeeDto): Employee
    {
        $employeeEntity = $this->entityFactory->create();

        return $this->writer->persistEmployee($employeeDto, $employeeEntity);
    }
}
