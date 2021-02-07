<?php

namespace Package\EmployeeApiClientBundle\Factory;

use GuzzleHttp\Client;
use Psr\Http\Client\ClientInterface;

class ClientFactory implements ClientFactoryInterface
{
    /**
     * @var string
     */
    private $baseUrl;

    /**
     * @param string $baseUrl
     */
    public function __construct(string $baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    /**
     * {@inheritDoc}
     */
    public function createClient(): ClientInterface
    {
        return new Client(
            [
                'base_uri' => $this->baseUrl,
            ]
        );
    }
}
