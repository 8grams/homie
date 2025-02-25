<?php

namespace App\Interfaces;

interface DataStoreInterface
{
    public function init();
    public function load();
    public function save(): bool;
    public function delete(): bool;
}