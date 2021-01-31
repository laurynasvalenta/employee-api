<?php

namespace Package\EmployeeDto;

class EmployeeList
{
    /**
     * @var Employee[]
     */
    private $employees = [];

    /**
     * @return Employee[]
     */
    public function getEmployees(): array
    {
        return $this->employees;
    }

    /**
     * @param Employee $employee
     */
    public function addEmployee(Employee $employee): void
    {
        $this->employees[] = $employee;
    }
}
