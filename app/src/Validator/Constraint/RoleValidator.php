<?php

namespace App\Validator\Constraint;

use App\Repository\EmployeeRepository;
use Package\EmployeeDto\Employee;
use Package\EmployeeDto\EmployeeFilter;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class RoleValidator extends ConstraintValidator
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

        if ($value->getRoleName() === self::ROLE_CEO && $this->doesCeoExist($value) === true) {
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
    private function doesCeoExist(Employee $value): bool
    {
        $filter = new EmployeeFilter();
        $filter->setRole(self::ROLE_CEO);

        $employees = $this->employeeRepository->findEmployees($filter);

        if (count($employees) === 0) {
            return false;
        }

        return $employees[0]->getId() !== $value->getId();
    }
}
