<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ResetPasswordController extends Controller
{
    public function changePassword(Request $request)
    {
        $user = Auth::user();

        
        $request->session()->forget('errors');

        $request->validate([
            'current_password' => 'required|string|min:8', 
            'new_password' => 'required|string|min:8|different:current_password', 
        ]);

        if (Hash::check($request->input('current_password'), $user->password)) {
            $newPassword = $request->input('new_password');

            
            $user->update(['password' => bcrypt($newPassword)]);

            return response()->json(['success' => true, 'message' => 'Hasło zostało pomyślnie zmienione']);
        } else {
            throw ValidationException::withMessages(['error' => 'Podane stare hasło jest nieprawidłowe']);
        }
    }
}
