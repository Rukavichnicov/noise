<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FileNoiseSourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arrayFile = [
            'гост 12.1.035-81.pdf' => 'Таблица 1 ГОСТ 12.1.035-81',
            'гост 30735-2001.pdf' => 'ГОСТ 30735-2001 ',
            'гост р 54425-2011.pdf' => 'ГОСТ Р 54425-2011',
            'гост р 54320-2011.pdf' => 'ГОСТ Р 54320-2011',
            'ленточный конвейер libelt 300 libelt 400.pdf' => 'Руководство по эксплуатации на ленточный конвейер LIBELT 300, LIBELT 400',
        ];
        foreach ($arrayFile as $fileName => $foundation) {
            DB::table('file_noise_sources')->insert([
                'file_name' => $fileName,
                'foundation' => $foundation,
            ]);
        }
    }
}
