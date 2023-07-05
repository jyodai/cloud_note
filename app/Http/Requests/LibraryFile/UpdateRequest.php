<?php

namespace App\Http\Requests\LibraryFile;

use App\Rules\FileCharAllowed;
use App\Rules\FileDuplicate;
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
                new FileDuplicate($path),
                new FileCharAllowed(),
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
