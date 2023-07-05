<?php

namespace App\Http\Requests\LibraryFile;

use App\Rules\DuplicateFile;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Storage;

class UpdateRequest extends FormRequest
{
    public function rules()
    {
        $user = Auth::user();
        $path = storage_path('userLibrary/' . $user->id . '/');
        return [
            'newFileName'    => [
                'required',
                'string',
                'max:255',
                new DuplicateFile($path),
                function ($attribute, $value, $fail) {
                    if (preg_match('#[\\\:?<>|]|\.{1,2}/#', $value)) {
                        $message = 'ファイルに使用できな文字が含まれています。「\,:,?,<,>,|」、「./」、「../」';
                        $fail($message);
                    }
                },
            ],
            'originFileName' => 'required|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'newFileName.required' => 'ファイル名は必須です。',
            'newFileName.string'   => 'ファイル名は文字列で入力してください。',
            'newFileName.max'      => 'ファイル名は最大で255文字まで入力できます。',
        ];
    }
}
