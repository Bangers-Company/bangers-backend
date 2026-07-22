<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Firebase Credentials (.env Based)
    |--------------------------------------------------------------------------
    |
    | Credentials loaded directly from .env for FCM HTTP v1 REST API messaging.
    |
    */

    'project_id' => env('FIREBASE_PROJECT_ID'),
    'client_email' => env('FIREBASE_CLIENT_EMAIL'),
    'private_key' => str_replace('\\n', "\n", env('FIREBASE_PRIVATE_KEY', '')),
];
