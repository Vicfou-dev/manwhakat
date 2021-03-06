<?php

namespace App\Providers;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use App\Modules\Downloaders\MangaImageDownloader;
use App\Modules\Scrappers\Scrapper;

class AppServiceProvider extends ServiceProvider
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
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.
        $this->app->bind(Client::class, function($app, $args = []) {
            $args['verify'] = 'C:\wamp64\cacert.pem';
            return new Client($args);
        });

        $this->app->singleton(MangaImageDownloader::class,function() {
            return new MangaImageDownloader();
        });
    }
}
