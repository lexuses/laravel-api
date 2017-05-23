<?php

namespace App\Modules\User\Requests\Auth;

use App\Services\Core\Request\Request;

/**
 * @property mixed email
 * @property mixed password
 */
class UserLoginRequest extends Request
{
    public function rules()
    {
        return [
            'email' => 'required|email',
            'password' => 'required',
        ];
    }

    public function authorize()
    {
        return true;
    }
}