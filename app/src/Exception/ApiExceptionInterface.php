<?php

namespace App\Exception;

interface ApiExceptionInterface
{
    /**
     * @return int
     */
    public function getHttpCode(): int;
}
