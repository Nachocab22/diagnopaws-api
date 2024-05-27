<?php

namespace App\Models;

use Flogti\SpanishCities\Models\Town;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Flogti\SpanishCities\Traits\HasTown;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    use HasFactory, HasTown;

    protected $fillable = ['street', 'number', 'flat', 'town_id'];

    /**
     * Get all the tenants for the Address
     *
     * @return HasMany
     */
    public function tenants(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the city that owns the Address
     *
     * @return BelongsTo
     */
    public function town(): BelongsTo
    {
        return $this->belongsTo(Town::class);
    }
}
