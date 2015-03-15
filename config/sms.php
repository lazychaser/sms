<?php return [
    /**
     * SMS DRIVER
     *
     * `log` or `smsc`
     */
    'driver' => env('SMS_DRIVER', 'log'),

    /**
     * The display name of the sender.
     */
    'sender_name' => null,

    /**
     * Configuration for smsc driver.
     */
    'smsc' => [
        'login' => env('SMSC_LOGIN'),
        'password' => env('SMSC_PASSWORD'),
    ],

];
