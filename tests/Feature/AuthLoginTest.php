<?php

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('admin user is seeded and can login through the api', function () {
    $this->seed(DatabaseSeeder::class);

    $this->assertDatabaseHas('users', [
        'email' => 'admin@gmail.com',
    ]);

    $response = $this->postJson('/api/login', [
        'email' => 'admin@gmail.com',
        'password' => '2244',
    ]);

    $response->assertOk()
        ->assertJson([
            'message' => 'Login successful.',
            'user' => [
                'email' => 'admin@gmail.com',
            ],
        ]);
});

test('web login redirects to the dashboard', function () {
    $this->seed(DatabaseSeeder::class);

    $response = $this->post('/login', [
        'email' => 'admin@gmail.com',
        'password' => '2244',
    ]);

    $response->assertRedirect(route('dashboard'));
    $this->assertAuthenticated();
});