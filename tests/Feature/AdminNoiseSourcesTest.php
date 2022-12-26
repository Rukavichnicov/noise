<?php

namespace Tests\Feature;

use App\Models\FileNoiseSource;
use App\Models\NoiseSource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminNoiseSourcesTest extends TestCase
{
    public function test_destroing_noise_source_and_bindings()
    {
        DB::beginTransaction();
        $user = $this->createAdminUser();
        $arrayDataOneNoiseSource = $this->createValidDataOneNoiseSource();
        $this->actingAs($user)->post(route('noise.main.sources.store'), $arrayDataOneNoiseSource);
        $fileSource = FileNoiseSource::query()->firstWhere('file_name', $arrayDataOneNoiseSource['file_name']->hashName());

        $response = $this
            ->actingAs($user)
            ->delete(route('noise.admin.sources.destroy', ['source' => $fileSource->id]));

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseMissing('noise_sources', [
            'mark' => $arrayDataOneNoiseSource['mark_1'],
        ]);
        $this->assertDatabaseMissing('file_noise_sources', [
            'file_name' => $arrayDataOneNoiseSource['file_name']->hashName(),
        ]);
        Storage::disk()->assertMissing(PATH_FILES_NOT_CHECK.$arrayDataOneNoiseSource['file_name']->hashName());

        DB::rollBack();
    }

    public function test_destroing_noise_source_and_bindings_is_unavailable_for_usual_user()
    {
        $user = $this->createUsualUser();

        $response = $this->actingAs($user)->delete(route('noise.admin.sources.destroy', ['source' => 99999999999]));

        $response->assertStatus(403);
    }

    public function test_destroing_noise_source_and_bindings_is_invalid_for_not_exist_source()
    {
        $user = $this->createAdminUser();

        $response = $this
            ->actingAs($user)
            ->from(route('noise.admin.sources.index'))
            ->delete(route('noise.admin.sources.destroy', ['source' => 99999999999]));

        $response->assertSessionHasErrors();
        $response->assertRedirect(route('noise.admin.sources.index'));
    }

    public function test_approving_noise_source_and_bindings()
    {
        DB::beginTransaction();
        $user = $this->createAdminUser();
        $arrayDataOneNoiseSource = $this->createValidDataOneNoiseSource();
        $this->actingAs($user)->post(route('noise.main.sources.store'), $arrayDataOneNoiseSource);
        $fileSource = FileNoiseSource::query()->firstWhere('file_name', $arrayDataOneNoiseSource['file_name']->hashName());

        $response = $this
            ->actingAs($user)
            ->from(route('noise.admin.sources.index'))
            ->patch(route('noise.admin.sources.approve', ['id_file_path' => $fileSource->id]));

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('noise.admin.sources.index'));
        Storage::disk()->assertExists(PATH_FILES_CHECK.$arrayDataOneNoiseSource['file_name']->hashName());
        $noiseSource = NoiseSource::query()->where('id_file_path', '=', $fileSource->id)->first();
        $this->assertEquals(APPROVE, $noiseSource->check_source);

        Storage::disk()->delete(PATH_FILES_CHECK.$arrayDataOneNoiseSource['file_name']->hashName());

        DB::rollBack();
    }

    public function test_approving_noise_source_and_bindings_is_unavailable_for_usual_user()
    {
        DB::beginTransaction();
        $user = $this->createUsualUser();

        $response = $this
            ->actingAs($user)
            ->from(route('noise.admin.sources.index'))
            ->patch(route('noise.admin.sources.approve', ['id_file_path' => 9999999999999]));

        $response->assertStatus(403);
        DB::rollBack();
    }

    public function test_approving_noise_source_and_bindings_is_invalid_for_not_exist_source()
    {
        DB::beginTransaction();
        $user = $this->createAdminUser();
        $incorrectId = 99999999999999;

        $response = $this
            ->actingAs($user)
            ->from(route('noise.admin.sources.index'))
            ->patch(route('noise.admin.sources.approve', ['id_file_path' => 9999999999999]));

        $response->assertSessionHasErrors();
        $response->assertRedirect(route('noise.admin.sources.index'));

        DB::rollBack();
    }

    public function test_update_noise_source()
    {
        DB::beginTransaction();
        $user = $this->createAdminUser();
        $idNoiseSource = 1;
        $oneSource = NoiseSource::query()->find($idNoiseSource)->first();
        $fileSource = FileNoiseSource::query()->find($oneSource->id_file_path)->first();
        $nameChanged = 'Источник 1';
        $dataForUpdating = [
            "name" => $nameChanged,
            "id_type_of_source" => $oneSource->id_type_of_source,
            "foundation" => $fileSource->foundation,
            ];

        $response = $this
            ->actingAs($user)
            ->from(route('noise.admin.sources.edit', ['source' => $idNoiseSource]))
            ->patch(route('noise.admin.sources.update', ['source' => $idNoiseSource]), $dataForUpdating);

        $sourceAfterChanged = NoiseSource::query()->find(1)->first();
        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('noise.admin.sources.index'));
        $this->assertSame($nameChanged, $sourceAfterChanged->name);

        DB::rollBack();
    }

    public function test_update_noise_source_is_invalid_without_name()
    {
        DB::beginTransaction();
        $user = $this->createAdminUser();
        $idNoiseSource = 1;
        $oneSource = NoiseSource::query()->find($idNoiseSource)->first();
        $fileSource = FileNoiseSource::query()->find($oneSource->id_file_path)->first();
        $mark = 'Mark';
        $dataForUpdating = [
            "mark" => $mark,
            "id_type_of_source" => $oneSource->id_type_of_source,
            "foundation" => $fileSource->foundation,
        ];

        $response = $this
            ->actingAs($user)
            ->from(route('noise.admin.sources.edit', ['source' => $idNoiseSource]))
            ->patch(route('noise.admin.sources.update', ['source' => $idNoiseSource]), $dataForUpdating);

        $sourceAfterChanged = NoiseSource::query()->find(1)->first();
        $response->assertSessionHasErrors();
        $response->assertRedirect(route('noise.admin.sources.edit', ['source' => $idNoiseSource]));
        $this->assertNotSame($mark, $sourceAfterChanged->mark);

        DB::rollBack();
    }
}
