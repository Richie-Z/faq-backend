<?php

return [
    'defaults' => [
        'guard' => 'api',
    ],
    'guards' => [
        'api' => ['driver' => 'jwt', 'provider' => 'users'],
        'admin' => ['driver' => 'jwt', 'provider' => 'admin'],
        'users' => ['driver' => 'jwt', 'provider' => 'users']
    ],

    'providers' => [
        'admin' => ['driver' => 'eloquent', 'model' => \App\Models\Admin::class],
        'users' => ['driver' => 'eloquent', 'model' => \App\Models\User::class],
    ],
];
