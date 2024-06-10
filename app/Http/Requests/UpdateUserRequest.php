<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()->can('update user');
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
            'surname' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'dni' => 'required|string|max:9',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:9',
            'gender_id' => 'required|exists:genders,id',
            'address_id' => 'required|exists:addresses,id',
        ];
    }
}
