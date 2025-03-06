<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:admin,user',
        ];

        if ($this->isMethod('POST')) {
            $rules['password'] = 'required|min:8';
        }

        if ($this->isMethod('PUT')) {
            $rules['email'] = 'required|email|unique:users,email,' . $this->user->id;
            $rules['password'] = 'nullable|min:8';
        }

        return $rules;
    }
} 