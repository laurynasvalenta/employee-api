<?php

namespace App\Factory;

use Package\EmployeeDto\Employee;

class EmployeeDtoFactory implements EmployeeDtoFactoryInterface
{
    /**
     * @inheritDoc
     */
    public function create(): Employee
    {
        return new Employee();
    }
}
