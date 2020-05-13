<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Keygen\Keygen;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|sudo a2enmod rewrite
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::prefix('v1')->group(function () {
    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/login', 'API\UserController@login');
    Route::post('/register', 'API\UserController@register');
    Route::get('/logout', 'API\UserController@logout');

//    Account Generator
    Route::get('/newAccountNumber', function (){
        return response()->json([
            "Account_Number" => Keygen::numeric(16)->generate()
        ], 200);
    });
});
