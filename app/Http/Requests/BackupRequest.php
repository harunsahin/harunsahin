<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BackupRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'tables' => 'nullable|array',
            'tables.*' => 'required_if:tables,*',
            'directories' => 'nullable|array',
            'directories.*' => 'required_if:directories,*',
            'backup_location' => 'required|in:default,custom',
            'custom_path' => 'required_if:backup_location,custom'
        ];
    }

    public function messages()
    {
        return [
            'tables.*.required_if' => 'Seçilen tablolar boş olamaz.',
            'directories.*.required_if' => 'Seçilen dizinler boş olamaz.',
            'backup_location.required' => 'Yedekleme konumu seçilmelidir.',
            'backup_location.in' => 'Geçersiz yedekleme konumu.',
            'custom_path.required_if' => 'Özel konum seçildiğinde yol belirtilmelidir.'
        ];
    }
} 