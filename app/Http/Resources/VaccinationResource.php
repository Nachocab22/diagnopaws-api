<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VaccinationResource extends JsonResource
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
            'vaccine' => new VaccineResource($this->vaccine),
            'vaccination_date' => $this->vaccination_date,
            'next_vaccination_date' => $this->next_vaccination_date,
            'sicknesses_treated' => $this->vaccine->sicknesses_treated,
        ];
    }
}
