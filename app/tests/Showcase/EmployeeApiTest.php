<?php

namespace App\Tests\Showcase;

use DateTime;
use Faker\Factory;
use Faker\Generator;
use Package\EmployeeApiClientBundle\EmployeeApiClientInterface;
use Package\EmployeeApiClientBundle\Exception\EmployeeNotFoundException;
use Package\EmployeeApiClientBundle\Exception\ValidationFailedException;
use Package\EmployeeDto\Employee;
use Package\EmployeeDto\EmployeeFilter;
use Package\EmployeeDto\EmployeeList;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EmployeeApiTest extends WebTestCase
{
    private const TEST_ROLE_NAME = 'CFO';
    private const TEST_ROLE_NAME2 = 'CTO';
    private const ROLE_CEO = 'CEO';
    private const NON_EXISTING_ID = '103e071b-037e-4fad-8f4a-5bd370df49e4';
    private const TEST_VALUE_FIRSTNAME = 'Firstname';
    private const TEST_VALUE_LASTNAME = 'Lastname';

    /**
     * @var EmployeeApiClientInterface
     */
    private $employeeApiClient;

    /**
     * @var Generator
     */
    private $faker;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        self::bootKernel();

        $container = self::$kernel->getContainer();

        $this->employeeApiClient = $container->get(EmployeeApiClientInterface::class);
        $this->faker = Factory::create();
    }

    /**
     * @test
     */
    public function employeeCanBeCreated(): void
    {
        $employeeToCreate = $this->buildFakeEmployee();

        $createdEmployee = $this->employeeApiClient->createEmployee($employeeToCreate);

        $foundEmployee = $this->employeeApiClient->getEmployee($createdEmployee->getId());

        $this->assertEquals($createdEmployee, $foundEmployee);
    }

    /**
     * @test
     */
    public function notFoundExceptionRaised(): void
    {
        $this->expectException(EmployeeNotFoundException::class);

        $this->employeeApiClient->getEmployee(self::NON_EXISTING_ID);
    }

    /**
     * @param EmployeeFilter $filter
     * @param bool $isMatchingFilter
     *
     * @dataProvider providerListFilteringWorksAsExpected
     * @test
     */
    public function listFilteringWorksAsExpected(EmployeeFilter $filter, bool $isMatchingFilter): void
    {
        $employeeToCreate = $this->buildFakeEmployee();

        $employeeToCreate->setFirstname(self::TEST_VALUE_FIRSTNAME);
        $employeeToCreate->setLastname(self::TEST_VALUE_LASTNAME);

        $createdEmployee = $this->employeeApiClient->createEmployee($employeeToCreate);
        $matchingEmployees = $this->employeeApiClient->findEmployees($filter);

        $employeeFound = $this->isEmployeeInTheList($createdEmployee, $matchingEmployees);

        $this->assertEquals($isMatchingFilter, $employeeFound);
    }

    /**
     * @test
     */
    public function employeeIsUpdated(): void
    {
        $newBoss = $this->employeeApiClient->createEmployee($this->buildFakeEmployee());

        $employeeToCreate = $this->buildFakeEmployee();
        $employeeToUpdate = $this->employeeApiClient->createEmployee($employeeToCreate);

        $employeeToUpdate->setFirstname($employeeToCreate->getFirstname() . '-');
        $employeeToUpdate->setLastname($employeeToCreate->getLastname() . '-');
        $employeeToUpdate->setBossId($newBoss->getId());
        $employeeToUpdate->setRoleName(self::TEST_ROLE_NAME2);
        $employeeToUpdate->setEmploymentDate((new DateTime('yesterday'))->setTime(0, 0, 0));
        $employeeToUpdate->setBirthdate((new DateTime())->setDate(1990, 3, 11)->setTime(0, 0, 0));
        $employeeToUpdate->setHomeAddressLine1($employeeToUpdate->getHomeAddressLine1() . '-');
        $employeeToUpdate->setHomeAddressLine2($employeeToUpdate->getHomeAddressLine2() . '-');
        $employeeToUpdate->setHomeAddressZip(substr($this->faker->postcode, 0, 5));
        $employeeToUpdate->setHomeAddressCity($employeeToUpdate->getHomeAddressCity() . '-');
        $employeeToUpdate->setHomeAddressCountry($this->faker->countryISOAlpha3);

        $this->employeeApiClient->updateEmployee($employeeToUpdate);

        $foundEmployee = $this->employeeApiClient->getEmployee($employeeToUpdate->getId());

        $this->assertEquals($employeeToUpdate, $foundEmployee);
    }

    /**
     * @test
     */
    public function creatingSecondCeoFails(): void
    {
        $this->expectException(ValidationFailedException::class);

        $this->provideCeoId();

        $duplicatedCeo = $this->buildFakeEmployee(false);
        $duplicatedCeo->setRoleName(self::ROLE_CEO);

        $this->employeeApiClient->createEmployee($duplicatedCeo);
    }

    /**
     * @param Employee $faultyEmployee
     *
     * @dataProvider providerBasicValidationIsTriggered
     * @test
     */
    public function basicValidationIsTriggered(Employee $faultyEmployee): void
    {
        $this->expectException(ValidationFailedException::class);

        $this->employeeApiClient->createEmployee($faultyEmployee);
    }

    /**
     * @test
     */
    public function employeeCanBeDeleted(): void
    {
        $employeeToCreate = $this->buildFakeEmployee();
        $createdEmployee = $this->employeeApiClient->createEmployee($employeeToCreate);
        $this->employeeApiClient->deleteEmployee($createdEmployee->getId());

        $filter = new EmployeeFilter();
        $filter->setFirstname($employeeToCreate->getFirstname());
        $filter->setLastname($employeeToCreate->getLastname());

        $foundEmployees = $this->employeeApiClient->findEmployees($filter);

        $this->assertFalse($this->isEmployeeInTheList($createdEmployee, $foundEmployees));
    }

    /**
     * @test
     */
    public function someonesBossCannotBeDeleted(): void
    {
        $this->expectException(ValidationFailedException::class);

        $bossToCreate = $this->buildFakeEmployee();
        $createdBoss = $this->employeeApiClient->createEmployee($bossToCreate);

        $employeeToCreate = $this->buildFakeEmployee();
        $employeeToCreate->setBossId($createdBoss->getId());
        $this->employeeApiClient->createEmployee($employeeToCreate);

        $this->employeeApiClient->deleteEmployee($createdBoss->getId());
    }

    /**
     * @test
     */
    public function cannotCreateAnEmployeeWithoutABoss(): void
    {
        $this->expectException(ValidationFailedException::class);

        $bossToCreate = $this->buildFakeEmployee(false);
        $this->employeeApiClient->createEmployee($bossToCreate);
    }

    /**
     * @return array
     */
    public function providerListFilteringWorksAsExpected(): array
    {
        $this->setUp();

        $matchingFilter = new EmployeeFilter();
        $matchingFilter->setFirstname(self::TEST_VALUE_FIRSTNAME);
        $matchingFilter->setLastname(self::TEST_VALUE_LASTNAME);
        $matchingFilter->setRole(self::TEST_ROLE_NAME);
        $matchingFilter->setBossId($this->provideCeoId());
        $matchingFilter->setBirthdateFrom((new DateTime())->setDate(1994, 5, 10));
        $matchingFilter->setBirthdateTo((new DateTime())->setDate(1994, 5, 11));

        $filter1 = clone $matchingFilter;
        $filter1->setFirstname('not matching');

        $filter2 = clone $matchingFilter;
        $filter2->setLastname('not matching');

        $filter3 = clone $matchingFilter;
        $filter3->setRole(self::ROLE_CEO);

        $filter4 = clone $matchingFilter;
        $filter4->setBossId(self::NON_EXISTING_ID);

        $filter5 = clone $matchingFilter;
        $filter5->setBirthdateFrom((new DateTime())->setDate(1994, 5, 11));

        $filter6 = clone $matchingFilter;
        $filter6->setBirthdateTo((new DateTime())->setDate(1994, 5, 10));

        return [
            [new EmployeeFilter(), true],
            [$matchingFilter, true],
            [$filter1, false],
            [$filter2, false],
            [$filter3, false],
            [$filter4, false],
            [$filter5, false],
            [$filter6, false],
        ];
    }

    /**
     * @return array
     */
    public function providerBasicValidationIsTriggered(): array
    {
        $this->setUp();

        $item1 = $this->buildFakeEmployee();
        $item1->setFirstname(str_pad('-', 51));

        $item2 = $this->buildFakeEmployee();
        $item2->setLastname(str_pad('-', 51));

        $item3 = $this->buildFakeEmployee();
        $item3->setFirstname('same value');
        $item3->setLastname('same value');

        $item4 = $this->buildFakeEmployee();
        $item4->setBirthdate(new DateTime('-17 year 11 month 29 day'));

        $item5 = $this->buildFakeEmployee();
        $item5->setBirthdate(new DateTime('tomorrow'));

        return [
            [$item1],
            [$item2],
            [$item3],
            [$item4],
            [$item5],
        ];
    }

    /**
     * @param bool $includeBossId
     *
     * @return Employee
     */
    private function buildFakeEmployee(bool $includeBossId = true): Employee
    {
        $result = new Employee();
        $result->setFirstname($this->faker->firstName);
        $result->setLastname($this->faker->lastName);
        $result->setBirthdate((new DateTime())->setDate(1994, 5, 10));
        $result->setEmploymentDate(new DateTime());
        $result->setHomeAddressLine1($this->faker->streetAddress);
        $result->setHomeAddressLine2($this->faker->secondaryAddress);
        $result->setHomeAddressZip(substr($this->faker->postcode, 0, 5));
        $result->setHomeAddressCity($this->faker->city);
        $result->setHomeAddressCountry($this->faker->countryISOAlpha3);
        $result->setRoleName(self::TEST_ROLE_NAME);

        if ($includeBossId) {
            $result->setBossId($this->provideCeoId());
        }

        return $result;
    }

    /**
     * @return string
     */
    private function provideCeoId(): string
    {
        $filter = new EmployeeFilter();
        $filter->setRole(self::ROLE_CEO);

        $employees = $this->employeeApiClient->findEmployees($filter);

        if ($employees->getFirst() !== null) {
            return $employees->getFirst()->getId();
        }

        $employee = $this->buildFakeEmployee(false);
        $employee->setRoleName(self::ROLE_CEO);

        return $this->employeeApiClient->createEmployee($employee)->getId();
    }

    /**
     * @param Employee $employeeToFind
     * @param EmployeeList $list
     *
     * @return bool
     */
    private function isEmployeeInTheList(Employee $employeeToFind, EmployeeList $list): bool
    {
        foreach ($list->getEmployees() as $employee) {
            if ($employee->getId() === $employeeToFind->getId()) {
                return true;
            }
        }

        return false;
    }
}
