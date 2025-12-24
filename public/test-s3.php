<?php
require_once '../vendor/autoload.php';
use Aws\S3\S3Client;

// We are defining them exactly as they appear in your records
$key = 'DO00DANKEJDP2KBBQEN4';
$secret = 'u13UEtS9/TFMZ7aB7R87N/SnnPL998yZIgaWNQDS9yQ';

$client = new S3Client([
    'version'     => 'latest',
    'region'      => 'atl1',
    'endpoint'    => 'https://atl1.digitaloceanspaces.com',
    'use_path_style_endpoint' => false, // DigitalOcean prefers false
    'credentials' => [
        'key'    => trim($key),    // trim removes accidental spaces
        'secret' => trim($secret), // trim removes accidental spaces
    ],
]);

try {
    $result = $client->listBuckets();
    echo "<h2>SUCCESS! Connection Established.</h2>";
    echo "Buckets found: <br>";
    foreach ($result['Buckets'] as $bucket) {
        echo " - " . $bucket['Name'] . "<br>";
    }
} catch (Exception $e) {
    echo "<h2>Still Failing</h2>";
    echo "<b>Technical Reason:</b> " . $e->getAwsErrorCode() . "<br>";
    echo "<b>Message:</b> " . $e->getMessage();
}