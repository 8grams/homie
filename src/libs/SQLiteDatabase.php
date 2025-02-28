<?php 

namespace App\Libs;

require __DIR__ . '/../dependencies/rb-sqlite.php';

use App\Interfaces\DataStoreInterface;
use R;
use RedBeanPHP\OODB;

class SQLiteDatabase implements DataStoreInterface
{
    public function __construct($connection)
    {
        R::setup($connection);
        R::useFeatureSet('novice/latest');
    }

    public function init(): OODB
    {
        return R::getRedBean();
    }

    public function getPDO(): \PDO
    {
        return R::getPDO();
    }
}