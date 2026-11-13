<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class PasswordController extends Controller
{
    /**
     * Show the password reset request form.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Show the new password form (after clicking the email link).
     */
    public function update(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'confirmed', Password::defaults()],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('status', 'Password updated successfully.');
    }
}

