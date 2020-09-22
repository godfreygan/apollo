<?php
return [
    'test'                        => [//业务名,区分host，用途：用于测试kafka队列，自产自销
        'connection' => [
            'kafka'        => [
                'metadata.broker.list'  => env('KAFKA_BROKER_LIST', 'localhost:9092'),
                'socket.timeout.ms'     => 6000,
                'enable.auto.commit'    => 'false',
                'request.required.acks' => 1,
                'session.timeout.ms'    => 10000,
            ],
            'topic'        => [
                'auto.offset.reset'  => 'earliest', //读取最早的
                'message.timeout.ms' => 3000,
            ],
            'poll_timeout' => 30,
        ],
        'producers'  => [
            'p1' => [
                'queue'     => 'test', //队列名，topic名
                'partition' => null,
                'key'       => null,
            ],
        ],
        'consumers'  => [
            'c1' => [
                'group_id' => 'test',
                'queues'   => [
                    [
                        'queue'     => 'test',//队列名，topic名
                        'partition' => null,
                        'offset'    => null,
                    ],
                ],
            ],
        ],

    ],
];

