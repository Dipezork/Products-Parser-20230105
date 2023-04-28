<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//endpoint GET /
Route::get('/', [ProductController::class, 'apiDetails']);

//endpoint PUT /products/:code
Route::put('/products/{code}', [ProductController::class, 'update']);

//endpoint DELETE /products/:code
Route::delete('/products/{code}', [ProductController::class, 'destroy']);

//endpoint GET /products/:code
Route::get('/products/{code}', [ProductController::class, 'show']);

//endpoint GET /products
Route::get('/products', [ProductController::class, 'index']);

//import
Route::get('/import', 'App\Http\Controllers\ImportController@import');

