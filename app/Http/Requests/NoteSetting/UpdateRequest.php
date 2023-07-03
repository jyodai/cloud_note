<?php

namespace App\Http\Requests\NoteSetting;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function rules()
    {
        return [
            'editor_option' => 'required|string|max:65535',
            'editor_css'    => 'string|max:65535',
        ];
    }

    public function messages()
    {
        return [
            'editor_option.required' => 'エディタオプションは必須です。',
            'editor_option.string'   => 'エディタオプションは文字列で入力してください。',
            'editor_option.max'      => 'エディタオプションは最大で65535文字まで入力できます。',
            'editor_css.string'      => 'CSSは文字列で入力してください。',
            'editor_css.max'         => 'CSSは最大で65535文字まで入力できます。',
        ];
    }
}
