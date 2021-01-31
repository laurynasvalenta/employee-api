<?php

namespace App\Tests\Unit\Converter\Employee;

use App\Converter\Employee\DtoToEntityConverter;
use App\Converter\Employee\DtoToEntityConverterInterface;
use App\Entity\Address;
use App\Entity\Employee as EmployeeEntity;
use App\Entity\Role;
use App\Repository\EmployeeRepository;
use App\Repository\RoleRepository;
use DateTime;
use Package\EmployeeDto\Employee as EmployeeDto;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class DtoToEntityConverterTest extends TestCase
{
    private const VALUE_FIRST_NAME = 'First name';
    private const VALUE_LAST_NAME = 'Last name';
    private const VALUE_BIRTH_DATE = '2020-12-12';
    private const VALUE_EMPLOYMENT_DATE = '2020-12-12';
    private const VALUE_ADDRESS1 = 'Address line 1';
    private const VALUE_ADDRESS2 = 'Address line 2';
    private const VALUE_ZIP = '00100';
    private const VALUE_CITY = 'Vilnius';
    private const VALUE_COUNTRY = 'LTU';
    private const VALUE_ADDRESS_ID = 'test-test';
    private const BOSS_ID = 'boss-id';
    private const VALUE_ROLE_NAME = 'CEO';
    const VALUE_ROLE_ID = 'id';

    /**
     * @var DtoToEntityConverterInterface
     */
    private $converter;

    /**
     * @var EmployeeRepository|MockObject
     */
    private $employeeRepository;

    /**
     * @var RoleRepository|MockObject
     */
    private $roleRepository;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->employeeRepository = $this->getMockBuilder(EmployeeRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->roleRepository = $this->getMockBuilder(RoleRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->converter = new DtoToEntityConverter($this->employeeRepository, $this->roleRepository);
    }

    /**
     * @test
     */
    public function emptyDtoIsConverted(): void
    {
        $expectedEmployeeEntity = new EmployeeEntity();
        $expectedEmployeeEntity->setFirstname('');
        $expectedEmployeeEntity->setLastname('');
        $expectedEmployeeEntity->setBirthdate(new DateTime());
        $expectedEmployeeEntity->setEmploymentDate(new DateTime());
        $expectedEmployeeEntity->setFirstname('');
        $expectedEmployeeEntity->setFirstname('');
        $expectedEmployeeEntity->setHomeAddress(new Address());
        $expectedEmployeeEntity->setRole(new Role());

        $employeeDto = new EmployeeDto();
        $employeeDto->setBirthdate(clone $expectedEmployeeEntity->getBirthdate());
        $employeeDto->setEmploymentDate(clone $expectedEmployeeEntity->getEmploymentDate());
        $employeeEntity = new EmployeeEntity();

        $this->converter->convertToEntity($employeeDto, $employeeEntity);

        $this->assertEquals($expectedEmployeeEntity, $employeeEntity);
    }

    /**
     * @test
     */
    public function simpleValuesAreTransferred(): void
    {
        $employeeDto = new EmployeeDto();
        $employeeDto->setFirstname(self::VALUE_FIRST_NAME);
        $employeeDto->setLastname(self::VALUE_LAST_NAME);
        $employeeDto->setBirthdate((new DateTime(self::VALUE_BIRTH_DATE))->setTime(0, 0, 0));
        $employeeDto->setEmploymentDate((new DateTime(self::VALUE_EMPLOYMENT_DATE))->setTime(0, 0, 0));
        $employeeDto->setHomeAddressLine1(self::VALUE_ADDRESS1);
        $employeeDto->setHomeAddressLine2(self::VALUE_ADDRESS2);
        $employeeDto->setHomeAddressZip(self::VALUE_ZIP);
        $employeeDto->setHomeAddressCity(self::VALUE_CITY);
        $employeeDto->setHomeAddressCountry(self::VALUE_COUNTRY);

        $expectedAddressEntity = new Address();
        $expectedAddressEntity->setLine1(self::VALUE_ADDRESS1);
        $expectedAddressEntity->setLine2(self::VALUE_ADDRESS2);
        $expectedAddressEntity->setZip(self::VALUE_ZIP);
        $expectedAddressEntity->setCity(self::VALUE_CITY);
        $expectedAddressEntity->setCountry(self::VALUE_COUNTRY);

        $expectedEmployeeEntity = new EmployeeEntity();
        $expectedEmployeeEntity->setFirstname(self::VALUE_FIRST_NAME);
        $expectedEmployeeEntity->setLastname(self::VALUE_LAST_NAME);
        $expectedEmployeeEntity->setBirthdate((new DateTime(self::VALUE_BIRTH_DATE))->setTime(0, 0, 0));
        $expectedEmployeeEntity->setEmploymentDate((new DateTime(self::VALUE_EMPLOYMENT_DATE))->setTime(0, 0, 0));
        $expectedEmployeeEntity->setHomeAddress($expectedAddressEntity);
        $expectedEmployeeEntity->setRole(new Role());

        $employeeEntity = new EmployeeEntity();

        $this->converter->convertToEntity($employeeDto, $employeeEntity);

        $this->assertEquals($expectedEmployeeEntity, $employeeEntity);
    }

    /**
     * @test
     */
    public function homeAddressIdIsPreserved(): void
    {
        $employeeAddress = new Address();
        $employeeAddress->setId(self::VALUE_ADDRESS_ID);

        $employeeEntity = new EmployeeEntity();
        $employeeEntity->setHomeAddress($employeeAddress);

        $employeeDto = new EmployeeDto();
        $employeeDto->setHomeAddressLine1(self::VALUE_ADDRESS1);
        $employeeDto->setHomeAddressLine2(self::VALUE_ADDRESS2);
        $employeeDto->setHomeAddressZip(self::VALUE_ZIP);
        $employeeDto->setHomeAddressCity(self::VALUE_CITY);
        $employeeDto->setHomeAddressCountry(self::VALUE_COUNTRY);

        $this->converter->convertToEntity($employeeDto, $employeeEntity);

        $resultEmployeeAddress = $employeeEntity->getHomeAddress();

        $this->assertEquals(self::VALUE_ADDRESS_ID, $resultEmployeeAddress->getId());
        $this->assertEquals(self::VALUE_ADDRESS1, $resultEmployeeAddress->getLine1());
        $this->assertEquals(self::VALUE_ADDRESS2, $resultEmployeeAddress->getLine2());
        $this->assertEquals(self::VALUE_ZIP, $resultEmployeeAddress->getZip());
        $this->assertEquals(self::VALUE_CITY, $resultEmployeeAddress->getCity());
        $this->assertEquals(self::VALUE_COUNTRY, $resultEmployeeAddress->getCountry());
    }

    /**
     * @test
     */
    public function bossEntityIsAssignedBasedOnId(): void
    {
        $addressEntity = new Address();
        $addressEntity->setLine1(self::VALUE_ADDRESS1);
        $addressEntity->setLine2(self::VALUE_ADDRESS2);
        $addressEntity->setZip(self::VALUE_ZIP);
        $addressEntity->setCity(self::VALUE_CITY);
        $addressEntity->setCountry(self::VALUE_COUNTRY);

        $bossEntity = new EmployeeEntity();
        $bossEntity->setFirstname(self::VALUE_FIRST_NAME);
        $bossEntity->setLastname(self::VALUE_LAST_NAME);
        $bossEntity->setBirthdate((new DateTime(self::VALUE_BIRTH_DATE))->setTime(0, 0, 0));
        $bossEntity->setEmploymentDate((new DateTime(self::VALUE_EMPLOYMENT_DATE))->setTime(0, 0, 0));
        $bossEntity->setHomeAddress($addressEntity);
        $bossEntity->setRole(new Role());

        $this->employeeRepository->expects($this->once())
            ->method('findEmployeeById')
            ->with(self::BOSS_ID)
            ->willReturn($bossEntity);

        $employeeDto = new EmployeeDto();
        $employeeDto->setBossId(self::BOSS_ID);

        $employeeEntity = new EmployeeEntity();

        $this->converter->convertToEntity($employeeDto, $employeeEntity);

        $this->assertSame($bossEntity, $employeeEntity->getBoss());
    }

    /**
     * @test
     */
    public function existingRoleIsAssignedIfNameMatches(): void
    {
        $role = new Role();
        $role->setId(self::VALUE_ROLE_ID);
        $role->setName(self::VALUE_ROLE_NAME);

        $this->roleRepository->expects($this->once())
            ->method('findRoleByName')
            ->with(self::VALUE_ROLE_NAME)
            ->willReturn($role);

        $employeeDto = new EmployeeDto();
        $employeeDto->setRoleName(self::VALUE_ROLE_NAME);

        $employeeEntity = new EmployeeEntity();

        $this->converter->convertToEntity($employeeDto, $employeeEntity);

        $this->assertSame($role, $employeeEntity->getRole());
    }

    /**
     * @test
     */
    public function newRoleIsCreatedEntityIfDoesNotExistYet(): void
    {
        $this->roleRepository->expects($this->once())
            ->method('findRoleByName')
            ->with(self::VALUE_ROLE_NAME)
            ->willReturn(null);

        $employeeDto = new EmployeeDto();
        $employeeDto->setRoleName(self::VALUE_ROLE_NAME);

        $employeeEntity = new EmployeeEntity();

        $this->converter->convertToEntity($employeeDto, $employeeEntity);

        $this->assertEmpty($employeeEntity->getRole()->getId());
        $this->assertSame(self::VALUE_ROLE_NAME, $employeeEntity->getRole()->getName());
    }
}
