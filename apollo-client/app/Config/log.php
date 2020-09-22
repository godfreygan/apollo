<?php
return [
    'prefix' => env('LOG_DIR', App::storagePath('logs')) . '/apollo-client/apollo-client.log',
    'level'  =>  env('LOG_LEVEL', 'debug'),
    'name'   => env('APP_ENV', 'production'),
    'channel'=>'apollo-client',
];
