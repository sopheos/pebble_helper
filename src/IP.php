<?php

namespace Pebble\Helpers;

/**
 * Classe de manipulation des adresse IP
 * Supporte l'IPv4 et l'IPv6
 * Cette classe remplace les fonctionnalités ip2long et long2ip
 *
 * @author mathieu
 */
class IP
{

    private $ip_bin = '0';
    private $ip_hex = '0';
    private $ip_str = '0.0.0.0';
    private $v6     = false;

    // -------------------------------------------------------------------------
    // Constructor
    // -------------------------------------------------------------------------

    public function __construct(string $ip, string $from)
    {
        if ($from === 'str') {
            $this->initStr($ip);
        } elseif ($from === 'hex') {
            $this->initHex($ip);
        } elseif ($from === 'bin') {
            $this->initBin($ip);
        }
    }

    // -------------------------------------------------------------------------
    // Factory
    // -------------------------------------------------------------------------

    /**
     * @param string $ip
     * @return static
     */
    public static function fromString(string $ip)
    {
        return new static($ip, 'str');
    }

    /**
     * @param string $ip
     * @return static
     */
    public static function fromBin(string $ip)
    {
        return new static($ip, 'bin');
    }

    /**
     * @param string $ip
     * @return static
     */
    public static function fromHex(string $ip)
    {
        return new static($ip, 'hex');
    }

    // -------------------------------------------------------------------------
    // Getter
    // -------------------------------------------------------------------------

    /**
     * The current IP is an IPv6
     *
     * @return boolean
     */
    public function isIPv6()
    {
        return $this->v6;
    }

    /**
     * The current IP is an IPv4
     *
     * @return boolean
     */
    public function isIPv4()
    {
        return !$this->v6;
    }

    /**
     * Returns the IP to the string format
     *
     * @return string
     */
    public function toString()
    {
        return $this->ip_str;
    }

    /**
     * Returns the IP to the binary format
     *
     * @return string
     */
    public function toBin()
    {
        return $this->ip_bin;
    }

    /**
     * Returns the IP to the hexadecimal format
     *
     * @return string
     */
    public function toHex()
    {
        return $this->ip_hex;
    }

    // -------------------------------------------------------------------------
    // Init
    // -------------------------------------------------------------------------

    /**
     * @param string $ip
     */
    protected function initBin(string $ip)
    {
        if (self::isIPBin($ip)) {
            $this->v6     = self::isIPv6Bin($ip, false);
            $this->ip_bin = $ip;
            $this->ip_hex = self::binToHex($ip, $this->v6);
            $this->ip_str = self::binToStr($ip, $this->v6);
        }
    }

    /**
     * @param string $ip
     */
    protected function initHex(string $ip)
    {
        if (self::isIPHex($ip)) {
            $this->v6     = self::isIPv6Hex($ip, false);
            $this->ip_bin = self::hexToBin($ip, $this->v6);
            $this->ip_hex = $ip;
            $this->ip_str = self::binToStr($this->ip_bin, $this->v6);
        }
    }

    /**
     * @param string $ip
     */
    protected function initStr(string $ip)
    {
        if (self::isIPStr($ip)) {
            $this->v6     = self::isIPv6Str($ip, false);
            $this->ip_bin = self::strToBin($ip, $this->v6);
            $this->ip_hex = self::binToHex($this->ip_bin, $this->v6);
            $this->ip_str = $ip;
        }
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    /**
     * Test an IP from binary format
     *
     * @param string $ip
     * @return boolean
     */
    public static function isIPBin(string $ip)
    {
        return preg_match('#^[01]{0,128}$#', $ip) === 1;
    }

    /**
     * Test an IPv6 from binary format
     * @param string $ip
     * @return boolean
     */
    public static function isIPv6Bin(string $ip)
    {
        return mb_strlen($ip) === 128;
    }

    /**
     * Test an IP from hexadecimal format
     *
     * @param string $ip
     * @return boolean
     */
    public static function isIPHex(string $ip)
    {
        return preg_match('#^[0-9a-fA-F]{0,32}$#', $ip) === 1;
    }

    /**
     * Test an IPv6 from hexadecimal format
     *
     * @param string $ip
     * @return boolean
     */
    public static function isIPv6Hex(string $ip)
    {
        return mb_strlen($ip) === 32;
    }

    /**
     * Test an IP from string format
     *
     * @param string $ip
     * @return boolean
     */
    public static function isIPStr(string $ip)
    {
        return filter_var($ip, FILTER_VALIDATE_IP) !== false;
    }

    /**
     * Test an IPv6 from string format
     *
     * @param string $ip
     * @return boolean
     */
    public static function isIPv6Str(string $ip)
    {
        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) !== false;
    }

    /**
     * @param string $ip
     * @param bool $v6
     * @return string
     */
    public static function binToHex(string $ip, bool $v6 = false)
    {
        if (!$v6) {
            return base_convert($ip, 2, 16);
        }

        $out = '';
        for ($i = 0; $i < 2; $i++) {
            $chr = mb_substr($ip, $i * 64, 64);

            $hex = base_convert($chr, 2, 16);
            $pad = 16 - mb_strlen($hex);
            if ($pad) {
                $hex = str_repeat('0', $pad) . $hex;
            }

            $out .= $hex;
        }

        return $out;
    }

    /**
     * @param string $ip
     * @param bool $v6
     * @return string
     */
    public static function binToStr(string $ip, bool $v6 = false)
    {
        if (!$v6) {
            return long2ip(base_convert($ip, 2, 10));
        }

        $bin = $ip;
        $pad = 128 - mb_strlen($bin);
        for ($i = 1; $i <= $pad; $i++) {
            $bin = "0" . $bin;
        }

        $bits = 0;
        $ipv6 = '';
        while ($bits <= 7) {
            $bin_part = mb_substr($bin, ($bits * 16), 16);
            $ipv6     .= dechex(bindec($bin_part)) . ":";
            $bits++;
        }

        return inet_ntop(inet_pton(mb_substr($ipv6, 0, -1)));
    }

    /**
     * @param string $ip
     * @param bool $v6
     * @return string
     */
    public static function hexToBin(string $ip, bool $v6 = false)
    {
        if (!$v6) {
            return base_convert($ip, 16, 2);
        }

        $out = '';
        for ($i = 0; $i < 2; $i++) {
            $chr = mb_substr($ip, $i * 16, 16);

            $bin = base_convert($chr, 16, 2);
            $pad = 64 - mb_strlen($bin);
            if ($pad) {
                $bin = str_repeat('0', $pad) . $bin;
            }

            $out .= $bin;
        }

        return $out;
    }

    /**
     * @param string $ip
     * @param bool $v6
     * @return string
     */
    public static function strToBin(string $ip, bool $v6 = false)
    {
        if (!$v6) {
            return base_convert(ip2long($ip), 10, 2);
        }

        if (($ip_n = inet_pton($ip)) !== false) {
            $bits = 15; // 16 x 8 bit = 128bit (ipv6)
            $out  = '';

            while ($bits >= 0) {
                $bin = sprintf("%08b", (ord($ip_n[$bits])));
                $out = $bin . $out;
                $bits--;
            }

            return $out;
        }

        return '0';
    }

    // -------------------------------------------------------------------------
}

/* End of file */
