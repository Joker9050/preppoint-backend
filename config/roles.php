<?php

return [
    /*
    |--------------------------------------------------------------------------
    | User Roles Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration for user roles in the application.
    | Define role names and their associated permissions here.
    |
    */

    'roles' => [
        'ADMIN',
        'KADES',
        'SEKDES',
        'KAUR PERENCANAAN',
        'KAUR KEUANGAN',
        'KAUR TATA USAHA & UMUM',
    ],

    /*
    |--------------------------------------------------------------------------
    | Special Role Groups
    |--------------------------------------------------------------------------
    |
    | Define groups of roles for specific purposes.
    |
    */

    'groups' => [
        'KAUR' => [
            'ADMIN',
            'KADES',
            'SEKDES',
            'KAUR PERENCANAAN',
            'KAUR KEUANGAN',
            'KAUR TATA USAHA & UMUM',
        ],
    ],
];
