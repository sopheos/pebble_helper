<?php

namespace Pebble\Helpers;

class Emoji
{
    private static $data;

    private static $code  = [
        "a" => "\u{1f1e6}",
        "b" => "\u{1f1e7}",
        "c" => "\u{1f1e8}",
        "d" => "\u{1f1e9}",
        "e" => "\u{1f1ea}",
        "f" => "\u{1f1eb}",
        "g" => "\u{1f1ec}",
        "h" => "\u{1f1ed}",
        "i" => "\u{1f1ee}",
        "j" => "\u{1f1ef}",
        "k" => "\u{1f1f0}",
        "l" => "\u{1f1f1}",
        "m" => "\u{1f1f2}",
        "n" => "\u{1f1f3}",
        "o" => "\u{1f1f4}",
        "p" => "\u{1f1f5}",
        "q" => "\u{1f1f6}",
        "r" => "\u{1f1f7}",
        "s" => "\u{1f1f8}",
        "t" => "\u{1f1f9}",
        "u" => "\u{1f1fa}",
        "v" => "\u{1f1fb}",
        "w" => "\u{1f1fc}",
        "x" => "\u{1f1fd}",
        "y" => "\u{1f1fe}",
        "z" => "\u{1f1ff}",
    ];


    /**
     * @param string $name
     * @return string
     */
    public static function get(string $name): string
    {
        if (self::$data === null) {
            self::$data = include __DIR__ . '/emoji_list.php';
        }

        return self::$data[$name] ?? '';
    }

    /**
     * @return array
     */
    public static function all(): array
    {
        if (self::$data === null) {
            self::$data = include __DIR__ . '/emoji_list.php';
        }

        return self::$data;
    }

    /**
     * @param string $country
     * @return string
     */
    public static function flag(string $country): string
    {
        if (mb_strlen($country) !== 2) {
            return '';
        }

        $country = mb_strtolower($country);

        $a = self::$code[$country[0]] ?? null;
        $b = self::$code[$country[1]] ?? null;

        return $a && $b ? $a . $b : '';
    }
}
