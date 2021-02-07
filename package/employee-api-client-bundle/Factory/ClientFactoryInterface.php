<?php

namespace Package\EmployeeApiClientBundle\Factory;

use Psr\Http\Client\ClientInterface;

interface ClientFactoryInterface
{
    /**
     * @return ClientInterface
     */
    public function createClient(): ClientInterface;
}
