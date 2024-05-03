<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBreedRequest;
use App\Http\Requests\UpdateBreedRequest;
use App\Http\Resources\BreedResource;
use App\Models\Breed;

class BreedController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $breeds = Breed::all();
        return BreedResource::collection($breeds);
    }

    /**
     * Display the specified resource.
     */
    public function show(Breed $breed)
    {
        return new BreedResource($breed);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBreedRequest $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Breed $breed)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBreedRequest $request, Breed $breed)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Breed $breed)
    {
        //
    }
}
