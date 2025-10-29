<?php

return [
    'unsplash' => [
        'api_key' => env('UNSPLASH_API_KEY'),
        'utm' => env('UNSPLASH_UTM', '?utm_source=' . env('APP_NAME') . '&utm_medium=referral'),
        'fallback_css' => env('UNSPLASH_FALLBACK_CSS',
            'background: #302a9b; background: linear-gradient(44deg,rgba(48, 42, 155, 1) 0%, rgba(199, 87, 182, 1) 100%);'),
        'query' => env('UNSPLASH_QUERY', 'beautiful,landscape'),
    ],

    'account' => [
        'default_avatar_url' => env('DEFAULT_AVATAR_URL', 'https://avatars.cyanfox.de/beam/100/{email_md5}'),
    ],

    'auth' => [
        'rate_limit' => env('AUTH_RATE_LIMIT', '10'),

        'oauth' => [
            'enabled' => env('OAUTH_ENABLED', false),
            'login_color' => env('OAUTH_LOGIN_COLOR', 'success'),
            'login_text' => env('OAUTH_LOGIN_TEXT', 'Login with OAuth'),
            'discovery_url' => env('OAUTH_DISCOVERY_URL'),
            'client_id' => env('OAUTH_CLIENT_ID'),
            'client_secret' => env('OAUTH_CLIENT_SECRET'),
            'redirect_uri' => env('OAUTH_REDIRECT_URI'),
            'fields' => [
                'id' => env('OAUTH_FIELD_ID', 'id'),
                'name' => env('OAUTH_FIELD_NAME', 'preferred_username'),
                'email' => env('OAUTH_FIELD_EMAIL', 'email'),
            ],
        ]
    ]
];
