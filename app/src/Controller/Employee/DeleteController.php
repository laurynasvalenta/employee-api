<?php

namespace App\Controller\Employee;

use App\Manager\EmployeeDeleterInterface;
use Package\EmployeeDto\EmployeeFilter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DeleteController
{
    /**
     * @var EmployeeDeleterInterface
     */
    private $deleter;

    /**
     * @param EmployeeDeleterInterface $deleter
     */
    public function __construct(EmployeeDeleterInterface $deleter)
    {
        $this->deleter = $deleter;
    }

    /**
     * @Route("/employee/{id}", methods={"DELETE"})
     *
     * @param EmployeeFilter $employeeFilter
     *
     * @return Response
     */
    public function __invoke(EmployeeFilter $employeeFilter): Response
    {
        $this->deleter->deleteEmployee($employeeFilter->getEmployeeId());

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
