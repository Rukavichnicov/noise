<?php

namespace Tests\Feature;

use App\Models\FileNoiseSource;
use App\Models\NoiseSource;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminNoiseSourcesTest extends TestCase
{
    public function test_destroing_noise_source_and_bindings()
    {
        $user = $this->createAdminUser();
        $arrayDataOneNoiseSource = $this->createValidDataOneNoiseSource();
        $this->actingAs($user)->post('/noise/main/sources', $arrayDataOneNoiseSource);
        $fileSource = FileNoiseSource::firstWhere('file_name', $arrayDataOneNoiseSource['file_name']->hashName());

        $response = $this->delete('noise/admin/sources/'. $fileSource->id);

        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success', 'Успешно удалено');
        $this->assertNotInstanceOf(
            FileNoiseSource::class,
            FileNoiseSource::firstWhere('file_name', $arrayDataOneNoiseSource['file_name']->hashName()));
        $this->assertNotInstanceOf(
            NoiseSource::class,
            NoiseSource::firstWhere('mark', $arrayDataOneNoiseSource['mark_1']));
        Storage::disk()->assertMissing(PATH_FILES_NOT_CHECK.$arrayDataOneNoiseSource['file_name']->hashName());
    }

    public function test_destroing_noise_source_and_bindings_is_unavailable_for_usual_user()
    {
        $user = $this->createUsualUser();

        $response = $this->delete('noise/admin/sources/99999999999999');

        $response->assertStatus(403);
    }
}
