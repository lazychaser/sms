<?php

namespace Kalnoy\Sms;

use Illuminate\Support\Manager as BaseManager;

class Manager extends BaseManager {

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

        return new SmscSender($login, $password, $sender);
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
}