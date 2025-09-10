<?php

namespace App\Providers;
use Livewire\Livewire;
use App\Http\Livewire\LacakPesanan;
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
    public function boot()
    {
        Livewire::component('lacak-pesanan', LacakPesanan::class);
    }
}
