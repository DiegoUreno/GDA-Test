<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/login', 'AuthController@login');
Route::post('/register', 'AuthController@register')->middleware('validate.user.data'); 


Route::post('customers/', 'CustomerController@register')->middleware(['verify.token', 'validate.customer.data']);
Route::get('customers/{identifier}', 'CustomerController@findByDniOrEmail')->middleware(['verify.token']);
Route::delete('customers/{dni}', 'CustomerController@softDelete')->middleware(['verify.token']);

