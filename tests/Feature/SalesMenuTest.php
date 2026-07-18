<?php

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guest is redirected to login when accessing sales menu', function () {
    $response = $this->get(route('sales.index'));

    $response->assertRedirect('/login');
});

test('authenticated user can view sales menu', function () {
    $this->seed(DatabaseSeeder::class);
    $user = User::whereEmail('admin@gmail.com')->first();

    $response = $this->actingAs($user)->get(route('sales.index'));

    $response->assertOk()
        ->assertViewIs('sales.index')
        ->assertViewHas('transactions');
});

test('authenticated user can save transaction to session', function () {
    $this->seed(DatabaseSeeder::class);
    $user = User::whereEmail('admin@gmail.com')->first();

    $response = $this->actingAs($user)->postJson(route('sales.save'), [
        'total_items' => 4,
        'total_payment' => 95000,
        'payment_method' => 'card',
    ]);

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'transaction' => [
                'cashier_name' => 'Admin',
                'total_items' => 4,
                'total_payment' => 95000,
                'payment_method' => 'card',
            ]
        ]);

    // Check it exists in session
    $sessionTransactions = session('transactions', []);
    expect($sessionTransactions)->toHaveCount(1);
    expect($sessionTransactions[0]['total_payment'])->toBe(95000.0);
});
