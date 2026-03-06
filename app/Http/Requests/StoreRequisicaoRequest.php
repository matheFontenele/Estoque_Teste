<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequisicaoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'equipamento_id' => 'required|exists:equipamentos,id',
            'cliente_id' => 'required|exists:clientes,id',
            'tipo' => 'required|in:novo,substituicao',
            'etiqueta' => 'required|in:Moreia,Alucom,ZapLoc',
            'tombo_antigo' => 'required_if:tipo,substituicao', // Obrigatório se for troca
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $equipamento = \App\Models\Equipamento::find($this->equipamento_id);

            // Verifica se o equipamento que você quer enviar está disponível
            if ($equipamento && $equipamento->situacao !== 'disponivel') {
                $validator->errors()->add('equipamento_id', 'Este equipamento não está disponível no estoque.');
            }
        });
    }
}
