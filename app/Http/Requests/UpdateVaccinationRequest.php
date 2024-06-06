<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateVaccinationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()->can('update vaccination');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'vaccination_date' => 'required|date',
            'next_vaccination_date' => 'required|date',
            'lot_number' => 'nullable|integer',
            'pet_id' => 'required|exists:pets,id',
            'vaccine_id' => 'required|exists:vaccines,id',
        ];
    }
}
