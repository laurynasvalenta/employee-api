<?php

namespace App\Manager;

use App\Entity\Employee as EmployeeEntity;
use App\Exception\ValidationException;
use Package\EmployeeDto\Employee as EmployeeDto;

interface EmployeeWriterInterface
{
    /**
     * @param EmployeeDto $employeeDto
     * @param EmployeeEntity $employeeEntity
     *
     * @return EmployeeDto
     *
     * @throws ValidationException
     */
    public function persistEmployee(EmployeeDto $employeeDto, EmployeeEntity $employeeEntity): EmployeeDto;
}
