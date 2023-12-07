<?php

namespace App\Providers;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\ServiceProvider;

class BlueprintServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blueprint::macro('defaultColumn', function () {
            $this->string('created_by')->nullable();
            $this->string('updated_by')->nullable();
            $this->string('created_time')->nullable();
            $this->string('updated_time')->nullable();
            $this->tinyInteger('status')->default(1);
        });
    }
}
