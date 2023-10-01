<?php

namespace App\Http\Requests\Canvas;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function rules()
    {
        return [
            'content' => "string|max:4194304", // 4MB
        ];
    }

    public function messages()
    {
        return [
            'content.string' => 'コンテンツは文字列で指定してください。',
            'content.max'    => 'コンテンツの最大サイズ(4MB)を超えています',
        ];
    }
}
