<?php

namespace Tests\Feature;

use Tests\TestCase;

class AccessToPageTest extends TestCase
{
    public function test_access_welcome_page()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_access_login_page()
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
    }

    public function test_access_register_page()
    {
        $response = $this->get(route('register'));

        $response->assertStatus(200);
    }

    public function test_access_search_sources_page()
    {
        $response = $this->get(route('noise.main.sources.search', ['search' => 'автобус']));

        $response->assertStatus(200);
    }

    public function test_access_sources_page()
    {
        $response = $this->get(route('noise.main.sources.index'));

        $response->assertStatus(200);
    }

    public function test_access_sources_create_page()
    {
        $user = $this->createAdminUser();

        $response = $this->actingAs($user)->get(route('noise.main.sources.create'));

        $response->assertStatus(200);
    }

    public function test_sources_create_page_is_unavailable_for_guest()
    {
        $response = $this->get(route('noise.main.sources.create'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_access_sources_basket_page()
    {
        $user = $this->createUsualUser();

        $response = $this->actingAs($user)->get(route('noise.main.basket.index'));

        $response->assertStatus(200);
    }

    public function test_sources_basket_page_is_unavailable_for_guest()
    {
        $response = $this->get(route('noise.main.basket.index'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_admin_page_with_sources_is_unavailable_for_usually_user()
    {
        $user = $this->createUsualUser();

        $response = $this->actingAs($user)->get(route('noise.admin.sources.index'));

        $response->assertStatus(403);
    }

    public function test_admin_page_with_sources_is_unavailable_for_guest()
    {
        $response = $this->get(route('noise.admin.sources.index'));

        $response->assertStatus(403);
    }

    public function test_access_admin_page_with_new_sources()
    {
        $user = $this->createAdminUser();

        $response = $this->actingAs($user)->get(route('noise.admin.sources.index'));

        $response->assertStatus(200);
    }

    public function test_admin_page_edit_sources_is_unavailable_for_usually_user()
    {
        $user = $this->createUsualUser();

        $response = $this->actingAs($user)->get(route('noise.admin.sources.edit', ['source' => 1]));

        $response->assertStatus(403);
    }

    public function test_admin_page_edit_sources_is_unavailable_for_guest()
    {
        $response = $this->get(route('noise.admin.sources.edit', ['source' => 1]));

        $response->assertStatus(403);
    }

    public function test_access_admin_page_edit_sources()
    {
        $user = $this->createAdminUser();

        $response = $this->actingAs($user)->get(route('noise.admin.sources.edit', ['source' => 1]));

        $response->assertStatus(200);
    }
}
