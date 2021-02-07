<?php

namespace Package\EmployeeApiClientBundle\Handler;

use Package\EmployeeDto\EmployeeList;
use Psr\Http\Message\ResponseInterface;

class NoEmployeeResponseHandler extends AbstractRequestHandler
{
    /**
     * @inheritDoc
     */
    protected function parseResponse(ResponseInterface $response): EmployeeList
    {
        return new EmployeeList();
    }
}
