<?php

namespace App\Manager;

use Package\EmployeeDto\EmployeeFilter;
use Package\EmployeeDto\EmployeeList;

interface EmployeeReaderInterface
{
    /**
     * @param EmployeeFilter $employeeFilter
     *
     * @return EmployeeList
     */
    public function findEmployees(EmployeeFilter $employeeFilter): EmployeeList;
}
