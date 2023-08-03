<?php

namespace Pebble\Helpers;

/**
 * Help to work with texts
 *
 * @author mathieu
 */
class Text
{

    private static $accents = [
        "phonetic" => [
            "chars" => [
                "ä|æ|ǽ" => "ae",
                "ö|œ" => "oe",
                "ü" => "ue",
                "Ä" => "Ae",
                "Ü" => "Ue",
                "Ö" => "Oe",
                "À|Á|Â|Ã|Ä|Å|Ǻ|Ā|Ă|Ą|Ǎ" => "A",
                "à|á|â|ã|å|ǻ|ā|ă|ą|ǎ|ª" => "a",
                "Ç|Ć|Ĉ|Ċ|Č" => "C",
                "ç|ć|ĉ|ċ|č" => "c",
                "Ð|Ď|Đ" => "D",
                "ð|ď|đ" => "d",
                "È|É|Ê|Ë|Ē|Ĕ|Ė|Ę|Ě" => "E",
                "è|é|ê|ë|ē|ĕ|ė|ę|ě" => "e",
                "Ĝ|Ğ|Ġ|Ģ" => "G",
                "ĝ|ğ|ġ|ģ" => "g",
                "Ĥ|Ħ" => "H",
                "ĥ|ħ" => "h",
                "Ì|Í|Î|Ï|Ĩ|Ī|Ĭ|Ǐ|Į|İ" => "I",
                "ì|í|î|ï|ĩ|ī|ĭ|ǐ|į|ı" => "i",
                "Ĵ" => "J",
                "ĵ" => "j",
                "Ķ" => "K",
                "ķ" => "k",
                "Ĺ|Ļ|Ľ|Ŀ|Ł" => "L",
                "ĺ|ļ|ľ|ŀ|ł" => "l",
                "Ñ|Ń|Ņ|Ň" => "N",
                "ñ|ń|ņ|ň|ŉ" => "n",
                "Ò|Ó|Ô|Õ|Ō|Ŏ|Ǒ|Ő|Ơ|Ø|Ǿ" => "O",
                "ò|ó|ô|õ|ō|ŏ|ǒ|ő|ơ|ø|ǿ|º" => "o",
                "Ŕ|Ŗ|Ř" => "R",
                "ŕ|ŗ|ř" => "r",
                "Ś|Ŝ|Ş|Š" => "S",
                "ś|ŝ|ş|š|ſ" => "s",
                "Ţ|Ť|Ŧ" => "T",
                "ţ|ť|ŧ" => "t",
                "Ù|Ú|Û|Ũ|Ū|Ŭ|Ů|Ű|Ų|Ư|Ǔ|Ǖ|Ǘ|Ǚ|Ǜ" => "U",
                "ù|ú|û|ũ|ū|ŭ|ů|ű|ų|ư|ǔ|ǖ|ǘ|ǚ|ǜ" => "u",
                "Ý|Ÿ|Ŷ" => "Y",
                "ý|ÿ|ŷ" => "y",
                "Ŵ" => "W",
                "ŵ" => "w",
                "Ź|Ż|Ž" => "Z",
                "ź|ż|ž" => "z",
                "Æ|Ǽ" => "AE",
                "ß" => "ss",
                "Ĳ" => "IJ",
                "ĳ" => "ij",
                "Œ" => "OE",
                "ƒ" => "f",
                "’" => "'"
            ]
        ],
        "mono" => [
            "chars" => [
                "æ|ǽ" => "ae",
                "œ" => "oe",
                "Ä|À|Á|Â|Ã|Ä|Å|Ǻ|Ā|Ă|Ą|Ǎ" => "A",
                "ä|à|á|â|ã|å|ǻ|ā|ă|ą|ǎ|ª" => "a",
                "Ç|Ć|Ĉ|Ċ|Č" => "C",
                "ç|ć|ĉ|ċ|č" => "c",
                "Ð|Ď|Đ" => "D",
                "ð|ď|đ" => "d",
                "È|É|Ê|Ë|Ē|Ĕ|Ė|Ę|Ě" => "E",
                "è|é|ê|ë|ē|ĕ|ė|ę|ě" => "e",
                "Ĝ|Ğ|Ġ|Ģ" => "G",
                "ĝ|ğ|ġ|ģ" => "g",
                "Ĥ|Ħ" => "H",
                "ĥ|ħ" => "h",
                "Ì|Í|Î|Ï|Ĩ|Ī|Ĭ|Ǐ|Į|İ" => "I",
                "ì|í|î|ï|ĩ|ī|ĭ|ǐ|į|ı" => "i",
                "Ĵ" => "J",
                "ĵ" => "j",
                "Ķ" => "K",
                "ķ" => "k",
                "Ĺ|Ļ|Ľ|Ŀ|Ł" => "L",
                "ĺ|ļ|ľ|ŀ|ł" => "l",
                "Ñ|Ń|Ņ|Ň" => "N",
                "ñ|ń|ņ|ň|ŉ" => "n",
                "Ö|Ò|Ó|Ô|Õ|Ō|Ŏ|Ǒ|Ő|Ơ|Ø|Ǿ" => "O",
                "ö|ò|ó|ô|õ|ō|ŏ|ǒ|ő|ơ|ø|ǿ|º" => "o",
                "Ŕ|Ŗ|Ř" => "R",
                "ŕ|ŗ|ř" => "r",
                "Ś|Ŝ|Ş|Š" => "S",
                "ś|ŝ|ş|š|ſ" => "s",
                "Ţ|Ť|Ŧ" => "T",
                "ţ|ť|ŧ" => "t",
                "Ü|Ù|Ú|Û|Ũ|Ū|Ŭ|Ů|Ű|Ų|Ư|Ǔ|Ǖ|Ǘ|Ǚ|Ǜ" => "U",
                "ü|ù|ú|û|ũ|ū|ŭ|ů|ű|ų|ư|ǔ|ǖ|ǘ|ǚ|ǜ" => "u",
                "Ý|Ÿ|Ŷ" => "Y",
                "ý|ÿ|ŷ" => "y",
                "Ŵ" => "W",
                "ŵ" => "w",
                "Ź|Ż|Ž" => "Z",
                "ź|ż|ž" => "z",
                "Æ|Ǽ" => "AE",
                "ß" => "ss",
                "Ĳ" => "IJ",
                "ĳ" => "ij",
                "Œ" => "OE",
                "ƒ" => "f",
                "’" => "'"
            ]
        ],
        "french" => [
            "chars" => [
                "ǽ" => "ae",
                "Ä|Á|Ã|Ä|Å|Ǻ|Ā|Ă|Ą|Ǎ" => "A",
                "ä|á|ã|å|ǻ|ā|ă|ą|ǎ|ª" => "a",
                "Ć|Ĉ|Ċ|Č" => "C",
                "ć|ĉ|ċ|č" => "c",
                "Ð|Ď|Đ" => "D",
                "ð|ď|đ" => "d",
                "Ē|Ĕ|Ė|Ę|Ě" => "E",
                "ē|ĕ|ė|ę|ě" => "e",
                "Ĝ|Ğ|Ġ|Ģ" => "G",
                "ĝ|ğ|ġ|ģ" => "g",
                "Ĥ|Ħ" => "H",
                "ĥ|ħ" => "h",
                "Ì|Í|Ĩ|Ī|Ĭ|Ǐ|Į|İ" => "I",
                "ì|í|ĩ|ī|ĭ|ǐ|į|ı" => "i",
                "Ĵ" => "J",
                "ĵ" => "j",
                "Ķ" => "K",
                "ķ" => "k",
                "Ĺ|Ļ|Ľ|Ŀ|Ł" => "L",
                "ĺ|ļ|ľ|ŀ|ł" => "l",
                "Ń|Ņ|Ň" => "N",
                "ń|ņ|ň|ŉ" => "n",
                "Ö|Ò|Ó|Õ|Ō|Ŏ|Ǒ|Ő|Ơ|Ø|Ǿ" => "O",
                "ö|ò|ó|õ|ō|ŏ|ǒ|ő|ơ|ø|ǿ|º" => "o",
                "Ŕ|Ŗ|Ř" => "R",
                "ŕ|ŗ|ř" => "r",
                "Ś|Ŝ|Ş|Š" => "S",
                "ś|ŝ|ş|š|ſ" => "s",
                "Ţ|Ť|Ŧ" => "T",
                "ţ|ť|ŧ" => "t",
                "Ú|Ũ|Ū|Ŭ|Ů|Ű|Ų|Ư|Ǔ|Ǖ|Ǘ|Ǚ|Ǜ" => "U",
                "ú|ũ|ū|ŭ|ů|ű|ų|ư|ǔ|ǖ|ǘ|ǚ|ǜ" => "u",
                "Ý|Ŷ" => "Y",
                "ý|ŷ" => "y",
                "Ŵ" => "W",
                "ŵ" => "w",
                "Ź|Ż|Ž" => "Z",
                "ź|ż|ž" => "z",
                "Æ|Ǽ" => "AE",
                "ß" => "ss",
                "Ĳ" => "IJ",
                "ĳ" => "ij",
                "Œ" => "OE",
                "ƒ" => "f",
                "’" => "'"
            ]
        ]
    ];
    private static $vowels = ["a", "e", "i", "o", "u", "y"];

