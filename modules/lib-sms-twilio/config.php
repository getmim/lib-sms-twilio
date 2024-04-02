<?php

return [
    '__name' => 'lib-sms-twilio',
    '__version' => '0.1.0',
    '__git' => 'git@github.com:getmim/lib-sms-twilio.git',
    '__license' => 'MIT',
    '__author' => [
        'name' => 'Iqbal Fauzi',
        'email' => 'iqbalfawz@gmail.com',
        'website' => 'https://iqbalfn.com/'
    ],
    '__files' => [
        'modules/lib-sms-twilio' => ['install','update','remove']
    ],
    '__dependencies' => [
        'required' => [
            [
                'lib-curl' => NULL
            ],
            [
                'lib-sms' => NULL
            ]
        ],
        'optional' => []
    ],
    'autoload' => [
        'classes' => [
            'LibSmsTwilio\\Library' => [
                'type' => 'file',
                'base' => 'modules/lib-sms-twilio/library'
            ]
        ],
        'files' => []
    ],
    '__inject' => [
        [
            'name' => 'libSmsTwilio',
            'children' => [
                [
                    'name' => 'Verify',
                    'children' => [
                        [
                            'name' => 'Host',
                            'question' => 'TWILIO Verify Hostname',
                            'rule' => '!^.+$!'
                        ],
                        [
                            'name' => 'AccountSID',
                            'question' => 'TWILIO Verify Account SID',
                            'rule' => '!^.+$!'
                        ],
                        [
                            'name' => 'AuthToken',
                            'question' => 'TWILIO Verify Auth Token',
                            'rule' => '!^.+$!'
                        ],
                        [
                            'name' => 'ServiceID',
                            'question' => 'TWILIO Verify Service ID',
                            'rule' => '!^.+$!'
                        ]
                    ]
                ],
                [
                    'name' => 'SMS',
                    'children' => [
                        [
                            'name' => 'AccountSID',
                            'question' => 'TWILIO SMS Account SID',
                            'rule' => '!^.+$!'
                        ],
                        [
                            'name' => 'AuthToken',
                            'question' => 'TWILIO SMS Auth Token',
                            'rule' => '!^.+$!'
                        ],
                        [
                            'name' => 'ServiceID',
                            'question' => 'TWILIO SMS Service ID',
                            'rule' => '!^.+$!'
                        ]
                    ]
                ]
            ]
        ]
    ],
    'libSms' => [
        'senders' => [
            'twilio' => 'LibSmsTwilio\\Library\\Sender'
        ]
    ]
];
