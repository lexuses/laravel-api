<?php

namespace App\Modules\User\Controllers;

use App\Services\Core\Controller\ApiController;
use Illuminate\Http\Request;

class UserGetController extends ApiController
{
    function __invoke(Request $request)
    {
        return $request->user();
    }
}