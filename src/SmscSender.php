<?php

namespace Kalnoy\Sms;

class SmscSender extends AbstractSender {

    /**
     * The format of the response.
     */
    const FMT_JSON = 3;

    /**
     * The url to the api entry.
     */
    const SCRIPT = 'http://smsc.ru/sys/send.php';

    /**
     * @var string
     */
    protected $login;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var string
     */
    protected $senderName;

    /**
     * @param $login
     * @param $password
     * @param $senderName
     */
    function __construct($login, $password, $senderName)
    {
        $this->login = $login;
        $this->password = $this->processPassword($password);
        $this->senderName = $senderName;
    }

    /**
     * Send a sms.
     *
     * @param string|array $phones
     * @param $message
     *
     * @return int
     */
    public function send($phones, $message = null)
    {
        if (is_null($message))
        {
            $params['list'] = $this->getMessageList($phones);
        }
        else
        {
            $params['phones'] = implode(',', (array)$phones);
            $params['mes'] = $message;
        }

        $response = $this->request($params);

        return (int)$response->cnt;
    }

    /**
     * Make a request to the API.
     *
     * @param array $params
     *
     * @return object
     *
     * @throws SendFailedException
     */
    protected function request(array $params)
    {
        $params['login'] = $this->login;
        $params['psw'] = $this->password;
        $params['fmt'] = static::FMT_JSON;
        $params['charset'] = 'utf-8';
        $params['sender'] = $this->senderName;

        $response = $this->client->get(static::SCRIPT, [ 'query' => $params ])->getBody();

        if ( ! $response or ( $response = json_decode($response)) === false)
        {
            throw new SendFailedException('Unexpected response.');
        }

        if (isset($response->error))
        {
            throw new SendFailedException($response->error, $response->error_code);
        }

        return $response;
    }

    /**
     * @param array $phones
     *
     * @return string
     */
    private function getMessageList($phones)
    {
        $list = [];

        foreach ((array)$phones as $phone => $message)
        {
            $list[] = $phone.':'.str_replace([ "\r", "\n" ], [ '', '\n' ], $message);
        }

        return implode(PHP_EOL, $list);
    }

    /**
     * @param $password
     *
     * @return string
     */
    protected function processPassword($password)
    {
        return md5(strtolower($password));
    }
}