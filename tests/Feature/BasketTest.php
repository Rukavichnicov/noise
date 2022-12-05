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

        $response = $this->actingAs($user)->post('noise/main/basket', [
            "addSources" => "93"
        ]);

        $response->assertRedirect();

        Basket::query()->where('id_user', '=', $user->id)->delete();
    }

    public function test_double_adding_sources_in_basket_is_invalid()
    {
        $user = $this->createUsualUser();

        $response = $this->actingAs($user)->post('noise/main/basket', [
            "addSources" => "93"
        ]);
        $response = $this->actingAs($user)->post('noise/main/basket', [
            "addSources" => "93"
        ]);

        $response->assertInvalid(['addSources']);

        Basket::query()->where('id_user', '=', $user->id)->delete();
    }
}
