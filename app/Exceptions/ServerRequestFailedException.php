<?php

namespace App\Exceptions;

use App\Services\Core\Exception\ApiException;
use Symfony\Component\HttpFoundation\Response;

class ServerRequestFailedException extends ApiException
{
    public $httpStatusCode = Response::HTTP_FORBIDDEN;

    public $message = 'Access denied!';
}