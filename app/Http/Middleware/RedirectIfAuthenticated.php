<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
          /*  $user = Auth::user();
            if ($user->user_type  == "0") {
                return redirect('/admin/dashboard');
            }
            if ($user->user_type  == "1") {
                return redirect('/dashboard');
            }*/

              $user = Auth::user();

              if($user->hasRole('ADMIN') || $user->hasRole('SUPER_ADMIN'))
              {
                return redirect('/admin/dashboard');
              }
              elseif($user->hasRole('SELLER'))
              {
               abort(404);
              }
              else
              {
                return redirect('/dashboard');
              }
              

        }

        return $next($request);
    }
}
