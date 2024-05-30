<?php

use App\Http\Controllers\BreedController;
use App\Http\Controllers\GenderController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\SpeciesController;
use App\Http\Controllers\TownController;
use App\Http\Controllers\VaccineController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PetController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AddressController;

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
    Route::get('/pets/search/{query}', [PetController::class, 'search']);

    //Endpoints User
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/user', [UserController::class, 'show']);
    Route::put('/user/{user}', [UserController::class, 'update']);
    Route::put('/user', [UserController::class, 'updateProfile']);
    Route::delete('/user/{user}', [UserController::class, 'destroy']);
    Route::get('user/search/{query}', [UserController::class, 'search']);

    //Endpoints Vaccinations
    Route::get('/vaccinations', [VaccineController::class, 'index']);
    Route::post('/vaccinations', [VaccineController::class, 'store']);
    Route::get('/vaccinations/{vaccination}', [VaccineController::class, 'show']);
    Route::put('/vaccinations/{vaccination}', [VaccineController::class, 'update']);
    Route::delete('/vaccinations/{vaccination}', [VaccineController::class, 'destroy']);

    //Endpoints Vaccines
    Route::get('/vaccines', [VaccineController::class, 'index']);
    Route::post('/vaccines', [VaccineController::class, 'store']);
    Route::delete('/vaccines/{vaccine}', [VaccineController::class, 'destroy']);

    //Endpoints Address
    Route::get('/addresses/{address}', [AddressController::class, 'show']);
});
