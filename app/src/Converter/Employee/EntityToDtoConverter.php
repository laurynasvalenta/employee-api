<?php

namespace App\Converter\Employee;

use App\Entity\Employee as EmployeeEntity;
use Package\EmployeeDto\Employee as EmployeeDto;

class EntityToDtoConverter implements EntityToDtoConverterInterface
{
    /**
     * @param EmployeeEntity $employeeEntity
     * @param EmployeeDto $employeeDto
     */
    public function convertToDto(EmployeeEntity $employeeEntity, EmployeeDto $employeeDto): void
    {
        $employeeDto->setId((string)$employeeEntity->getId());
        $employeeDto->setFirstname((string)$employeeEntity->getFirstname());
        $employeeDto->setLastname((string)$employeeEntity->getLastname());
        $employeeDto->setBirthdate(clone $employeeEntity->getBirthdate());
        $employeeDto->setEmploymentDate(clone $employeeEntity->getEmploymentDate());
        $employeeDto->setHomeAddressLine1((string)$employeeEntity->getHomeAddress()->getLine1());
        $employeeDto->setHomeAddressLine2((string)$employeeEntity->getHomeAddress()->getLine2());
        $employeeDto->setHomeAddressZip((string)$employeeEntity->getHomeAddress()->getZip());
        $employeeDto->setHomeAddressCity((string)$employeeEntity->getHomeAddress()->getCity());
        $employeeDto->setHomeAddressCountry((string)$employeeEntity->getHomeAddress()->getCountry());
        $employeeDto->setRoleName((string)$employeeEntity->getRole()->getName());

        if ($employeeEntity->getBoss() !== null) {
            $employeeDto->setBossId($employeeEntity->getBoss()->getId());
        }
    }
}
