<?php

namespace LibSmsTwilio\Library;

use LibCurl\Library\Curl;

class Sender implements \LibSms\Iface\Sender
{
    protected static $host = 'https://api.twilio.com/2010-04-01';

    protected static $error;
    protected static $errno;

    protected static function setError($message, $code)
    {
        self::$errno = $code;
        self::$error = $message;

        return false;
    }

    static function send(string $phone, string $message): bool
    {
        $config = \Mim::$app->config->libSmsTwilio->SMS;

        // ISO-ish phone number
        $phone = preg_replace('![^0-9]!', '', $phone);
        $phone = preg_replace('!^0!', '62', $phone);
        $phone = '+' . $phone;

        $serv_id = $config->ServiceID;
        $accn_id = $config->AccountSID;
        $url = self::$host . '/Accounts/' . $accn_id . '/Messages.json';

        $res = Curl::fetch([
            'url' => $url,
            'method' => 'POST',
            'body' => [
                'Body' => $message,
                'MessagingServiceSid' => $serv_id,
                'To' => $phone
            ],
            'user' => [
                'name' => $config->AccountSID,
                'password' => $config->AuthToken
            ]
        ]);


        if (!$res) {
            return self::setError('Unable to reach SMS provider');
        }

        if (isset($res->code)) {
            return self::setError($res->message, $res->code);
        }

        return true;
    }

    static function lastError(): ?string
    {
        return self::$error;
    }

    static function lastErrno(): ?int
    {
        return self::$errno;
    }
}
