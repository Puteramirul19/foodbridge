<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Show the profile view page
     */
    public function show()
    {
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }

    /**
     * Show the profile edit form
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Update the user's profile information
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id)
            ],
            'phone_number' => 'required|string|max:20',
            'password' => 'nullable|min:8|confirmed',
        ], [
            'name.required' => 'Name is required.',
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already taken.',
            'phone_number.required' => 'Phone number is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        // Format phone number - automatically add Malaysia country code (6)
        $phoneNumber = $this->formatMalaysiaPhoneNumber($request->phone_number);

        // Update user information
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone_number = $phoneNumber; // Use formatted phone number

        // Only update password if provided
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('profile.show')->with('success', 'Profile updated successfully!');
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
}