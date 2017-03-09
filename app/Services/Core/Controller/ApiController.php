<?php

namespace App\Services\Core\Controller;

use App\Http\Controllers\Controller;
use App\Services\Core\Response\ResponseManager;
use ErrorException;

/**
 * @property ResponseManager response
 */
class ApiController extends Controller
{
    protected function response()
    {
        return new ResponseManager();
    }

    public function __get($key)
    {
        $callable = [
            'api', 'user', 'auth', 'response',
        ];

        if (in_array($key, $callable) && method_exists($this, $key)) {
            return $this->$key();
        }

        throw new ErrorException('Undefined property '.get_class($this).'::'.$key);
    }

    public function __call($method, $parameters)
    {
        if (method_exists($this->response(), $method) || $method == 'array') {
            return call_user_func_array([$this->response(), $method], $parameters);
        }

        throw new ErrorException('Undefined method '.get_class($this).'::'.$method);
    }
}