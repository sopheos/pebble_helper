<?php

namespace Pebble\Helpers;

/**
 * RomanNumber
 *
 * @author mathieu
 */
class RomanNumber
{

    // Values
    private static $roman_values = [
        'M'  => 1000,
        'CM' => 900,
        'D'  => 500,
        'CD' => 400,
        'C'  => 100,
        'XC' => 90,
        'L'  => 50,
        'XL' => 40,
        'X'  => 10,
        'IX' => 9,
        'V'  => 5,
        'IV' => 4,
        'I'  => 1
    ];
    // Values that should evaluate as 0
    private static $roman_zero   = ['N', 'nulla'];
    // Regex - checking for valid Roman numerals
    private static $roman_regex  = '/^M{0,3}(CM|CD|D?C{0,3})(XC|XL|L?X{0,3})(IX|IV|V?I{0,3})$/';

    /**
     * Test
     *
     * @param string $str
     * @return bool
     */
    public static function isRomanNumber($str)
    {
        return preg_match(self::$roman_regex, $str) > 0;
    }

    /**
     *
     * @param type $int
     * @param type $null
     * @return type
     */
    public static function int2Roman($int, $null = 'N')
    {
        $n = (int) $int;

        if ($n === 0) {
            return $null;
        }

        $res = '';
        foreach (self::$roman_values as $roman => $number) {
            $matches = intval($n / $number);
            $res     .= str_repeat($roman, $matches);
            $n       = $n % $number;
        }

        return $res;
    }

    //Conversion: Roman Numeral to Integer
    public static function roman2Int($str)
    {
        $result = 0;

        if ($str) {
            $str = preg_replace('/[^A-Z]/', '', mb_strtoupper($str));
        }

        if (in_array($str, self::$roman_zero)) {
            return $result;
        }

        if (!self::isRomanNumber($str)) {
            return $result;
        }

        foreach (self::$roman_values as $roman => $number) {
            while (mb_strpos($str, $roman) === 0) {
                $result += $number;
                $str    = mb_substr($str, mb_strlen($roman));
            }
        }

        return $result;
    }
}
