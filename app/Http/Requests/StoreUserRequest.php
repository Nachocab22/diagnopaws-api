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
        return auth()->user()->can('create user');
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'dni' => 'required|string|max:9',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|max:255',
            'gender_id' => 'required|exists:genders,id',
            'address_id' => 'required|exists:addresses,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'surname.required' => 'El apellido es obligatorio.',
            'birth_date.required' => 'La fecha de nacimiento es obligatoria.',
            'dni.required' => 'El campo DNI es obligatorio.',
            'email.unique' => 'Este email ya esta en uso.',
            'email.required' => 'El email es obligatorio.',
            'email.email' => 'El email debe ser con el formato correcto.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener mas de 8 caracteres.',
            'gender_id.required' => 'Indique su género.',
            'address_id.required' => 'Introduzca una dirección.',
        ];
    }

}
