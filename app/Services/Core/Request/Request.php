<?php

namespace App\Services\Core\Request;

use App\Services\Core\Exception\ValidationFailedException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest as LaravelFrameworkRequest;

/**
 * Class Request.
 */
abstract class Request extends LaravelFrameworkRequest
{

    /**
     * overriding the failedValidation function to throw my custom
     * exception instead of the default Laravel exception.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     *
     * @return mixed|void
     */
    public function failedValidation(Validator $validator)
    {
        throw new ValidationFailedException($validator->getMessageBag());
    }
}