<?php 

namespace App\Libs;

use App\Interfaces\BaseController;

class InitController extends BaseController
{
    public function migrate()
    {
        $this->migrateDatabase();
    }

    private function migrateDatabase()
    {
        $rb = $this->db->getPDO();

        // get all migrations files from migrations folder
        // sorted by its name, and load its content
        $migrations = glob(__DIR__ . '/../migrations/*.php');
        natsort($migrations);
        $migrations = [];
        foreach ($migrations as $migration) {
            $migrations[$migration] = file_get_contents($migration);
        }

        foreach ($migrations as $filename => $content) {
            $filename = basename($filename);
            $rb->exec($content);

            $rb->exec('INSERT INTO migrations (Name) VALUES ("'.$filename.'")');
        }
    }
}