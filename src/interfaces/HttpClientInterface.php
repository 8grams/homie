<?php

namespace App\Interfaces;

interface HttpClientInterface
{
    public function get(array $options = []): array;
}