<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\NoteSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function __construct()
    {
    }

    public function index(): AnonymousResourceCollection
    {
        $users = User::all();
        return UserResource::collection($users);
    }

    public function show(int $id): UserResource
    {
        $user = User::find($id);
        return new UserResource($user);
    }

    public function showLoginUser(Request $request): UserResource
    {
        $token = $request->bearerToken();
        $user  = \App\Models\User::where("api_token", $token)->first();
        if (!$user) {
            abort(401);
        }
        return new UserResource($user);
    }

    public function store(Request $request): UserResource
    {
        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'api_token' => Str::random(60),
        ]);
        NoteSetting::create([
            'user_id'       => $user->id,
            'editor_option' => '{}',
            'editor_css'    => '',
        ]);
        return new UserResource($user);
    }

    public function update(int $id, UpdateRequest $request): UserResource
    {
        User::find($id)->update([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $user = User::find($id);
        return new UserResource($user);
    }

    public function destroy(int $id): Response
    {
        User::destroy($id);
        NoteSetting::where('user_id', $id)->delete();
        return response()->noContent();
    }

    public function createToken(Request $request): array
    {
        $email = $request->email;
        $user  = \App\Models\User::where("email", $email)->first();
        if ($user) {
            if ($this->overAttempts($user)) {
                $ret ["message"] = "100回ログインに失敗しました。アカウントはロック中です";
                return response($ret, 401);
            };
        }

        $credentials = request(['email', 'password']);
        if (!Auth::attempt($credentials)) {
            $ret ["message"] = "メールアドレス、若しくはパスワードが間違っています";
            return response($ret, 401);
        }

        $token              = Str::random(60);
        $user->api_token    = $token;
        $user->attempts_num = 0;
        $user->save();
        return [
            'user'  => new UserResource($user),
            'token' => $token,
        ];
    }

    protected function overAttempts($user): bool
    {
        $ret                = false;
        $user->attempts_num = $user->attempts_num + 1;
        $user->save();
        if ($user->attempts_num > 100) {
            $ret = true;
        }
        return $ret;
    }

    public function deleteToken(Request $request): Response
    {
        $token = $request->bearerToken();
        $user  = \App\Models\User::where("api_token", $token)->first();
        if ($token && $user) {
            $user->api_token = null;
            $user->save();
            return response()->noContent();
        } else {
            abort(401);
        }
    }
}
