<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\GenderResource;
use App\Http\Resources\AddressResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'surname' => $this->surname,
            'birth_date' => $this->birth_date->format('Y-m-d'),
            'dni' => $this->dni,
            'phone' => $this->phone,
            'email' => $this->email,
            'gender' => new GenderResource($this->gender),
            'address' => new AddressResource($this->address)
        ];
    }
}
