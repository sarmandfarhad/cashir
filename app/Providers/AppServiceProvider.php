<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('app.env') === 'production') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        $usesMemoryDatabase = config('database.default') === 'sqlite'
            && config('database.connections.sqlite.database') === ':memory:';
        $autoMigrate = filter_var(env('APP_AUTO_MIGRATE', false), FILTER_VALIDATE_BOOL);

        if ($usesMemoryDatabase || $autoMigrate) {
            \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);

            $users = [
                ['name' => 'Noor', 'email' => 'noor@gmail.com'],
                ['name' => 'Yousef', 'email' => 'yousef@gmail.com'],
            ];

            foreach ($users as $user) {
                \App\Models\User::query()->updateOrCreate([
                    'email' => $user['email'],
                ], [
                    'name' => $user['name'],
                    'password' => '2244',
                ]);
            }
        }
    }
}
