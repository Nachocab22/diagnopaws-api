<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePetRequest;
use App\Http\Requests\UpdatePetRequest;
use App\Http\Resources\PetResource;
use App\Models\Pet;
use App\Models\User;
use App\Models\Breed;
use Illuminate\Routing\Controller;

class PetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pets = Pet::all();
        return PetResource::collection($pets);
    }

    /**
     * Display the specified resource.
     */
    public function show(Pet $pet)
    {
        return new PetResource($pet);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePetRequest $request)
    {
        $pet = new Pet($request->all());
        $pet->owner()->associate(User::find($request->owner_id));
        $pet->breed()->associate(Breed::find($request->breed_id));
        $pet->save();
        return new PetResource($pet);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pet $pet)
    {
        $pet->deleteOrFail();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePetRequest $request, Pet $pet)
    {
        $pet->name = $request->name;
        $pet->birth_date = $request->birth_date;
        $pet->color = $request->color;
        $pet->sex = $request->sex;
        $pet->chip_number = $request->chip_number;
        $pet->chip_marking_date = $request->chip_marking_date;
        $pet->chip_position = $request->chip_position;

        $pet->save();

        return new PetResource($pet);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pet $pet)
    {
//        $pet->name = $request->name ?? $pet->name;
//        $pet->birth_date = $request->birth_date ?? $pet->birth_date;
//        $pet->color = $request->color ?? $pet->color;
//        $pet->sex = $request->sex ?? $pet->sex;
//        $pet->chip_number = $request->chip_number ?? $pet->chip_number;
//        $pet->chip_marking_date = $request->chip_marking_date ?? $pet->chip_marking_date;
//        $pet->chip_position = $request->chip_position ?? $pet->chip_position;
    }
}
