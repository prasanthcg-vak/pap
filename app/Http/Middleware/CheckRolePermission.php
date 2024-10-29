<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRolePermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, $permission)
    {
        $role = Auth::user()->roles()->first();
      //  $role = $user->roles()->first(); // Assuming the User model has a `role` relationship

        if (!$role) {
            abort(403, 'Unauthorized action.');
        }

        $hasPermission = $role->permissions()->where('name', $permission)->exists();
        //
        if (!$hasPermission) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }

}
