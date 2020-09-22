<?php
return array(
    'common'         => array(
        'host'     => env('COMMON_REDIS_HOST','127.0.0.1'),
        'port'     => env('COMMON_REDIS_PORT', 6379),
        'database' => env('COMMON_REDIS_DATABASE', 0),
        'password' => env('COMMON_REDIS_PASSWORD', ''),
        'prefix'   => 'apollo:common:',
        'desc'     => '无法归类的业务模块'
    ),
);
