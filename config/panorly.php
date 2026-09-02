<?php

return [
    'name' => env('APP_NAME', 'Panorly'),

    'themes' => [
        'default' => 'midnight',
        'presets' => ['midnight', 'daylight', 'forest', 'sunset'],
        'allow_custom' => true,
    ],

    'uploads' => [
        'backgrounds' => [
            'disk' => 'public',
            'path' => 'backgrounds',
            'max_size_kb' => 5120,
            'allowed_mime' => ['image/jpeg', 'image/png', 'image/webp'],
        ],
    ],

    'export' => [
        'format_version' => 1,
    ],
];
