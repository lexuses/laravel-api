<?php

namespace App\Services\Core\Exception;

use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

/**
 * Class ValidationFailedException.
 *
 * Note: exceptionally extending from `Dingo\Api\Exception\ResourceException` instead of
 * `App\Services\Core\Exception\Abstracts\ApiException`. To keep the request validation
 * throwing well formatted error. To be debugged later and switched to extending from
 * `ApiException` while carefully looking at the validation response error format.
 */
class ValidationFailedException extends ResourceException
{

    public $httpStatusCode = SymfonyResponse::HTTP_UNPROCESSABLE_ENTITY;

    public $message = 'Invalid Input.';

    /**
     * ValidationFailedException constructor.
     *
     * @param null                                                    $errors
     * @param null                                                    $previous
     * @param array                                                   $headers
     * @param int                                                     $code
     */
    public function __construct($errors = null, $previous = null, $headers = [], $code = 0)
    {
        parent::__construct($this->message, $errors, $previous, $headers, $code);
    }
}
