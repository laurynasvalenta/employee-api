<?php

namespace App\Manager;

use App\Exception\NotFoundException;
use App\Exception\ValidationException;
use Package\EmployeeDto\Employee;

interface EmployeeUpdaterInterface
{
    /**
     * @param Employee $employeeDto
     *
     * @return Employee
     *
     * @throws NotFoundException
     * @throws ValidationException
     */
    public function updateEmployee(Employee $employeeDto): Employee;
}
