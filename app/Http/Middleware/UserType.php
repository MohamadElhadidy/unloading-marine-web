<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UserType
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$role)
    {
        if (isset(auth()->user()->auth->name)) {
            for ($i = 0; $i < count($role); $i++) {
                if (auth()->user()->auth->name == $role[$i]) {
                    return $next($request);

                }
            }
        }
        return redirect('/');
    }
}