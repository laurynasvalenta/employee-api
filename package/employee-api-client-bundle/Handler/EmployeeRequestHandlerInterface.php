<?php

namespace Package\EmployeeApiClientBundle\Handler;

use GuzzleHttp\Psr7\Request;
use Package\EmployeeDto\EmployeeList;

interface EmployeeRequestHandlerInterface
{
    /**
     * @param Request $request
     *
     * @return EmployeeList
     */
    public function handleRequest(Request $request): EmployeeList;
}
