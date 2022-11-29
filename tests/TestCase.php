<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Hash;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function createAdminUser(): User
    {
        $user = new User();
        $user->id = 1;
        $user->name = 'Admin';
        $user->password = Hash::make('password');
        $user->id_role = ADMIN_USER;
        return $user;
    }

    public function createUsualUser(): User
    {
        $user = new User();
        $user->id = 2;
        $user->name = 'Admin';
        $user->password = Hash::make('password');
        $user->id_role = USUAL_USER;
        return $user;
    }
}
