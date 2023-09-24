<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::user() && in_array(Auth::user()->type, [1, 2])) {
            if (Auth::user()->type == 2 && (request()->is('sub-admins*') || request()->is('email-settings*') || request()->is('firebase-settings*') || request()->is('payment-settings*') || request()->is('twilio-settings*') || request()->is('general-settings*')) ) {
                abort(404);
            }
            return $next($request);
        }
        return redirect('admin');
    }
}
