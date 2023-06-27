<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AuthenticateToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = hash('sha256', $request->bearerToken());
        $user  = \App\Models\User::where("api_token", $token)->first();
        if (!$token || !$user) {
            abort(401);
        }

        Auth::setUser($user);

        return $next($request);
    }
}
