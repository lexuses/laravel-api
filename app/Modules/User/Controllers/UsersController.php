<?php

namespace App\Modules\User\Controllers;

use App\Modules\User\Exceptions\UserNotFoundException;
use App\Services\Core\Controller\ApiController;
use App\Modules\User\Models\User;
use App\Modules\User\Requests\UsersRequest;
use App\Modules\User\Tasks\UsersTask;
use App\Modules\User\Transformers\UserTransformer;

class UsersController extends ApiController
{
    public function __invoke(UsersRequest $request, UsersTask $task)
    {
        $users = $task->run();
        if( ! $users)
            throw new UserNotFoundException();

        return $this->response
            ->collection($users)
            ->transform(new UserTransformer())
            ->addMeta(['count' => User::count()])
            ->send();
    }
}