<?php

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('cashier users are seeded and can login through the api', function (string $name, string $email) {
    $this->seed(DatabaseSeeder::class);

    $this->assertDatabaseHas('users', [
        'name' => $name,
        'email' => $email,
    ]);

    $response = $this->postJson('/api/login', [
        'email' => $email,
        'password' => '2244',
    ]);

    $response->assertOk()
        ->assertJson([
            'message' => 'Login successful.',
            'user' => [
                'name' => $name,
                'email' => $email,
            ],
        ]);
})->with([
    'Noor' => ['Noor', 'noor@gamil.com'],
    'Yousef' => ['Yousef', 'yousef@gmail.com'],
]);

test('web login redirects to the dashboard', function () {
    $this->seed(DatabaseSeeder::class);

    $response = $this->post('/login', [
        'email' => 'noor@gamil.com',
        'password' => '2244',
    ]);

    $response->assertRedirect(route('dashboard'));
    $this->assertAuthenticated();
});