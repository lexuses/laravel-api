<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['namespace' => 'Modules\User\Controllers'], function () {

    Route::group(['namespace' => 'Auth'], function () {
        Route::post('/login', 'UserLoginController');
    });

    /**
     * @SWG\Get(
     *     path="/users",
     *     summary="Get all users",
     *     tags={"User"},
     *     description="Get all users",
     *     operationId="UserGet",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Limit list of users",
     *         required=true,
     *         type="integer",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="successful",
     *     ),
     *     @SWG\Response(
     *         response="422",
     *         description="Invalid Input.",
     *     ),
     *     security={
     *         {
     *             "JWT": {}
     *         }
     *     }
     * )
     */
    Route::get('/users', 'UsersController')->middleware(['auth:api']);

});
