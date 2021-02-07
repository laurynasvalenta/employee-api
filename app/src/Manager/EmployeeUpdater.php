<?php

namespace App\Manager;

use App\Exception\NotFoundException;
use App\Repository\EmployeeRepository;
use Package\EmployeeDto\Employee;

class EmployeeUpdater implements EmployeeUpdaterInterface
{
    /**
     * @var EmployeeWriterInterface
     */
    private $writer;

    /**
     * @var EmployeeRepository
     */
    private $repository;

    /**
     * @param EmployeeWriterInterface $writer
     * @param EmployeeRepository $repository
     */
    public function __construct(EmployeeWriterInterface $writer, EmployeeRepository $repository)
    {
        $this->writer = $writer;
        $this->repository = $repository;
    }

    /**
     * @inheritDoc
     */
    public function updateEmployee(Employee $employeeDto): Employee
    {
        $employeeEntity = $this->repository->findEmployeeById((string)$employeeDto->getId());

        if ($employeeEntity === null) {
            throw new NotFoundException();
        }

        return $this->writer->persistEmployee($employeeDto, $employeeEntity);
    }
}
