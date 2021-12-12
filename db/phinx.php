<?php

declare(strict_types=1);

use Nette\Neon\Neon;
use Phinx\Migration\AbstractMigration;

require __DIR__ . '/../vendor/autoload.php';

$configContent = file_get_contents(__DIR__ . '/../config/config.neon');

if (!$configContent) {
    throw new Exception("There is not configuration.");
}

$config = Neon::decode($configContent);

const DSN_REGEX = '/(?P<driver>\w+)?:(.*)=(?P<host>\S+)?;(.*)=(?P<dbname>\w+)/';

$credentials = $config['parameters']['db'];
$dsn = [];
preg_match(DSN_REGEX, $credentials['dsn'], $dsn);

return [
    'paths' => [
        'migrations' => __DIR__ . '/migrations',
        'seeds' => __DIR__ . '/seeds'
    ],
    'templates' => [
        'file' => __DIR__ . '/templates/updown.php.dist'
    ],
    'migration_base_class' => AbstractMigration::class,
    'environments' => [
        'default_migration_table' => 'phinx_log',
        'default_database' => 'development',
        'test' => [
            'adapter' => $dsn['driver'] ?? 'pgsql',
            'host' => $dsn['host'] ?? 'localhost',
            'name' => $dsn['dbname'] ?? 'db',
            'user' => $credentials['user'] ?? 'db',
            'pass' => $credentials['password'] ?? 'db',
            'port' => '5432',
            'charset' => 'utf8'
        ]
    ],
    'version_order' => 'creation'
];
