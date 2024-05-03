<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; // Add missing import
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Add missing import

class Breed extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * Get all of the pets for the Breed
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pets(): HasMany
    {
        return $this->hasMany(Pet::class);
    }

    /**
     * Get the species that owns the Breed
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function species(): BelongsTo
    {
        return $this->belongsTo(Species::class);
    }
}
