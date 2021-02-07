<?php

namespace App\Controller\Employee;

use App\Manager\EmployeeCreatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class CreateController
{
    use SerializerAwareTrait;

    /**
     * @var EmployeeCreatorInterface
     */
    private $employeeWriter;

    /**
     * @param SerializerInterface $serializer
     * @param EmployeeCreatorInterface $employeeWriter
     */
    public function __construct(SerializerInterface $serializer, EmployeeCreatorInterface $employeeWriter)
    {
        $this->serializer = $serializer;
        $this->employeeWriter = $employeeWriter;
    }

    /**
     * @Route("/employee", methods={"POST"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function __invoke(Request $request): Response
    {
        $employeeDto = $this->employeeWriter->createEmployee($this->getEmployeeDto($request));

        return new JsonResponse($this->serializer->serialize($employeeDto, 'json'), Response::HTTP_CREATED, [], true);
    }
}
