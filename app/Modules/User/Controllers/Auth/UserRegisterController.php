<?php

namespace App\Modules\User\Controllers\Auth;

use App\Services\Core\Controller\ApiController;
use App\Modules\User\Requests\Auth\UserRegisterRequest;
use App\Modules\User\Tasks\UserRegisterTask;
use Illuminate\Http\Request;

class UserRegisterController extends ApiController
{
    public function __invoke(
        UserRegisterRequest $validation,
        UserRegisterTask $task
    )
    {
        return $task->run($validation);
    }
}