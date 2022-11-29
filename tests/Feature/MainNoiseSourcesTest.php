<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use \Illuminate\Http\UploadedFile;
use Tests\TestCase;

class MainNoiseSourcesTest extends TestCase
{
    public function test_store_one_noise_sources()
    {
        $user = $this->createUsualUser();
        $path = public_path('foundation1.pdf');
        $name = 'foundation1.pdf';
        $file_pdf = new UploadedFile($path, $name, 'application/pdf', 0, true);

        $response = $this->actingAs($user)->post('/noise/main/sources', [
              "count" => "1",
              "name_1" => "ИсточникТест",
              "mark_1" => "МаркаТест",
              "id_type_of_source_1" => "1",
              "distance_1" => "1",
              "la_31_5_1" => "50",
              "la_63_1" => "50",
              "la_125_1" => "50",
              "la_250_1" => "50",
              "la_500_1" => "50",
              "la_1000_1" => "50",
              "la_2000_1" => "50",
              "la_4000_1" => "50",
              "la_8000_1" => "50",
              "la_eq_1" => "80",
              "la_max_1" => "80",
              "remark_1" => "ПримечаниеТест",
              "foundation" => "Обоснование Тест",
              "submit" => "Добавить в базу данных",
              "file_name" => $file_pdf
            ]);
        $response->assertRedirect('noise/main/sources');
    }
}
