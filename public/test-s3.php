<?php
require_once '../vendor/autoload.php';
use Aws\S3\S3Client;
use Aws\Exception\AwsException;

// 1. Setup - Pulling directly from your current ENV settings
$config = [
    'key'      => 'DO00LZ6Y84H63UYP2T2X', 
    'secret'   => 'RWK6UZMxf+4WaBqd6/DH+y3j8nGRICILetA9EVyFbsE',
    'region'   => 'atl1',
    'bucket'   => 'principacore-bucket-for-storing-all-the-files-uploaded',
    'endpoint' => 'https://atl1.digitaloceanspaces.com',
    'url_base' => 'https://principacore-bucket-for-storing-all-the-files-uploaded.atl1.digitaloceanspaces.com'
];

echo "<h1>Master S3 Debugger</h1>";

$s3 = new S3Client([
    'version'     => 'latest',
    'region'      => $config['region'],
    'endpoint'    => $config['endpoint'],
    'credentials' => [
        'key'    => trim($config['key']),
        'secret' => trim($config['secret']),
    ],
]);

// TEST 1: Connection
try {
    $s3->listBuckets();
    echo "✅ <b>Step 1: Connection</b> - SUCCESS<br>";
} catch (Exception $e) {
    die("❌ <b>Step 1: Connection</b> - FAILED: " . $e->getMessage());
}

// TEST 2: Upload a dummy file
$test_file_name = 'debug-test-' . time() . '.txt';
try {
    $s3->putObject([
        'Bucket' => $config['bucket'],
        'Key'    => 'blog/' . $test_file_name,
        'Body'   => 'This is a test file for debugging.',
        'ACL'    => 'public-read', // This is what your code uses
    ]);
    echo "✅ <b>Step 2: Upload</b> - SUCCESS (File: blog/$test_file_name)<br>";
} catch (AwsException $e) {
    die("❌ <b>Step 2: Upload</b> - FAILED: " . $e->getAwsErrorMessage());
}

// TEST 3: Public Accessibility
$full_url = $config['url_base'] . '/blog/' . $test_file_name;
echo "✅ <b>Step 3: URL Generation</b> - URL: <a href='$full_url' target='_blank'>$full_url</a><br>";

echo "<br><b>Action Required:</b> Click the link above. <br>";
echo "1. If it downloads/shows text: Your settings are perfect.<br>";
echo "2. If it says <b>'NoSuchKey'</b>: Your bucket name or folder 'blog/' in the URL is wrong.<br>";
echo "3. If it says <b>'AccessDenied'</b>: Your ACL 'public-read' is being blocked by DigitalOcean settings.";