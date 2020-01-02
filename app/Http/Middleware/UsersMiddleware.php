<?php

namespace App\Http\Middleware;
use Auth;
use Closure;
use App\Users;

class UsersMiddleware
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
        /*
        if ($request->user() == null ){
            $url = '/login';
            return redirect($url);
        }

        if($request->user() != null ){
            $user = @Auth::user();
            if ($user->user_type != '1' ) {
                abort('401');
                //return redirect($url);
            }
            return $next($request);
        }
        return $next($request);*/

        //$user = User::all()->count();
        if (Auth::user() && !Auth::user()->hasRole(USER)) //If user does //not have this permission
        {
            abort('401');
            exit;
        }
        
        return $next($request);
    }
}
