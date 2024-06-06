<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVaccinationRequest;
use App\Http\Requests\UpdateVaccinationRequest;
use App\Http\Resources\VaccinationResource;
use App\Models\Pet;
use App\Models\Vaccination;
use App\Models\Vaccine;

class VaccinationController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vaccinations = Vaccination::all();
        return response()->json(['vaccinations' => VaccinationResource::collection($vaccinations)], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Vaccination $vaccination)
    {
        return response()->json(['vaccination' => new VaccinationResource($vaccination)], 200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVaccinationRequest $request)
    {
        try {
            $vaccination = new Vaccination();
            $vaccination->fill($request->only(['vaccination_date', 'next_vaccination_date', 'lot_number']));

            $pet = Pet::find($request->pet_id);
            $vaccine = Vaccine::find($request->vaccine_id);

            if (!$pet || !$vaccine) {
                return response()->json(['message' => 'Invalid pet or vaccine provided'], 400);
            }

            $vaccination->pet()->associate($pet);
            $vaccination->vaccine()->associate($vaccine);

            $vaccination->save();
            return response()->json(['vaccination' => new VaccinationResource($vaccination)], 201);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Error creating vaccination', 'message' => $e->getMessage()], 500);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVaccinationRequest $request, Vaccination $vaccination)
    {
        try {
            $vaccination->fill($request->validated());
            $vaccination->save();
            return response()->json(['vaccination' => new VaccinationResource($vaccination)], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error updating vaccination', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vaccination $vaccination)
    {
        try {
            $vaccination->delete();
            return response()->json(['message' => 'Vaccination deleted'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error deleting vaccination', 'message' => $e->getMessage()], 500);
        }
    }
}
