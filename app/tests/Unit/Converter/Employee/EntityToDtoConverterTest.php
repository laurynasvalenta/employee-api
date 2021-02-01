<?php

namespace App\Tests\Unit\Converter\Employee;

use App\Converter\Employee\EntityToDtoConverter;
use App\Entity\Address;
use App\Entity\Employee as EmployeeEntity;
use DateTime;
use Package\EmployeeDto\Employee as EmployeeDto;
use PHPUnit\Framework\TestCase;

class EntityToDtoConverterTest extends TestCase
{
    private const VALUE_ID = 'id-value';
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

    /**
     * @var EntityToDtoConverter
     */
    private $converter;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->converter = new EntityToDtoConverter();
    }

    /**
     * @test
     */
    public function emptyDtoIsConverted(): void
    {
        $employeeEntity = new EmployeeEntity();
        $employeeDto = new EmployeeDto();

        $expectedDto = new EmployeeDto();
        $expectedDto->setId('');
        $expectedDto->setFirstname('');
        $expectedDto->setLastname('');
        $expectedDto->setBirthdate(clone $employeeEntity->getBirthdate());
        $expectedDto->setEmploymentDate(clone $employeeEntity->getEmploymentDate());
        $expectedDto->setBossId(null);
        $expectedDto->setHomeAddressLine1('');
        $expectedDto->setHomeAddressLine2('');
        $expectedDto->setHomeAddressCity('');
        $expectedDto->setHomeAddressZip('');
        $expectedDto->setHomeAddressCountry('');
        $employeeDto->setRoleName('');

        $this->converter->convertToDto($employeeEntity, $employeeDto);

        $this->assertEquals($expectedDto, $employeeDto);
    }

    /**
     * @test
     */
    public function simpleDtoIsConverted(): void
    {
        $bossEntity = new EmployeeEntity();
        $bossEntity->setId(self::BOSS_ID);

        $employeeEntity = new EmployeeEntity();
        $employeeEntity->setId(self::VALUE_ID);
        $employeeEntity->setFirstname(self::VALUE_FIRST_NAME);
        $employeeEntity->setLastname(self::VALUE_LAST_NAME);
        $employeeEntity->setBirthdate((new DateTime(self::VALUE_BIRTH_DATE))->setTime(0, 0, 0));
        $employeeEntity->setEmploymentDate((new DateTime(self::VALUE_EMPLOYMENT_DATE))->setTime(0, 0, 0));
        $employeeEntity->setBoss($bossEntity);
        $employeeEntity->getHomeAddress()->setId(self::VALUE_ADDRESS_ID);
        $employeeEntity->getHomeAddress()->setLine1(self::VALUE_ADDRESS1);
        $employeeEntity->getHomeAddress()->setLine2(self::VALUE_ADDRESS2);
        $employeeEntity->getHomeAddress()->setCountry(self::VALUE_COUNTRY);
        $employeeEntity->getHomeAddress()->setCity(self::VALUE_CITY);
        $employeeEntity->getHomeAddress()->setZip(self::VALUE_ZIP);
        $employeeEntity->getRole()->setName(self::VALUE_ROLE_NAME);

        $employeeDto = new EmployeeDto();

        $expectedDto = new EmployeeDto();
        $expectedDto->setId(self::VALUE_ID);
        $expectedDto->setFirstname(self::VALUE_FIRST_NAME);
        $expectedDto->setLastname(self::VALUE_LAST_NAME);
        $expectedDto->setBirthdate(clone $employeeEntity->getBirthdate());
        $expectedDto->setEmploymentDate(clone $employeeEntity->getEmploymentDate());
        $expectedDto->setBossId(self::BOSS_ID);
        $expectedDto->setHomeAddressLine1(self::VALUE_ADDRESS1);
        $expectedDto->setHomeAddressLine2(self::VALUE_ADDRESS2);
        $expectedDto->setHomeAddressCity(self::VALUE_CITY);
        $expectedDto->setHomeAddressZip(self::VALUE_ZIP);
        $expectedDto->setHomeAddressCountry(self::VALUE_COUNTRY);
        $expectedDto->setRoleName(self::VALUE_ROLE_NAME);

        $this->converter->convertToDto($employeeEntity, $employeeDto);

        $this->assertEquals($expectedDto, $employeeDto);
    }
}
