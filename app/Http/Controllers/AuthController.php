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

        // Format phone number - automatically add Malaysia country code (6)
        $phoneNumber = $this->formatMalaysiaPhoneNumber($request->phone_number);

        // Create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $phoneNumber, // Use formatted phone number
            'password' => Hash::make($request->password),
            'role' => $request->role
        ]);

        // Login the user after registration
        Auth::login($user);

        // Redirect based on role
        return redirect()->route($user->role . '.dashboard');
    }

    /**
     * Format Malaysian phone number to include country code
     * 
     * @param string $phoneNumber
     * @return string
     */
    private function formatMalaysiaPhoneNumber($phoneNumber)
    {
        // Remove all non-numeric characters
        $cleanNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
        
        // If number already starts with 6 (already has country code), return as is
        if (substr($cleanNumber, 0, 1) === '6') {
            return $cleanNumber;
        }
        
        // Just add 6 in front of the number (keep the 0)
        return '6' . $cleanNumber;
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

        // Attempt login
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