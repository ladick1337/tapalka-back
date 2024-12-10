<?php

namespace Tests\Trader;

use App\Models\Client;
use App\Models\Item;
use App\Models\Trader;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class Auth extends TestCase
{

    protected $mail = 'mail@mail.ru';
    protected $password = 'password';

    public function testRegister()
    {

        Trader::where('email', $this->mail)->delete();

        $response = $this->post('/api/trader/register', [
            'email' => $this->mail,
            'password' => $this->password,
            'language' => 'ru-RU'
        ]);

        $response->assertJson(['status' => true]);

        $token = $response->json('response');

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->post('/api/trader/profile/me');
        $response->assertStatus(200);
        $response->assertJson(['status' => true]);

        $response->dump();

    }

    public function testAuth(){

        $response = $this->post('/api/trader/login', [
            'email' => $this->mail,
            'password' => $this->password
        ]);

        $response->assertJson(['status' => true]);

        $token = $response->json('response');

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->post('/api/trader/profile/me');
        $response->assertStatus(200);
        $response->assertJson(['status' => true]);

        $response->dump();

    }

}
