<?php

namespace App\Validator\Constraint;

use App\Repository\EmployeeRepository;
use Package\EmployeeDto\Employee;
use Package\EmployeeDto\EmployeeFilter;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class BossValidator extends ConstraintValidator
{
    public const ROLE_CEO = 'CEO';

    /**
     * @var EmployeeRepository
     */
    private $employeeRepository;

    /**
     * @param EmployeeRepository $employeeRepository
     */
    public function __construct(EmployeeRepository $employeeRepository)
    {
        $this->employeeRepository = $employeeRepository;
    }

    /**
     * @inheritDoc
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$value instanceof Employee) {
            throw new UnexpectedTypeException($value, Employee::class);
        }

        if ($value->getRoleName() === self::ROLE_CEO && empty($value->getBossId()) === false) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->addViolation();

            return;
        }

        if ($this->bossEmployeeExists($value) === false) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->addViolation();

            return;
        }

        if ($value->getId() !== null && $value->getId() === $value->getBossId()) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->addViolation();

            return;
        }
    }

    /**
     * @param Employee $value
     *
     * @return bool
     */
    private function bossEmployeeExists(Employee $value): bool
    {
        $filter = new EmployeeFilter();
        $filter->setEmployeeId((string)$value->getBossId());

        $employees = $this->employeeRepository->findEmployees($filter);

        return count($employees) > 0;
    }
}
