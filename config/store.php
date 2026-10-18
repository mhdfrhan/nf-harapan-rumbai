<?php

return [
    'name' => env('STORE_NAME', 'Toko NF Harapan Rumbai'),
    'address' => env('STORE_ADDRESS', 'Jl. Harapan, Lembah Sari, Rumbai, Kota Pekanbaru, Riau 28266'),
    'timezone' => env('STORE_TIMEZONE', 'Asia/Jakarta'),
    'currency' => env('STORE_CURRENCY', 'IDR'),
    'seed_owner_password' => env('SEED_OWNER_PASSWORD'),

    'operations' => [
        'open_time' => env('STORE_OPEN_TIME', '09:00'),
        'close_time' => env('STORE_CLOSE_TIME', '22:00'),
        'work_days' => array_map(
            static fn (string $day): int => (int) $day,
            array_filter(explode(',', (string) env('STORE_WORK_DAYS', '1,2,3,4,5,6'))),
        ),
    ],

    'inventory' => [
        'allow_negative_stock' => (bool) env('STORE_ALLOW_NEGATIVE_STOCK', false),
        'quantity_scale' => 4,
    ],

    'documents' => [
        'allowed_mime_types' => ['image/jpeg', 'image/png', 'application/pdf'],
        'max_kilobytes' => (int) env('STORE_DOCUMENT_MAX_KILOBYTES', 8192),
        'max_files_per_transaction' => (int) env('STORE_DOCUMENT_MAX_FILES', 5),
        'max_image_pixels' => (int) env('STORE_DOCUMENT_MAX_IMAGE_PIXELS', 24000000),
    ],
];
