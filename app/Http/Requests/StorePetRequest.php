<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use App\Enums\SexEnum;

class StorePetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
        //En este caso deberian poder añadir nuevas mascotas:
        // 1. desde el panel de administración cualquier usuario con rol de veterinario
        // 2. desde el panel de administración cualquier usuario con rol de usuario
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'color' => 'required|string|max:255',
            'sex' => ['required', 'in:Male,Female'],
            'chip_number' => 'required|string|max:255|unique:App\Models\Pet,chip_number',
            'chip_marking_date' => 'required|date',
            'chip_position' => 'required|string|max:255',
            'user_id' => 'required|integer|exists:App\Models\User,id',
            'breed_id' => 'required|integer|exists:App\Models\Breed,id'
        ];
    }
}
