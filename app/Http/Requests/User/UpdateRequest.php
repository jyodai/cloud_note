<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function rules()
    {
        $userId = $this->input('id');
        return [
            'name'     => 'required',
            'email'    => "required|email|unique:users,email,$userId",
            'password' => 'required|min:4',
        ];
    }

    public function messages()
    {
        return [
            'name.required'     => 'ユーザー名は必須です。',
            'email.required'    => 'メールアドレスは必須です。',
            'email.email'       => '有効なメールアドレスの形式で入力してください。',
            'email.unique'      => '入力されたメールアドレスは既に使用されています。',
            'password.required' => 'パスワードは必須です。',
            'password.min'      => 'パスワードは少なくとも8文字以上で入力してください。',
        ];
    }
}
