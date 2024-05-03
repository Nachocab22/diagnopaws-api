<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PetController;
use App\Http\Controllers\UserController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
//Authenticate
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

//Endpoints Pet
Route::get('/pets', [PetController::class, 'index']);
Route::get('/pets/{pet}', [PetController::class, 'show']);
Route::post('/pets', [PetController::class, 'store']);
Route::delete('/pets/{pet}', [PetController::class, 'destroy']);
Route::put('/pets/{pet}', [PetController::class, 'update']);

//Endpoints User
Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{user}', [UserController::class, 'show']);
Route::post('/users', [UserController::class, 'store']);
