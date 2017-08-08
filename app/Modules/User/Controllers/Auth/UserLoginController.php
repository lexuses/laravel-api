<?php

namespace App\Modules\User\Controllers\Auth;

use App\Modules\User\Requests\Auth\UserLoginRequest;
use App\Modules\User\Tasks\UserLoginTask;
use App\Services\Core\Controller\ApiController;
use Illuminate\Http\Request;

class UserLoginController extends ApiController
{
    public function __invoke(
        UserLoginRequest $validation,
        UserLoginTask $task
    )
    {
        return $task->run($validation);
    }
}