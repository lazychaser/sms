<?php

namespace Kalnoy\Sms;

use GuzzleHttp\ClientInterface;
use Illuminate\Support\Manager as BaseManager;

class Manager extends BaseManager {

    /**
     * @var ClientInterface
     */
    private $client;

    public function __construct($app, ClientInterface $client)
    {
        parent::__construct($app);

        $this->client = $client;
    }

    /**
     * @return LogSender
     */
    protected function createLogDriver()
    {
        return new LogSender($this->app['log']);
    }

    /**
     * @return SmscSender
     */
    protected function createSmscDriver()
    {
        /** @var \Illuminate\Contracts\Config\Repository $config */
        $config = $this->app->make('config');

        $login = $config->get('sms.smsc.login');
        $password = $config->get('sms.smsc.password');
        $sender = $config->get('sms.sender_name');

        return $this->setClient(new SmscSender($login, $password, $sender));
    }

    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return $this->app->make('config')->get('sms.driver', 'log');
    }

    /**
     * @param AbstractSender $driver
     *
     * @return mixed
     */
    protected function setClient(AbstractSender $driver)
    {
        $driver->setHttpClient($this->client);

        return $driver;
    }
}