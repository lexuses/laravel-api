<?php

namespace App\Modules\User\Exceptions;

use App\Services\Core\Exception\ApiException;
use Symfony\Component\HttpFoundation\Response;

class UserNotFoundException extends ApiException
{
    public $httpStatusCode = Response::HTTP_NOT_FOUND;

    public $message = 'Failed: user not found';
}