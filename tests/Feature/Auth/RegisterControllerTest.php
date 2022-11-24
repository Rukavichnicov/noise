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
        $user = new User;
        $user->name = 'TestUser';
        $user->email = 'testemail@yandex.ru';
        $user->password = Hash::make('password');
        $user->save();

        $newUser = DB::table('users')->find($user->id);

        $this->assertSame('TestUser', $newUser->name);
        $this->assertSame('testemail@yandex.ru', $newUser->email);
        $this->assertTrue(Hash::check('password', $newUser->password));

        $user->delete();
    }

    public function test_registration_a_user_with_existing_email_is_invalid()
    {
        $user = new User;
        $user->name = 'TestUser';
        $user->email = 'testemail@yandex.ru';
        $user->password = Hash::make('password');
        $user->save();

        try {
            $userWithExistingEmail = new User;
            $userWithExistingEmail->name = 'TestUser1';
            $userWithExistingEmail->email = 'testemail@yandex.ru';
            $userWithExistingEmail->password = Hash::make('password1');
            $userWithExistingEmail->save();
        } catch (QueryException $exception) {
            $this->assertInstanceOf(QueryException::class, $exception);
            $user->delete();
        }
    }
}
