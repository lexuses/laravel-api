<?php

namespace App\Services\Core\Exception;

use App;
use Illuminate\Http\Request;

class ApiException
{
    public static function show(Request $request, $exception)
    {
        if (App::environment('production'))
            $data = [
                "message" => "Internal Server Error",
                "status_code" => 500
            ];

        elseif($exception instanceof ValidationFailedException)
            $data = [
                'message' => $exception->getMessage(),
                'errors' => $exception->getErrors()
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


        return response()->json($data);
    }
}