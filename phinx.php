<?php

use Nyholm\DSN;

$dsnData = new DSN(getenv('DATABASE_URL'));
return [
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/migrations',
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_database' => 'development',
        'production' => [
            'adapter' => 'pgsql',
            'host' => $dsnData->getFirstHost(),
            'name' => $dsnData->getDatabase(),
            'user' => $dsnData->getUsername(),
            'pass' => $dsnData->getPassword(),
            'port' => $dsnData->getFirstPort(),
            'charset' => 'utf8',
        ]
    ],
    'version_order' => 'creation'
];