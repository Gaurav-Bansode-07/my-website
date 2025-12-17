<?php

namespace Config;

use CodeIgniter\Database\Config;

class Database extends Config
{
    /**
     * Migration & seed files path
     */
    public string $filesPath = APPPATH . 'Database' . DIRECTORY_SEPARATOR;

    /**
     * Default connection group
     */
    public string $defaultGroup = 'default';

    /**
     * MAIN DATABASE CONFIG
     * (DigitalOcean Managed MySQL + SSL)
     */
    public array $default = [
        'DSN'      => '',
        'hostname' => '',   // filled in constructor
        'username' => '',
        'password' => '',
        'database' => '',
        'DBDriver' => 'MySQLi',
        'DBPrefix' => '',
        'pConnect' => false,
        'DBDebug'  => false, // ⚠️ production
        'charset'  => 'utf8mb4',
        'DBCollat' => 'utf8mb4_general_ci',
        'swapPre'  => '',
        'encrypt'  => true,
        'compress' => false,
        'strictOn' => false,
        'failover' => [],
        'port'     => 25060,
        'options' => [
    MYSQLI_OPT_SSL_VERIFY_SERVER_CERT => false,
    MYSQLI_OPT_SSL_CA => '/etc/ssl/certs/ca-certificates.crt',
],

    ];

    /**
     * PHPUnit test DB (do not touch)
     */
    public array $tests = [
        'DSN'         => '',
        'hostname'    => '127.0.0.1',
        'username'    => '',
        'password'    => '',
        'database'    => ':memory:',
        'DBDriver'    => 'SQLite3',
        'DBPrefix'    => 'db_',
        'pConnect'    => false,
        'DBDebug'     => true,
        'charset'     => 'utf8',
        'DBCollat'    => '',
        'swapPre'     => '',
        'encrypt'     => false,
        'compress'    => false,
        'strictOn'    => false,
        'failover'    => [],
        'port'        => 3306,
    ];

    public function __construct()
    {
        parent::__construct();

        // Load env vars safely
        $this->default['hostname'] = env('DB_HOST');
        $this->default['username'] = env('DB_USERNAME');
        $this->default['password'] = env('DB_PASSWORD');
        $this->default['database'] = env('DB_DATABASE');
        $this->default['port']     = (int) env('DB_PORT');

        // Protect live DB during tests
        if (ENVIRONMENT === 'testing') {
            $this->defaultGroup = 'tests';
        }
    }
}
