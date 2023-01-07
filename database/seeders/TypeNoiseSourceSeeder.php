<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TypeNoiseSourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arrayTypeName = [
            'Промышленное оборудование',
            'Насосы',
            'Вентиляторы',
            'Строительная техника',
            'Автотранспорт',
            'Электронные устройства',
        ];
        foreach ($arrayTypeName as $name) {
            DB::table('type_noise_sources')->insert([
                'name' => $name,
                'slug' => Str::slug($name),
            ]);
        }

    }
}
