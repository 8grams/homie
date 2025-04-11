<?php

/**
 * Upgrade script for Homie framework
 * 
 * This script:
 * 1. Creates a temporary directory
 * 2. Downloads the latest version via composer
 * 3. Preserves src/pages directory
 * 4. Merges src/migrations directory
 * 5. Replaces other files with new versions
 */

// Package configuration
$rootDir = __DIR__ . '/../';

$packageName = '8grams/homie';
$tempDirName = 'temp_upgrade';
$backupDirPrefix = 'backup_';

// Directory configuration
$preserveDirs = [
    'src/pages'
];

$mergeDirs = [
    'src/migrations'
];

$excludeDirs = [
    'vendor',
    '.github'
];

// File configuration
$excludeFiles = [
    '.DS_Store',
    '.git',
    '.gitignore'
];

// Directory paths
$tempDir = $rootDir . $tempDirName;
$backupDir = $rootDir . $backupDirPrefix . date('Y-m-d_H-i-s');

// Get latest version from Packagist
echo "Fetching latest version from Packagist...\n";
$latestVersion = getLatestVersion($packageName);
if (!$latestVersion) {
    die("Failed to fetch latest version from Packagist\n");
}

echo "Latest version found: {$latestVersion}\n";

// Create backup
echo "Creating backup...\n";
if (!file_exists($backupDir)) {
    mkdir($backupDir, 0755, true);
}
copyDirectory($rootDir, $backupDir, array_merge($preserveDirs, $mergeDirs, $excludeDirs));

// Create temporary directory
echo "Creating temporary directory...\n";
if (file_exists($tempDir)) {
    removeDirectory($tempDir);
}
mkdir($tempDir, 0755, true);

// Run composer create-project with latest version
echo "Downloading version {$latestVersion}...\n";
$composerCmd = "composer create-project 8grams/homie:{$latestVersion} {$tempDir} --prefer-dist --no-dev";
exec($composerCmd, $output, $returnVar);

if ($returnVar !== 0) {
    die("Failed to download version {$latestVersion}\n");
}

// Preserve src/pages directory
echo "Preserving src/pages directory...\n";
if (file_exists($rootDir . 'src/pages')) {
    copyDirectory($rootDir . 'src/pages', $tempDir . '/src/pages');
}

// Merge migrations directory
echo "Merging migrations directory...\n";
if (file_exists($rootDir . 'src/migrations')) {
    mergeMigrations($rootDir . 'src/migrations', $tempDir . '/src/migrations');
}

// Replace files
echo "Replacing files...\n";

// Remove src/pages from temp directory to prevent copying new files
$tempPagesDir = $tempDir . '/src/pages';
if (file_exists($tempPagesDir)) {
    removeDirectory($tempPagesDir);
}

copyDirectory($tempDir, $rootDir, $excludeDirs);

// Remove .github folder if it exists
$githubDir = $rootDir . '.github';
if (file_exists($githubDir)) {
    echo "Removing .github folder...\n";
    removeDirectory($githubDir);
}

// Clean up
echo "Cleaning up...\n";
removeDirectory($tempDir);

echo "Upgrade completed successfully!\n";
echo "Backup created in: {$backupDir}\n";

/**
 * Copy directory recursively
 */
function copyDirectory($source, $destination, $excludeDirs = [])
{
    global $excludeFiles;
    $rootDir = __DIR__ . '/../';
    
    if (!is_dir($destination)) {
        mkdir($destination, 0755, true);
    }

    $dir = opendir($source);
    while (($file = readdir($dir)) !== false) {
        if ($file != '.' && $file != '..' && !in_array($file, $excludeFiles)) {
            $srcFile = $source . '/' . $file;
            $destFile = $destination . '/' . $file;

            // Skip excluded directories and backup directories
            $relativePath = str_replace($rootDir, '', $srcFile);
            if (in_array($relativePath, $excludeDirs) || strpos($relativePath, 'backup_') === 0) {
                continue;
            }

            if (is_dir($srcFile)) {
                copyDirectory($srcFile, $destFile, $excludeDirs);
            } else {
                // Ensure destination directory exists
                $destDir = dirname($destFile);
                if (!file_exists($destDir)) {
                    mkdir($destDir, 0755, true);
                }
                
                // Try to copy with error handling
                if (!@copy($srcFile, $destFile)) {
                    echo "Warning: Failed to copy {$srcFile} to {$destFile}\n";
                    // Try to fix permissions and retry
                    chmod($destFile, 0644);
                    if (!@copy($srcFile, $destFile)) {
                        echo "Error: Still failed to copy {$srcFile}\n";
                    }
                }
            }
        }
    }
    closedir($dir);
}

/**
 * Remove directory recursively
 */
function removeDirectory($dir)
{
    if (!file_exists($dir)) {
        return true;
    }

    if (!is_dir($dir)) {
        return unlink($dir);
    }

    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }

        if (!removeDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
            return false;
        }
    }

    return rmdir($dir);
}

/**
 * Merge migrations directories
 * 
 * This function:
 * 1. Copies existing migrations to temp directory
 * 2. Copies new migrations from temp to current
 * 3. Ensures no duplicate migrations
 */
function mergeMigrations($sourceDir, $tempDir)
{
    // Create temp migrations directory if it doesn't exist
    if (!file_exists($tempDir)) {
        mkdir($tempDir, 0755, true);
    }

    // Get existing migrations
    $existingMigrations = glob($sourceDir . '/*.sql');
    $existingNames = array_map('basename', $existingMigrations);

    // Get new migrations
    $newMigrations = glob($tempDir . '/*.sql');
    $newNames = array_map('basename', $newMigrations);

    // Copy existing migrations to temp
    foreach ($existingMigrations as $migration) {
        $filename = basename($migration);
        copy($migration, $tempDir . '/' . $filename);
    }

    // Copy new migrations to current
    foreach ($newMigrations as $migration) {
        $filename = basename($migration);
        if (!in_array($filename, $existingNames)) {
            copy($migration, $sourceDir . '/' . $filename);
        }
    }
}

/**
 * Get latest version from Packagist
 */
function getLatestVersion($package)
{
    $url = "https://packagist.org/packages/{$package}.json";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    curl_close($ch);

    if (!$response) {
        return false;
    }

    $data = json_decode($response, true);
    if (!$data || !isset($data['package']['versions'])) {
        return false;
    }

    // Get all versions and filter only those starting with 'v'
    $versions = array_filter(array_keys($data['package']['versions']), function($version) {
        return strpos($version, 'v') === 0;
    });

    if (empty($versions)) {
        return false;
    }

    // Sort versions
    usort($versions, 'version_compare');
    
    // Return the latest version (last in sorted array)
    return end($versions);
} 