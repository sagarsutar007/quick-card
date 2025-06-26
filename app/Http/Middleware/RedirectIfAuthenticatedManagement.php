<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;


class RedirectIfAuthenticatedManagement
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            if (Auth::user()->role === 'influencer') {
                Auth::logout();
                return redirect()->route('login.management')->withErrors([
                    'status' => 'Access denied! Insufficient permissions to access the page.',
                ]);
            }

            return redirect()->route('management.dashboard');
        }

        return $next($request);
    }
}
