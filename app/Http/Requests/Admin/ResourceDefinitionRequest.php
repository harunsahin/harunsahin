<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ResourceDefinitionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'kaynak' => 'required|string|max:255',
            'is_active' => 'required|boolean',
            'position' => 'nullable|integer',
        ];

        // Eğer güncelleme işlemi ise ve kaynak değişiyorsa unique kontrolü ekle
        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $rules['kaynak'] .= ',kaynak_tanimlari,kaynak,' . $this->route('id');
        } else {
            $rules['kaynak'] .= '|unique:kaynak_tanimlari,kaynak';
        }

        return $rules;
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'kaynak' => 'Kaynak',
            'is_active' => 'Durum',
            'position' => 'Sıra',
        ];
    }
} 