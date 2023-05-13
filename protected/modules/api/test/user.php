<?php
return [
    'user' => [

        'POST:login' => [
            'params' => [
                'LoginForm[username]' => 'Trand59est@209String.com',
                'LoginForm[password]' => 'Test String',
                'LoginForm[device_token]' => '12131313',
                'LoginForm[device_type]' => '1',
                'LoginForm[device_name]' => '1'
            ]
        ],

        'GET:check' => [
            'params' => [
                'DeviceDetail[device_token]' => 'harman',
                'DeviceDetail[device_type]' => 'admin',
                'DeviceDetail[device_name]' => '12131313'
            ]
        ],
        'POST:signup' => [
            'params' => [
                'User[full_name]' => 'Test String',
                'User[email]' => 'Trand' . rand(0, 499) . 'est@' . rand(0, 499) . 'String.com',
                'User[password]' => 'Test String',
                'User[role_id]' => '1',
                'User[contact_no]' => '8989898'
            ]
        ],
        'POST:change-password' => [
            'params' => [
                'User[oldPassword]' => 'Test String',
                'User[newPassword]' => 'Test String',
                'User[confirm_password]' => 'Test String'
            ]
        ]
    ]
];