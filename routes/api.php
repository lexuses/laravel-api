<?php

use App\Services\Core\Route\RoutePath;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::namespace('Modules\User\Controllers')->group(RoutePath::get('User'));

Route::namespace('Modules\Welcome\Controllers')->group(RoutePath::get('Welcome'));