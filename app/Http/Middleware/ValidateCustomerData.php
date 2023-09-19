<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Commune;

class ValidateCustomerData
{
    public function handle(Request $request, Closure $next)
    {

        $validator = Validator::make($request->all(), [
            'dni' => 'required|unique:customers',
            'id_reg' => 'required|exists:regions,id_reg',
            'id_com' => 'required|exists:communes,id_com',
            'email' => 'required|email|unique:customers',
            'name' => 'required',
            'last_name' => 'required',
            'address' => 'nullable',
        ]);


        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Fallo la validación de datos', 'errors' => $validator->errors()], 422);
        }


        $validatedData = $request->only(['dni', 'id_reg', 'id_com', 'email', 'name', 'last_name', 'address']);

        $commune = Commune::find($validatedData['id_com']);
        if ($commune->id_reg !== $validatedData['id_reg']) {
            return response()->json(['success' => false, 'message' => 'La commune no pertenece a la región especificada']);
        }


        $request->merge($validatedData);

        return $next($request);
    }
}