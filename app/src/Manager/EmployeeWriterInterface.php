<?php

namespace App\Manager;

use App\Exception\ValidationException;
use Package\EmployeeDto\Employee;

interface EmployeeWriterInterface
{
    /**
     * @param Employee $employeeDto
     *
     * @return Employee
     *
     * @throws ValidationException
     */
    public function createEmployee(Employee $employeeDto): Employee;
}
