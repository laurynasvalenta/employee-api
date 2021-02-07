<?php

namespace App\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class ParsingException extends Exception implements ApiExceptionInterface
{
    /**
     * @inheritDoc
     */
    public function getHttpCode(): int
    {
        return Response::HTTP_BAD_REQUEST;
    }
}
