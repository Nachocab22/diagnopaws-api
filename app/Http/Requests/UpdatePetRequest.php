<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdatePetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()->can('update pet');
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
            'chip_number' => 'nullable|string|max:255|unique:App\Models\Pet,chip_number',
            'chip_marking_date' => 'nullable|date',
            'chip_position' => 'nullable|string|max:255',
            'user_id' => 'required|integer|exists:App\Models\User,id',
            'breed_id' => 'required|integer|exists:App\Models\Breed,id',
            'image' => 'nullable|image|mimes:jpeg,png,svg|max:2048',
        ];
    }
}
