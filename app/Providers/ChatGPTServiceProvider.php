<?php

namespace App\Providers;

use App\Services\ChatGPTService;
use Illuminate\Support\ServiceProvider;

class ChatGPTServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ChatGPTService::class, function ($app) {
            return new ChatGPTService();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
