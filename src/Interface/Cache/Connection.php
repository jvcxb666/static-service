<?php

namespace StaticService\Interface\Cache;

use Predis\ClientInterface;

interface Connection
{
    public function getClient(): ClientInterface;
}
