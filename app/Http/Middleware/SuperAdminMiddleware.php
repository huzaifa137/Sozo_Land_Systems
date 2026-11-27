<?php

namespace App\Http\Middleware;

use App\Models\AdminRegister;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Lets a request through only when the logged-in admin (identified by the
 * server-side session, never by anything in the URL or request body) is a SuperAdmin.
 */
class SuperAdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $admin = AdminRegister::find(session('LoggedAdmin'));

        if (!$admin || $admin->admin_category !== 'SuperAdmin') {
            if ($request->expectsJson()) {
                abort(403, 'Only Super Admins can perform this action.');
            }

            return redirect()->route('admin-dashboard')
                ->with('error', 'Only Super Admins can perform this action.');
        }

        return $next($request);
    }
}
