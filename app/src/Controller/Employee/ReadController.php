<?php

namespace App\Controller\Employee;

use App\Exception\NotFoundException;
use App\Manager\EmployeeReaderInterface;
use Package\EmployeeDto\EmployeeFilter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ReadController
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var EmployeeReaderInterface
     */
    private $reader;

    /**
     * @param SerializerInterface $serializer
     * @param EmployeeReaderInterface $reader
     */
    public function __construct(SerializerInterface $serializer, EmployeeReaderInterface $reader)
    {
        $this->serializer = $serializer;
        $this->reader = $reader;
    }

    /**
     * @Route("/employee/{id}", methods={"GET"})
     *
     * @param EmployeeFilter $employeeFilter
     *
     * @return Response
     */
    public function one(EmployeeFilter $employeeFilter): Response
    {
        $employee = $this->reader->findEmployees($employeeFilter)->getFirst();

        if ($employee === null) {
            throw new NotFoundException();
        }

        return new JsonResponse($this->serializer->serialize($employee, 'json'), Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/employee", methods={"GET"})
     *
     * @param EmployeeFilter $employeeFilter
     *
     * @return Response
     */
    public function list(EmployeeFilter $employeeFilter): Response
    {
        $employeeList = $this->reader->findEmployees($employeeFilter)->getEmployees();

        return new JsonResponse($this->serializer->serialize($employeeList, 'json'), Response::HTTP_OK, [], true);
    }
}
