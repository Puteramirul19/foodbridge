<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Show registration form
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle user registration
     */
    public function register(Request $request)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone_number' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:donor,recipient'
        ]);

        // Check validation
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'role' => $request->role
        ]);

        // Login the user after registration
        Auth::login($user);

        // Redirect based on role
        return redirect()->route($user->role . '.dashboard');
    }

    // Helper function to clean phone number (if needed)
    private function cleanPhoneNumber($phoneNumber) 
    {
        // Remove any non-digit characters
        $cleanNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
        
        // If starts with 60, keep as is
        if (substr($cleanNumber, 0, 2) === '60') {
            return $cleanNumber;
        }
        
        // If starts with 0, replace with 60
        if (substr($cleanNumber, 0, 1) === '0') {
            return '60' . substr($cleanNumber, 1);
        }
        
        // If starts with 1 and length is 9-10, prepend 60
        if (substr($cleanNumber, 0, 1) === '1' && (strlen($cleanNumber) === 9 || strlen($cleanNumber) === 10)) {
            return '60' . $cleanNumber;
        }
        
        return $cleanNumber;
    }

    /**
     * Show login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle user login
     */
    public function login(Request $request)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        // Check validation
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Attempt login with remember functionality
        $credentials = $request->only('email', 'password');
        
        if (Auth::attempt($credentials, $request->has('remember'))) {
            $user = Auth::user();
            
            // Check if user account is active
            if (!$user->is_active) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                // Use session flash message instead of validation error
                return redirect()->back()
                    ->with('error', 'Your account has been deactivated. Please contact support for assistance.')
                    ->withInput(['email' => $request->email]); // Keep email in form
            }
            
            // Regenerate session
            $request->session()->regenerate();

            // Redirect based on user role
            return redirect()->route($user->role . '.dashboard');
        }

        // Login failed - use validation error for invalid credentials
        return redirect()->back()
            ->withErrors(['email' => 'Invalid email or password. Please try again.'])
            ->withInput(['email' => $request->email]);
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}