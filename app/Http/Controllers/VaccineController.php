<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVaccineRequest;
use App\Http\Requests\UpdateVaccineRequest;
use App\Http\Resources\VaccineResource;
use App\Models\Vaccine;

class VaccineController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vaccines = Vaccine::all();
        return VaccineResource::collection($vaccines);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVaccineRequest $request)
    {
        try {
            $vaccine = new Vaccine();
            $vaccine->fill($request->only(['name', 'manufacturer', 'sicknesses_treated']));

            $vaccine->save();
            return response()->json(['vaccine' => new VaccineResource($vaccine)], 201);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Error creating vaccine', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vaccine $vaccine)
    {
        try {
            $vaccine->delete();
            return response()->json(['message' => 'Vaccine deleted'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error deleting vaccine', 'message' => $e->getMessage()], 500);
        }
    }
}