    // -------------------------------------------------------------------------

    /**
     * Set a new accents table
     *
     * @param string $table
     * @param array $accents
     */
    public static function setAccentsTable($table, array $accents)
    {
        self::$accents[$table] = [];
        self::$accents[$table]['chars'] = $accents;
    }

    // -------------------------------------------------------------------------

    /**
     * Get an accent table
     *
     * @param string $table
     * @return array
     */
    public static function getAccentsTable($table)
    {
        // Table not found
        if (!isset(self::$accents[$table]['chars'])) {
            return [];
        }

        return self::$accents[$table]['chars'];
    }

    // -------------------------------------------------------------------------

    /**
     * Convert Accented Characters to ASCII
     *
     * @param string $str
     * @return string
     */
    public static function convertAccents($str, $table = 'phonetic')
    {
        // Table not found
        if (!isset(self::$accents[$table]['chars'])) {
            return $str;
        }

        $chars = self::$accents[$table]['chars'];
        $search = isset(self::$accents[$table]['search']) ? self::$accents[$table]['search'] : null;
        $replace = isset(self::$accents[$table]['replace']) ? self::$accents[$table]['replace'] : null;

        // Search and replace not found
        if ($search === null || $replace === null) {
            self::$accents[$table]['search'] = [];
            self::$accents[$table]['replace'] = [];
            foreach ($chars as $k => $v) {
                // Flag u => UTF-8
                self::$accents[$table]['search'][] = '#' . $k . '#u';
                self::$accents[$table]['replace'][] = $v;

                $search = self::$accents[$table]['search'];
                $replace = self::$accents[$table]['replace'];
            }
        }

        return preg_replace($search, $replace, $str);
    }

