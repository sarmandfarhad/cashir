<?php

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->withoutVite();
    $this->seed(DatabaseSeeder::class);
});

test('authenticated cashier can view the complete pos interface', function () {
    $user = User::query()->whereEmail('noor@gmail.com')->firstOrFail();

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSee('id="product-grid"', false)
        ->assertSee('id="cart-items"', false)
        ->assertSee('id="payment-modal"', false)
        ->assertSee('data-payment-method="mobile_pay"', false)
        ->assertSee($user->name);
});

test('pos interface renders in arabic and right to left', function () {
    $user = User::query()->whereEmail('noor@gmail.com')->firstOrFail();

    $this->actingAs($user)
        ->withSession(['locale' => 'ar'])
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSee('lang="ar"', false)
        ->assertSee('dir="rtl"', false);
});

test('pos interface renders the selected dark theme', function () {
    $user = User::query()->whereEmail('yousef@gmail.com')->firstOrFail();

    $this->actingAs($user)
        ->withSession(['theme' => 'dark'])
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSee('data-theme="dark"', false);
});
