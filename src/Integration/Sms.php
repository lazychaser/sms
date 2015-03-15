<?php

namespace Kalnoy\Sms\Facade;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Kalnoy\Sms\Manager|\Kalnoy\Sms\Sender
 */
class Sms extends Facade {

    /**
     * @return string
     */
    protected static function getFacadeAccessor() { return 'sms'; }
}