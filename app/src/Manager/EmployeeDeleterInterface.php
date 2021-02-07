<?php

namespace App\Manager;

use App\Exception\NotFoundException;
use App\Exception\ValidationException;

interface EmployeeDeleterInterface
{
    /**
     * @param string $employeeId
     *
     * @throws NotFoundException
     * @throws ValidationException
     */
    public function deleteEmployee(string $employeeId): void;
}
