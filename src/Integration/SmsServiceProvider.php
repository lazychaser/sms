<?php

namespace Kalnoy\Sms\Integration;

use Illuminate\Support\ServiceProvider;
use Kalnoy\Sms\Manager;

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
            __DIR__.'/../../config/sms.php' => config_path('sms.php'),
        ]);

        $this->app->bindShared('sms', function ($app)
        {
            return new Manager($app);
        });

        $this->app->bindShared('sms.driver', function ($app)
        {
            return $app['sms']->driver();
        });

        $this->app->alias('sms', 'Kalnoy\Sms\Manager');
        $this->app->alias('sms.driver', 'Kalnoy\Sms\Sender');
    }

    /**
     * @return array
     */
    public function provides()
    {
        return [ 'sms', 'Kalnoy\Sms\Manager', 'sms.driver', 'Kalnoy\Sms\Sender' ];
    }
}