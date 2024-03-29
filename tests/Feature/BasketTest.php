<?php

namespace Tests\Feature;

use App\Models\Basket;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class BasketTest extends TestCase
{
    public function test_adding_sources_in_basket_without_authorization_is_invalid()
    {
        DB::beginTransaction();
        $response = $this->post(route('noise.main.basket.store'), [
            "addSources" => "1"
        ]);

        $response->assertRedirect(route('login'));
        DB::rollBack();
    }

    public function test_adding_sources_in_basket()
    {
        $user = $this->createUsualUser();
        $user->id = 2;
        DB::beginTransaction();
        Basket::query()->where('id_user', '=', $user->id)->delete();

        $response = $this
            ->from(route('noise.main.sources.index'))
            ->actingAs($user)
            ->post(
                route('noise.main.basket.store'),
                ["addSources" => "1"]
            );

        $response->assertRedirect(route('noise.main.sources.index'));

        DB::rollBack();
    }

    public function test_double_adding_sources_in_basket_is_invalid()
    {
        DB::beginTransaction();
        $user = $this->createUsualUser();
        $user->id = 2;
        Basket::query()->where('id_user', '=', $user->id)->delete();

        $this->actingAs($user)->post(route('noise.main.basket.store'), [
            "addSources" => "1"
        ]);
        $response = $this->actingAs($user)->post(route('noise.main.basket.store'), [
            "addSources" => "1"
        ]);

        $response->assertInvalid(['addSources']);

        DB::rollBack();
    }

    public function test_deleting_sources_from_basket_without_authorization_is_invalid()
    {
        $response = $this->delete(route('noise.main.basket.destroy', ['basket' => '1']));

        $response->assertRedirect(route('login'));
    }

    public function test_deleting_sources_from_basket()
    {
        DB::beginTransaction();
        $user = $this->createUsualUser();
        $user->id = 2;
        Basket::query()->insert(['id_user' => $user->id, 'id_noise_source' => '1']);

        $response = $this
            ->from(route('noise.main.sources.index'))
            ->actingAs($user)
            ->delete(
                route('noise.main.basket.destroy', ['basket' => '1'])
            );

        $response->assertRedirect(route('noise.main.sources.index'));
        DB::rollBack();
    }

    public function test_double_deleting_sources_from_basket_is_invalid()
    {
        DB::beginTransaction();
        $user = $this->createUsualUser();
        $user->id = 2;
        Basket::query()->insert(['id_user' => $user->id, 'id_noise_source' => '1']);

        $this->actingAs($user)->delete(route('noise.main.basket.destroy', ['basket' => '1']));
        $response = $this->actingAs($user)->delete(route('noise.main.basket.destroy', ['basket' => '1']));

        $response->assertSessionHasErrors();
        DB::rollBack();
    }

    public function test_loading_report()
    {
        $user = $this->createUsualUser();
        DB::beginTransaction();
        Basket::query()->where('id_user', '=', $user->id)->delete();
        Basket::query()->insert([
            'id_user' => $user->id,
            'id_noise_source' => 1,
            'created_at' => now(),
        ]);

        $response = $this->from(route('noise.main.basket.index'))->actingAs($user)->get(
            route('noise.main.basket.downloadReport')
        );
        ob_start();
        $response->sendContent();
        ob_end_clean();

        $response->assertDownload();

        DB::rollBack();
    }

    public function test_loading_report_is_unavailable_for_guest()
    {
        $response = $this->from(route('noise.main.basket.index'))->get(route('noise.main.basket.downloadReport'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_loading_archive()
    {
        $user = $this->createUsualUser();
        DB::beginTransaction();
        Basket::query()->where('id_user', '=', $user->id)->delete();
        Basket::query()->insert([
            'id_user' => $user->id,
            'id_noise_source' => 1,
            'created_at' => now(),
        ]);

        $response = $this->from(route('noise.main.basket.index'))->actingAs($user)->get(
            route('noise.main.basket.downloadArchiveFile')
        );
        ob_start();
        $response->sendContent();
        ob_end_clean();

        $response->assertDownload();

        DB::rollBack();
    }

    public function test_loading_archive_without_sources_is_invalid()
    {
        $user = $this->createUsualUser();
        DB::beginTransaction();
        Basket::query()->where('id_user', '=', $user->id)->delete();

        $response = $this->from(route('noise.main.basket.index'))->actingAs($user)->get(
            route('noise.main.basket.downloadArchiveFile')
        );

        $response->assertSessionHasErrors();
        $response->assertRedirect(route('noise.main.basket.index'));

        DB::rollBack();
    }

    public function test_loading_archive_is_unavailable_for_guest()
    {
        $response = $this->from(route('noise.main.basket.index'))->get(route('noise.main.basket.downloadArchiveFile'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
