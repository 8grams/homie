<?php 

namespace App\Libs;

require_once __DIR__ . '/../dependencies/rb-sqlite.php';

use App\Libs\Interfaces\DataStoreInterface;
use R;
use RedBeanPHP\OODB;

/**
 * SQLite database implementation using RedBeanPHP
 * 
 * This class provides SQLite database functionality through RedBeanPHP,
 * implementing the DataStoreInterface for consistent database access.
 */
class SQLiteDatabase implements DataStoreInterface
{
    /**
     * Constructor
     * 
     * @param string $connection SQLite database connection string
     */
    public function __construct($connection)
    {
        R::setup($connection);
        R::useFeatureSet('novice/latest');
    }

    /**
     * Initialize and get the RedBeanPHP database instance
     * 
     * @return OODB RedBeanPHP database instance
     */
    public function init(): OODB
    {
        return R::getRedBean();
    }

    /**
     * Get the underlying PDO connection
     * 
     * @return \PDO PDO database connection
     */
    public function getPDO(): \PDO
    {
        return R::getPDO();
    }
}