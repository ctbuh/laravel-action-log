<?php

namespace ctbuh\ActionLog;

use Illuminate\Support\ServiceProvider;

class ActionLogServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
		
		$this->publishes(array(
			__DIR__.'/migrations/' => database_path('migrations')
		), 'migrations');
		
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
