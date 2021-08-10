<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'         => 'required',
            //Obrigatório e com tamanho mínimo de 10 caracteres separado por "|"
            'description'  => 'required|min:10',
            'phone'        => 'required',
            'mobile_phone' => 'required',
            'logo' => 'image'
        ];
    }

    public function messages()
    {
        return [
            //:attribute 
            'required' => 'Este campo é obrigatório',
            //Para erro "min" definido em rules, imprimirá a mensagem
            'min' => 'Campo deve ter no mínimo :min caracteres',
            'image'    => 'Arquivo não é uma imagem válida!'
        ];
    }
}