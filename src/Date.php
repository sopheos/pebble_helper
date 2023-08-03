<?php

namespace Pebble\Helpers;

use DateTime;

/**
 * Date
 *
 * @author mathieu
 * @package Pebble\Helpers
 */
class Date
{
    /**
     * The day constants
     */
    const SUNDAY = 0;
    const MONDAY = 1;
    const TUESDAY = 2;
    const WEDNESDAY = 3;
    const THURSDAY = 4;
    const FRIDAY = 5;
    const SATURDAY = 6;

    /**
     * The month constants
     */
    const JANUARY = 1;
    const FEBRUARY = 2;
    const MARCH = 3;
    const APRIL = 4;
    const MAY = 5;
    const JUNE = 6;
    const JULY = 7;
    const AUGUST = 8;
    const SEPTEMBER = 9;
    const OCTOBER = 10;
    const NOVEMBER = 11;
    const DECEMBER = 12;

    /**
     * The seasons constants
     */
    const SEASON_SPRING = 0;
    const SEASON_SUMMER = 1;
    const SEASON_AUTUMN = 2;
    const SEASON_WINTER = 3;
    const SPRING = 0;
    const SUMMER = 1;
    const AUTUMN = 2;
    const WINTER = 3;

    /**
     *  Formats
     */
    const DATETIME_SQL = 'Y-m-d H:i:s';
    const DATE_SQL = 'Y-m-d';
    const TIME_SQL = 'H:i:s';

    /**
     * Names of days of the week.
     *
     * @var array
     */
    public static $days = [
        self::SUNDAY => 'Sunday',
        self::MONDAY => 'Monday',
        self::TUESDAY => 'Tuesday',
        self::WEDNESDAY => 'Wednesday',
        self::THURSDAY => 'Thursday',
        self::FRIDAY => 'Friday',
        self::SATURDAY => 'Saturday'
    ];

    /**
     * Names of days of the week.
     *
     * @var array
     */
    public static $daysFr = [
        self::SUNDAY => 'Dimanche',
        self::MONDAY => 'Lundi',
        self::TUESDAY => 'Mardi',
        self::WEDNESDAY => 'Mercredi',
        self::THURSDAY => 'Jeudi',
        self::FRIDAY => 'Vendredi',
        self::SATURDAY => 'Samedi'
    ];

    /**
     * Names of months of the week.
     *
     * @var array
     */
    public static $months = [
        self::JANUARY => 'January',
        self::FEBRUARY => 'February',
        self::MARCH => 'March',
        self::APRIL => 'April',
        self::MAY => 'May',
        self::JUNE => 'June',
        self::JULY => 'July',
        self::AUGUST => 'August',
        self::SEPTEMBER => 'September',
        self::OCTOBER => 'October',
        self::NOVEMBER => 'November',
        self::DECEMBER => 'December'
    ];

    /**
     * Names of months of the week.
     *
     * @var array
     */
    public static $monthsFr = [
        self::JANUARY => 'Janvier',
        self::FEBRUARY => 'Février',
        self::MARCH => 'Mars',
        self::APRIL => 'Avril',
        self::MAY => 'Mai',
        self::JUNE => 'Juin',
        self::JULY => 'Juillet',
        self::AUGUST => 'Août',
        self::SEPTEMBER => 'Septembre',
        self::OCTOBER => 'Octobre',
        self::NOVEMBER => 'Novembre',
        self::DECEMBER => 'Décembre'
    ];

    /**
     * Array current date elements
     *
     * @var array
     */
    protected static $todayAry = null;

    /**
     * Lang
     * @var array
     */
    protected static $lang = [
        'date_year' => 'année',
        'date_years' => 'années',
        'date_month' => 'mois',
        'date_months' => 'mois',
        'date_day' => 'jour',
        'date_days' => 'jours',
        'date_hour' => 'heure',
        'date_hours' => 'heures',
        'date_minute' => 'minute',
        'date_minutes' => 'minutes',
        'date_second' => 'seconde',
        'date_seconds' => 'secondes'
    ];

