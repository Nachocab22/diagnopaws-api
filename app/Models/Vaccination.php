<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Vaccination extends Model
{
    use HasFactory;

    protected $fillable = ['vaccination_date', 'next_vaccination_date', 'lot_number', 'pet_id'];

/**
     * Get the pet that owns the Vaccination
     *
     * @return BelongsTo
     */
    public function pet(): BelongsTo
    {
        return $this->belongsTo(Pet::class, 'pet_id');
    }

    public function vaccine(): belongsTo
    {
        return $this->belongsTo(Vaccine::class, 'vaccine_id');
    }

}
