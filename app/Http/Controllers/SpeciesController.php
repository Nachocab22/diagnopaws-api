<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSpeciesRequest;
use App\Http\Requests\UpdateSpeciesRequest;
use App\Http\Resources\SpeciesResource;
use App\Models\Species;

class SpeciesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $species = Species::all();
        return SpeciesResource::collection($species);
    }

    /**
     * Display the specified resource.
     */
    public function show(Species $species)
    {
        return new SpeciesResource($species);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSpeciesRequest $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Species $species)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSpeciesRequest $request, Species $species)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Species $species)
    {
        //
    }
}
