<?php

namespace App\Http\Requests\TaskElement;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function rules()
    {
        return [
            'task_id'                => 'required|integer',
            'parent_task_element_id' => 'required|integer',
            'name'                   => "required|string|max:65535",
        ];
    }

    public function messages()
    {
        return [
            'task_id.required'                => 'タスクIDは必須です。',
            'task_id.integer'                 => 'タスクIDは整数で指定してください。',
            'parent_task_element_id.required' => '親タスク要素IDは必須です。',
            'parent_task_element_id.integer'  => '親タスク要素IDは整数で指定してください。',
            'name.required'                   => '名前は必須です。',
            'name.string'                     => '名前は文字列で指定してください。',
            'name.max'                        => '名前は65535文字以内で指定してください。',
        ];
    }
}
