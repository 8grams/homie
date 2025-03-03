<?php 

namespace App\Libs;

use App\Libs\Interfaces\BaseController;
use Symfony\Component\HttpFoundation\Response;

class InitController extends BaseController
{
    public function init()
    {
        return $this->initDatabase();
    }

    public function migrate()
    {
        return $this->migrateDatabase();
    }

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

    // get all migrations files from migrations folder
    // sorted by its name, and load its content
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