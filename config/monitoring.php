<?php
return [
    'websocket' => [
        'host' => 'localhost',
        'port' => 8080,
        'ssl' => false
    ],
    'metrics' => [
        'collection_interval' => 5, // 秒
        'retention_period' => 7,    // 天
        'alert_thresholds' => [
            'memory_usage' => 85,   // 百分比
            'cpu_load' => 80,       // 百分比
            'response_time' => 2000 // 毫秒
        ]
    ]
];