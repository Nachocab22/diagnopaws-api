<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
        //En este caso deberian poder añadir nuevas mascotas:
        // 1. desde el panel de administración cualquier usuario con rol de veterinario
        // 2. desde el panel de administración cualquier usuario con rol de usuario
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'color' => 'required|string|max:255',
            'sex' => 'required|enum',
            'chip_number' => 'required|string|max:255|unique:App\Models\Pet,chip_number',
            'chip_marking_date' => 'required|date',
            'chip_position' => 'required|string|max:255',
            'user_id' => 'required|integer|exists:App\Models\User,id',
            'breed_id' => 'required|integer|exists:App\Models\Breed,id'
        ];
    }
}