    // -------------------------------------------------------------------------

    /**
     * @param string $str
     * @param string $table
     * @return string
     */
    public static function normalize(string $str, string $table = 'phonetic'): string
    {
        $str = self::convertAccents($str, $table);
        $str = mb_strtolower($str);
        $str = preg_replace("#[^a-z0-9'-]#i", ' ', $str);
        $str = trim($str);
        $str = preg_replace('#\s+#', ' ', $str);
        $str = preg_replace("#([a-z])['-]?\s#", '$1 ', $str);
        $str = preg_replace("#\s['-]?([a-z])#", ' $1', $str);
        return $str;
    }

    /**
     * @param string $str
     * @param string $table
     * @return string
     */
    public static function alias(string $str, string $table = 'phonetic'): string
    {
        $str = self::convertAccents($str, $table);
        $str = mb_strtolower($str);
        $str = preg_replace('#[^a-z0-9]#i', '-', $str);
        $str = trim($str, '-');
        $str = preg_replace('#-+#', '-', $str);
        return $str;
    }

    // -------------------------------------------------------------------------

    /**
     * Get accents list
     * @return string
     */
    public static function getAccents($table = 'phonetic', $char = '|')
    {
        // Table not found
        if (!isset(self::$accents[$table])) {
            return '';
        }

        // Accents list not found
        if (!isset(self::$accents[$table]['accents'])) {
            self::$accents[$table]['accents'] = implode($char, array_keys(self::$accents[$table]['chars']));
        }

        return self::$accents[$table]['accents'];
    }

