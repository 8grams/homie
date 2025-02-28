<?php

namespace App\Libs\Interfaces;

interface HttpClientInterface
{
    public function get(array $options = []): array;
}