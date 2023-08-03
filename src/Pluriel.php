<?php

namespace Pebble\Helpers;

class Pluriel
{
    private static $exceptions = [
        'au' => ['landau', 'sarrau'],
        'eu' => ['bleu', 'pneu'],
        'al' => ['aval', 'bal', 'cal', 'carnaval', 'chacal', 'festival', 'pal', 'récital', 'régal'],
        'ou' => ['bijou', 'caillou', 'chou', 'genou', 'hibou', 'joujou', 'pou'],
        'ail' => ['bail', 'corail', 'émail', 'fermail', 'gemmail', 'soupirail', 'travail', 'vantail', 'ventail', 'vitrail'],
    ];

    public static function create(string $word, int $nb = 2)
    {
        if (!($normalize = self::normalize($word))) {
            return $word;
        }

        $isMajuscule = mb_strtoupper($word) === $word;
        $firstLetter = mb_substr($word, 0, 1);
        $isFirst = $isMajuscule === false && mb_strtoupper($firstLetter) === $firstLetter;

        if (mb_strrpos($normalize, 'au') !== false) {
            if (self::isException($word, 'al')) {
                return self::format($word . 's', $isMajuscule, $isFirst);
            }

            return self::format($word . 'x', $isMajuscule, $isFirst);
        }

        if (mb_strrpos($normalize, 'eau') !== false) {
            return self::format($word . 'x', $isMajuscule, $isFirst);
        }

        if (mb_strrpos($normalize, 'eu') !== false) {
            if (self::isException($word, 'al')) {
                return self::format($word . 's', $isMajuscule, $isFirst);
            }

            return self::format($word . 'x', $isMajuscule, $isFirst);
        }

        if (mb_strrpos($normalize, 'al') !== false) {
            if (self::isException($word, 'al')) {
                return self::format($word . 's', $isMajuscule, $isFirst);
            }

            return self::format(mb_substr($word, 0, -2) . 'aux', $isMajuscule, $isFirst);
        }

        if (mb_strrpos($normalize, 'ou') !== false) {
            if (self::isException($word, 'ou')) {
                return self::format($word . 'x', $isMajuscule, $isFirst);
            }

            return self::format($word . 's', $isMajuscule, $isFirst);
        }

        if (mb_strrpos($normalize, 'ail') !== false) {
            if (self::isException($word, 'ail')) {
                return self::format(mb_substr($word, 0, -3) . 'aux', $isMajuscule, $isFirst);
            }

            return self::format($word . 's', $isMajuscule, $isFirst);
        }

        return self::format($word . 's', $isMajuscule, $isFirst);
    }

    private static function isException(string $word, string $suffix): bool
    {
        if (!isset(self::$exceptions[$suffix])) {
            return false;
        }

        return in_array($word, self::$exceptions[$suffix]);
    }

    private static function normalize(string $word)
    {
        return mb_strtolower(trim($word));
    }

    private static function format(string $word, bool $isMajuscule, bool $isFirst): string
    {
        if ($isMajuscule || $isFirst && mb_strlen($word) === 1) {
            return mb_strtoupper($word);
        }

        if ($isFirst) {
            return mb_strtoupper(mb_substr($word, 0, 1)) . mb_substr($word, 1);
        }

        return $word;
    }
}
