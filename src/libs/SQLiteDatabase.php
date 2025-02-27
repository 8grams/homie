<?php 

namespace App\Libs;

require __DIR__ . '/../dependencies/rb-sqlite.php';

use App\Interfaces\DataStoreInterface;
use R;
use RedBeanPHP\OODB;

class SQLiteDatabase implements DataStoreInterface
{
    public function __construct()
    {
        R::setup();
        R::useFeatureSet('novice/latest');
    }

    public function init(): OODB
    {
        return R::getRedBean();
    }
}