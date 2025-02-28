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
        $rb = $this->db->getPDO();

        // get migrations data from migrations table
        $migrations = $rb->query('SELECT * FROM migrations')->fetchAll();

        foreach ($this->getMigrations() as $filename => $content) {
            // only run the migration if it's not already in the migrations table
            if (in_array($filename, $migrations)) {
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
        $table = $rb->query('SELECT 1 FROM migrations LIMIT 1');
        if ($table) {
            return new Response("Database already initialized");
        }

        foreach ($this->getMigrations() as $filename => $content) {
            $filename = basename($filename);
            $rb->exec($content);
            $rb->exec('INSERT INTO migrations (Name, CreatedTime) VALUES ("'.$filename.'", "'.date('Y-m-d H:i:s').'")');
        }
        return new Response("Database initialization success");
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