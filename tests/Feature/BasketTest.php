<?php

namespace Tests\Feature;

use App\Models\Basket;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class BasketTest extends TestCase
{
    public function test_adding_sources_in_basket_without_authorization_is_invalid()
    {
        $response = $this->post('noise/main/basket', [
            "addSources" => "1"
        ]);

        $response->assertRedirect('/login');
    }

    public function test_adding_sources_in_basket()
    {
        $user = $this->createUsualUser();
        $user->id = 2;

        $response = $this->from('noise/main/sources')->actingAs($user)->post('noise/main/basket', [
            "addSources" => "1"
        ]);

        $response->assertRedirect('noise/main/sources');

        Basket::query()->where('id_user', '=', $user->id)->delete();
    }

    public function test_double_adding_sources_in_basket_is_invalid()
    {
        $user = $this->createUsualUser();
        $user->id = 2;

        $this->actingAs($user)->post('noise/main/basket', [
            "addSources" => "1"
        ]);
        $response = $this->actingAs($user)->post('noise/main/basket', [
            "addSources" => "1"
        ]);

        $response->assertInvalid(['addSources']);

        Basket::query()->where('id_user', '=', $user->id)->delete();
    }

    public function test_deleting_sources_from_basket_without_authorization_is_invalid()
    {
        $response = $this->delete('noise/main/basket/1');

        $response->assertRedirect('/login');
    }

    public function test_deleting_sources_from_basket()
    {
        $user = $this->createUsualUser();
        $user->id = 2;
        Basket::query()->insert(['id_user' => $user->id, 'id_noise_source' => '1']);

        $response = $this->from('noise/main/sources')->actingAs($user)->delete('noise/main/basket/1');

        $response->assertRedirect('noise/main/sources');
    }

    public function test_double_deleting_sources_from_basket_is_invalid()
    {
        $user = $this->createUsualUser();
        $user->id = 2;
        Basket::query()->insert(['id_user' => $user->id, 'id_noise_source' => '1']);

        $this->actingAs($user)->delete('noise/main/basket/1');
        $response = $this->actingAs($user)->delete('noise/main/basket/1');

        $response->assertSessionHasErrors();
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

        $response = $this->from(route('noise.main.basket.index'))->actingAs($user)->get(route('noise.main.basket.downloadArchiveFile'));

        $response->assertDownload();

        DB::rollBack();
        $content = $response->headers->get('content-disposition');
        $loadedFileName = mb_substr($content, 21);
        unlink(public_path($loadedFileName));
    }

    public function test_loading_archive_without_sources_is_invalid()
    {
        $user = $this->createUsualUser();
        DB::beginTransaction();
        Basket::query()->where('id_user', '=', $user->id)->delete();

        $response = $this->from(route('noise.main.basket.index'))->actingAs($user)->get(route('noise.main.basket.downloadArchiveFile'));

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
