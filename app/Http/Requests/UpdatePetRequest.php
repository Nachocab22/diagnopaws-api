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
            'name' => 'sometimes|required|string|max:255',
            'birth_date' => 'sometimes|required|date',
            'color' => 'sometimes|required|string|max:255',
            'sex' => 'sometimes|required|enum',
            'chip_number' => 'sometimes|required|string|max:255|unique:App\Models\Pet,chip_number',
            'chip_marking_date' => 'sometimes|required|date',
            'chip_position' => 'sometimes|required|string|max:255'
        ];
    }
}
