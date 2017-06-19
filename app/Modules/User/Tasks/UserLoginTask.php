<?php

namespace App\Modules\User\Tasks;

use App\Modules\User\Requests\Auth\UserLoginRequest;
use App\Modules\User\Requests\UserRegisterRequest;
use App\Services\Core\Task\Task;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class UserLoginTask extends Task
{
    use ThrottlesLogins;

    /**
     * @param UserLoginRequest|UserRegisterRequest $validation
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function run($validation, Request $request)
    {
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            //return $this->sendLockoutResponse($request);
            return $this->sendFailedLoginResponse($request);
        }

        return $this->attemptLogin($validation, $request);
    }

    protected function attemptLogin($validation, $request)
    {
        $request->request->add([
            'username' => $validation->email,
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

        $response = Route::dispatch($proxy);

        if($response->getStatusCode() == 200)
            return $response;

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        $errors = ['message' => trans('auth.failed')];

        return response()->json($errors, 422);
    }

    public function username()
    {
        return 'email';
    }
}