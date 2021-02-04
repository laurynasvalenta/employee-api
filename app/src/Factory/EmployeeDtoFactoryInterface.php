<?php

namespace App\Factory;

use Package\EmployeeDto\Employee;

interface EmployeeDtoFactoryInterface
{
    /**
     * @return Employee
     */
    public function create(): Employee;
}
