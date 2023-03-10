<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
    }

    public function createToken(Request $request)
    {
        $email = $request->email;
        $user = \App\Models\User::where("email",$email)->first();
        if ($user) {
            if ($this->overAttempts($user)) {
                $ret ["message"] = "100回ログインに失敗しました。アカウントはロック中です";
                return $ret;
            };
        }

        $credentials = request(['email', 'password']);
        if (!Auth::attempt($credentials)) {
            $ret ["message"] = "メールアドレス、若しくはパスワードが間違っています";
            return $ret;
        }

        $token = Str::random(60);
        $user->api_token = hash('sha256', $token);
        $user->attempts_num = 0;
        $user->save();
        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    protected function overAttempts($user)
    {
        $ret = false;
        $user->attempts_num = $user->attempts_num + 1;
        $user->save();
        if ($user->attempts_num > 100) {
            $ret = true;
        }
        return $ret;
    }

    public function deleteToken(Request $request)
    {
        $token = hash('sha256', $request->token);
        $user = \App\Models\User::where("api_token", $token)->first();
        if ($token && $user) {
            $user->api_token = null;
            $user->save();
            return [];
        }else{
            abort(401);
        }
    }
}
