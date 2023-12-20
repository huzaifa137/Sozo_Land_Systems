<?php

// namespace App\Http\Middleware;

// use Closure;
// use Illuminate\Http\Request;
// use Symfony\Component\HttpFoundation\Response;

  /**
class SuperAdminMiddleware
{
  
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
    
    public function handle(Request $request, Closure $next): Response
    {

        if (session()->has('username') && session()->has('admin_category')) {
           
            $username = session('username');
            $role = session('admin_category');

            dd($role);
            // You can also add the username and role to the request for later use in controllers
            $request->merge(['username' => $username, 'role' => $role]);

            // Perform checks based on username and role
            if ($role === 'Admin') {

                return redirect()->route('admin-dashboard')->with('error', 'Only Super Admins can perform this action.');

            }
             elseif ($role === 'SuperAdmin')
            {

                return $next($request);

            }
        }

        return redirect()->route('admin-dashboard')->with('error', 'Only Super Admins can perform this action.');

        // If there is no session username or role, you can redirect or perform other actions
        // return redirect()->route('/')->with('error', 'You must be logged in to access this resource.');
    }
}
 */