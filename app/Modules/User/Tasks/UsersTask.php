<?php

namespace App\Modules\User\Tasks;

use App\Modules\User\Modules\User;
use App\Services\Core\Task\Task;

class UsersTask extends Task
{
    public function run()
    {
        return User::get();
    }
}