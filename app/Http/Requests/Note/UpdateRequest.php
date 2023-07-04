<?php

namespace App\Http\Requests\Note;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function rules()
    {
        return [
            'title'          => "required|string|max:65535",
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'タイトルは必須です。',
            'title.string'   => 'タイトルは文字列で指定してください。',
            'title.max'      => 'タイトルは65535文字以内で指定してください。',
        ];
    }
}
