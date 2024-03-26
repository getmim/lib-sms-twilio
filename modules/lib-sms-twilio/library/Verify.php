<?php
/**
 * Verify
 * @package lib-sms-twilio
 * @version 0.0.1
 */

namespace LibSmsTwilio\Library;

use LibCurl\Library\Curl;

class Verify
{
    protected static $host = 'https://verify.twilio.com/v2';
    protected static $error;

    protected static function getConfig()
    {
        return \Mim::$app->config->libSmsTwilio->Verify;
    }

    static function lastError()
    {
        return self::$error;
    }

    static function request(string $phone) {
        $config = self::getConfig();
        $serv_id = $config->ServiceID;
        $url = self::$host . '/Services/' . $serv_id . '/Verifications';
        $res = Curl::fetch([
            'url' => $url,
            'method' => 'POST',
            'body' => [
                'To' => $phone,
                'Channel' => 'sms'
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

    static function setError(string $message, string $code = null)
    {
        self::$error = $message;
        if ($code) {
            self::$error .= ' (' . $code . ')';
        }

        return false;
    }

    static function verify(string $phone, string $code)
    {
        $config = self::getConfig();
        $serv_id = $config->ServiceID;
        $url = self::$host . '/Services/' . $serv_id . '/VerificationCheck';

        $res = Curl::fetch([
            'url' => $url,
            'method' => 'POST',
            'body' => [
                'To' => $phone,
                'Code' => $code
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

        return $res->valid;
    }
}
