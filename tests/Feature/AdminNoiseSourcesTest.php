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

    public function test_update_noise_source()
    {
        $user = $this->createAdminUser();
        $oneSource = NoiseSource::query()->find(1)->first();
        $fileSource = FileNoiseSource::query()->find($oneSource->id_file_path)->first();
        $nameChanged = 'Источник 1';

        $response = $this->actingAs($user)->from("noise/admin/sources/$oneSource->id/edit")->patch("noise/admin/sources/$oneSource->id", [
            "name" => $nameChanged,
            "mark" => $oneSource->mark,
            "id_type_of_source" => $oneSource->id_type_of_source,
            "distance" => $oneSource->distance,
            "la_31_5" => $oneSource->la_31_5,
            "la_63" => $oneSource->la_63,
            "la_125" => $oneSource->la_125,
            "la_250" => $oneSource->la_250,
            "la_500" => $oneSource->la_500,
            "la_1000" => $oneSource->la_1000,
            "la_2000" => $oneSource->la_2000,
            "la_4000" => $oneSource->la_4000,
            "la_8000" => $oneSource->la_8000,
            "la_eq" => $oneSource->la_eq,
            "la_max" => $oneSource->la_max,
            "remark" => $oneSource->remark,
            "foundation" => $fileSource->foundation,
        ]);

        $sourceAfterChanged = NoiseSource::query()->find(1)->first();
        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('noise.admin.sources.index'));
        $this->assertSame($nameChanged, $sourceAfterChanged->name);


        $sourceAfterChanged->update(['name' => 'Сварочное оборудование']);
    }

    public function test_update_noise_source_is_invalid_without_name()
    {
        $user = $this->createAdminUser();
        $oneSource = NoiseSource::query()->find(1)->first();
        $fileSource = FileNoiseSource::query()->find($oneSource->id_file_path)->first();
        $fromUri = "noise/admin/sources/$oneSource->id/edit";
        $mark = 'Mark';

        $response = $this->actingAs($user)->from($fromUri)->patch("noise/admin/sources/$oneSource->id", [
            "mark" => $mark,
            "id_type_of_source" => $oneSource->id_type_of_source,
            "distance" => $oneSource->distance,
            "la_31_5" => $oneSource->la_31_5,
            "la_63" => $oneSource->la_63,
            "la_125" => $oneSource->la_125,
            "la_250" => $oneSource->la_250,
            "la_500" => $oneSource->la_500,
            "la_1000" => $oneSource->la_1000,
            "la_2000" => $oneSource->la_2000,
            "la_4000" => $oneSource->la_4000,
            "la_8000" => $oneSource->la_8000,
            "la_eq" => $oneSource->la_eq,
            "la_max" => $oneSource->la_max,
            "remark" => $oneSource->remark,
            "foundation" => $fileSource->foundation,
        ]);

        $sourceAfterChanged = NoiseSource::query()->find(1)->first();
        $response->assertSessionHasErrors();
        $response->assertRedirect($fromUri);
        $this->assertNotSame($mark, $sourceAfterChanged->mark);
    }
}
