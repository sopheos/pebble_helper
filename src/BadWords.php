<?php

namespace Pebble\Helpers;

/**
 * BadWords
 *
 * @author mathieu
 */
class BadWords
{

    private $chars      = [];
    private $symbols    = [];
    private $dictionary = [];
    private $cache      = [];

    // -------------------------------------------------------------------------

    /**
     * @param array $dictionary
     */
    public function __construct(array $dictionary = [])
    {
        $leet = [
            'Æ|æ|Ǽ|ǽ'                                                             => 'ae',
            'Œ|œ'                                                                 => 'oe',
            'Ĳ|ĳ'                                                                 => 'ij',
            '4|@|ª|À|Á|Â|Ã|Ä|Å|à|á|â|ã|ä|å|Ā|ā|Ă|ă|Ą|ą|Ǎ|ǎ|Ǻ|ǻ|Δ|Λ|α|λ'           => 'a',
            '\|3|8|ß|Β|β'                                                         => 'b',
            '\|{|<|¢|©|Ç|ç|Ć|ć|Ĉ|ĉ|Ċ|ċ|Č|č|€'                                     => 'c',
            '\|\|Þ|Ð|ð|þ|Ď|ď|Đ|đ|∂'                                               => 'd',
            '3|È|É|Ê|Ë|è|é|ê|ë|Ē|ē|Ĕ|ĕ|Ė|ė|Ę|ę|Ě|ě|€|∑'                           => 'e',
            'ƒ'                                                                   => 'f',
            '6|9|Ĝ|ĝ|Ğ|ğ|Ġ|ġ|Ģ|ģ'                                                 => 'g',
            'Ĥ|ĥ|Ħ|ħ'                                                             => 'h',
            '!|\||1|\]\[|]|Ì|Í|Î|Ï|ì|í|î|ï|Ĩ|ĩ|Ī|ī|Ĭ|ĭ|Į|į|İ|ı|Ǐ|ǐ|∫'             => 'i',
            'Ĵ|ĵ'                                                                 => 'j',
            'Ķ|ķ|Κ|κ'                                                             => 'k',
            '\||\]\[|]|£|Ì|Í|Î|Ï|Ĺ|ĺ|Ļ|ļ|Ľ|ľ|Ŀ|ŀ|Ł|ł|∫'                           => 'l',
            'Ñ|ñ|Ń|ń|Ņ|ņ|Ň|ň|ŉ|Ν|Π|η'                                             => 'n',
            '0|¤|°|º|Ò|Ó|Ô|Õ|Ö|Ø|ò|ó|ô|õ|ö|ø|Ō|ō|Ŏ|ŏ|Ő|ő|Ơ|ơ|Ǒ|ǒ|Ǿ|ǿ|Ο|Φ|ο'       => 'o',
            '¶|þ|Ρ|ρ'                                                             => 'p',
            '®|Ŕ|ŕ|Ŗ|ŗ|Ř|ř'                                                       => 'r',
            '5|\$|§|Ś|ś|Ŝ|ŝ|Ş|ş|Š|š|ſ'                                            => 's',
            '7|Ţ|ţ|Ť|ť|Ŧ|ŧ|Τ|τ|\+'                                                => 't',
            'µ|Ù|Ú|Û|Ü|ù|ú|û|ü|Ũ|ũ|Ū|ū|Ŭ|ŭ|Ů|ů|Ű|ű|Ų|ų|Ư|ư|Ǔ|ǔ|Ǖ|ǖ|Ǘ|ǘ|Ǚ|ǚ|Ǜ|ǜ|υ' => 'u',
            'ν|υ'                                                                 => 'v',
            'Ŵ|ŵ|Ψ|ψ|ω'                                                           => 'w',
            'Χ|χ'                                                                 => 'x',
            '¥|Ý|ý|ÿ|Ŷ|ŷ|Ÿ|γ'                                                     => 'y',
            'Ź|ź|Ż|ż|Ž|ž|Ζ'                                                       => 'z'
        ];

        $chars   = [];
        $symbols = [];
        foreach ($leet as $k => $v) {
            $this->symbols[] = '/(' . $k . ')/u';
            $this->chars[]   = $v;

            if (mb_strlen($v) === 1) {
                $chars[]   = $v;
                $symbols[] = "{$v}[ _]*";
            }
        }

        $chars[]   = '#';
        $chars[]   = ' ';
        $symbols[] = '\b';
        $symbols[] = '.?';

        foreach ($dictionary as $word) {
            $this->dictionary[] = str_replace('#', '', $word);
            $this->cache[]      = '/' . str_replace($chars, $symbols, $word) . '/i';
        }
    }

    /**
     * @param string $str
     * @param bool $alone
     * @return array
     */
    public function search($str)
    {
        $txt = $this->sanitize($str);

        $matches = [];
        foreach ($this->cache as $k => $regex) {
            if (preg_match($regex, $txt)) {
                $matches[] = $this->dictionary[$k];
            }
        }

        return $matches;
    }

    // -------------------------------------------------------------------------

    /**
     * @param string $str
     * @return string
     */
    public function sanitize($str)
    {
        // Symbols to letters
        $str = preg_replace($this->symbols, $this->chars, $str);
        // Clean spaces
        $str = preg_replace("/[\t\n\r\x0B]/iu", " ", $str);
        $str = preg_replace("/ +/iu", " ", $str);
        // Convert ponctuation
        $str = preg_replace("/[^a-z0-9 ]/iu", "_", $str);
        $str = preg_replace("/_+/iu", "_", $str);
        // Remove 2+ characters
        $str = preg_replace('/(.)\1{2,}/', '$1', $str);
        // To lower case
        $str = mb_strtolower($str);
        // Trim
        $str = trim($str);

        return $str;
    }

    public function getChars()
    {
        return $this->chars;
    }

    public function getSymbols()
    {
        return $this->symbols;
    }

    public function getDictionary()
    {
        return $this->dictionary;
    }

    public function getCache()
    {
        return $this->cache;
    }


    // -------------------------------------------------------------------------
}
