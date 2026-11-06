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
            'nome' => ['required', 'string', 'min:3', 'max:100'],
            'login' => ['required', 'string', 'max:100', 'unique:usuarios,login'],
            'senha' => ['required', 'string', 'min:3', 'max:72'],
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'Informe o nome.',
            'nome.min' => 'O nome precisa ter pelo menos 3 caracteres.',
            'nome.max' => 'O nome pode ter no máximo 100 caracteres.',
            'login.required' => 'Informe o login.',
            'login.max' => 'O login pode ter no máximo 100 caracteres.',
            'login.unique' => 'Esse login já está em uso.',
            'senha.required' => 'Informe a senha.',
            'senha.min' => 'A senha precisa ter pelo menos 3 caracteres.',
            'senha.max' => 'A senha pode ter no máximo 72 caracteres.',
        ];
    }
}
