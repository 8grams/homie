<?php

namespace App\Interfaces;

use RedBeanPHP\OODB;

interface DataStoreInterface
{
    public function init(): OODB;
}