<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BasketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 3; ++$i) {
            DB::table('baskets')->insert([
                'id_user' => $i,
                'id_noise_source' => $i,
                'created_at' => now(),
            ]);
        }
    }
}