    // -------------------------------------------------------------------------

    /**
     * Returns if a year is a leap year
     *
     * @param int $y
     * @return boolean
     */
    public static function isLeapYear($y)
    {
        return $y % 400 === 0 || ($y % 100 != 0 && $y % 4 === 0);
    }

    // -------------------------------------------------------------------------

    /**
     * Returns the number of days in a month
     *
     * @param int $m
     * @param int $y
     * @return int
     */
    public static function daysInMonth($m, $y)
    {
        return $m === 2 ? 28 + (int)self::isLeapYear($y) : 31 - ($m - 1) % 7 % 2;
    }

    // -------------------------------------------------------------------------

    /**
     * Returns if a date is in the past
     *
     * @param int $y Year
     * @param int $m Month
     * @param int $d Day
     * @param boolean $strict Not today
     * @return boolean
     */
    public static function isPast($y, $m, $d, $strict = true)
    {

        // Initialize & cache current day
        if (self::$todayAry == null) {
            self::$todayAry = explode('-', date('Y-n-j'));
        }

        // Validate
        if (!$strict && self::isToday($y, $m, $d)) {
            return true;
        } elseif ($y < self::$todayAry[0]) {
            return true;
        } elseif ($y == self::$todayAry[0] && $m < self::$todayAry[1]) {
            return true;
        } elseif ($y == self::$todayAry[0] && $m == self::$todayAry[1] && $d < self::$todayAry[2]) {
            return true;
        }

        return false;
    }

    // -------------------------------------------------------------------------

    /**
     * Returns if a date is in the futur
     *
     * @param int $y Year
     * @param int $m Month
     * @param int $d Day
     * @param boolean $strict Not today
     * @return boolean
     */
    public static function isFutur($y, $m, $d, $strict = true)
    {

        // Initialize & cache current day
        if (self::$todayAry == null) {
            self::$todayAry = explode('-', date('Y-n-j'));
        }

        // Today
        if (!$strict && self::isToday($y, $m, $d)) {
            return true;
        } elseif ($y > self::$todayAry[0]) {
            return true;
        } elseif ($y == self::$todayAry[0] && $m > self::$todayAry[1]) {
            return true;
        } elseif ($y == self::$todayAry[0] && $m == self::$todayAry[1] && $d > self::$todayAry[2]) {
            return true;
        }

        return false;
    }

    // -------------------------------------------------------------------------

    /**
     * Returns if a date is today
     *
     * @param int $y
     * @param int $m
     * @param int $d
     * @return boolean
     */
    public static function isToday($y, $m, $d)
    {
        // Initialize & cache current day
        if (self::$todayAry == null) {
            self::$todayAry = explode('-', date('Y-n-j'));
        }

        return self::$todayAry[0] && $m == self::$todayAry[1] && $d == self::$todayAry[2];
    }

    // -------------------------------------------------------------------------

    /**
     * Convert a mysql formatted timestamp to unix timestamp
     *
     * @param string $time
     * @return int
     */
    public static function mysqlToUnix(mixed $timestamp): int
    {
        if (is_int($timestamp)) {
            return $timestamp;
        }

        if (!$timestamp) {
            return time();
        }

        if ($timestamp instanceof DateTime) {
            return $timestamp->getTimestamp();
        }

        return strtotime($timestamp) ?: 0;
    }

    // -------------------------------------------------------------------------

    /**
     * Modifies a timestamp
     *
     * @param mixed $timestamp (null = current)
     * @param integer|null $year
     * @param integer|null $month
     * @param integer|null $day
     * @param integer|null $hour
     * @param integer|null $minute
     * @param integer|null $second
     * @return integer
     */
    public static function change(mixed $timestamp, ?int $year = null, ?int $month = null, ?int $day = null, ?int $hour = null, ?int $minute = null, ?int $second = null): int
    {
        $timestamp = self::mysqlToUnix($timestamp);
        $date = getdate($timestamp);

        return mktime(
            $hour ?? $date['hours'],
            $minute ?? $date['minutes'],
            $second ?? $date['seconds'],
            $month ?? $date['mon'],
            $day ?? $date['mday'],
            $year ?? $date['year']
        );
    }

