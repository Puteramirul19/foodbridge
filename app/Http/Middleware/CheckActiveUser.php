<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
class CheckActiveUser
{
/**
* Handle an incoming request.
*
* @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
*/
public function handle(Request $request, Closure $next): Response
{
// Check if user is authenticated and if their account is inactive
if (Auth::check() && !Auth::user()->is_active) {
// Log the user out immediately
Auth::logout();
        // Invalidate the session
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // Redirect to login with error message
        return redirect()->route('login')
            ->with('error', 'Your account has been deactivated. Please contact support for assistance.');
    }

    return $next($request);
}
}