<?php

namespace App\Controller\Employee;

use App\Exception\ParsingException;
use Package\EmployeeDto\Employee;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Throwable;

trait SerializerAwareTrait
{
    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * @param Request $request
     * @param string|null $id
     *
     * @return Employee
     *
     * @throws ParsingException
     */
    protected function getEmployeeDto(Request $request, ?string $id = null): Employee
    {
        try {
            /** @var Employee $employeeDto */
            $employeeDto = $this->serializer->deserialize((string)$request->getContent(), Employee::class, 'json');
        } catch (Throwable $e) {
            throw new ParsingException($e->getMessage());
        }

        $employeeDto->setId($id);

        return $employeeDto;
    }
}
