<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordRequest extends FormRequest
{
    public function rules()
    {
        return [
            'password' => 'required|string|min:4|max:255',
        ];
    }

    public function messages()
    {
        return [
            'password.required' => 'パスワードは必須です。',
            'password.string'   => 'パスワードは文字列で入力してください。',
            'password.min'      => 'パスワードは少なくとも8文字以上で入力してください。',
            'password.max'      => 'パスワードは最大で255文字まで入力できます。',
        ];
    }
}
