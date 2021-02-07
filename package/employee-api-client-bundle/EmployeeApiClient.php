<?php

namespace Package\EmployeeApiClientBundle;

use DateTime;
use GuzzleHttp\Psr7\Query;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;
use Package\EmployeeApiClientBundle\Handler\EmployeeRequestHandlerInterface;
use Package\EmployeeApiClientBundle\Handler\MultipleEmployeesResponseHandler;
use Package\EmployeeApiClientBundle\Handler\NoEmployeeResponseHandler;
use Package\EmployeeApiClientBundle\Handler\SingleEmployeeResponseHandler;
use Package\EmployeeDto\Employee;
use Package\EmployeeDto\EmployeeFilter;
use Package\EmployeeDto\EmployeeList;
use Symfony\Component\Serializer\SerializerInterface;

class EmployeeApiClient implements EmployeeApiClientInterface
{
    private const URI_EMPLOYEE = '/employee';

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var EmployeeRequestHandlerInterface
     */
    private $singleItemRequestHandler;

    /**
     * @var EmployeeRequestHandlerInterface
     */
    private $multipleItemsRequestHandler;

    /**
     * @var EmployeeRequestHandlerInterface
     */
    private $voidResponseRequestHandler;

    /**
     * @param SerializerInterface $serializer
     * @param SingleEmployeeResponseHandler $singleItemRequestHandler
     * @param MultipleEmployeesResponseHandler $multipleItemsRequestHandler
     * @param NoEmployeeResponseHandler $voidResponseRequestHandler
     */
    public function __construct(
        SerializerInterface $serializer,
        SingleEmployeeResponseHandler $singleItemRequestHandler,
        MultipleEmployeesResponseHandler $multipleItemsRequestHandler,
        NoEmployeeResponseHandler $voidResponseRequestHandler
    ) {
        $this->serializer = $serializer;
        $this->singleItemRequestHandler = $singleItemRequestHandler;
        $this->multipleItemsRequestHandler = $multipleItemsRequestHandler;
        $this->voidResponseRequestHandler = $voidResponseRequestHandler;
    }

    /**
     * @inheritDoc
     */
    public function createEmployee(Employee $employee): Employee
    {
        $request = new Request('POST', self::URI_EMPLOYEE, [], $this->serializer->serialize($employee, 'json'));

        return $this->singleItemRequestHandler->handleRequest($request)->getFirst();
    }

    /**
     * @inheritDoc
     */
    public function updateEmployee(Employee $employee): Employee
    {
        $uri = new Uri(sprintf('%s/%s', self::URI_EMPLOYEE, $employee->getId()));
        $request = new Request('PUT', $uri, [], $this->serializer->serialize($employee, 'json'));

        return $this->singleItemRequestHandler->handleRequest($request)->getFirst();
    }

    /**
     * @inheritDoc
     */
    public function getEmployee(string $id): Employee
    {
        $uri = new Uri(sprintf('%s/%s', self::URI_EMPLOYEE, $id));
        $request = new Request('GET', $uri);

        return $this->singleItemRequestHandler->handleRequest($request)->getFirst();
    }

    /**
     * @inheritDoc
     */
    public function findEmployees(EmployeeFilter $filter = null): EmployeeList
    {
        $uri = new Uri(self::URI_EMPLOYEE);
        $uri = $uri->withQuery(Query::build($this->buildQuery($filter)));
        $request = new Request('GET', $uri);

        return $this->multipleItemsRequestHandler->handleRequest($request);
    }

    /**
     * @inheritDoc
     */
    public function deleteEmployee(string $id): void
    {
        $uri = new Uri(sprintf('%s/%s', self::URI_EMPLOYEE, $id));
        $request = new Request('DELETE', $uri);

        $this->voidResponseRequestHandler->handleRequest($request);
    }

    /**
     * @param EmployeeFilter|null $filter
     *
     * @return array
     */
    private function buildQuery(?EmployeeFilter $filter): array
    {
        return array_filter([
            'lastname' => $filter->getLastname(),
            'firstname' => $filter->getFirstname(),
            'boss_id' => $filter->getBossId(),
            'birthdate_from' => $this->dateToString($filter->getBirthdateFrom()),
            'birthdate_to' => $this->dateToString($filter->getBirthdateTo()),
            'role' => $filter->getRole(),
        ]);
    }

    /**
     * @param DateTime|null $date
     *
     * @return string|null
     */
    private function dateToString(?DateTime $date): ?string
    {
        if ($date === null) {
            return null;
        }

        return $date->format('Y-m-d');
    }
}
