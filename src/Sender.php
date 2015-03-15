<?php

namespace Kalnoy\Sms;

interface Sender {

    /**
     * Send one or several sms.
     *
     * @param string|array $phone
     * @param string|null $message
     *
     * @return int The number of successfully sent messages
     *
     * @throws SendFailedException
     */
    public function send($phones, $message = null);
}