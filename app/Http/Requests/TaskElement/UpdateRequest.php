<?php

namespace App\Http\Requests\TaskElement;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name'       => 'required|string',
            'content'    => 'required|string',
            'start_date' => 'nullable|date_format:Y-m-d',
            'due_date'   => 'nullable|date_format:Y-m-d',
        ];
    }

    public function messages()
    {
        return [
            'name.required'          => 'タスク名は必須です。',
            'name.string'            => 'タスク名は文字列である必要があります。',
            'content.required'       => '内容は必須です。',
            'content.string'         => '内容は文字列である必要があります。',
            'start_date.date_format' => '開始日はY-m-dの形式である必要があります。',
            'due_date.date_format'   => '締切日はY-m-dの形式である必要があります。',
        ];
    }
}
