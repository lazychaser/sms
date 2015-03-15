<?php

use Kalnoy\Sms\SmscSender;
use Mockery as m;

class SmscSenderTest extends PHPUnit_Framework_TestCase {

    public function setUp()
    {
        $this->driver = new SmscSender('login', 'password', 'sender');
        $this->response = m::mock('request')->shouldReceive('getBody')->andReturn('{ "cnt": "1" }')->mock();
    }

    public function testSingleMessage()
    {
        $client = m::mock('client')->shouldReceive('get')
            ->with(SmscSender::SCRIPT, $this->getParams([
                'phones' => 'phone',
                'mes' => 'message',

            ]))->andReturn($this->response)->mock();

        $this->driver->setHttpClient($client);

        $count = $this->driver->send('phone', 'message');

        $this->assertEquals(1, $count);
    }

    public function testManyPhones()
    {
        $client = m::mock('client')->shouldReceive('get')
            ->with(SmscSender::SCRIPT, $this->getParams([
                'phones' => 'phone1,phone2',
                'mes' => 'message',
            ]))->andReturn($this->response)->mock();

        $this->driver->setHttpClient($client);

        $this->driver->send([ 'phone1', 'phone2' ], 'message');
    }

    public function testManyMessages()
    {
        $client = m::mock('client')->shouldReceive('get')
            ->with(SmscSender::SCRIPT, $this->getParams([
                'list' => 'phone1:message1\nnewline'.PHP_EOL.'phone2:message2',
            ]))->andReturn($this->response)->mock();

        $this->driver->setHttpClient($client);

        $this->driver->send([ 'phone1' => 'message1'.PHP_EOL.'newline', 'phone2' => 'message2' ]);
    }

    /**
     * @expectedException \Kalnoy\Sms\SendFailedException
     * @expectedExpcetionMessage error
     * @expectedExceptionCode 12
     */
    public function testErrourneusResponse()
    {
        $response = m::mock('request')->shouldReceive('getBody')->andReturn('{"error": "error","error_code": 12}')
            ->mock();

        $client = m::mock('client')->shouldReceive('get')->withAnyArgs()->andReturn($response)->mock();

        $this->driver->setHttpClient($client);

        $this->driver->send('phone', 'message');
    }

    public function getParams($params)
    {
        $params['login'] = 'login';
        $params['psw'] = md5('password');
        $params['fmt'] = 3;
        $params['charset'] = 'utf-8';
        $params['sender'] = 'sender';

        return [ 'query' => $params ];
    }
}