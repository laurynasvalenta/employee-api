<?php

namespace App\Manager;

use App\Converter\Employee\EntityToDtoConverterInterface;
use App\Factory\EmployeeDtoFactoryInterface;
use App\Repository\EmployeeRepository;
use Package\EmployeeDto\EmployeeFilter;
use Package\EmployeeDto\EmployeeList;

class EmployeeReader implements EmployeeReaderInterface
{
    /**
     * @var EmployeeRepository
     */
    private $repository;

    /**
     * @var EntityToDtoConverterInterface
     */
    private $entityConverter;

    /**
     * @var EmployeeDtoFactoryInterface
     */
    private $dtoFactory;

    /**
     * @param EmployeeRepository $repository
     * @param EntityToDtoConverterInterface $entityConverter
     * @param EmployeeDtoFactoryInterface $dtoFactory
     */
    public function __construct(
        EmployeeRepository $repository,
        EntityToDtoConverterInterface $entityConverter,
        EmployeeDtoFactoryInterface $dtoFactory
    ) {
        $this->repository = $repository;
        $this->entityConverter = $entityConverter;
        $this->dtoFactory = $dtoFactory;
    }

    /**
     * @param EmployeeFilter $employeeFilter
     *
     * @return EmployeeList
     */
    public function findEmployees(EmployeeFilter $employeeFilter): EmployeeList
    {
        $result = new EmployeeList();

        $entities = $this->repository->findEmployees($employeeFilter);

        foreach ($entities as $entity) {
            $employeeDto = $this->dtoFactory->create();

            $this->entityConverter->convertToDto($entity, $employeeDto);

            $result->addEmployee($employeeDto);
        }

        return $result;
    }
}
