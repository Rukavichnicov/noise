<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NoiseSourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arrayData = [
            ['Сварочное оборудование', NULL, NULL, 1, 1, 1],
            ['Котел отопительный водогрейный', NULL, 'Теплопроизводительность от 0,1 до 4,0 МВт ', 2, 1, 1],
            ['Смесители лопастные', NULL, NULL, 3, 1, 1],
            ['Печь хлебопекарная ротационная', NULL, NULL, 4, 1, 1],
            ['Ленточный конвейер', 'LIBELT 300, LIBELT 400', NULL, 5, 1, 1],
        ];
        foreach ($arrayData as $data) {
            DB::table('noise_sources')->insert([
                'check_source' => true,
                'name' => $data[0],
                'mark' => $data[1],
                'distance' => fake()->numberBetween(0, 10),
                'la_31_5' => fake()->numberBetween(0, 200),
                'la_63'  => fake()->numberBetween(0, 200),
                'la_125'  => fake()->numberBetween(0, 200),
                'la_250'  => fake()->numberBetween(0, 200),
                'la_500'  => fake()->numberBetween(0, 200),
                'la_1000'  => fake()->numberBetween(0, 200),
                'la_2000'  => fake()->numberBetween(0, 200),
                'la_4000'  => fake()->numberBetween(0, 200),
                'la_8000'  => fake()->numberBetween(0, 200),
                'la_eq'  => fake()->numberBetween(0, 200),
                'la_max'  => fake()->numberBetween(0, 200),
                'remark' => $data[2],
                'id_file_path' => $data[3],
                'id_type_of_source' => $data[4],
                'id_user' => $data[5],
            ]);
        }
    }
}
