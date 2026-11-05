<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReceitaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_categorias' => 'nullable|integer|exists:categorias,id',
            'nome' => 'nullable|string|max:45',
            'tempo_preparo_minutos' => 'nullable|integer|min:1',
            'porcoes' => 'nullable|integer|min:1',
            'modo_preparo' => 'required|string',
            'ingredientes' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'id_categorias.exists' => 'Categoria não encontrada.',
            'nome.max' => 'O nome pode ter no máximo 45 caracteres.',
            'modo_preparo.required' => 'Informe o modo de preparo.',
        ];
    }
}
