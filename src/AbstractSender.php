<?php

namespace Kalnoy\Sms;

use GuzzleHttp\ClientInterface;

abstract class AbstractSender implements Sender {

    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @param ClientInterface $client
     */
    public function setHttpClient($client)
    {
        $this->client = $client;
    }

}