<?php

namespace App\Factory;

use App\Entity\Employee;

interface EmployeeEntityFactoryInterface
{
    /**
     * @return Employee
     */
    public function create(): Employee;
}
