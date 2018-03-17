<?php

return [
    'grants' => [
        'refresh_token',
        'password',
        'client_credentials',
    ],
    'client_credentials' => [
        'access_token_ttl' => new DateInterval('P1Y'),
    ],
    'access_token_ttl' => new DateInterval('PT2H'),
    'refresh_token_ttl' => new DateInterval('P2Y'),
    'private_key' => storage_path('oauth-private.key'),
    'public_key' => storage_path('oauth-public.key'),
];
