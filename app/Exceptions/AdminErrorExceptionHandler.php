<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class AdminErrorExceptionHandler extends ExceptionHandler
{
    // ...

    public function render($request, Exception $exception)
    {
        // Handle the exception and return a custom response for API requests.
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Route Not Found'], 404);
        }

        // For non-API requests, you can show a custom error page using a view.
        return response()->view('errors.custom', [], 404);
    }

    // ...
}