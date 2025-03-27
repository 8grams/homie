<?php

namespace App\Libs\Interfaces;

use RedBeanPHP\OODB;

/**
 * Interface for database operations using RedBeanPHP
 * 
 * This interface defines methods for:
 * - Initializing the database connection
 * - Accessing the underlying PDO instance
 */
interface DataStoreInterface
{
    /**
     * Initialize the database connection
     * 
     * @return OODB RedBeanPHP database instance
     */
    public function init(): OODB;

    /**
     * Get the underlying PDO instance
     * 
     * @return \PDO PDO database connection
     */
    public function getPDO(): \PDO;
}