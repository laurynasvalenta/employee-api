<?php

namespace App\Factory;

use App\Entity\Employee;

class EmployeeEntityFactory implements EmployeeEntityFactoryInterface
{
    /**
     * @inheritDoc
     */
    public function create(): Employee
    {
        return new Employee();
    }
}
