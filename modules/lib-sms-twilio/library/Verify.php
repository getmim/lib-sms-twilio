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

    protected static function getMockFile()
    {
        return BASEPATH . '/twilio-verify.txt';
    }

    protected static function getConfig()
    {
        $config = \Mim::$app->config->libSmsTwilio->Verify;
        if ($config->Host) {
            self::$host = $config->Host;
        }
        return $config;
    }

    static function lastError()
    {
        return self::$error;
    }

    static function request(string $phone) {
        $mock_file = self::getMockFile();
        if (is_file($mock_file)) {
            $otp = rand(100000, 999999);
            file_put_contents($mock_file, $phone . ':' . $otp);
            return true;
        }

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
        $mock_file = self::getMockFile();
        if (is_file($mock_file)) {
            $ctn = file_get_contents($mock_file);
            $ctns = explode(':', $ctn);

            return $ctns[0] == $phone && $code == $ctns[1];
        }

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
