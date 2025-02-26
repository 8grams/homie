<?php 

namespace App\Libs;

require __DIR__ . '/../dependencies/rb-sqlite.php';

use App\Interfaces\DataStoreInterface;
use R;

class SQLiteDatabase implements DataStoreInterface
{
    public function __construct()
    {
        R::setup();
        R::useFeatureSet('novice/latest');
    }

    public function init()
    {
        return R::getRedBean();
    }

    public function getOne($id)
    {
        return null;
    }

    public function getAll($options = [])
    {
        return [];
    }

    public function save(): bool
    {
        return true;
    }

    public function delete(): bool
    {
        return true;
    }
}