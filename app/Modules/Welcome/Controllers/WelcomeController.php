<?php

namespace App\Modules\Welcome\Controllers;

use App\Services\Core\Controller\ApiController;
use App\Modules\Welcome\Requests\WelcomeRequest;
use App\Modules\Welcome\Tasks\WelcomeTask;

class WelcomeController extends ApiController
{
    public function __invoke()
    {
        return response()->json(['message' => 'Welcome to API']);
    }
}