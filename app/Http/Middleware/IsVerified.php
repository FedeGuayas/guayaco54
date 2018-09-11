<?php

namespace App\Http\Middleware;

use App\Exceptions\UserVerification\UserNotVerifiedException;
use Closure;
use Session;

class IsVerified
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
        if(! is_null($request->user()) && ! $request->user()->verified) {
            Session::flush();
            $notification=[
              'message_toastr'=> 'La cuenta no ha sido verificada. Verifique su email antes de iniciar sessión',
                'alert-type' => 'warning'
            ];
            return redirect('login')->with($notification);
//            throw new UserNotVerifiedException;
        }

        return $next($request);


    }
}
