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
        
        if (!session()->has('LoggedAdmin') && !in_array($request->path(), ['/', 'login', 'privacy-policy'])) {
    // Own key on purpose: 'fail' is turned into a SweetAlert popup site-wide (ui-extras);
        // this notice is shown as an inline red alert on the login page instead.
        return redirect('login')->with('auth_required', 'You must be logged in');
}


        if (session()->has('LoggedAdmin') && in_array($request->path(), ['/', 'login'])) {
            return redirect()->route('admin-dashboard');
        }

        return $next($request);
    }
}
