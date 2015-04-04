<?php

$appConfig = require 'config.php';

return [
    'propel' => [
        'database' => [
            'connections' => [
                'default' => [
                    'adapter'    => 'mysql',
                    'classname'  => 'Propel\Runtime\Connection\ConnectionWrapper',
                    'dsn'        => $appConfig['db']['connectionString'],
                    'user'       => $appConfig['db']['user'],
                    'password'   => $appConfig['db']['pass'],
                    'attributes' => [],
                    'settings' => [
                        'charset'    => 'utf8',
                        'queries' => [
                            'utf8' => 'SET NAMES utf8 COLLATE utf8_unicode_ci, COLLATION_CONNECTION = utf8_unicode_ci, COLLATION_DATABASE = utf8_unicode_ci, COLLATION_SERVER = utf8_unicode_ci',
                      ]
                    ]
                ]
            ]
        ],
        'runtime' => [
            'defaultConnection' => 'default',
            'connections' => ['default']
        ],
        'generator' => [
            'defaultConnection' => 'default',
            'connections' => ['default'],
            'namespaceAutoPackage' => false
        ],
        'paths' => [
            'phpDir' => './Model'
        ],
    ]
];
