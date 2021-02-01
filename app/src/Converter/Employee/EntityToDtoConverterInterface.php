<?php

namespace App\Converter\Employee;

use App\Entity\Employee as EmployeeEntity;
use Package\EmployeeDto\Employee as EmployeeDto;

interface EntityToDtoConverterInterface
{
    /**
     * @param EmployeeEntity $employeeEntity
     * @param EmployeeDto $employeeDto
     */
    public function convertToDto(EmployeeEntity $employeeEntity, EmployeeDto $employeeDto): void;
}
