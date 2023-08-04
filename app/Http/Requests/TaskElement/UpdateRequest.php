<?php

namespace App\Http\Requests\TaskElement;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name'            => 'required|string',
            'content'         => 'present|string',
            'start_date'      => 'nullable|date_format:Y-m-d',
            'due_date'        => 'nullable|date_format:Y-m-d',
            'completion_date' => 'nullable|date_format:Y-m-d',
            'completion_flag' => "boolean",
        ];
    }

    public function messages()
    {
        return [
            'name.required'               => 'タスク名は必須です。',
            'name.string'                 => 'タスク名は文字列である必要があります。',
            'content.present'             => '内容は必須です。',
            'content.string'              => '内容は文字列である必要があります。',
            'start_date.date_format'      => '開始日はY-m-dの形式である必要があります。',
            'due_date.date_format'        => '締切日はY-m-dの形式である必要があります。',
            'completion_date.date_format' => '完了日はY-m-dの形式である必要があります。',
            'completion_flag'             => '完了状態はbool型で指定してください。',
        ];
    }
}
