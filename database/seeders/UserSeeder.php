<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Администартор',
            'email' => 'serega1998love@yandex.ru',
            'password' => Hash::make('password'),
            'id_role' => ADMIN_USER,
            'remember_token' => Str::random(10),
        ]);
        DB::table('users')->insert([
            'name' => 'Обычный пользователь',
            'email' => 'serega_1998_love@mail.ru',
            'password' => Hash::make('password'),
            'id_role' => USUAL_USER,
            'remember_token' => Str::random(10),
        ]);
        DB::table('users')->insert([
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'password' => Hash::make('password'),
            'id_role' => USUAL_USER,
            'remember_token' => Str::random(10),
        ]);
    }
}
