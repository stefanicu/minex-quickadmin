<?php
// App\Providers\ViewServiceProvider.php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if (app()->runningUnitTests()) {
            return;
        }

        View::composer('*', function ($view) {
            $locale = app()->getLocale();
            
            $gdprTranslation = DB::table('page_translations')
                ->where('page_id', 1) // ID-ul paginii GDPR
                ->where('locale', $locale)
                ->first();
            
            $view->with('gdprTranslation', $gdprTranslation);
        });
    }
}