<?php

namespace App\Tests\Unit\ArgumentResolver;

use App\ArgumentResolver\EmployeeFilterResolver;
use DateTime;
use Package\EmployeeDto\EmployeeFilter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EmployeeFilterResolverTest extends TestCase
{
    private const ARG_NAME = 'anything';
    private const VALUE_ID = 'test-id';
    private const VALUE_BOSS_ID = 'test';
    private const VALUE_FIRST_NAME = 'first name';
    private const VALUE_LAST_NAME = 'last name';
    private const VALUE_BIRTHDATE_FROM = '1994-05-10';
    private const VALUE_BIRTHDATE_TO = '1994-06-10';
    private const VALUE_ROLE = 'CEO';

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var ArgumentValueResolverInterface
     */
    private $employeeFilterResolver;

    /**
     * @var ArgumentMetadata
     */
    private $argument;

    /**
     * @var ConstraintViolationList
     */
    private $violations;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->violations = new ConstraintViolationList();

        $this->validator = $this->getMockBuilder(ValidatorInterface::class)->getMock();
        $this->validator->expects($this->any())->method('validate')->willReturn($this->violations);
        $this->employeeFilterResolver = new EmployeeFilterResolver($this->validator);
        $this->argument = new ArgumentMetadata(self::ARG_NAME, EmployeeFilter::class, false, false, null);
    }

    /**
     * @param string $type
     * @param bool $expectedResult
     *
     * @dataProvider providerSupportsOnlyExpectedTypes
     * @test
     */
    public function supportsOnlyExpectedTypes(string $type, bool $expectedResult): void
    {
        $argument = new ArgumentMetadata(
            self::ARG_NAME,
            $type,
            false,
            false,
            null
        );

        $supports = $this->employeeFilterResolver->supports(new Request(), $argument);

        $this->assertSame($expectedResult, $supports);
    }

    /**
     * @return array
     */
    public function providerSupportsOnlyExpectedTypes(): array
    {
        return [
            [EmployeeFilter::class, true],
            [Request::class, false],
        ];
    }

    /**
     * @test
     */
    public function emptyFilterIsResolved(): void
    {
        $filter = $this->employeeFilterResolver->resolve(Request::create('/'), $this->argument);

        $this->assertEquals([self::ARG_NAME => new EmployeeFilter()], $filter);
    }

    /**
     * @test
     */
    public function getParamsAreIgnoredIfIdIsProvided(): void
    {
        $request = Request::create('/');
        $request->attributes->add(['id' => self::VALUE_ID]);
        $request->query->add([
            'boss_id' => self::VALUE_BOSS_ID,
            'firstname' => self::VALUE_FIRST_NAME,
            'lastname' => self::VALUE_LAST_NAME,
            'birthdate_from' => self::VALUE_BIRTHDATE_FROM,
            'birthdate_to' => self::VALUE_BIRTHDATE_TO,
            'role' => self::VALUE_ROLE,
        ]);

        $filter = $this->employeeFilterResolver->resolve($request, $this->argument);

        $employeeFilter = new EmployeeFilter();
        $employeeFilter->setEmployeeId(self::VALUE_ID);

        $this->assertEquals([self::ARG_NAME => $employeeFilter], $filter);
    }

    /**
     * @dataProvider providerGetParamsAreTranslated
     * @test
     *
     * @param array $query
     * @param EmployeeFilter $employeeFilter
     *
     * @return void
     */
    public function getParamsAreTranslated(array $query, EmployeeFilter $employeeFilter): void
    {
        $request = Request::create('/');
        $request->query->add($query);

        $filter = $this->employeeFilterResolver->resolve($request, $this->argument);

        $this->assertEquals([self::ARG_NAME => $employeeFilter], $filter);
    }

    /**
     * @return array
     */
    public function providerGetParamsAreTranslated(): array
    {
        $filter = new EmployeeFilter();
        $filter->setFirstname(self::VALUE_FIRST_NAME);
        $filter->setLastname(self::VALUE_LAST_NAME);
        $filter->setBossId(self::VALUE_BOSS_ID);
        $filter->setBirthdateFrom((new DateTime(self::VALUE_BIRTHDATE_FROM))->setTime(0, 0, 0));
        $filter->setBirthdateTo((new DateTime(self::VALUE_BIRTHDATE_TO))->setTime(0, 0, 0));
        $filter->setRole(self::VALUE_ROLE);

        return [
            [
                [
                    'lastname' => self::VALUE_LAST_NAME,
                    'firstname' => self::VALUE_FIRST_NAME,
                    'boss_id' => self::VALUE_BOSS_ID,
                    'birthdate_from' => self::VALUE_BIRTHDATE_FROM,
                    'birthdate_to' => self::VALUE_BIRTHDATE_TO,
                    'role' => self::VALUE_ROLE,
                ],
                $filter,
            ]
        ];
    }
}
