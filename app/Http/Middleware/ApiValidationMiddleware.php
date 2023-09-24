<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class ApiValidationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if ($response->exception instanceof ValidationException) {
            foreach ($response->exception->errors() as $err) {
                throw new HttpResponseException(response()->json(['status' => 0, 'message' => $err[0]], 200));
            }
            // throw new HttpResponseException(response()->json(['status' => 0, 'message' => 'Something went wrong!', 'errors' => $response->exception->errors()], 200));
        }
        return $response;
    }
}
