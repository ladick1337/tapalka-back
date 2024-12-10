<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminAuthHistory;
use App\Modules\Api\ApiError;
use App\Modules\Api\ApiResponses;
use App\Modules\Traits\AdminGuard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    use AdminGuard, ApiResponses;

    public function login(Request $request) : string
    {
        $data = $request->validate([
            'login' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:6']
        ], [
            'login.required' => 'Вы не указали логин',
            'password.required' => 'Неверный логин или пароль',
            'code.max' => 'Неверный код 2FA'
        ]);

        $user = Admin::findByLogin($data['login']);

        if(!$user || !Hash::check($data['password'], $user->password)){
            throw new ApiError('Неверный логин или пароль', 403);
        }

        if($user->tfa_secret){

            $code = Arr::get($data, 'code') ?: null;

            if(!$code){
                throw new ApiError('Введите код 2FA', 401);
            }

            if(!$user->verifyTFACode($code)){
                throw new ApiError('Неверный код 2FA', 403);
            }

        }

        return DB::transaction(function() use ($user, $request){

            AdminAuthHistory::factory()
                ->for($user)
                ->ip($request->ip() ?: 'UNKNOWN')
                ->create();

            return $user->createToken($user->id)->plainTextToken;

        });

    }

    public function logout(Request $request) : void
    {
        $this->admin()->currentAccessToken()->delete();
    }


}
