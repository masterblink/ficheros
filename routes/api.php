<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoginController as LoginV1;
use App\Http\Controllers\Api\V1\FicheroController as FicheroV1;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/auth/login', [LoginV1::class, 'login']);

Route::group(['prefix' => 'V1/ficheros', 'middleware' => ['auth:sanctum','throttle:3,1']], function() {

    Route::get('/', [FicheroV1::class, 'index']);
    Route::get('/{id}', [FicheroV1::class, 'show']);
    Route::delete('/{id}', [FicheroV1::class, 'destroy']);

    Route::post('/', [FicheroV1::class, 'store']);
    Route::post('/massive', [FicheroV1::class, 'storeMassive']);

});

/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
}); */
