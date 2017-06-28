<?php

namespace App\Services\Core\Transformer;

use League\Fractal\TransformerAbstract;

abstract class Transformer extends TransformerAbstract
{
    public function make($data)
    {
        return $this->getCurrentScope()->getManager()->createData($data)->toArray();
    }
}