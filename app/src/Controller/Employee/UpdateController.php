<?php

namespace App\Controller\Employee;

use App\Exception\ParsingException;
use App\Exception\ValidationException;
use App\Manager\EmployeeUpdaterInterface;
use Package\EmployeeDto\Employee;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class UpdateController
{
    use SerializerAwareTrait;

    /**
     * @var EmployeeUpdaterInterface
     */
    private $employeeWriter;

    /**
     * @param SerializerInterface $serializer
     * @param EmployeeUpdaterInterface $employeeWriter
     */
    public function __construct(SerializerInterface $serializer, EmployeeUpdaterInterface $employeeWriter)
    {
        $this->serializer = $serializer;
        $this->employeeWriter = $employeeWriter;
    }

    /**
     * @Route("/employee/{id}", methods={"PUT"})
     *
     * @param Request $request
     * @param string $id
     *
     * @throws ValidationException
     * @throws ParsingException
     */
    public function __invoke(Request $request, string $id): Response
    {
        $employeeDto = $this->employeeWriter->updateEmployee($this->getEmployeeDto($request, $id));

        return new JsonResponse($this->serializer->serialize($employeeDto, 'json'), Response::HTTP_OK, [], true);
    }
}
