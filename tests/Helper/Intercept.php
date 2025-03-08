<?php

namespace Tests\Helper;

use php_user_filter;

class Intercept extends php_user_filter
{
    public static string $cache = '';

    public function filter($in, $out, &$consumed, $closing): int
    {
        while ($bucket = stream_bucket_make_writeable($in)) {
            self::$cache .= $bucket->data;
            $consumed += $bucket->datalen;
            stream_bucket_append($out, $bucket);
        }
        return PSFS_PASS_ON;
    }
}