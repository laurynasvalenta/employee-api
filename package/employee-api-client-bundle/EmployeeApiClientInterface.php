<?php

namespace Package\EmployeeApiClientBundle;

use Package\EmployeeApiClientBundle\Exception\EmployeeRequestFailedException;
use Package\EmployeeDto\Employee;
use Package\EmployeeDto\EmployeeFilter;
use Package\EmployeeDto\EmployeeList;

interface EmployeeApiClientInterface
{
    /**
     * @param Employee $employee
     *
     * @return Employee
     */
    public function createEmployee(Employee $employee): Employee;

    /**
     * @param Employee $employee
     *
     * @return Employee
     */
    public function updateEmployee(Employee $employee): Employee;

    /**
     * @param string $id
     *
     * @return Employee
     * @throws EmployeeRequestFailedException
     *
     */
    public function getEmployee(string $id): Employee;

    /**
     * @param EmployeeFilter|null $filter
     *
     * @return EmployeeList
     */
    public function findEmployees(EmployeeFilter $filter = null): EmployeeList;

    /**
     * @param string $id
     *
     * @throws EmployeeRequestFailedException
     */
    public function deleteEmployee(string $id): void;
}
