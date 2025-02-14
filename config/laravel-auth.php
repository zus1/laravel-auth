<?php

use Zus1\LaravelAuth\Constant\Token\TokenType;

return [
    'user_namespace' => env('LARAVEL_AUTH_USER_NAMESPACE', 'App\Models'),
    'user_class' => sprintf(
        '%s\\%s',
        env('LARAVEL_AUTH_USER_NAMESPACE', 'App\Models'),
        env('LARAVEL_AUTH_USER_CLASS', 'User')
    ),

    'token' => [
        'expires_in' => [
            'access_token' => 30 * 24 * 60,
            'refresh_token' => 60 * 24 * 60,
            'user_verification_token' => 60,
            'reset_password_token' => 30,
        ],
        'length' => [
            'access_token' => 100,
            'refresh_token' => 100,
            'user_verification_token' => 50,
            'reset_password_token' => 50,
        ],
        'type_class' => TokenType::class,
        'request_header' => 'Authorization'
    ],
    'email' => [
        'subject' => [
            'verification' => 'Email verification',
            'reset_password' => 'Reset Password',
            'welcome' => 'Welcome'
        ],
        'templates' => [
            'verification' => [
                'txt' => 'mail/authentication::verify-txt',
                'markdown' => 'mail/authentication::verify'
            ],
            'reset_password' => [
                'txt' => 'mail/authentication::reset-password-txt',
                'markdown' => 'mail/authentication::reset-password'
            ],
            'welcome' => [
                'txt' => 'mail/authentication::welcome-txt',
                'markdown' => 'mail/authentication::welcome'
            ]
        ],
    ],

    'authorization' => [
        'mapping' => [
            //'routeName' => 'policyAction'
        ],
        'possible_route_parameters' => [
            //user,tag,token...
        ],
        'additional_subjects' => [
            //'routeName' => 'AdditionalSubject'
        ],
    ]
];