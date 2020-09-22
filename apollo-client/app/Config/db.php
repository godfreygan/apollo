<?php
return [
    'db_order_1' => [
        'driver'    => 'mysql',
        'read'      => [    // ps：从库更新有延迟，使用主库查询及操作
            'host'     => env('ORDER_1_DB_WRITE_HOST'),
            'database' => env('ORDER_1_DB_WRITE_DATABASE'),
            'username' => env('ORDER_1_DB_WRITE_USERNAME'),
            'password' => env('ORDER_1_DB_WRITE_PASSWORD'),
            'port'     => env('ORDER_1_DB_WRITE_PORT'),
        ],
        'write'     => [
            'host'     => env('ORDER_1_DB_WRITE_HOST'),
            'database' => env('ORDER_1_DB_WRITE_DATABASE'),
            'username' => env('ORDER_1_DB_WRITE_USERNAME'),
            'password' => env('ORDER_1_DB_WRITE_PASSWORD'),
            'port'     => env('ORDER_1_DB_WRITE_PORT'),
        ],
        'sticky'    => true,//必须为true
        'charset'   => 'utf8mb4',
        'collation' => 'utf8mb4_general_ci',
        'prefix'    => 't_'
    ],

];
