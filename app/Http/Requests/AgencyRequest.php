<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AgencyRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'is_active' => 'boolean'
        ];

        // Eğer yeni kayıt oluşturuluyorsa unique kuralını ekle
        if ($this->isMethod('post')) {
            $rules['name'] .= '|unique:agencies,name';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'Acente adı zorunludur.',
            'name.max' => 'Acente adı en fazla 255 karakter olabilir.',
            'name.unique' => 'Bu isimde bir acente zaten mevcut.',
            'email.email' => 'Geçerli bir e-posta adresi giriniz.',
            'phone.max' => 'Telefon numarası en fazla 20 karakter olabilir.'
        ];
    }
} 