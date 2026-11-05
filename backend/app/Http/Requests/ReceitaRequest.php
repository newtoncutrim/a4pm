<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ReceitaRequest extends FormRequest
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
            'id_categorias' => ['required', 'integer', 'exists:categorias,id'],
            'nome' => ['required', 'string', 'min:3', 'max:45'],
            'tempo_preparo_minutos' => ['nullable', 'integer', 'min:1'],
            'porcoes' => ['nullable', 'integer', 'min:1'],
            'modo_preparo' => ['required', 'string'],
            'ingredientes' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'id_categorias.required' => 'Informe a categoria.',
            'id_categorias.exists' => 'Categoria não encontrada.',
            'nome.required' => 'Informe o nome.',
            'nome.min' => 'O nome precisa ter pelo menos 3 caracteres.',
            'nome.max' => 'O nome pode ter no máximo 45 caracteres.',
            'modo_preparo.required' => 'Informe o modo de preparo.',
        ];
    }
}
