<?php

namespace StaticService\Interface\S3;

interface S3ProviderCached extends S3Provider
{
    public const KEY_PREFIX = 'static:file:';
}
