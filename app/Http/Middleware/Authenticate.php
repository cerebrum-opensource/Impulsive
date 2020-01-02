<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Route;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function redirectTo($request)
    {
       /* if (! $request->expectsJson()) {
            return route('login');
        }
        */
        $exploded_uri = explode('/', Route::getFacadeRoot()->current()->uri());
        if(isset($exploded_uri[0]) && $exploded_uri[0] == 'admin')
            return route('adminlogin');
        else
            return route('login');
    }
}
