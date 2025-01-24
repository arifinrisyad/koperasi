<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Penjualan;
use App\Models\Pembelian;
use App\Observers\PenjualanObserver;
use App\Observers\PembelianObserver;

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
        Penjualan::observe(PenjualanObserver::class);
        Pembelian::observe(PembelianObserver::class);
    }
}
