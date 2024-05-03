<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Species extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * Get all of the breeds for the Species
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function breeds(): HasMany
    {
        return $this->hasMany(Breed::class);
    }
}
