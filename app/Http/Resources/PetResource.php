<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\BreedResource;
use App\Http\Resources\SpeciesResource;
use Illuminate\Support\Facades\Storage;

class PetResource extends JsonResource
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
            'birth_date' => $this->birth_date,
            'color' => $this->color,
            'sex' => $this->sex,
            'chip' => [
                'number' => $this->chip_number,
                'marking_date' => $this->chip_marking_date,
                'position' => $this->chip_position
            ],
            'breed' => new BreedResource($this->breed),
            'species' => new SpeciesResource($this->breed->species),
            'owner' => $this->owner->name . ' ' . $this->owner->surname,
            'image' => $this->image ? url('storage/' . $this->image) : null
        ];
    }
}
