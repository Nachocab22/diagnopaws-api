<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTownRequest;
use App\Http\Requests\UpdateTownRequest;
use App\Http\Resources\TownResource;
use Flogti\SpanishCities\Models\Town;
use Illuminate\Routing\Controller;

class TownController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cities = Town::all();
        return TownResource::collection($cities);
    }

    /**
     * Display the specified resource.
     */
    public function show(Town $town)
    {
        return new TownResource($town);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTownRequest $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Town $town)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTownRequest $request, Town $town)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Town $town)
    {
        //
    }
}
