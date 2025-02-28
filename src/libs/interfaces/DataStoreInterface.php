<?php

namespace App\Libs\Interfaces;

use RedBeanPHP\OODB;

interface DataStoreInterface
{
    public function init(): OODB;
    public function getPDO(): \PDO;
}