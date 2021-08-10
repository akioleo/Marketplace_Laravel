<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'name' => 'required',
            //Obrigatório e com tamanho mínimo de 30 caracteres separado por "|"
            'description' => 'required|min:30',
            'body' => 'required',
            'price' => 'required',
            //Como photos está indo por array, deve-se colocar a extensao ".*" para validar todos os itens deste array
	        'photos.*' => 'image'
        ];
    }

    public function messages()
    {
    	return [
            //:attribute 
    		'required' => 'Este campo é obrigatório!',
            //Para erro "min" definido em rules, imprimirá a mensagem
		    'min'      => 'Campo deve ter no mínimo :min caracteres',
		    'image'    => 'Arquivo não é uma imagem válida!'
	    ];
    }
}