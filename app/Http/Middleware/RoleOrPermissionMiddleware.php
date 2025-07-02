<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleOrPermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $roles, $permission): Response
    {
        $user = auth()->user();

        $roleList = explode('|', $roles);

        if (!($user->hasAnyRole($roleList) || $user->can($permission))) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
