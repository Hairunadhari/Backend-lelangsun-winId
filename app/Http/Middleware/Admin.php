<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Admin
{
    public function handle($request, Closure $next, $role)
    {
        // dd(Auth::user()->role->role);
        // dd($next);
        if(Auth::check() && Auth::user()->role->role == $role){
            return $next($request);
        }
        

        return redirect()->back();
    }
}
