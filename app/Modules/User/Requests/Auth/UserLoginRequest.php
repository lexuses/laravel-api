<?php

namespace App\Modules\User\Requests\Auth;

use App\Services\Core\Request\Request;

/**
 * @property mixed username
 * @property mixed password
 */
class UserLoginRequest extends Request
{
    public function rules()
    {
        return [
            'username' => 'required|email',
            'password' => 'required',
        ];
    }

    public function authorize()
    {
        return true;
    }
}