<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if($this->isMethod('post') && $this->route()->named('register')) {
            return true;
        }
        return true;
        //En este caso deberian poder añadir nuevos usuarios:
        // 1. desde el registro cualquier usuario
        // 2. desde el panel de administración cualquier usuario con rol de administrador
        // 3. desde el panel de administración cualquier usuario con rol de veterinario
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'dni' => 'required|string|max:9',
            'phone' => 'string|max:20',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|max:255',
            'gender_id' => 'required|exists:genders,id',
            'address_id' => 'required|exists:addresses,id',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Name is required.',
            'surname.required' => 'Surname is required.',
            'birth_date.required' => 'Birth date is required.',
            'dni.required' => 'DNI is required.',
            'email.unique' => 'The email has already been taken.',
            'email.required' => 'Email is required.',
            'email.email' => 'Email must be a valid email address.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'gender_id.required' => 'Gender is required.',
            'address_id.required' => 'Address is required.',
        ];
    }

}
