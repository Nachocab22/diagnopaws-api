<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreAddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'street' => 'required|string|max:255',
            'number' => 'required|integer',
            'flat' => 'nullable|string|max:5',
            'town_id' => 'required|integer|exists:Flogti\SpanishCities\Models\Town,id',
        ];
    }
}
