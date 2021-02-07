<?php

namespace Package\EmployeeApiClientBundle\Handler;

use Package\EmployeeDto\Employee;
use Package\EmployeeDto\EmployeeList;
use Psr\Http\Message\ResponseInterface;

class MultipleEmployeesResponseHandler extends AbstractRequestHandler
{
    /**
     * @inheritDoc
     */
    protected function parseResponse(ResponseInterface $response): EmployeeList
    {
        /** @var Employee[] $employees */
        $employees = $this->serializer->deserialize(
            (string)$response->getBody(),
            sprintf('%s[]', Employee::class),
            'json'
        );

        $result = new EmployeeList();
        $result->setEmployees($employees);

        return $result;
    }
}
