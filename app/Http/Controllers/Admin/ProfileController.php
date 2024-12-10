<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminAuthHistory;
use App\Modules\Api\ApiError;
use App\Modules\Api\ApiResponses;
use App\Modules\Prepare\AdminPrepare;
use App\Modules\QR\QR;
use App\Modules\TableGenerator\TableGenerator;
use App\Modules\Traits\AdminGuard;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use PragmaRX\Google2FA\Google2FA;

class ProfileController extends Controller
{

    use AdminGuard, ApiResponses;

    public function me(Request $request) : array
    {

        return AdminPrepare::admin(
            $this->admin()->load('role')
        );

    }

    public function authHistory(Request $request) : array
    {

        $generator = new TableGenerator(
            $this->admin()->authHistory()
        );

        $generator->setSortFields(['id', 'created_at']);

        return $this->tableGeneratorToJson(
            $generator->build($request)->map(function(AdminAuthHistory $row){
                return AdminPrepare::authHistory($row);
            })
        );
    }

    public function changePassword(Request $request) : void
    {

        $data = $request->validate([
            'password' => ['required', 'string', 'min:4', 'max:255']
        ], [
            'password.min' => 'Пароль слишком короткий',
            'password.max' => 'Пароль слишком длинный'
        ]);

        $admin = $this->admin();

        if(Hash::check($data['password'], $admin->password)){
            throw new ApiError('У вас сейчас установлен такой же пароль', 422);
        }

        DB::transaction(function() use ($admin, $data){

            $admin->password = Hash::make($data['password']);
            $admin->save();

            //Drop tokens
            $admin->tokens()->where('id', '!=', $admin->currentAccessToken()->id)->delete();

        });

    }

    public function generateTFA() : array
    {

        $google = new Google2FA;

        $secret = $google->generateSecretKey();

        $qrUrl = $google->getQRCodeUrl(
            config('app.name'),
            $this->admin()->login,
            $secret
        );

        $qrPicture = new QR($qrUrl, 150, 5);

        return [
            'secret' => $secret,
            'qr' => $qrPicture->toSVG()->getString()
        ];

    }

    public function confirmTFA(Request $request) : void
    {

        $data = $request->validate([
            'secret' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255']
        ]);

        $google = new Google2FA;
        $admin = $this->admin();

        if($admin->tfa_secret){
            throw new ApiError('У вас уже установлен 2FA', 401);
        }

        if(!$google->verifyKey($data['secret'], $data['code'])) {
            throw new ApiError('Вы ввели неверный код', 422);
        }

        $admin->tfa_secret = $data['secret'];
        $admin->save();

    }

    public function removeTFA(Request $request) : void
    {

        $data = $request->validate([
           'code' => ['required', 'string', 'max:255']
        ]);

        $admin = $this->admin();

        if(!$admin->tfa_secret){
            throw new ApiError('У вас не установлен 2FA', 401);
        }

        if(!$admin->verifyTFACode($data['code'])){
            throw new ApiError('Вы ввели неверный код', 422);
        }

        $admin->tfa_secret = null;
        $admin->save();

    }

}
