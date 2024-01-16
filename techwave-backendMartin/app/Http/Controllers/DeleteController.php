<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class DeleteController extends Controller
{
    public function deleteAccount(Request $request)
    {
        $user = Auth::user();

        // Sprawdź, czy użytkownik jest zalogowany
        if ($user) {
            try {
                $user = $request->user();
                Auth::logout();
                $user->delete();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return response()->json(['message' => 'Konto zostało usunięte.']);
               
              
            } catch (\Exception $e) {
                return response()->json(['error' => 'Wystąpił błąd podczas usuwania konta.'], 500);
            }
        } else {
            return response()->json(['error' => 'Nieprawidłowa autentykacja.'], 401);
        }
    }
}
