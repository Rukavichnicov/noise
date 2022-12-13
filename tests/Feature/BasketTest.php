<?php

namespace Tests\Feature;

use App\Models\Basket;
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
}
