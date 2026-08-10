<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Noor',
                'email' => 'noor@gmail.com',
            ],
            [
                'name' => 'Yousef',
                'email' => 'yousef@gmail.com',
            ],
        ];

        foreach ($users as $user) {
            User::query()->updateOrCreate([
                'email' => $user['email'],
            ], [
                'name' => $user['name'],
                'password' => '2244',
            ]);
        }
    }
}
