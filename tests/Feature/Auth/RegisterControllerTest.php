<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    public function test_register_a_user_success()
    {
        $response = $this->from('register')->post('register', [
            'name' => 'TestUser',
            'email' => 'testemail@yandex.ru',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect('');
        $response->assertSessionHasNoErrors();

        $user = User::query()->firstWhere('name', '=', 'TestUser');
        $user->delete();
    }

    public function test_registration_a_user_with_existing_email_is_invalid()
    {
        $user = new User;
        $user->name = 'TestUser';
        $user->email = 'testemail@yandex.ru';
        $user->password = Hash::make('password');
        $user->save();

        $response = $this->from('register')->post('register', [
            'name' => 'TestUser',
            'email' => 'testemail@yandex.ru',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect('register');
        $response->assertSessionHasErrors();
        $response->assertInvalid(['email']);

        $user->delete();
    }
}
