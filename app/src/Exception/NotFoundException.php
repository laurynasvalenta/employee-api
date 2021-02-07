<?php

namespace App\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class NotFoundException extends Exception implements ApiExceptionInterface
{
    /**
     * @inheritDoc
     */
    public function getHttpCode(): int
    {
        return Response::HTTP_NOT_FOUND;
    }
}
