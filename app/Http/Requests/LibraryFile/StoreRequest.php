<?php

namespace App\Http\Requests\LibraryFile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Storage;

class StoreRequest extends FormRequest
{
    public function rules()
    {
        return [
            'file'   => 'required',
            'file.*' => [
                'file',
                'mimes:jpg,jpeg,png',
                'max:2048',
                function ($attribute, $value, $fail) {
                    $fileName = $value->getClientOriginalName();
                    $user     = Auth::user();
                    $path     = storage_path('userLibrary/' . $user->id . '/');
                    if (file_exists($path . $fileName)) {
                        $fail($fileName . 'は既に使用されています。');
                    }
                },
                function ($attribute, $value, $fail) {
                    $fileName = $value->getClientOriginalName();
                    if (preg_match('#[\\\:?<>|]|\.{1,2}/#', $fileName)) {
                        $message = $fileName . 'に使用できな文字が含まれています。「\,:,?,<,>,|」、「./」、「../」';
                        $fail($message);
                    }
                },
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
