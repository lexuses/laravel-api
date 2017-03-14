<?php

namespace App\Services\Core\Validation;

class Rules
{
    public function notExists($attribute, $value, $parameters, $validator)
    {
        return \DB::table($parameters[0])
                ->where($attribute, $value)
                ->count()<1;
    }
}