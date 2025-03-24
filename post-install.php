<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/dependencies/rb-sqlite.php';

use Symfony\Component\Dotenv\Dotenv;

$envPath = __DIR__.'/.env';
if (!file_exists($envPath)) {
    exit;    
}

$dotenv = new Dotenv();
$dotenv->loadEnv($envPath, overrideExistingVars: true);
function generateKey($length = 64) {
    return substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()-_=+[]{}|;:,.<>?/`~', $length)), 0, $length);
}

function initDatabase()
{
    echo "Initiate Database\n";
    R::setup("sqlite:". __DIR__.'/data/' . $_ENV['SQLITE_DATABASE']);
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
    $migrations = glob(__DIR__ . '/src/migrations/*.sql');
    natsort($migrations);
    $migrationData = [];
    foreach ($migrations as $migration) {
        $migrationData[$migration] = file_get_contents($migration);
    }

    return $migrationData;
}

// initDatabase();

if ($_SERVER['RUN_ON_CLI'] == 'true') {
    $config = require __DIR__.'/src/dependencies/config.php';
    $container = include __DIR__ . '/src/dependencies/injector.php';
    
    $sitemapGenerator = $container->get('sitemap_generator');
    $sitemapGenerator->setUrl($config['app_url']);
    $sitemapGenerator->writeToFile(__DIR__ . '/public/sitemap.xml', $container->get('template'));
}

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
