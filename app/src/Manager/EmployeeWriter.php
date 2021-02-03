<?php

namespace App\Manager;

use App\Converter\Employee\DtoToEntityConverterInterface;
use App\Converter\Employee\EntityToDtoConverterInterface;
use App\Entity\Employee as EmployeeEntity;
use App\Exception\ValidationException;
use App\Factory\EmployeeEntityFactoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Package\EmployeeDto\Employee as EmployeeDto;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EmployeeWriter implements EmployeeWriterInterface
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var DtoToEntityConverterInterface
     */
    private $dtoConverter;

    /**
     * @var EntityToDtoConverterInterface
     */
    private $entityConverter;

    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @var EmployeeEntityFactoryInterface
     */
    private $entityFactory;

    /**
     * @param ValidatorInterface $validator
     * @param DtoToEntityConverterInterface $dtoConverter
     * @param EntityToDtoConverterInterface $entityConverter
     * @param EntityManagerInterface $manager
     * @param EmployeeEntityFactoryInterface $entityFactory
     */
    public function __construct(
        ValidatorInterface $validator,
        DtoToEntityConverterInterface $dtoConverter,
        EntityToDtoConverterInterface $entityConverter,
        EntityManagerInterface $manager,
        EmployeeEntityFactoryInterface $entityFactory
    ) {
        $this->validator = $validator;
        $this->dtoConverter = $dtoConverter;
        $this->entityConverter = $entityConverter;
        $this->manager = $manager;
        $this->entityFactory = $entityFactory;
    }

    /**
     * @inheritDoc
     */
    public function createEmployee(EmployeeDto $employeeDto): EmployeeDto
    {
        $errors = $this->validator->validate($employeeDto);

        if ($errors->count() > 0) {
            throw new ValidationException($errors->get(0)->getMessage());
        }

        $employeeEntity = $this->entityFactory->create();

        $this->dtoConverter->convertToEntity($employeeDto, $employeeEntity);

        $this->manager->persist($employeeEntity);
        $this->manager->flush();

        $this->entityConverter->convertToDto($employeeEntity, $employeeDto);

        return $employeeDto;
    }
}