    // -------------------------------------------------------------------------

    /**
     * Explode a string and trim all elements
     *
     * @param string $char
     * @param string $str
     * @return array
     */
    public static function split($char, $str)
    {
        if ($char && mb_strpos('#!^$()[]{}|?+*.\\', $char) !== false) {
            $char = '\\' . $char;
        }

        return preg_split("#\s*{$char}\s*#", $str, -1, PREG_SPLIT_NO_EMPTY);
    }

    // -------------------------------------------------------------------------

    /**
     * Code Highlighter : Colorizes code strings
     *
     * @param	string	the text string
     * @return	string
     */
    public static function highlightCode($str)
    {
        // The highlight string function encodes and highlights
        // brackets so we need them to start raw
        $str = str_replace(array('&lt;', '&gt;'), array('<', '>'), $str);

        // Replace any existing PHP tags to temporary markers so they don't accidentally
        // break the string out of PHP, and thus, thwart the highlighting.

        $str = str_replace(array('<?', '?>', '<%', '%>', '\\', '</script>'), array('phptagopen', 'phptagclose', 'asptagopen', 'asptagclose', 'backslashtmp', 'scriptclose'), $str);

        // The highlight_string function requires that the text be surrounded
        // by PHP tags, which we will remove later
        $str = '<?php ' . $str . ' ?>'; // <?
        // All the magic happens here, baby!
        $str = highlight_string($str, true);

        // Remove our artificially added PHP, and the syntax highlighting that came with it
        $str = preg_replace('/<span style="color: #([A-Z0-9]+)">&lt;\?php(&nbsp;| )/i', '<span style="color: #$1">', $str);
        $str = preg_replace('/(<span style="color: #[A-Z0-9]+">.*?)\?&gt;<\/span>\n<\/span>\n<\/code>/is', "$1</span>\n</span>\n</code>", $str);
        $str = preg_replace('/<span style="color: #[A-Z0-9]+"\><\/span>/i', '', $str);

        // Replace our markers back to PHP tags.
        $str = str_replace(array('phptagopen', 'phptagclose', 'asptagopen', 'asptagclose', 'backslashtmp', 'scriptclose'), array('&lt;?', '?&gt;', '&lt;%', '%&gt;', '\\', '&lt;/script&gt;'), $str);

        return $str;
    }

    // -------------------------------------------------------------------------

    /**
     * Phrase Highlighter : Highlights a phrase within a text string
     *
     * @param	string	the text string
     * @param	string	the phrase you'd like to highlight
     * @param	string	the openging tag to precede the phrase with
     * @param	string	the closing tag to end the phrase with
     * @return	string
     */
    public static function highlightPhrase($str, $phrase, $tag_open = '<strong>', $tag_close = '</strong>')
    {
        if ($str == '') {
            return '';
        }

        if ($phrase != '') {
            return preg_replace('/(' . preg_quote($phrase, '/') . ')/i', $tag_open . "\\1" . $tag_close, $str);
        }

        return $str;
    }

    // -------------------------------------------------------------------------

    /**
     * Format a string for urls
     *
     * @param string $str A string not formatted
     * @param boolean $lowercase TRUE to returns a lowercase string
     * @return string Returns a string formatted for urls
     */
    public static function urlTitle($str, $lowercase = true)
    {
        if ($str == '') {
            return '';
        }

        $separator = '-';

        $stopwords1 = ' d\'| l\'| de | une | un | le | la | les | des |'
            . ' à | a | en |-d\'| est | nous | vous | ils | par |'
            . ' sur | dans | vos | nos | qui | que | pour | votre |'
            . ' notre | mais | pas | tes | mes | dans | avec | dès | aux ';

        $stopwords2 = '^un |^une |^le |^les |^du |^des |^de la ';

        $trans = [
            $stopwords1 => ' ',
            $stopwords2 => '',
            ' |\'|\/' => $separator,
            '[^s0-9=@A-Z\-a-z]*' => '',
            '-+|-d-|-l-' => $separator
        ];

        $str = Text::convertAccents($str);
        $str = Text::stripTags($str);

        foreach ($trans as $key => $val) {
            $str = preg_replace("#" . $key . "#i", $val, $str);
        }

        if ($lowercase) {
            $str = mb_strtolower($str);
        }

        return trim($str, $separator);
    }

