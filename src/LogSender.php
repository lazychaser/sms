<?php

namespace Kalnoy\Sms;

use Illuminate\Log\Writer;

class LogSender implements Sender {

    /**
     * @var \Illuminate\Log\Writer
     */
    protected $log;

    /**
     * @param Writer $log
     */
    function __construct(Writer $log)
    {
        $this->log = $log;
    }

    /**
     * Send a sms.
     *
     * @param $phone
     * @param $message
     *
     * @return bool
     */
    public function send($phone, $message)
    {
        $this->log->info('A SMS to '.$phone.': '.$message);

        return true;
    }
}