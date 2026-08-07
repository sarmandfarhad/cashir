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

            \App\Models\User::firstOrCreate([
                'email' => 'noor@gmail.com',
            ], [
                'name' => 'Noor',
                'password' => '2244',
            ]);

            \App\Models\User::firstOrCreate([
                'email' => 'yousef@gmail.com',
            ], [
                'name' => 'Yousef',
                'password' => '2244',
            ]);
        }

        // Set locale from session
        if (session()->has('locale')) {
            app()->setLocale(session()->get('locale'));
        }
    }
}
