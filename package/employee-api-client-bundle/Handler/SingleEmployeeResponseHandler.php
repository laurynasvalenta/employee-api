<?php

namespace Package\EmployeeApiClientBundle\Handler;

use Package\EmployeeDto\Employee;
use Package\EmployeeDto\EmployeeList;
use Psr\Http\Message\ResponseInterface;

class SingleEmployeeResponseHandler extends AbstractRequestHandler
{
    /**
     * @inheritDoc
     */
    protected function parseResponse(ResponseInterface $response): EmployeeList
    {
        /** @var Employee $employee */
        $employee = $this->serializer->deserialize((string)$response->getBody(), Employee::class, 'json');

        $result = new EmployeeList();
        $result->addEmployee($employee);

        return $result;
    }
}