    /**
     * Modifies a timestamp with relative
     *
     * @param mixed $timestamp (null = current)
     * @param string $modifier
     */
    public static function modify(mixed $timestamp, string $modifier)
    {
        return strtotime($modifier, self::mysqlToUnix($timestamp));
    }

    // -------------------------------------------------------------------------

    /**
     * Get the beggining date of a season for a given year
     *
     * @param int $annee
     * @param int $saison
     * @return array
     */
    public static function dateSaison($annee, $saison)
    {

        $TEST = (float)0;
        $M = (float)0;
        $Y1 = (float)($annee / 1000);

        switch ($saison) {
            case 0:
                $JD = (float)(1721139.2855 + 365.2421376 * $annee + 0.067919 * pow($Y1, 2) - 0.0027879 * pow($Y1, 3));
                break;
            case 1:
                $JD = (float)(1721233.2486 + 365.2417284 * $annee - 0.053018 * pow($Y1, 2) + 0.009332 * pow($Y1, 3));
                break;
            case 2:
                $JD = (float)(1721325.6978 + 365.2425055 * $annee - 0.126689 * pow($Y1, 2) + 0.0019401 * pow($Y1, 3));
                break;
            case 3:
                $JD = (float)(1721414.392 + 365.2428898 * $annee - 0.010965 * pow($Y1, 2) - 0.0084885 * pow($Y1, 3));
                break;
        }

        $RAD = (float)(M_PI / 180);

        $encore = true;

        while ($encore) {
            $T = ($JD - 2415020) / 36525;


            $L = 279.69668 + (36000.76892 * $T) + 0.0003025 * pow($T, 2);


            $M = (358.47583 + (35999.04975 * $T) - 0.00015 * pow($T, 2) - 0.0000033 * pow($T, 3)) / 360;
            $M = ($M - floor($M)) * 360;


            $C = (1.91946 - 0.004789 * $T - 0.000014 * pow($T, 2)) * sin($M * $RAD) + (0.020094 - 0.0001 * $T) * sin($M * 2) + (0.000293 * sin($M * 3));

            $OME = (259.18 - 1934.142 * $T) / 360;
            $OME = ($OME - floor($OME)) * 360 * $RAD;

            $AP = ($L + $C - 0.00569 - 0.00479 * sin($OME)) / 360;
            $AP = ($AP - floor($AP)) * 360;


            $TEST = $JD;
            $COR = 58 * sin(($saison * 90 - $AP) * $RAD);
            $JD = $JD + $COR;

            $encore = ($JD - $TEST) > 0.001;
        }

        $JD = $JD + 0.5;
        $Z = floor($JD);
        if ($Z < 2299161) {
            $A = $Z;
        } else {
            $X = floor(($Z - 1867216.25) / 36524.25);
            $A = $Z + 1 + $X - floor($X / 4);
        }

        $B = $A + 1524;
        $C = floor(($B - 122.1) / 365.25);
        $D = floor(365.25 * $C);
        $E = floor(($B - $D) / 30.6001);
        $F = $JD - $Z;
        $DayDec = $B - $D - floor(30.6001 * $E) + $F;

        if ($E < 13.5) {
            $MN = $E - 1;
        } else {
            $MN = $E - 13;
        }

        $FRAC = $DayDec - floor($DayDec);
        $Day = floor($DayDec);
        $Heure = floor($FRAC * 24);
        $Minute = ($FRAC * 24 - $Heure) * 60;

        $retour["JOUR"] = $Day;
        $retour["MOIS"] = $MN;
        $retour["ANNEE"] = $annee;
        $retour["HEURE"] = $Heure;
        $retour["MINUTE"] = floor($Minute);
        $retour["DATE_SAISON"] = mktime(0, 0, 0, $MN, $retour["JOUR"], $retour["ANNEE"]);
        $retour["DATE_SAISON_SQL"] = date("Y-m-d", $retour["DATE_SAISON"]);

        return $retour;
    }

