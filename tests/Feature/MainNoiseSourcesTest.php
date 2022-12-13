<?php

namespace Tests\Feature;

use App\Models\FileNoiseSource;
use App\Models\NoiseSource;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MainNoiseSourcesTest extends TestCase
{
    public function test_store_one_noise_sources()
    {
        $user = $this->createUsualUser();
        $arrayDataOneNoiseSource = $this->createValidDataOneNoiseSource();

        $response = $this->actingAs($user)->post('/noise/main/sources', $arrayDataOneNoiseSource);

        $response->assertRedirect('noise/main/sources');
        Storage::disk()->assertExists(PATH_FILES_NOT_CHECK.$arrayDataOneNoiseSource['file_name']->hashName());

        $fileSource = FileNoiseSource::query()->firstWhere('file_name', $arrayDataOneNoiseSource['file_name']->hashName());
        NoiseSource::query()->where('id_file_path', '=', $fileSource->id)->delete();
        $fileSource->destroy($fileSource->id);
        Storage::disk()->delete(PATH_FILES_NOT_CHECK.$arrayDataOneNoiseSource['file_name']->hashName());
    }

    public function test_store_two_noise_sources()
    {
        $user = $this->createUsualUser();
        $arrayDataTwoNoiseSource = $this->createValidDataOneNoiseSource();
        $arrayDataTwoNoiseSource['count'] = '2';
        $arrayDataTwoNoiseSource = array_merge($arrayDataTwoNoiseSource, [
            "name_2" => "ИсточникТест2",
            "mark_2" => "МаркаТест2",
            "id_type_of_source_2" => "2",
            "distance_2" => "2",
            "la_31_5_2" => "50",
            "la_63_2" => "50",
            "la_125_2" => "50",
            "la_250_2" => "50",
            "la_500_2" => "50",
            "la_1000_2" => "50",
            "la_2000_2" => "50",
            "la_4000_2" => "50",
            "la_8000_2" => "50",
            "la_eq_2" => "70",
            "la_max_2" => "70",
            "remark_2" => "ПримечаниеТест2",
        ]);

        $response = $this->actingAs($user)->post('/noise/main/sources', $arrayDataTwoNoiseSource);

        $response->assertRedirect('noise/main/sources');
        Storage::disk()->assertExists(PATH_FILES_NOT_CHECK.$arrayDataTwoNoiseSource['file_name']->hashName());

        $fileSource = FileNoiseSource::query()->firstWhere('file_name', $arrayDataTwoNoiseSource['file_name']->hashName());
        NoiseSource::query()->where('id_file_path', '=', $fileSource->id)->delete();
        $fileSource->destroy($fileSource->id);
        Storage::disk()->delete(PATH_FILES_NOT_CHECK.$arrayDataTwoNoiseSource['file_name']->hashName());
    }

    public function test_store_one_noise_sources_without_file_is_invalid()
    {
        $user = $this->createUsualUser();
        $arrayDataOneNoiseSource = $this->createValidDataOneNoiseSource();
        $arrayDataOneNoiseSource['file_name'] = '';

        $response = $this->from('noise/main/sources/create')->actingAs($user)->post('/noise/main/sources', $arrayDataOneNoiseSource);

        $response->assertRedirect('noise/main/sources/create');
        $response->assertInvalid(['file_name']);
    }

    public function test_store_one_noise_sources_without_name_is_invalid()
    {
        $user = $this->createUsualUser();
        $arrayDataOneNoiseSource = $this->createValidDataOneNoiseSource();
        $arrayDataOneNoiseSource['name_1'] = '';

        $response = $this->from('noise/main/sources/create')->actingAs($user)->post('/noise/main/sources', $arrayDataOneNoiseSource);
        $response->assertRedirect('noise/main/sources/create');
        $response->assertInvalid(['name_1']);
        Storage::disk()->assertMissing(PATH_FILES_NOT_CHECK.$arrayDataOneNoiseSource['file_name']->hashName());
    }

    public function test_store_one_noise_sources_without_foundation_is_invalid()
    {
        $user = $this->createUsualUser();
        $arrayDataOneNoiseSource = $this->createValidDataOneNoiseSource();
        $arrayDataOneNoiseSource['foundation'] = '';

        $response = $this->from('noise/main/sources/create')->actingAs($user)->post('/noise/main/sources', $arrayDataOneNoiseSource);

        $response->assertRedirect('noise/main/sources/create');
        $response->assertInvalid(['foundation']);
        Storage::disk()->assertMissing(PATH_FILES_NOT_CHECK.$arrayDataOneNoiseSource['file_name']->hashName());
    }
}
