<?php

namespace App\Services\Core\Exception;

use App;
use Illuminate\Http\Request;

class ApiHandlerException
{
    public static function show(Request $request, $exception)
    {
        if($exception instanceof ValidationFailedException OR $exception instanceof ApiException)
        {
            $data = ['message' => $exception->getMessage()];
            if( ! empty($exception->getErrors()->all())) $data['errors'] =  $exception->getErrors()->all();
            if( ! empty($exception->httpStatusCode)) $data['status_code'] =  $exception->httpStatusCode;
        }
        elseif (App::environment('production'))
            $data = [
                "message" => "Internal Server Error",
                "status_code" => 500
            ];
        else
            $data = [
                'message' => $exception->getMessage(),
                'code' => $exception->getCode(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'request' => $request->all(),
                'trace' => $exception->getTraceAsString(),
            ];

        return response()->json($data, $exception->httpStatusCode);
    }
}