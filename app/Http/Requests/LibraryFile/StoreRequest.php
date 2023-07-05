<?php

namespace App\Http\Requests\LibraryFile;

use App\Rules\FileCharAllowed;
use App\Rules\FileDuplicate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Storage;

class StoreRequest extends FormRequest
{
    public function rules()
    {
        $user = Auth::user();
        $path = storage_path('userLibrary/' . $user->id . '/');

        return [
            'file'   => 'required',
            'file.*' => [
                'file',
                'mimes:jpg,jpeg,png',
                'max:2048',
                new FileDuplicate($path),
                new FileCharAllowed(),
            ],
        ];
    }

    public function messages()
    {
        return [
            'file.required' => 'ファイルは必須です。',
            'file.*.file'   => '有効なファイルをアップロードしてください。',
            'file.*.mimes'  => 'ファイルはjpg、jpeg、png形式である必要があります。',
            'file.*.max'    => 'ファイルサイズは2048KB以下にしてください。',
        ];
    }
}
