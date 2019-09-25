<?php

namespace App;

class Version
{
    /**
     * Return the Major and Minor Version part of a Semantic Version Number
     * eg. v5.0 from v5.0.0
     *
     * @param  string $version
     * @return string
     */
    public static function minorVersion(string $version): string
    {
        return substr($version, 0, 4);
    }

    public static function isLaravelVersion(string $version): bool
    {
        // "v4.0 to v4.2" or "v5.0 to v5.8"
        $pattern = '/(^v(4)\.([0-2])$)|(^v(5)\.([0-8])$)|(^v(6)\.([0-8])$)/';

        return preg_match($pattern, $version);
    }
}
