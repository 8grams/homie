<?php

namespace App\Interfaces;

interface DataStoreInterface
{
    public function init();
    public function getOne($id);
    public function getAll($options = []);
    public function save(): bool;
    public function delete(): bool;
}