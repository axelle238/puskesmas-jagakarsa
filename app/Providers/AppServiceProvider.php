<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

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
        $this->configureDefaults();
        $this->defineGates();
    }

    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null
        );
    }

    protected function defineGates(): void
    {
        // Admin memiliki akses penuh ke seluruh sistem (Superuser)
        Gate::before(function ($user, $ability) {
            if ($user->peran === 'admin') {
                return true;
            }
        });

        Gate::define('akses-admin', function ($user) {
            return $user->peran === 'admin';
        });

        Gate::define('akses-pendaftaran', function ($user) {
            return $user->peran === 'pendaftaran';
        });

        Gate::define('akses-medis', function ($user) {
            return in_array($user->peran, ['dokter', 'perawat']);
        });

        Gate::define('akses-farmasi', function ($user) {
            return $user->peran === 'apoteker';
        });

        Gate::define('akses-lab', function ($user) {
            return $user->peran === 'analis';
        });

        Gate::define('akses-kasir', function ($user) {
            return $user->peran === 'kasir';
        });
        
        Gate::define('akses-manajemen', function ($user) {
            return $user->peran === 'kapus';
        });
    }
}
