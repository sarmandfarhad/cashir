<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guest can set display preferences on the login page', function (string $route, array $payload, string $field) {
    $this->from(route('login.form'))
        ->post(route($route), $payload)
        ->assertRedirect(route('login.form'))
        ->assertSessionHas($field, $payload[$field]);
})->with([
    'locale' => ['preferences.locale', ['locale' => 'ar'], 'locale'],
    'theme' => ['preferences.theme', ['theme' => 'dark'], 'theme'],
]);

test('authenticated user can switch locale and it is applied to later requests', function () {
    $user = User::factory()->create(['name' => 'Noor']);

    $this->actingAs($user)
        ->from(route('dashboard'))
        ->post(route('preferences.locale'), ['locale' => 'ar'])
        ->assertRedirect(route('dashboard'))
        ->assertSessionHas('locale', 'ar');

    $this->get(route('dashboard'))
        ->assertOk()
        ->assertSee('lang="ar"', false)
        ->assertSee('dir="rtl"', false)
        ->assertSee('كاشير نقطة البيع');
});

test('authenticated user can switch theme through json', function () {
    $user = User::factory()->create(['name' => 'Noor']);

    $this->actingAs($user)
        ->postJson(route('preferences.theme'), ['theme' => 'dark'])
        ->assertOk()
        ->assertJson([
            'success' => true,
            'theme' => 'dark',
        ])
        ->assertSessionHas('theme', 'dark');

    $this->get(route('dashboard'))
        ->assertOk()
        ->assertSee('data-theme="dark"', false);
});

test('unsupported preference values are rejected without replacing the session value', function (
    string $route,
    string $field,
    string $current,
    string $invalid,
) {
    $user = User::factory()->create(['name' => 'Noor']);

    $response = $this->actingAs($user)
        ->withSession([$field => $current])
        ->from(route('dashboard'))
        ->post(route($route), [$field => $invalid]);

    $response->assertRedirect(route('dashboard'))
        ->assertSessionHas($field, $current);

    expect(session()->has('errors'))->toBeTrue();
})->with([
    'locale' => ['preferences.locale', 'locale', 'en', 'ku'],
    'theme' => ['preferences.theme', 'theme', 'light', 'system'],
]);