    public static function stripTags(string $string): string
    {
        $tag = "~" . uniqid() . "~";
        $string = preg_replace("/(<)(\d)/", "$1 {$tag}$2", $string);
        $string = strip_tags($string);
        $string = preg_replace("/(<) {$tag}(\d)/", "$1$2", $string);
        return $string;
    }

    // -------------------------------------------------------------------------

    /**
     * Convert strings with underscores into CamelCase
     *
     * @param string $str The string to convert
     * @param bool|null $firstCharCaps camelCase or CamelCase
     * @return string The converted string
     */
    public static function underscoreToCamel($str, $firstCharCaps = null)
    {
        if (!$str) return $str;

        if ($firstCharCaps === true) $str = self::ucfirst($str);
        elseif ($firstCharCaps === false) $str = self::lcfirst($str);

        return preg_replace_callback('/_([a-z])/', function ($c) {
            return mb_strtoupper($c[1]);
        }, $str);
    }

    // -------------------------------------------------------------------------

    /**
     * Convert a camel case string to underscores
     *
     * @param string $str The string to convert
     * @return string The converted string
     */
    public static function camelToUnderscore($str)
    {
        if (!$str) return $str;

        $str = self::lcfirst($str);

        return preg_replace_callback('/([A-Z])/', function ($c) {
            return "_" . mb_strtolower($c[1]);
        }, $str);
    }

    // -------------------------------------------------------------------------

    /**
     * SQL Like operator in PHP.
     * Returns TRUE if match else FALSE.
     *
     * @param   string $pattern
     * @param   string $subject
     * @return  bool
     */
    public static function like($pattern, $subject)
    {
        $pattern = str_replace('%', '.*', preg_quote($pattern));
        return (bool) preg_match("/^{$pattern}$/i", $subject);
    }

    // -------------------------------------------------------------------------

    public static function de($str)
    {
        $char = mb_strtolower(mb_substr(trim($str), 0, 1));
        $char = self::convertAccents($char);

        $haystack = self::$vowels;
        $haystack[] = 'h';

        if (in_array($char, $haystack)) {
            return 'd\'' . $str;
        }

        return 'de ' . $str;
    }

    // -------------------------------------------------------------------------

    public static function a($str)
    {
        if (strpos($str, 'Le ') === 0) {
            return 'au ' . mb_substr(trim($str), 3);
        }

        if (strpos($str, 'Les ') === 0) {
            return 'aux ' . mb_substr(trim($str), 4);
        }

        return 'à ' . $str;
    }

    // -------------------------------------------------------------------------

    public static function le($str)
    {
        $char = mb_strtolower(mb_substr(trim($str), 0, 1));

        if (in_array($char, self::$vowels)) {
            return 'l\'' . $str;
        }

        return 'le ' . $str;
    }

    // -------------------------------------------------------------------------

    public static function la($str)
    {
        $char = mb_strtolower(mb_substr(trim($str), 0, 1));

        if (in_array($char, self::$vowels)) {
            return 'l\'' . $str;
        }

        return 'la ' . $str;
    }

    // -------------------------------------------------------------------------

    /**
     * Plural of a word
     *
     * @param int $amount
     * @param string $singular
     * @param string $plural
     * @return string
     */
    public static function plural($amount, $singular = '', $plural = 's')
    {
        return ($amount === 0 || $amount === 1) ? $singular : $plural;
    }

    // -------------------------------------------------------------------------

    /**
     * Add a hashtag on a list of words found in a string
     *
     * @param string $str
     * @param string $search
     * @return string
     */
    public static function hashtagify($str, $search)
    {
        $replace = [];
        foreach ($search as $value) {
            $replace[] = '#' . str_replace('-', '', $value);
        }

        return str_replace($search, $replace, $str);
    }

