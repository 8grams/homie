<?php
require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->loadEnv(__DIR__.'/.env', overrideExistingVars: true);

function generate_key($length = 64) {
    return substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()-_=+[]{}|;:,.<>?/`~', $length)), 0, $length);
}

if ($_ENV['ENABLE_BLOG']) {
    echo "Initiate Wordpress\n";

    shell_exec('[ ! -d wp ] && composer create-project 8grams/bedrock wp --no-interaction --remove-vcs || echo "wp already exists, skipping..."');

    // Define the content for the .env file
    $envVars = [
        'WP_ENV' => 'production',
        'WP_HOME' => $_ENV['BLOG_DOMAIN'],
        'WP_SITEURL' => $_ENV['BLOG_SITE_URL'],
        'AUTH_KEY' => generate_key(),
        'SECURE_AUTH_KEY' => generate_key(),
        'LOGGED_IN_KEY' => generate_key(),
        'NONCE_KEY' => generate_key(),
        'AUTH_SALT' => generate_key(),
        'SECURE_AUTH_SALT' => generate_key(),
        'LOGGED_IN_SALT' => generate_key(),
        'NONCE_SALT' => generate_key(),
    ];

    // Generate .env file content
    $envContent = <<<EOL
    # env
    WP_ENV='{$envVars['WP_ENV']}'
    WP_HOME='{$envVars['WP_HOME']}'
    WP_SITEURL="{$envVars['WP_SITEURL']}"
    AUTH_KEY='{$envVars['AUTH_KEY']}'
    SECURE_AUTH_KEY='{$envVars['SECURE_AUTH_KEY']}'
    LOGGED_IN_KEY='{$envVars['LOGGED_IN_KEY']}'
    NONCE_KEY='{$envVars['NONCE_KEY']}'
    AUTH_SALT='{$envVars['AUTH_SALT']}'
    SECURE_AUTH_SALT='{$envVars['SECURE_AUTH_SALT']}'
    LOGGED_IN_SALT='{$envVars['LOGGED_IN_SALT']}'
    NONCE_SALT='{$envVars['NONCE_SALT']}'
    EOL;

    // Define the file path
    $filePath = __DIR__ . '/wp/.env';

    // Write content to the file
    if (file_put_contents($filePath, $envContent) !== false) {
        echo ".env file created successfully at: $filePath\n";
    } else {
        echo "Failed to create .env file.\n";
    }

    echo "Successfully initiate wordpress!\n";

    echo "Initiate SQLite Database!\n";
    copy(__DIR__ . '/wp/web/app/plugins/sqlite-database-integration/db.copy', __DIR__ . '/wp/web/app/db.php');
    echo "Successfully initiate SQLite Database!\n";
}
