<?php

namespace StaticService\Interface\S3;

use Aws\S3\S3Client;

interface Connection
{
    public function getClient(): S3Client;
}
