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
        $fileSource = FileNoiseSource::query()->firstWhere('file_name', $arrayDataOneNoiseSource['file_name']->hashName());

        $response = $this->actingAs($user)->delete("noise/admin/sources/$fileSource->id");

        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success', 'Успешно удалено');
        $this->assertNotInstanceOf(
            FileNoiseSource::class,
            FileNoiseSource::query()->firstWhere('file_name', $arrayDataOneNoiseSource['file_name']->hashName()));
        $this->assertNotInstanceOf(
            NoiseSource::class,
            NoiseSource::query()->firstWhere('mark', $arrayDataOneNoiseSource['mark_1']));
        Storage::disk()->assertMissing(PATH_FILES_NOT_CHECK.$arrayDataOneNoiseSource['file_name']->hashName());
    }

    public function test_destroing_noise_source_and_bindings_is_unavailable_for_usual_user()
    {
        $user = $this->createUsualUser();

        $response = $this->actingAs($user)->delete('noise/admin/sources/99999999999999');

        $response->assertStatus(403);
    }

    public function test_destroing_noise_source_and_bindings_is_invalid_for_not_exist_source()
    {
        $user = $this->createAdminUser();

        $response = $this->actingAs($user)->from('noise/admin/sources')->delete('noise/admin/sources/99999999999999');

        $response->assertSessionHasErrors();
        $response->assertRedirect('noise/admin/sources');
    }

    public function test_approving_noise_source_and_bindings()
    {
        $user = $this->createAdminUser();
        $arrayDataOneNoiseSource = $this->createValidDataOneNoiseSource();
        $this->actingAs($user)->post('/noise/main/sources', $arrayDataOneNoiseSource);
        $fileSource = FileNoiseSource::query()->firstWhere('file_name', $arrayDataOneNoiseSource['file_name']->hashName());

        $response = $this->actingAs($user)->from('noise/admin/sources')->patch('noise/admin/approve/'. $fileSource->id);

        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success', 'Успешно согласовано');
        $response->assertRedirect('noise/admin/sources');
        Storage::disk()->assertExists(PATH_FILES_CHECK.$arrayDataOneNoiseSource['file_name']->hashName());
        $noiseSource = NoiseSource::query()->where('id_file_path', '=', $fileSource->id)->first();
        $this->assertEquals(APPROVE, $noiseSource->check_source);

        NoiseSource::query()->where('id_file_path', '=', $fileSource->id)->delete();
        $fileSource->destroy($fileSource->id);
        Storage::disk()->delete(PATH_FILES_CHECK.$arrayDataOneNoiseSource['file_name']->hashName());
    }

    public function test_approving_noise_source_and_bindings_is_unavailable_for_usual_user()
    {
        $user = $this->createUsualUser();

        $response = $this->actingAs($user)->from('noise/admin/sources')->patch('noise/admin/approve/'. 99999999999999);

        $response->assertStatus(403);
    }

    public function test_approving_noise_source_and_bindings_is_invalid_for_not_exist_source()
    {
        $user = $this->createAdminUser();

        $response = $this->actingAs($user)->from('noise/admin/sources')->patch('noise/admin/approve/'. 99999999999999);

        $response->assertSessionHasErrors();
        $response->assertRedirect('noise/admin/sources');
    }
}
