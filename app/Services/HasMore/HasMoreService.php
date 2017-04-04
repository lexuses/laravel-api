<?php

namespace App\Services\HasMore;

class HasMoreService
{
    /**
     * @param $model
     * @param $limit
     * @return bool
     */
    public function __invoke($model, $limit)
    {
        if( ! $model->count() OR $model->count() <= $limit)
            $more = false;
        else
        {
            $more = true;
            $model->pop();
        }

        return $more;
    }
}