    // -------------------------------------------------------------------------

    /**
     * Timespan
     *
     * @param int $min
     * @param int $max
     * @return string
     */
    public static function timespan(int $min = 1, int $max = 0, int $limit = 2)
    {
        $dt_min = new \DateTime();
        $dt_min->setTimestamp($min);

        $dt_max = new \DateTime();
        if ($max) {
            $dt_max->setTimestamp($max);
        }

        $diff = $dt_max->diff($dt_min);

        $out = [];
        $len = 0;
        $limit = $limit ?: PHP_INT_MAX;

        if ($len < $limit && $diff->y) {
            $out[] = self::getLang($diff->y, 'date_year');
            $len++;
        }

        if ($len < $limit && $diff->m) {
            $out[] = self::getLang($diff->m, 'date_month');
            $len++;
        }

        if ($len < $limit && $diff->d) {
            $out[] = self::getLang($diff->d, 'date_day');
            $len++;
        }

        if ($len < $limit && $diff->h) {
            $out[] = self::getLang($diff->h, 'date_hour');
            $len++;
        }

        if ($len < $limit && $diff->i) {
            $out[] = self::getLang($diff->i, 'date_minute');
            $len++;
        }

        if ($len < $limit && $diff->s) {
            $out[] = self::getLang($diff->s, 'date_second');
            $len++;
        }

        if ($len === 0) {
            return '';
        }

        if ($len === 1) {
            return $out[0];
        }

        $last = array_pop($out);
        return join(', ', $out) . ' et ' . $last;
    }

    private static function getLang($value, $key)
    {
        return $value . ' ' . self::$lang[$key . ($value > 1 ? 's' : '')];
    }

    // -------------------------------------------------------------------------

    /**
     * Date shortener
     *
     * @param DateTime|int|string $time timestamp or mysql datetime string
     * @param boolean $short_day display short day
     * @return string
     */
    public static function ago($time, $short_day = false, $today = false)
    {
        $time = self::mysqlToUnix($time);
        $date = date('Ymd', $time);
        $now = time();

        // Today
        if ($date == date('Ymd')) {
            return $today ? "Aujourd'hui" : date('H:i', $time);
        }
        // Yesterday
        elseif ($date == date('Ymd', $now - 86400)) {
            return 'Hier';
        }
        // Tomorrow
        elseif ($date == date('Ymd', $now + 86400)) {
            return 'Demain';
        }
        // This year
        elseif (date('Y', $time) === date('Y', $now)) {
            return $short_day ? static::strfull("%a %e %b", $time) : static::strfull("%e %b", $time);
        }

        return $short_day ? static::strfull("%a %e %b %Y", $time) : static::strfull("%e %b %Y", $time);
    }

    // -------------------------------------------------------------------------

    /**
     * Date shortener
     *
     * @param DateTime|int|string $time timestamp or mysql datetime string
     * @param boolean $detail_time display hour and minutes for days
     * @return string
     */
    public static function agoLong($time, $detail_time = false)
    {
        $time = self::mysqlToUnix($time);

        $now = time();
        $delta = $now - $time;
        $date = date('Ymd', $time);

        // 1 minute
        if ($delta < 120) {
            return "il y a une minute";
        }
        // moins d'une heure
        elseif ($delta < 3600) {
            return "il y a " . floor($delta / 60) . " minutes";
        }
        // aujourd'hui
        elseif ($date == date('Ymd', $now)) {
            return "aujourd'hui à " . date('H\hi', $time);
        }
        // hier
        elseif ($date == date('Ymd', $now - 86400)) {
            return 'hier à ' . date('H\hi', $time);
        }
        // Cette année
        elseif (date('Y', $time) === date('Y', $now)) {

            $_time = 'le ' . static::strfull("%e %b", $time);
            if ($detail_time === true) {
                $_time .= ' à ' . date('H\hi', $time);
            }
            return $_time;
        }

        $_time = 'le ' . static::strfull("%e %b %Y", $time);
        if ($detail_time === true) {
            $_time .= ' à ' . date('H\hi', $time);
        }
        return $_time;
    }

