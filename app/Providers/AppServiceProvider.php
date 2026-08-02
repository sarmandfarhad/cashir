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

        if (config('database.default') === 'sqlite' && config('database.connections.sqlite.database') === ':memory:') {
            \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
            
            \App\Models\User::firstOrCreate([
                'email' => 'admin@gmail.com',
            ], [
                'name' => 'Admin',
                'password' => env('ADMIN_PASSWORD', 'SecurePass123!'),
            ]);
        }
    }
}
