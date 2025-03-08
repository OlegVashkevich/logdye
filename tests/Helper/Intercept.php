<?php

namespace Tests\Helper;

use php_user_filter;

class Intercept extends php_user_filter
{
    public static string $cache = '';

    public function filter($in, $out, &$consumed, $closing): int
    {
        while ($bucket = stream_bucket_make_writeable($in)) {
            /** @var string $string */
            $string = $bucket->data;
            self::$cache .= $string;
            /** @var int $int */
            $int = $bucket->datalen;
            $consumed += $int;
            stream_bucket_append($out, $bucket);
        }
        return PSFS_PASS_ON;
    }
}