<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\User;
use App\Models\Breed;

class Pet extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'birth_date', 'color', 'sex', 'chip_number', 'chip_marking_date', 'chip_position', 'image'];

    /**
     * Get the owner that owns the Pet
     *
     * @return BelongsTo
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the breed that owns the Pet
     *
     * @return BelongsTo
     */
    public function breed(): BelongsTo
    {
        return $this->belongsTo(Breed::class);
    }
}
