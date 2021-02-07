<?php

namespace App\ArgumentResolver;

use DateTime;
use Exception;
use Package\EmployeeDto\EmployeeFilter;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EmployeeFilterResolver implements ArgumentValueResolverInterface
{
    private const KEY_FIRST_NAME = 'firstname';
    private const KEY_LAST_NAME = 'lastname';
    private const KEY_BOSS_ID = 'boss_id';
    private const KEY_BIRTHDATE_FROM = 'birthdate_from';
    private const KEY_BIRTHDATE_TO = 'birthdate_to';
    private const KEY_ROLE = 'role';
    private const PARAM_ID = 'id';

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @inheritDoc
     */
    public function supports(Request $request, ArgumentMetadata $argument)
    {
        return $argument->getType() === EmployeeFilter::class;
    }

    /**
     * @inheritDoc
     */
    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        $employeeFilter = $this->buildFilter($request);
        $violations = $this->validator->validate($employeeFilter);

        if ($violations->count() > 0) {
            throw new BadRequestException();
        }

        return [
            $argument->getName() => $employeeFilter,
        ];
    }

    /**
     * @param Request $request
     * @param string $key
     *
     * @return DateTime|null
     *
     * @throws Exception
     */
    protected function getDateTimeObject(Request $request, string $key): ?DateTime
    {
        $dateAsString = $request->query->get($key);

        if ($dateAsString === null) {
            return null;
        }

        return (new DateTime($dateAsString))->setTime(0, 0, 0);
    }

    /**
     * @param Request $request
     *
     * @return EmployeeFilter
     */
    protected function buildFilter(Request $request): EmployeeFilter
    {
        $employeeFilter = new EmployeeFilter();

        if ($request->attributes->get(self::PARAM_ID) !== null) {
            $employeeFilter->setEmployeeId((string)$request->attributes->get(self::PARAM_ID));

            return $employeeFilter;
        }

        $employeeFilter->setFirstname($request->query->get(self::KEY_FIRST_NAME));
        $employeeFilter->setLastname($request->query->get(self::KEY_LAST_NAME));
        $employeeFilter->setBossId($request->query->get(self::KEY_BOSS_ID));
        $employeeFilter->setRole($request->query->get(self::KEY_ROLE));
        $employeeFilter->setBirthdateFrom($this->getDateTimeObject($request, self::KEY_BIRTHDATE_FROM));
        $employeeFilter->setBirthdateTo($this->getDateTimeObject($request, self::KEY_BIRTHDATE_TO));

        return $employeeFilter;
    }
}
