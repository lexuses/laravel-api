<?php

namespace App\Modules\User\Tasks;

use App\Modules\User\Models\User;
use App\Services\Core\Task\Task;
use App\Modules\User\Requests\UserRegisterRequest;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UserRegisterTask extends Task
{
    /**
     * @param $validation UserRegisterRequest
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function run($validation, Request $request)
    {
        event(new Registered($user = $this->create($validation->all())));

        //return $this->guard()->login($user);

        return (new UserLoginTask())->run($validation, $request);
    }

    /**
     * Get the guard to be used during registration.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }
}