<?php

namespace App\Http\Middleware;
use Auth;
use Closure;
use App\Users;
class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next){
        
      //  echo Auth::user()->hasRole(ADMIN);
       // echo Auth::user()->hasRole(SUPER_ADMIN);

     //   die();
        if (Auth::user()->hasRole(USER)) //If user does //not have this permission
        {
            abort('401');
            exit;
        }
        else if (Auth::user()->hasRole(SELLER)) //If user does //not have this permission
        {
            abort('401');
            exit;
        }
        return $next($request);
    }
}
