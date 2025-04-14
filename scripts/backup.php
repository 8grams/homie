<?php

use Symfony\Component\Dotenv\Dotenv;
use Aws\S3\S3Client as AwsClient;

// Define paths
$rootDir = realpath(__DIR__ . '/../') . '/';
$dataDir = $rootDir . 'data';
$backupDir = $rootDir . 'backup_manual';
$timestamp = date('Ymd_His');
$backupName = "data_backup_{$timestamp}.zip";
$backupPath = $backupDir . '/' . $backupName;

require_once $rootDir . 'vendor/autoload.php';

if (!file_exists($rootDir . '.dockerenv')) {
    $dotenv = new Dotenv();
    $dotenv->loadEnv($rootDir . '.env', overrideExistingVars: true);
}

// Check AWS credentials
if (!$_ENV['AWS_ACCESS_KEY_ID'] || !$_ENV['AWS_SECRET_ACCESS_KEY'] || !$_ENV['AWS_BUCKET_NAME']) {
    die("Error: AWS credentials not set. Please set AWS_ACCESS_KEY_ID, AWS_SECRET_ACCESS_KEY, and AWS_BUCKET_NAME environment variables.\n");
}

// Check if data directory exists
if (!is_dir($dataDir)) {
    die("Error: Data directory {$dataDir} does not exist.\n");
}

// Create backup directory if it doesn't exist
if (!is_dir($backupDir)) {
    mkdir($backupDir, 0755, true);
}

// Create zip backup
echo "Creating backup of {$dataDir}...\n";
$zip = new ZipArchive();

if ($zip->open($backupPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
    die("Error: Failed to create zip backup.\n");
}

// Normalize $dataDir to ensure trailing slash
$dataDir = rtrim($dataDir, '/') . '/';

$files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($dataDir, FilesystemIterator::SKIP_DOTS),
    RecursiveIteratorIterator::SELF_FIRST
);

foreach ($files as $file) {
    $filePath = $file->getPathname();
    $relativePath = substr($filePath, strlen($dataDir)); // relative to data/

    if ($file->isDir()) {
        $zip->addEmptyDir('data/' . $relativePath);
    } else {
        $zip->addFile($filePath, 'data/' . $relativePath);
    }
}

$zip->close();

// Upload to S3 using AWS SDK
echo "Uploading backup to S3 bucket " . $_ENV['AWS_BUCKET_NAME'] . "...\n";

try {
    $s3 = new AwsClient([
        'version' => 'latest',
        'region'  => $_ENV['AWS_REGION'] ?: 'ap-southeast-3',
        'credentials' => [
            'key'    => $_ENV['AWS_ACCESS_KEY_ID'],
            'secret' => $_ENV['AWS_SECRET_ACCESS_KEY'],
        ],
        'endpoint' => $_ENV['AWS_ENDPOINT'] ?: null,
        'use_path_style_endpoint' => $_ENV['AWS_USE_PATH_STYLE_ENDPOINT'] == 'true',
    ]);

    $result = $s3->putObject([
        'Bucket' => $_ENV['AWS_BUCKET_NAME'],
        'Key'    => $backupName,
        'Body'   => fopen($backupPath, 'r'),
        'ContentType' => 'application/zip',
    ]);

    echo "Upload successful!\n";
} catch (Exception $e) {
    die("Error: Failed to upload backup to S3: " . $e->getMessage() . "\n");
}

// Clean up local backup file
echo "Cleaning up local backup file...\n";
unlink($backupPath);

echo "Backup completed successfully!\n";
echo "Backup file: {$backupName}\n";