    // -------------------------------------------------------------------------

    /**
     * Return TRUE if string passed contains an email address, FALSE otherwise
     *
     * @param   string  $str
     * @return  boolean
     */
    public static function containsEmailAddress($str)
    {
        return preg_match('#\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*#i', $str) === 1;
    }

    // -------------------------------------------------------------------------

    /**
     * Return TRUE if string passed contains a phone number, FALSE otherwise
     *
     * @param   string  $str
     * @return  boolean
     */
    public static function containsPhoneNumber($str)
    {
        return preg_match('#\+?(?:[0-9] ?){6,14}[0-9]#', $str) === 1;
    }

    // -------------------------------------------------------------------------

    /**
     * Return TRUE if string passed contain email addresses and phone numbers, FALSE otherwise
     *
     * @param   string  $str
     * @return  boolean
     */
    public static function containsEmailAndPhone($str)
    {
        return self::containsEmailAddress($str) && self::containsPhoneNumber($str);
    }

    // -------------------------------------------------------------------------

    /**
     * Return TRUE if string passed not contains an email address, FALSE otherwise
     *
     * @param   string  $str
     * @return  boolean
     */
    public static function notContainsEmailAddress($str)
    {
        return self::containsEmailAddress($str) == false;
    }

    // -------------------------------------------------------------------------

    /**
     * Return TRUE if string passed not contains a phone number, FALSE otherwise
     *
     * @param   string  $str
     * @return  boolean
     */
    public static function notContainsPhoneNumber($str)
    {
        return self::containsPhoneNumber($str) == false;
    }

    /**
     * Return TRUE if string passed not contains email addresses and phone numbers, FALSE otherwise
     *
     * @param   string  $str
     * @return  boolean
     */
    public static function notContainsEmailAndPhone($str)
    {
        return self::notContainsEmailAddress($str) && self::notContainsPhoneNumber($str);
    }

    // -------------------------------------------------------------------------

    /**
     * Return the list of email addresses found in string passed
     *
     * @param   string  $str
     * @return  array
     */
    public static function getEmailAddressesFrom($str)
    {
        return preg_match_all('#\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*#i', $str, $matches, PREG_SET_ORDER) ? $matches : [];
    }

    // -------------------------------------------------------------------------

    /**
     * Return the list of phone numbers found in string passed
     *
     * @param   string  $str
     * @return  array
     */
    public static function getPhoneNumbersFrom($str)
    {
        return preg_match_all('#\+?(?:[0-9] ?){6,14}[0-9]#', $str, $matches, PREG_SET_ORDER) ? $matches : [];
    }

    // -------------------------------------------------------------------------

    /**
     * Return the percentage of upper letter of the string passed in parameter
     *
     * @param   string  $str
     * @return  int
     */
    public static function getPercentOfUpperLetter($str)
    {
        $str = str_replace(' ', '', $str);
        preg_match_all('/[A-Z]/', $str, $caps);

        if (empty($str)) {
            return 0;
        }

        return (count($caps[0]) * 100 / mb_strlen($str));
    }

    // -------------------------------------------------------------------------

    /**
     * Lowercase a string (ignore the first letter) which have more of 60% uppercase letters
     *
     * @param string $strBiens
     * @return string
     */
    public static function filterPercentOfUpperLetter($strBiens)
    {
        if ($strBiens && self::getPercentOfUpperLetter($strBiens) > 60) {
            return ucfirst(mb_strtolower($strBiens));
        }
        return $strBiens;
    }

    // -------------------------------------------------------------------------

    /**
     * Word Limiter
     *
     * Limits a string to X number of words.
     *
     * @param	string
     * @param	int
     * @param	string	the end character. Usually an ellipsis
     * @return	string
     */
    public static function wordLimiter($str, $limit = 100, $end_char = '&#8230;')
    {
        if (trim($str) === '') {
            return $str;
        }
        preg_match('/^\s*+(?:\S++\s*+){1,' . (int) $limit . '}/', $str, $matches);
        if (mb_strlen($str) === mb_strlen($matches[0])) {
            $end_char = '';
        }
        return rtrim($matches[0]) . $end_char;
    }

