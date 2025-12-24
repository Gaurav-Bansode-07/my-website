<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Filesystems extends BaseConfig
{
    public string $default = env('FILESYSTEM_DRIVER', 'public');

    public array $disks = [
        'public' => [
            'driver' => 'local',
            'root'   => FCPATH . 'uploads',
        ],
      's3' => [
    'driver'   => 's3',
    'key'      => env('AWS_ACCESS_KEY_ID'),
    'secret'   => env('AWS_SECRET_ACCESS_KEY'),
    'region'   => env('AWS_DEFAULT_REGION'),
    'bucket'   => env('AWS_BUCKET'),
    'endpoint' => env('AWS_ENDPOINT'),
    'url'      => env('AWS_URL'),
    'acl'      => 'public-read',
    'make_public' => true, // Add this line
],
    ];
}