<?php

namespace Pebble\Helpers;

/**
 * Regex
 *
 * @author mathieu
 */
class Regex
{

    /**
     * Regex range between two number
     *
     * @param int $from
     * @param int $to
     * @return string
     */
    public static function numRange($from, $to)
    {
        if ($from < 0 || $to < 0) {
            return;
        }

        if ($from > $to) {
            $save = $from;
            $from = $to;
            $to   = $save;
        }

        $ranges    = array($from);
        $increment = 1;
        $next      = $from;
        $higher    = true;

        while (true) {

            $next += $increment;

            if ($next + $increment > $to) {
                if ($next <= $to) {
                    $ranges[] = $next;
                }
                $increment /= 10;
                $higher    = false;
            } else if ($next % ($increment * 10) === 0) {
                $ranges[]  = $next;
                $increment = $higher ? $increment * 10 : $increment / 10;
            }

            if (!$higher && $increment < 10) {
                break;
            }
        }

        $ranges[] = $to + 1;

        $regex = '(?:';

        for ($i = 0; $i < sizeof($ranges) - 1; $i++) {
            $str_from = (string) ($ranges[$i]);
            $str_to   = (string) ($ranges[$i + 1] - 1);

            for ($j = 0; $j < mb_strlen($str_from); $j++) {
                if ($str_from[$j] == $str_to[$j]) {
                    $regex .= $str_from[$j];
                } else {
                    $regex .= "[" . $str_from[$j] . "-" . $str_to[$j] . "]";
                }
            }
            $regex .= "|";
        }

        return mb_substr($regex, 0, mb_strlen($regex) - 1) . ')';
    }
}
