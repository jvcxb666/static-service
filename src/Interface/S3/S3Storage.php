<?php

namespace StaticService\Interface\S3;

interface S3Storage
{
    public function add(string $path): string;
}
