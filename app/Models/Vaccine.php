<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vaccine extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'manufacturer', 'sicknesses_treated'];

    public function vaccinations(): HasMany
    {
        return $this->hasMany(Vaccination::class);
    }

}
