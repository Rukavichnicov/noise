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
            "addSources" => "93"
        ]);

        $response->assertRedirect('/login');
    }

    public function test_adding_sources_in_basket()
    {
        $user = $this->createUsualUser();
        $user->id = 2;

        $response = $this->actingAs($user)->post('noise/main/basket', [
            "addSources" => "93"
        ]);

        $response->assertRedirect('/');

        Basket::query()->where('id_user', '=', $user->id)->delete();
    }

    public function test_double_adding_sources_in_basket_is_invalid()
    {
        $user = $this->createUsualUser();
        $user->id = 2;

        $response = $this->actingAs($user)->post('noise/main/basket', [
            "addSources" => "93"
        ]);
        $response = $this->actingAs($user)->post('noise/main/basket', [
            "addSources" => "93"
        ]);

        $response->assertInvalid(['addSources']);

        Basket::query()->where('id_user', '=', $user->id)->delete();
    }

    public function test_deleting_sources_from_basket_without_authorization_is_invalid()
    {
        $response = $this->delete('noise/main/basket/93');

        $response->assertRedirect('/login');
    }

    public function test_deleting_sources_from_basket()
    {
        $user = $this->createUsualUser();
        $user->id = 2;
        Basket::query()->insert(['id_user' => $user->id, 'id_noise_source' => '93']);

        $response = $this->actingAs($user)->delete('noise/main/basket/93');

        $response->assertRedirect('/');
    }

    public function test_double_deleting_sources_from_basket_is_invalid()
    {
        $user = $this->createUsualUser();
        $user->id = 2;
        Basket::query()->insert(['id_user' => $user->id, 'id_noise_source' => '93']);

        $response = $this->actingAs($user)->delete('noise/main/basket/93');
        $response = $this->actingAs($user)->delete('noise/main/basket/93');

        $response->assertSessionHasErrors();
    }
}
