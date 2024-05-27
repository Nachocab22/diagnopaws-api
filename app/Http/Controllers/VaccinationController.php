<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVaccinationRequest;
use App\Http\Requests\UpdateVaccinationRequest;
use App\Models\Vaccination;

class VaccinationController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVaccinationRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Vaccination $vaccination)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVaccinationRequest $request, Vaccination $vaccination)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vaccination $vaccination)
    {
        //
    }
}
