<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,$permission=null)
    {
        if (Auth::guest()) {
            return redirect('/');
        }

        if($permission != null) {
            if (! $request->user()->can($permission)) {
                abort(403);
            }
        }

        return $next($request);
    }
}
