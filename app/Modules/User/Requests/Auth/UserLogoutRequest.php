<?php

namespace App\Modules\User\Requests\Auth;

use App\Services\Core\Request\Request;

/**
 * Class UserLogoutRequest.
 */
class UserLogoutRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}