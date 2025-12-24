<?php
// Simple S3 Connection Test
require_once '../vendor/autoload.php';
use Aws\S3\S3Client;

$client = new S3Client([
    'version' => 'latest',
    'region'  => 'atl1',
    'endpoint' => 'https://atl1.digitaloceanspaces.com',
    'credentials' => [
        'key'    => 'AWS_ACCESS_KEY_ID', 
        'secret' => 'AWS_SECRET_ACCESS_KEY',
    ],
]);

try {
    $result = $client->listBuckets();
    echo "Connection Successful! Found buckets: <br>";
    foreach ($result['Buckets'] as $bucket) {
        echo $bucket['Name'] . "<br>";
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}