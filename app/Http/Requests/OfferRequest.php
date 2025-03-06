<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OfferRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'agency_id' => 'nullable|exists:agencies,id|required_without:company_id',
            'company_id' => 'nullable|exists:companies,id|required_without:agency_id',
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'checkin_date' => 'required|date',
            'checkout_date' => 'required|date|after:checkin_date',
            'room_count' => 'required|integer|min:1',
            'pax_count' => 'required|integer|min:1',
            'option_date' => 'required|date',
            'status_id' => 'required|exists:statuses,id',
            'notes' => 'nullable|string',
            'files.*' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:5120'
        ];
    }

    public function messages()
    {
        return [
            'files.*.mimes' => 'Dosya türü desteklenmiyor. Desteklenen türler: PDF, DOC, DOCX, XLS, XLSX, JPG, JPEG, PNG',
            'files.*.max' => 'Dosya boyutu en fazla 5MB olabilir.',
            'files.*.file' => 'Yüklenen dosya geçerli bir dosya değil.',
            'agency_id.required_without' => 'Acente veya şirket seçilmelidir.',
            'company_id.required_without' => 'Acente veya şirket seçilmelidir.',
            'checkout_date.after' => 'Çıkış tarihi giriş tarihinden sonra olmalıdır.'
        ];
    }
} 