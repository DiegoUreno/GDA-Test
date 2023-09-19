<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ValidateUserData
{
    public function handle($request, Closure $next)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
        ]);


        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Fallo la validación de datos', 'errors' => $validator->errors()], 422);
        }


        $request->merge($validator->validated());

        return $next($request);
    }
}
