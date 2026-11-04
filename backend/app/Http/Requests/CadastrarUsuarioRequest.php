<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CadastrarUsuarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nome' => ['nullable', 'string', 'max:100'],
            'login' => ['required', 'string', 'max:100', 'unique:usuarios,login'],
            'senha' => ['required', 'string', 'min:3', 'max:72'],
        ];
    }

    public function messages(): array
    {
        return [
            'login.required' => 'Informe o login.',
            'login.unique' => 'Esse login já está em uso.',
            'senha.required' => 'Informe a senha.',
            'senha.min' => 'A senha precisa ter pelo menos 3 caracteres.',
        ];
    }
}
