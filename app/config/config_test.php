<?php
/**
 * Created by PhpStorm.
 * User: kasalirazaq
 * Date: 8/17/18
 * Time: 5:23 AM
 */

return [
    'settings' => [
        'displayErrorDetails' => true,

        'logger' => [
            'name' => 'slim-app',
            'level' => Monolog\Logger::DEBUG,
            'path' => __DIR__ . '/../logs/app.log',
        ],
        'db' => [
            'driver' => 'mysql',
            'host' => getenv('DB_HOST'),
            'database' => 'dump',
            'username' => getenv('DB_USERNAME'),
            'password' => getenv('DB_PASSWORD'),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
        ]
    ],
];