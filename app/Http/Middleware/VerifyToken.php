<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cache;

class VerifyToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('Authorization');


        if (!$token) {
            return response()->json(['success' => false, 'message' => 'No autenticado'], 401);
        }


        $token = str_replace('Bearer ', '', $token);


        $user = $this->getUserByToken($token);

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Token no encontrado o ha expirado'], 401);
        }


        return $next($request);
    }
    private function getUserByToken($token)
    {

        $tokenRecord = \App\Models\Token::where('token', $token)->first();


        if (!$tokenRecord) {
            return null;
        }


        $currentTime = now();
        $tokenExpirationTime = $tokenRecord->expires_at;
        

        if ($currentTime > $tokenExpirationTime) {

            $tokenRecord->delete();
            return null;
        }


        $user = \App\Models\User::find($tokenRecord->user_id);

        return $user;
    }
}
