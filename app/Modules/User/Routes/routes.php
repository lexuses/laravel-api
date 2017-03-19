<?php

Route::namespace('Auth')
    ->group(function () {
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
Route::get('/users', 'UsersController')
    ->middleware(['auth:api']);

Route::get('/user', 'UserGetController')
    ->middleware('auth:api');