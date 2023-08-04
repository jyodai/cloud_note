<?php

namespace App\Http\Requests\TaskElement;

use Illuminate\Foundation\Http\FormRequest;

class MoveRequest extends FormRequest
{
    public function rules()
    {
        return [
            'target_task_element_id' => 'required|integer',
            'type'                   => "required|string|max:255",
        ];
    }

    public function messages()
    {
        return [
            'target_note_id.required' => '移動先IDは必須です。',
            'target_note_id.integer'  => '移動先IDは整数で指定してください。',
            'type.required'           => 'タイプは必須です。',
            'type.string'             => 'タイプは文字列で入力してください。',
            'type.max'                => 'タイプは最大で255文字まで入力できます。',
        ];
    }
}
