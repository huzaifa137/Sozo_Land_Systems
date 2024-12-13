<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
public function handle(Request $request, Closure $next): Response
    {
        // if (!session()->has('LoggedAdmin') && $request->path() != '/') {
        //     return redirect('/')->with('fail', 'You must be logged in');
        // }
        
        if (!session()->has('LoggedAdmin') && $request->path() != '/' && $request->path() != 'privacy-policy') {
    return redirect('/')->with('fail', 'You must be logged in');
}


        if (session()->has('LoggedAdmin') && $request->path() == '/') {
            return redirect()->route('admin-dashboard');
        }

        return $next($request);
    }
}
