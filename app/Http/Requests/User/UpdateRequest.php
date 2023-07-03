<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function rules()
    {
        $userId = $this->input('id');
        return [
            'name'     => 'required|string|max:255',
            'email'    => "required|string|email|unique:users,email,$userId|max:191",
            'password' => 'required|string|min:4|max:255',
        ];
    }

    public function messages()
    {
        return [
            'name.required'     => 'ユーザー名は必須です。',
            'name.string'       => 'ユーザ名は文字列で入力してください。',
            'name.max'          => 'ユーザ名は最大で255文字まで入力できます。',
            'email.required'    => 'メールアドレスは必須です。',
            'email.string'      => 'メールアドレスは文字列で入力してください。',
            'email.email'       => '有効なメールアドレスの形式で入力してください。',
            'email.unique'      => '入力されたメールアドレスは既に使用されています。',
            'email.max'         => 'メールアドレスは最大で255文字まで入力できます。',
            'password.required' => 'パスワードは必須です。',
            'password.string'   => 'パスワードは文字列で入力してください。',
            'password.min'      => 'パスワードは少なくとも8文字以上で入力してください。',
            'password.max'      => 'パスワードは最大で255文字まで入力できます。',
        ];
    }
}
