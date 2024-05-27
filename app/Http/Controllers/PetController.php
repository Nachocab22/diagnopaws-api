<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePetRequest;
use App\Http\Requests\UpdatePetRequest;
use App\Http\Resources\PetResource;
use App\Models\Pet;
use App\Models\User;
use App\Models\Breed;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class PetController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index() {
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
        try {
            $pet = new Pet();
            $pet->fill($request->only(['name', 'birth_date', 'color', 'chip_number', 'chip_marking_date', 'chip_position', 'sex']));

            $owner = User::find($request->user_id);
            $breed = Breed::find($request->breed_id);

            if (!$owner || !$breed) {
                return response()->json(['message' => 'Invalid owner or breed provided'], 400);
            }

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('pets', 'public');
                $pet->image = $imagePath;
            }

            $pet->owner()->associate($owner);
            $pet->breed()->associate($breed);

            $pet->save();
            return response()->json(['pet' => new PetResource($pet)], 201);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Error creating pet', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pet $pet)
    {
        try {
            $pet->deleteOrFail();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error deleting pet'], 500);
        }
        return response()->json(['message' => 'Pet deleted'], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePetRequest $request, Pet $pet)
    {
        try {
            $pet->fill($request->validated());
            $pet->save();
            return new PetResource($pet);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error updating pet', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the pets of the specified user.
     */
    public function userPets()
    {
        $pets = Auth::user()->pets;
        return response()->json(['pets' => PetResource::collection($pets)], 200);
    }
}
