<?php

namespace App\Converter\Employee;

use App\Entity\Employee as EmployeeEntity;
use App\Entity\Role;
use App\Repository\EmployeeRepository;
use App\Repository\RoleRepository;
use DateTime;
use Package\EmployeeDto\Employee as EmployeeDto;

class DtoToEntityConverter implements DtoToEntityConverterInterface
{
    /**
     * @var EmployeeRepository
     */
    private $employeeRepository;

    /**
     * @var RoleRepository
     */
    private $roleRepository;

    /**
     * @param EmployeeRepository $employeeRepository
     * @param RoleRepository $roleRepository
     */
    public function __construct(EmployeeRepository $employeeRepository, RoleRepository $roleRepository)
    {
        $this->employeeRepository = $employeeRepository;
        $this->roleRepository = $roleRepository;
    }

    /**
     * @inheritDoc
     */
    public function convertToEntity(EmployeeDto $employeeDto, EmployeeEntity $employeeEntity): void
    {
        $employeeEntity->setFirstname($employeeDto->getFirstname() ?? '');
        $employeeEntity->setLastname($employeeDto->getLastname() ?? '');
        $employeeEntity->setBirthdate(clone ($employeeDto->getBirthdate() ?? new DateTime()));
        $employeeEntity->setEmploymentDate(clone ($employeeDto->getEmploymentDate() ?? new DateTime()));

        if ($employeeDto->getBossId() !== null) {
            $employeeEntity->setBoss($this->employeeRepository->findEmployeeById($employeeDto->getBossId()));
        }

        $address = $employeeEntity->getHomeAddress();

        $address->setLine1($employeeDto->getHomeAddressLine1() ?? '');
        $address->setLine2($employeeDto->getHomeAddressLine2() ?? '');
        $address->setZip($employeeDto->getHomeAddressZip() ?? '');
        $address->setCity($employeeDto->getHomeAddressCity() ?? '');
        $address->setCountry($employeeDto->getHomeAddressCountry() ?? '');

        if ($employeeDto->getRoleName() !== null) {
            $role = $this->roleRepository->findRoleByName($employeeDto->getRoleName());

            if ($role === null) {
                $employeeEntity->getRole()->setName($employeeDto->getRoleName());
            } else {
                $employeeEntity->setRole($role);
            }
        }
    }
}
