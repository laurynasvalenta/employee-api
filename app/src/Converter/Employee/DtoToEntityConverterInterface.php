<?php

namespace App\Converter\Employee;

use App\Entity\Employee as EmployeeEntity;
use Package\EmployeeDto\Employee as EmployeeDto;

interface DtoToEntityConverterInterface
{
    /**
     * @param EmployeeDto $employeeDto
     * @param EmployeeEntity $employeeEntity
     */
    public function convertToEntity(EmployeeDto $employeeDto, EmployeeEntity $employeeEntity): void;
}
