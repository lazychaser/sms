<?php

namespace Kalnoy\Sms;

use Illuminate\Support\ServiceProvider;

class SmsServiceProvider extends ServiceProvider {

    /**
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->publishes([
            __DIR__.'/../config/sms.php' => config_path('sms.php'),
        ]);

        $this->app->bindShared('sms', function ($app)
        {
            return new Manager($app);
        });

        $this->app->alias('sms', 'Kalnoy\Sms\Manager');
    }

    /**
     * @return array
     */
    public function provides()
    {
        return [ 'sms', 'Kalnoy\Sms\Manager' ];
    }
}