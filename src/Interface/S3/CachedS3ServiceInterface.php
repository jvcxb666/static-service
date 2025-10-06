<?php

namespace StaticService\Interface\S3;

interface CachedS3ServiceInterface extends S3ServiceInterface
{
    public const KEY_PREFIX = 'static:file:';
}
