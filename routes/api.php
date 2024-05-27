<?php

use App\Http\Controllers\BreedController;
use App\Http\Controllers\GenderController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\SpeciesController;
use App\Http\Controllers\TownController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PetController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AddressController;

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
Route::post('/users', [UserController::class, 'store']);
Route::post('/login', [UserController::class, 'login']);

//Endpoints Location
Route::post('/addresses', [AddressController::class, 'store']);
Route::get('/towns', [TownController::class, 'index']);
Route::get('/provinces', [ProvinceController::class, 'index']);
Route::get('/genders', [GenderController::class, 'index']);
Route::get('/species', [SpeciesController::class, 'index']);
Route::get('/breeds', [BreedController::class, 'index']);


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/users', function (Request $request) {
        return $request->user();
    });

    //Authenticate
    Route::post('/logout', [UserController::class, 'logout']);

    //Endpoints Pet
    Route::get('/pets', [PetController::class, 'showPets']);
    Route::get('/pets/{pet}', [PetController::class, 'show']);
    Route::post('/pets', [PetController::class, 'store']);
    Route::delete('/pets/{pet}', [PetController::class, 'destroy']);
    Route::put('/pets/{pet}', [PetController::class, 'update']);
    Route::get('/user/pets', [PetController::class, 'userPets']);

    //Endpoints User
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/user', [UserController::class, 'show']);

    //Endpoints Address
    Route::get('/addresses/{address}', [AddressController::class, 'show']);
});