    /**
     * Date relative
     *
     * @param DateTime|string|int $date
     * @param bool $withTitle
     * @return string
     */
    public static function relative($date, string $positif = "il y a", string $negatif = "dans environ", string $now = "à l'instant")
    {
        $date = self::mysqlToUnix($date);

        // Déduction de la date donnée à la date actuelle
        $time = time() - $date;

        // Cas particulier
        if ($time === 0) {
            return $now;
        }

        // Préfix
        $when = $time > 0 ? $positif : $negatif;

        // Valeur absolue
        $time = abs($time);

        // Tableau des unités et de leurs valeurs en secondes
        $times = [
            31104000 => 'an{s}',
            2592000 => 'mois',
            86400 => 'jour{s}',
            3600 => 'heure{s}',
            60 => 'minute{s}',
            1 => 'seconde{s}'
        ];

        foreach ($times as $seconds => $unit) {
            // Calcule le delta entre le temps et l'unité donnée
            $delta = floor($time / $seconds);

            // Si le delta est supérieur à 1
            if ($delta >= 1) {
                // L'unité est au singulier ou au pluriel ?
                if ($delta > 1) {
                    $unit = str_replace('{s}', 's', $unit);
                } else {
                    $unit = str_replace('{s}', '', $unit);
                }
                // Retourne la chaine adéquate
                return trim($when . " " . $delta . " " . $unit);
            }
        }

        return $now;
    }

    // -------------------------------------------------------------------------

    /**
     * Formatage d'une date avec la syntaxe strftime
     * Utiliser de préférence static::fr()
     *
     * @param DateTime|string|int $time timestamp or mysql datetime string
     * @return string
     */
    public static function full($time, $format = true)
    {
        // Mysql to Unix time
        $time = self::mysqlToUnix($time);

        // Default
        if (!is_string($format)) {
            $format = "%A %d %B %Y à %H:%M";
        }

        return ucfirst(static::strfull($format, $time));
    }

    /**
     * Formatage d'une date avec la syntaxe strftime
     * Utiliser de préférence static::fr()
     *
     * @param string $format
     * @param integer|null $timestamp
     * @return string
     */
    public static function strfull(string $format, ?int $timestamp = null): string
    {
        $format = str_replace("&nbsp;", ' ', $format);

        // Unsupported format
        foreach (['%U', '%V', '%C', '%g', '%G'] as $char) {
            if (strpos($format, $char) !== false) {
                trigger_error("$char is not supported");
            }
        }

        // Escape date characters
        $format = ' ' . $format . ' ';
        $format = preg_replace('/([^%])([dDjlNSwzWFmMntLoYyaABgGhHisuveIOPpTZcrU])/', "$1\\\\$2" . '', $format);
        $format = trim($format);

        // Convert strftime format to date format
        $format = str_replace([
            '%a', '%A', '%d', '%e', '%u', '%w', '%W', '%b', '%h', '%B',
            '%m', '%y', '%Y', '%D', '%F', '%x', '%n', '%t', '%H', '%k',
            '%I', '%l', '%M', '%p', '%P', '%r', '%R', '%S', '%T', '%X',
            '%z', '%Z', '%c', '%s', '%%'
        ], [
            'D', 'l', 'd', 'j', 'N', 'w', 'W', 'M', 'M', 'F',
            'm', 'y', 'Y', 'm/d/y', 'Y-m-d', 'm/d/y', "\n", "\t", 'H', 'G',
            'h', 'g', 'i', 'A', 'a', 'h:i:s A', 'H:i', 's', 'H:i:s', 'H:i:s',
            'O', 'T', 'D M j H:i:s Y', 'U', '%'
        ], $format);

        return Date::fr($format, $timestamp);
    }

    // -------------------------------------------------------------------------

    /**
     * Formatage d'une date avec la syntaxe date()
     * Utiliser de préférence static::fr()
     *
     * @param DateTime|string|int|null $time
     * @param boolean $format
     */
    public static function format(mixed $time, mixed $format = true)
    {
        $time = self::mysqlToUnix($time);

        if (!is_string($format)) {
            $format = self::DATETIME_SQL;
        }

        return self::fr($format, $time);
    }

