<?php

namespace StaticService\Interface\S3;

interface S3Provider
{
    public function get(string $key): string;
}
