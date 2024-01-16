<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Tylko zalogowani użytkownicy mogą się zapisać do newslettera.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Nieprawidłowy format adresu e-mail.'], 400);
        }

        $email = $request->input('email');

        if (!$user->newsletter) {
            // Sprawdź, czy użytkownik o podanym adresie e-mail istnieje
            $existingUser = User::where('email', $email)->first();

            if (!$existingUser) {
                return response()->json(['message' => 'Użytkownik o podanym adresie e-mail nie istnieje.'], 404);
            }

            // Zapisz subskrypcję dla użytkownika
            $existingUser->newsletter = true;
            $existingUser->save();

            return response()->json(['message' => 'Subskrypcja zapisana pomyślnie']);
        } else {
            return response()->json(['message' => 'Użytkownik jest już zapisany do newslettera.']);
        }
    }
}
