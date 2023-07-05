<?php

namespace App\Services;

use App\Models\NoteSetting;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserService
{
    public function create(array $attrs): User
    {
        $attrs['password']  = Hash::make($attrs['password']);
        $attrs['api_token'] = Str::random(60);

        $user = User::create($attrs);

        NoteSetting::create([
            'user_id'       => $user->id,
            'editor_option' => '{}',
            'editor_css'    => '',
        ]);

        $this->createLibraryDir($user->id);

        return $user;
    }

    private function createLibraryDir(int $userId): void
    {
        $path = storage_path('userLibrary/' . $userId . '/');
        if (file_exists($path)) {
            return;
        }
        mkdir($path);
    }
}
