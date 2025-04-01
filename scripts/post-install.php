<?php

$rootDir = __DIR__ . '/../';

require_once $rootDir . 'vendor/autoload.php';
require_once $rootDir . 'src/dependencies/rb-sqlite.php';

use Symfony\Component\Dotenv\Dotenv;

$envPath = $rootDir . '.env';
if (!file_exists($envPath)) {
    exit;    
}

if (!file_exists($rootDir . '.dockerenv')) {
    $dotenv = new Dotenv();
    $dotenv->loadEnv($envPath, overrideExistingVars: true);
}

function generateKey($length = 64) {
    return substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()-_=+[]{}|;:,.<>?/`~', $length)), 0, $length);
}

function initDatabase()
{
    echo "Initiate Database\n";
    R::setup("sqlite:". $rootDir . 'data/' . $_ENV['SQLITE_DATABASE']);
    R::useFeatureSet('novice/latest');

    $rb = R::getPDO();

    // check if migrations table exists
    $table = $rb->query('SELECT name FROM sqlite_master WHERE type="table" AND name="migrations";');
    $result = $table->fetch();

    if ($result) {
        if (count($result) > 0) {
            migrateDatabase($rb);
            return;
        }
    }
    
    foreach (getMigrations() as $filename => $content) {
        $filename = basename($filename);
        $rb->exec($content);
        $rb->exec('INSERT INTO migrations (name, created_time) VALUES ("'.$filename.'", "'.date('Y-m-d H:i:s').'")');
    }

    echo "Initialization success";
}

function migrateDatabase($rb)
{
    // get migrations data from migrations table
    $migrations = $rb->query('SELECT name FROM migrations')->fetchAll();
    $migrations = array_map(function($migration) {
        return $migration['name'];
    }, $migrations);

    foreach (getMigrations() as $filename => $content) {
        // only run the migration if it's not already in the migrations table
        if (in_array(basename($filename), $migrations)) {
            continue;
        }

        $filename = basename($filename);
        $rb->exec($content);
        $rb->exec('INSERT INTO migrations (Name) VALUES ("'.$filename.'")');
    }

    echo "Migration success";
}

function getMigrations() 
{
    $migrations = glob($rootDir . 'src/migrations/*.sql');
    natsort($migrations);
    $migrationData = [];
    foreach ($migrations as $migration) {
        $migrationData[$migration] = file_get_contents($migration);
    }

    return $migrationData;
}

initDatabase();

if ($_ENV['ENABLE_BLOG'] == 'true') {
    echo "Initiate Wordpress\n";

    shell_exec('[ ! -d wp ] && composer create-project 8grams/bedrock wp --no-interaction --remove-vcs || echo "wp already exists, skipping..."');

    // Define the content for the .env file
    $envVars = [
        'WP_ENV' => 'production',
        'WP_HOME' => $_ENV['BLOG_DOMAIN'],
        'WP_SITEURL' => $_ENV['BLOG_SITE_URL'],
        'AUTH_KEY' => generateKey(),
        'SECURE_AUTH_KEY' => generateKey(),
        'LOGGED_IN_KEY' => generateKey(),
        'NONCE_KEY' => generateKey(),
        'AUTH_SALT' => generateKey(),
        'SECURE_AUTH_SALT' => generateKey(),
        'LOGGED_IN_SALT' => generateKey(),
        'NONCE_SALT' => generateKey(),
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
    $filePath = $rootDir . 'wp/.env';

    // Write content to the file
    if (file_put_contents($filePath, $envContent) !== false) {
        echo ".env file created successfully at: $filePath\n";
    } else {
        echo "Failed to create .env file.\n";
    }

    echo "Successfully initiate wordpress!\n";

    echo "Initiate SQLite Database!\n";
    copy($rootDir . 'wp/web/app/plugins/sqlite-database-integration/db.copy', $rootDir . 'wp/web/app/db.php');
    echo "Successfully initiate SQLite Database!\n";
}
