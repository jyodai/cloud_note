<?php

namespace App\Http\Requests\Note;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function rules()
    {
        return [
            'parent_note_id' => 'required|integer',
            'note_type'      => "required|string|max:255",
            'title'          => "required|string|max:65535",
        ];
    }

    public function messages()
    {
        return [
            'parent_note_id.required' => '親ノートIDは必須です。',
            'parent_note_id.integer'  => '親ノートIDは整数で指定してください。',
            'note_type.required'      => 'ノートタイプは必須です。',
            'note_type.string'        => 'ノートタイプは文字列で指定してください。',
            'note_type.max'           => 'ノートタイプは255文字以内で指定してください。',
            'title.required'          => 'タイトルは必須です。',
            'title.string'            => 'タイトルは文字列で指定してください。',
            'title.max'               => 'タイトルは65535文字以内で指定してください。',
        ];
    }
}
