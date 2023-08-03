<?php

namespace Pebble\Helpers;

/**
 * Help to work with arrays
 */
class Arrays
{

    public static function sort($array, $on, $order = SORT_ASC)
    {

        $newArray      = [];
        $sortableArray = [];

        if ($array) {

            foreach ($array as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $k2 => $v2) {
                        if ($k2 == $on) {
                            $sortableArray[$k] = $v2;
                        }
                    }
                } elseif (is_object($v)) {
                    if (isset($v->$on)) {
                        $sortableArray[$k] = $v->$on;
                    }
                }
            }

            switch ($order) {
                case SORT_ASC:
                    asort($sortableArray);
                    break;
                case SORT_DESC:
                    arsort($sortableArray);
                    break;
                default:
                    break;
            }

            foreach ($sortableArray as $k => $v) {
                $newArray[$k] = $array[$k];
            }
        }

        return $newArray;
    }

    public static function liste($data, $col, $key = null)
    {
        $out = [];
        if ($key) {
            foreach ($data as $item) {
                $out[$item[$key]] = $item[$col];
            }
        } else {
            foreach ($data as $item) {
                $out[] = $item[$col];
            }
        }

        return $out;
    }

    /**
     * Recursive ksort
     *
     * @param array $ary
     * @return bool
     */
    public static function ksort(&$ary)
    {
        foreach ($ary as &$v) {
            if (is_array($v)) {
                self::ksort($v);
            }
        }
        return ksort($ary);
    }

    public static function toCsv(array $rows, bool $withLabel = true): string
    {
        if (!$rows) {
            return '';
        }

        $csv = fopen('php://temp', 'w+');

        if ($withLabel) {
            fputcsv($csv, reset($rows));
        }

        foreach ($rows as $row) {
            fputcsv($csv, $row);
        }

        rewind($csv);
        $content = stream_get_contents($csv);

        fclose($csv);
        return $content ?: '';
    }
}

/* End of file */
