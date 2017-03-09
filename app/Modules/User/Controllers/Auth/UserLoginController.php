<?php

namespace App\Modules\User\Controllers\Auth;

use App\Modules\User\Requests\Auth\UserLoginRequest;
use App\Services\Core\Controller\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class UserLoginController extends ApiController
{

    public function __invoke(UserLoginRequest $validation, Request $request)
    {
        $request->request->add([
            'username' => $validation->username,
            'password' => $validation->password,
            'grant_type' => 'password',
            'client_id' => config('auth_client.client_id'),
            'client_secret' => config('auth_client.client_secret'),
            'scope' => '*'
        ]);

        $proxy = Request::create(
            'oauth/token',
            'POST'
        );

        return Route::dispatch($proxy);
    }
}