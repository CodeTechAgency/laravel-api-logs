<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Guard
    |--------------------------------------------------------------------------
    |
    | The authentication guard used to resolve the user a logged request is
    | attributed to. When null, the request's default guard is used (the one
    | set by the auth middleware, or the application's default guard).
    |
    */

    'guard' => null,

    /*
    |--------------------------------------------------------------------------
    | Redaction
    |--------------------------------------------------------------------------
    |
    | Sensitive values are replaced with the string below before the log is
    | stored. "keys" are matched recursively (case-insensitively) against the
    | request and response data; "headers" are matched against the request
    | header names.
    |
    */

    'redact' => [

        'replacement' => '[REDACTED]',

        'keys' => [
            'password',
            'password_confirmation',
            'current_password',
            'new_password',
            'secret',
            'token',
            'api_token',
            'access_token',
            'refresh_token',
            'private_key',
            'credit_card',
            'card_number',
            'cvv',
        ],

        'headers' => [
            'authorization',
            'proxy-authorization',
            'cookie',
            'x-api-key',
            'x-csrf-token',
            'x-xsrf-token',
            'php-auth-user',
            'php-auth-pw',
            'php-auth-digest',
        ],

    ],

];
