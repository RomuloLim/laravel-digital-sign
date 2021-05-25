<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdatePdf extends FormRequest
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
            'pdf' => ['required', 'file', 'mimes:pdf'],
            'sign_name' => ['required', 'file'],
            'password' => ['required']
        ];
    }

    public function messages(){
        return [
            'message' => 'Erro ao processar os dados de envio',
            'required' => 'O campo :attribute é obrigatório',
            'file' => 'O campo :attribute deve receber um arquivo',
            'pdf.mimes' => 'O campo :attribute deve receber um arquivo PDF',
        ];
    }
}
