<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'You must be logged in to access this page.');
        }

        if (Auth::user()->role !== $role) {
            return redirect()->route(Auth::user()->role . '.dashboard')
                ->with('error', 'Unauthorized access.');
        }

        return $next($request);
    }
}