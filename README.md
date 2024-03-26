# lib-sms-twilio

## Instalasi

Jalankan perintah di bawah di folder aplikasi:

```
mim app install lib-sms-twilio
```

## Konfigurasi

Pastikan menambahkan informasi koneksi ke twilio seperti di bawah pada
konfigurasi aplikasi:

```php
return [
    'libSmsTwilio' => [
        'Verify' => [
            'AccountSID' => '...',
            'AuthToken' => '...',
            'ServiceID' => '...'
        ],
        'SMS' => [
            'AccountSID' => '...',
            'AuthToken' => '...',
            'ServiceID' => '...'
        ]
    ]
];
```

## Penggunaan

### Kirim SMS

Silahkan gunakan metode pengiriman SMS sesuai dengan instruksi module `lib-sms`.

```php
use LibSms\Library\Sms;

$res = Sms::send($phone, $text);
```

### Verify

Module ini memiliki class tambahan untuk verify ( sms otp ) dengan menggunakan
class `LibSmsTwilio\Library\Verify`.

```php
use LibSmsTwilio\Library\Verify;

# Request OTP
$result = Verify::request($phone);

# Verify OTP
$result = Verify::verify($phone, $otp);
```
