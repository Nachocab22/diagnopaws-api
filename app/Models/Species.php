<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Species extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * Get all the breeds for the Species
     *
     * @return HasMany
     */
    public function breeds(): HasMany
    {
        return $this->hasMany(Breed::class);
    }
}
