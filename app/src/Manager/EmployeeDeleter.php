<?php

namespace App\Manager;

use App\Exception\NotFoundException;
use App\Exception\ValidationException;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Package\EmployeeDto\EmployeeFilter;

class EmployeeDeleter implements EmployeeDeleterInterface
{
    /**
     * @var EmployeeReaderInterface
     */
    private $reader;

    /**
     * @var EmployeeRepository
     */
    private $repository;

    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @param EmployeeReaderInterface $reader
     * @param EmployeeRepository $repository
     * @param EntityManagerInterface $manager
     */
    public function __construct(
        EmployeeReaderInterface $reader,
        EmployeeRepository $repository,
        EntityManagerInterface $manager
    ) {
        $this->reader = $reader;
        $this->repository = $repository;
        $this->manager = $manager;
    }

    /**
     * @inheritDoc
     */
    public function deleteEmployee(string $employeeId): void
    {
        $employeeEntity = $this->repository->findEmployeeById($employeeId);

        if ($employeeEntity === null) {
            throw new NotFoundException();
        }

        if ($this->hasAnySubordinates($employeeId)) {
            throw new ValidationException('Cannot delete an employee who has subordinates.');
        }

        $employeeEntity->setIsDeleted(true);
        $this->manager->persist($employeeEntity);
        $this->manager->flush();
    }

    /**
     * @param string $employeeId
     *
     * @return bool
     */
    private function hasAnySubordinates(string $employeeId): bool
    {
        $filter = new EmployeeFilter();
        $filter->setBossId($employeeId);

        return $this->reader->findEmployees($filter)->getFirst() !== null;
    }
}
