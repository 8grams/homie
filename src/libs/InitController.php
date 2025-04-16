<?php 

namespace App\Libs;

use App\Libs\Interfaces\BaseController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controller for database initialization and migration
 * 
 * This class handles database setup and schema updates through SQL migrations,
 * extending BaseController for common functionality.
 */
class InitController extends BaseController
{
    /**
     * Initialize the database with initial schema
     * 
     * @return Response Success or error message
     */
    public function init()
    {
        session_start();
        if (!$this->authenticator->isAuthenticated()) {
            return new RedirectResponse('login');
        }
        return $this->initDatabase();
    }

    /**
     * Refresh the database by dropping all tables and reinitializing
     * 
     * @return Response Success or error message
     */
    public function refresh()
    {
        session_start();
        if (!$this->authenticator->isAuthenticated()) {
            return new RedirectResponse('login');
        }

        // unlink the database file
        $dbPath = $this->config['database']['path'];
        copy($dbPath, $dbPath . '.' . time() . '.bak');

        if (file_exists($dbPath)) {
            unlink($dbPath);
        }
        
        return $this->initDatabase();
    }

    /**
     * Run database migrations
     * 
     * @return Response Success or error message
     */
    public function migrate()
    {
        session_start();
        if (!$this->authenticator->isAuthenticated()) {
            return new RedirectResponse('login');
        }
        return $this->migrateDatabase();
    }

    /**
     * Execute pending database migrations
     * 
     * This method:
     * 1. Gets list of already executed migrations
     * 2. Finds new migration files
     * 3. Executes new migrations in order
     * 4. Records executed migrations
     * 
     * @return Response Success message
     */
    public function migrateDatabase()
    {
        $rb = $this->db->getPDO();

        // get migrations data from migrations table
        $migrations = $rb->query('SELECT name FROM migrations')->fetchAll();
        $migrations = array_map(function($migration) {
            return $migration['name'];
        }, $migrations);

        foreach ($this->getMigrations() as $filename => $content) {
            // only run the migration if it's not already in the migrations table
            if (in_array(basename($filename), $migrations)) {
                continue;
            }

            $filename = basename($filename);
            $rb->exec($content);
            $rb->exec('INSERT INTO migrations (Name) VALUES ("'.$filename.'")');
        }

        return new Response("Migration success");
    }

    /**
     * Initialize the database with initial schema
     * 
     * This method:
     * 1. Checks if migrations table exists
     * 2. If not, creates migrations table
     * 3. Executes all initial migrations
     * 4. Records executed migrations
     * 
     * @return Response Success or error message
     */
    private function initDatabase()
    {
        $rb = $this->db->getPDO();

        // check if migrations table exists
        $table = $rb->query('SELECT name FROM sqlite_master WHERE type="table" AND name="migrations";');
        $result = $table->fetch();
        if ($result) {
            if (count($result) > 0) {
                return new Response("Database already initialized");
            }
        }
        
        foreach ($this->getMigrations() as $filename => $content) {
            $filename = basename($filename);
            $rb->exec($content);
            $rb->exec('INSERT INTO migrations (name, created_time) VALUES ("'.$filename.'", "'.date('Y-m-d H:i:s').'")');
        }

        return new Response("Initialization success");
    }

    /**
     * Get all SQL migration files from the migrations folder
     * 
     * This method:
     * 1. Finds all .sql files in the migrations folder
     * 2. Sorts them naturally by filename
     * 3. Loads their contents
     * 
     * @return array Array of migration files and their contents
     */
    private function getMigrations() 
    {
        $migrations = glob(__DIR__ . '/../migrations/*.sql');
        natsort($migrations);
        $migrationData = [];
        foreach ($migrations as $migration) {
            $migrationData[$migration] = file_get_contents($migration);
        }

        return $migrationData;
    }
}