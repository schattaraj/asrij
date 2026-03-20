<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next,$roles): Response
    {
        if (!auth()->check()) {
            abort(403);
        }
        $allowedRoles = explode('|', $roles);
        $userRoles = auth()->user()->roles ?? [];

        // if (! in_array(auth()->user()->role, $allowedRoles)) {
        //     abort(403);
        // }
        // Check if any allowed role is in user's roles
        if (!array_intersect($allowedRoles, $userRoles)) {
        abort(403);
        }
        return $next($request);
    }
}
