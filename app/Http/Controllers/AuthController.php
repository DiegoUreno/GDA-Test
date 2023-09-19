<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;

use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        Log::channel('custom')->info('Entrada de información: ' . json_encode($request->all()));

        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);


        if (!Auth::attempt($credentials)) {
            return response()->json(['success' => false, 'message' => 'Credenciales incorrectas'], 401);
        }


        $user = Auth::user();


        $existingToken = \App\Models\Token::where('user_id', $user->id)->first();


        $email = $user->email;
        $loginTime = now();
        $randomValue = mt_rand(200, 500);
        $token = sha1($email . $loginTime . $randomValue);


        $tokenLifetime = 300; 

        if ($existingToken) {

            $existingToken->update([
                'token' => $token,
                'expires_at' => now()->addSeconds($tokenLifetime),
            ]);
        } else {

            \App\Models\Token::create([
                'token' => $token,
                'user_id' => $user->id,
                'expires_at' => now()->addSeconds($tokenLifetime),
            ]);
        }
        
        $response = new Response(['success' => true, 'token' => $token]);

        if (config('app.debug')) {

            Log::channel('custom')->info('Salida de información: ' . json_encode($response->getContent()));
        }
        
        return $response;
    
    }

    public function register(Request $request)
    {

        $validatedData = $request->only(['name', 'email', 'password']);

        $user = new User;
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->password = Hash::make($validatedData['password']); 
        $user->save();

        $response = new Response(['success' => true, 'message' => 'Usuario registrado con éxito']);

        if (config('app.debug')) {

            Log::channel('custom')->info('Salida de información: ' . json_encode($response->getContent()));
        }
        
        return $response;
    }
}
