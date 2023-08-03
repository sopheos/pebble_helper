<?php

namespace Pebble\Helpers;

/**
 * Url
 *
 * @author mathieu
 */
class Url
{

    /**
     * @param string $url
     * @return string
     */
    public static function normalize(string $url)
    {
        $pos = mb_strpos($url, '?');

        if ($pos === false) {
            return $url;
        }

        $a   = [];
        $uri = mb_substr($url, 0, $pos);
        $qs  = mb_substr($url, $pos);

        mb_parse_str($qs, $a);
        Arrays::ksort($a);

        return $uri . '?' . urldecode(http_build_query($a));
    }

    /**
     * @param string $url
     * @param array $params
     * @return string
     */
    public static function params(string $url, array $params = [])
    {
        if (!$params) {
            return $url;
        }

        return $url . '?' . http_build_query($params);
    }

    public static function getHostByName(string $domain): ?string
    {
        // Make sure the domain ends in a dot to prevent DNS recursion lookups
        $domain = trim(trim($domain), '.') . '.';

        // Set timeout and retries to 1 to have a max execution time of 1 second for the DNS lookup
        putenv('RES_OPTIONS=retrans:1 retry:1 timeout:1 attempts:1');

        // DNS lookup
        $ip = gethostbyname($domain);

        // Result not valid
        if ($ip !== $domain && filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) !== false) {
            return $ip;
        }

        return null;
    }
}
