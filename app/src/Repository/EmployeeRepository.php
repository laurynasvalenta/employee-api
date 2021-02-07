<?php

namespace App\Repository;

use App\Entity\Employee;
use App\Entity\Role;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Package\EmployeeDto\EmployeeFilter;

/**
 * @method Employee|null find($id, $lockMode = null, $lockVersion = null)
 * @method Employee|null findOneBy(array $criteria, array $orderBy = null)
 * @method Employee[]    findAll()
 * @method Employee[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmployeeRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Employee::class);
    }

    /**
     * @param string $id
     *
     * @return Employee|null
     */
    public function findEmployeeById(string $id): ?Employee
    {
        return $this->findOneBy(['id' => $id]);
    }

    /**
     * @param EmployeeFilter $employeeFilter
     *
     * @return Employee[]
     */
    public function findEmployees(EmployeeFilter $employeeFilter): array
    {
        $mapping = [
            'e.id' => $employeeFilter->getEmployeeId(),
            'e.firstname' => $employeeFilter->getFirstname(),
            'e.lastname' => $employeeFilter->getLastname(),
            'boss.id' => $employeeFilter->getBossId(),
            'birthdate_from' => $employeeFilter->getBirthdateFrom(),
            'birthdate_to' => $employeeFilter->getBirthdateTo(),
            'role.name' => $employeeFilter->getRole(),
        ];

        $query = $this->createQueryBuilder('e')
            ->innerJoin('e.role', 'role')
            ->leftJoin('e.boss', 'boss');

        $params = new ArrayCollection();

        foreach ($mapping as $paramName => $value) {
            if ($value === null) {
                continue;
            }

            $this->addParams($params, $query, $paramName, $value);
        }

        return $query->getQuery()
            ->setParameters($params)
            ->getResult();
    }

    /**
     * @param ArrayCollection $params
     * @param QueryBuilder $query
     * @param string $paramName
     * @param mixed $value
     */
    private function addParams(
        ArrayCollection $params,
        QueryBuilder $query,
        string $paramName,
        $value
    ): void {
        $sanitizedParamName = strtr($paramName, ['.' => '']);

        if ($paramName === 'birthdate_from') {
            $query->andWhere("e.birthdate >= :$sanitizedParamName");
            $params->add(new Parameter($sanitizedParamName, $value));

            return;
        }

        if ($paramName === 'birthdate_to') {
            $query->andWhere("e.birthdate < :$sanitizedParamName");
            $params->add(new Parameter($sanitizedParamName, $value));

            return;
        }

        if ($paramName === 'e.id' || $paramName === 'boss.id') {
            $query->andWhere("$paramName = :$sanitizedParamName");
            $params->add(new Parameter($sanitizedParamName, $value, 'uuid'));

            return;
        }

        $query->andWhere("$paramName = :$sanitizedParamName");
        $params->add(new Parameter($sanitizedParamName, $value));
    }
}
