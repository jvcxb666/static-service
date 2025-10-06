<?php

namespace StaticService\Interface\S3;

interface S3ServiceInterface
{
    public function get(string $key): string;

    public function add(string $path): string;
}
