<?php

namespace App\Modules\User\Controllers\Auth;

use App\Services\Core\Controller\ApiController;
use App\Modules\User\Requests\Auth\UserLogoutRequest;
use App\Modules\User\Tasks\UserLogoutTask;

class UserLogoutController extends ApiController
{
    public function __invoke(UserLogoutRequest $request, UserLogoutTask $task)
    {
        //nothing to do - just for notification

        return $this->response
                    ->data(['message' => 'Done!'])
                    ->send();
    }
}