<?php

namespace App\Http\Requests\Canvas;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function rules()
    {
        return [
            'content' => "string|max:65535",
        ];
    }

    public function messages()
    {
        return [
            'content.string' => 'タイトルは文字列で指定してください。',
            'content.max'    => 'タイトルは65535文字以内で指定してください。',
        ];
    }
}
