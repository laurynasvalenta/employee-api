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

    /**
     * @param Employee[] $employees
     */
    public function setEmployees(array $employees): void
    {
        $this->employees = $employees;
    }

    /**
     * @return Employee|null
     */
    public function getFirst(): ?Employee
    {
        if (isset($this->employees[0])) {
            return $this->employees[0];
        }

        return null;
    }
}
