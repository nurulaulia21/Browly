<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rules\Password;
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
        // Aturan password baru: minimal 8 karakter, mengandung huruf dan angka.
        Password::defaults(fn () => Password::min(8)->letters()->numbers());

        // Pada produksi (Vercel) semua alamat dibuat memakai https.
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