    /**
     * Get words from a string
     *
     * @param string $str
     * @return array
     */
    public static function words($str)
    {
        return preg_split('/\s/s', $str, -1, PREG_SPLIT_NO_EMPTY);
    }

    /**
     * @param string $str
     * @return string
     */
    public static function ucfirst(string $str): string
    {
        if ($str) {
            $str = mb_strtoupper(mb_substr($str, 0, 1)) . mb_substr($str, 1);
        }
        return $str;
    }

    /**
     * @param string $str
     * @return string
     */
    public static function lcfirst(string $str): string
    {
        if ($str) {
            $str[0] = mb_strtolower($str[0]);
        }
        return $str;
    }

    public static function remove_emoji($string)
    {

        // Match Emoticons
        $regex_emoticons = '/[\x{1F600}-\x{1F64F}]/u';
        $clear_string = preg_replace($regex_emoticons, '', $string);

        // Match Miscellaneous Symbols and Pictographs
        $regex_symbols = '/[\x{1F300}-\x{1F5FF}]/u';
        $clear_string = preg_replace($regex_symbols, '', $clear_string);

        // Match Transport And Map Symbols
        $regex_transport = '/[\x{1F680}-\x{1F6FF}]/u';
        $clear_string = preg_replace($regex_transport, '', $clear_string);

        // Match Miscellaneous Symbols
        $regex_misc = '/[\x{2600}-\x{26FF}]/u';
        $clear_string = preg_replace($regex_misc, '', $clear_string);

        // Match Dingbats
        $regex_dingbats = '/[\x{2700}-\x{27BF}]/u';
        $clear_string = preg_replace($regex_dingbats, '', $clear_string);

        return $clear_string;
    }

    /**
     * Représentation texte d'un nombre en standard français
     *
     * @param float $amount
     */
    public static function number(float $amount)
    {
        return number_format($amount, 2, ',', '&#8239;');
    }

    /**
     * Représentation texte d'une valeur monétaire en standard français
     *
     * @param float $amount
     * @param string $currency
     * @return string
     */
    public static function money(float $amount, string $currency = ''): string
    {
        return $currency ? self::number($amount) . '&nbsp;' . $currency : self::number($amount);
    }

    /**
     * Protège les valeurs des attributs HTML
     *
     * @param mixed $value
     * @return string
     */
    public static function xss($value): string
    {
        if ($value === null) {
            return '';
        }

        if (is_numeric($value)) {
            return (string) $value;
        }

        if (is_string($value)) {
            return self::htmlEncode($value);
        }

        if (is_bool($value)) {
            return $value ? '1' : '0';
        }

        return self::htmlEncode(json_encode($value));
    }

    public static function htmlEncode($value)
    {
        if ($value === null) {
            return '';
        }

        return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5, 'UTF-8');
    }

    public static function htmlDecode($value)
    {
        if ($value === null) {
            return '';
        }

        return html_entity_decode($value, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5, 'UTF-8');
    }


    // -------------------------------------------------------------------------

    public static function parseEmails(string $str): array
    {
        $qtext = '[^\\x0d\\x22\\x5c\\x80-\\xff]';
        $dtext = '[^\\x0d\\x5b-\\x5d\\x80-\\xff]';
        $atom  = '[^\\x00-\\x20\\x22\\x28\\x29\\x2c\\x2e\\x3a-\\x3c\\x3e\\x40\\x5b-\\x5d\\x7f-\\xff]+';
        $pair  = '\\x5c[\\x00-\\x7f]';

        $domain_literal = "\\x5b($dtext|$pair)*\\x5d";
        $quoted_string  = "\\x22($qtext|$pair)*\\x22";
        $sub_domain     = "($atom|$domain_literal)";
        $word           = "($atom|$quoted_string)";
        $domain         = "$sub_domain(\\x2e$sub_domain)*";
        $local_part     = "$word(\\x2e$word)*";
        $addr_spec      = "$local_part\\x40$domain";

        $emails = [];
        preg_match_all('/' . $addr_spec . '/', $str, $emails);

        return $emails ? $emails[0] : [];
    }


    // -------------------------------------------------------------------------
}

/* End of file */
