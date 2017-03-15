<?php

namespace App\Modules\User\Transformers;

use App\Modules\User\Models\User;
use App\Services\Core\Transformer\Transformer;

class UserTransformer extends Transformer
{
    public function transform(User $user)
    {
        return [
            'id'    => (int) $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ];
    }
}