    /**
     * date french version
     *
     * @param string $format
     * @param integer|null $timestamp
     * @return string
     */
    public static function fr(string $format, ?int $timestamp = null): string
    {
        if ($timestamp === null) {
            $timestamp = time();
        }

        $dt = date($format, $timestamp);

        if (preg_match("/[^\\\][DlFM]/", ' ' . $format)) {
            $dt = self::toFr($dt);
        }

        return $dt;
    }

    // -------------------------------------------------------------------------

    public static function toFr(string $date): string
    {
        return strtr($date, [
            'Wednesday' => 'Mercredi',
            'September' => 'Septembre',
            'December' => 'Décembre',
            'February' => 'Février',
            'Thursday' => 'Jeudi',
            'November' => 'Novembre',
            'Saturday' => 'Samedi',
            'January' => 'Janvier',
            'Tuesday' => 'Mardi',
            'October' => 'Octobre',
            'August' => 'Août',
            'Sunday' => 'Dimanche',
            'Monday' => 'Lundi',
            'Friday' => 'Vendredi',
            'April' => 'Avril',
            'March' => 'Mars',
            'July' => 'Juillet',
            'June' => 'Juin',
            'Aug' => 'Août',
            'Apr' => 'Avril',
            'Sun' => 'Dim.',
            'Dec' => 'Déc.',
            'Feb' => 'Févr.',
            'Jan' => 'Janv.',
            'Thu' => 'Jeu.',
            'Jul' => 'Juil.',
            'Jun' => 'Juin',
            'Mon' => 'Lun.',
            'May' => 'Mai',
            'Tue' => 'Mar.',
            'Mar' => 'Mars',
            'Wed' => 'Mer.',
            'Nov' => 'Nov.',
            'Oct' => 'Oct.',
            'Sat' => 'Sam.',
            'Sep' => 'Sept.',
            'Fri' => 'Ven.',
        ]);
    }


    // -------------------------------------------------------------------------

    /**
     * @param string $value
     * @return int
     */
    public static function sqlToTimestamp($value)
    {
        return strtotime($value);
    }

    /**
     * @param string $value
     * @return int
     */
    public static function isoToTimestamp($value)
    {
        return self::sqlToTimestamp($value);
    }

    /**
     * @param int $value
     * @return string
     */
    public static function timestampToIso($value)
    {
        return date('c', $value);
    }

    /**
     * @param int $value
     * @return string
     */
    public static function timestampToSql($value)
    {
        return date(self::DATETIME_SQL, $value);
    }

    /**
     * @param string $value
     * @return string
     */
    public static function sqlToIso($value)
    {
        return self::timestampToIso(self::sqlToTimestamp($value));
    }

    /**
     * @param string $value
     * @return string
     */
    public static function isoToSql($value)
    {
        return self::timestampToSql(self::isoToTimestamp($value));
    }

    // -------------------------------------------------------------------------

    /**
     * Retourne un tableau de timestamp pour chaque jours entre `$start` et `$end`
     *
     * @param integer $start
     * @param integer $end
     * @return integer[]
     */
    public static function dayList(int $start, int $end): array
    {
        $timestamps = [];
        $beginOfDay = strtotime("today", $start);
        $endOfDay   = strtotime("tomorrow", $end) - 1;

        for ($date = $beginOfDay; $date <= $endOfDay; $date = strtotime('+1 day', $date)) {
            $timestamps[] = $date;
        }

        return $timestamps;
    }

    /**
     * Différence en jour entre deux dates
     *
     * @param integer $a
     * @param integer|null $b
     * @param boolean $abs
     */
    public static function diffDays(int $a, ?int $b = null, bool $abs = true)
    {
        $diff = floor((($b ?? time()) - $a) / 86400);
        return $abs && $diff < 0 ? $diff * -1 : $diff;
    }
}

/* End of file */
