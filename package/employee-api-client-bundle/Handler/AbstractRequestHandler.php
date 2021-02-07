<?php

namespace Package\EmployeeApiClientBundle\Handler;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Request;
use Package\EmployeeApiClientBundle\Exception\EmployeeNotFoundException;
use Package\EmployeeApiClientBundle\Exception\EmployeeRequestFailedException;
use Package\EmployeeApiClientBundle\Exception\EmployeeResponseParsingException;
use Package\EmployeeApiClientBundle\Exception\ValidationFailedException;
use Package\EmployeeApiClientBundle\Factory\ClientFactoryInterface;
use Package\EmployeeDto\EmployeeList;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Throwable;

abstract class AbstractRequestHandler implements EmployeeRequestHandlerInterface
{
    private const DEFAULT_ERROR_MESSAGE = 'Employee request failed!';

    private const CODE_TO_EXCEPTION = [
        Response::HTTP_BAD_REQUEST => ValidationFailedException::class,
        Response::HTTP_NOT_FOUND => EmployeeNotFoundException::class,
    ];

    private const CODE_TO_DEFAULT_MESSAGE = [
        Response::HTTP_BAD_REQUEST => 'Employee validation failed!',
        Response::HTTP_NOT_FOUND => 'Employee not found!',
    ];

    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * @var ClientInterface
     */
    protected $httpClient;

    /**
     * @param SerializerInterface $serializer
     * @param ClientFactoryInterface $httpClientFactory
     */
    public function __construct(SerializerInterface $serializer, ClientFactoryInterface $httpClientFactory)
    {
        $this->serializer = $serializer;
        $this->httpClient = $httpClientFactory->createClient();
    }

    /**
     * @inheritDoc
     */
    public function handleRequest(Request $request): EmployeeList
    {
        try {
            $response = $this->httpClient->sendRequest($request);
        } catch (Throwable $e) {
            throw new EmployeeRequestFailedException($e->getMessage());
        }

        $this->interceptErrors($response);

        try {
            return $this->parseResponse($response);
        } catch (Throwable $e) {
            throw new EmployeeResponseParsingException($e->getMessage());
        }
    }

    /**
     * @param ResponseInterface $response
     */
    protected function interceptErrors(ResponseInterface $response): void
    {
        if ($response->getStatusCode() < 400) {
            return;
        }

        try {
            $data = json_decode((string)$response->getBody(), true);

            $message = (string)$data['message'] ?? '';
        } catch (Throwable $e) {
            throw new EmployeeResponseParsingException();
        }

        $this->raiseException($response->getStatusCode(), $message);
    }

    /**
     * @param int $statusCode
     * @param string $message
     */
    private function raiseException(int $statusCode, string $message): void
    {
        $exceptionType = self::CODE_TO_EXCEPTION[$statusCode] ?? EmployeeRequestFailedException::class;

        if (empty($message) === false) {
            throw new $exceptionType($message);
        }

        if (isset(self::CODE_TO_DEFAULT_MESSAGE[$statusCode])) {
            throw new $exceptionType(self::CODE_TO_DEFAULT_MESSAGE[$statusCode]);
        }

        throw new $exceptionType(self::DEFAULT_ERROR_MESSAGE);
    }

    /**
     * @param ResponseInterface $response
     *
     * @return EmployeeList
     */
    abstract protected function parseResponse(ResponseInterface $response): EmployeeList;
}
