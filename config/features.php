<?php

return [
    'flags' => [
        'enable_biometric_login' => env('FEATURE_BIOMETRIC_LOGIN', false),
        'enable_new_event_cards' => env('FEATURE_NEW_EVENT_CARDS', true),
        'enable_group_timetables' => env('FEATURE_GROUP_TIMETABLES', true),
    ]
];
