<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAddressRequest;
use App\Http\Requests\UpdateAddressRequest;
use App\Http\Resources\AddressResource;
use App\Models\Address;
use http\Env\Response;
use Illuminate\Routing\Controller;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $addresses = Address::all();
        return AddressResource::collection($addresses);
    }

    /**
     * Display the specified resource.
     */
    public function show(Address $address)
    {
        return new AddressResource($address);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAddressRequest $request)
    {
        try {
            $existingAddress = Address::where('street', $request->street)
                ->where('number', $request->number)
                ->where('flat', $request->flat)
                ->where('town_id', $request->town_id)
                ->first();
            if ($existingAddress) {
                return response()->json(['address' => new AddressResource($existingAddress)], 200);
            }

            $address = new Address();
            $address->fill($request->validated());
            $address->town()->associate($request->town_id);
            $address->save();
            return response()->json(['address' => new AddressResource($address)],201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error creating address'], 500);
        }

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Address $address)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAddressRequest $request, Address $address)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Address $address)
    {
        //
    }
}
