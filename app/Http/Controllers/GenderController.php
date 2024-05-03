<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGenderRequest;
use App\Http\Requests\UpdateGenderRequest;
use App\Http\Resources\GenderResource;
use App\Models\Gender;

class GenderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $genders = Gender::all();
        return GenderResource::collection($genders);
    }

    /**
     * Display the specified resource.
     */
    public function show(Gender $gender)
    {
        return new GenderResource($gender);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGenderRequest $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Gender $gender)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGenderRequest $request, Gender $gender)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Gender $gender)
    {
        //
    }
}
