<?php

namespace Pebble\Helpers;

/**
 * BBCode
 *
 * @author mathieu
 */
class BBCode
{
    private static $backslashn = "~BACKSLASHN~";
    private $filters = [];

    // -------------------------------------------------------------------------

    /**
     * @param bool $autoinit
     */
    public function __construct($autoinit = true)
    {
        if ($autoinit) {
            $this->init();
        }
    }

    /**
     * @param bool $autoinit
     * @return static
     */
    public static function make($autoinit = true)
    {
        return new static($autoinit);
    }

    // -------------------------------------------------------------------------

    /**
     * BBCode par défaut
     * @return static
     */
    public function init()
    {
        $this->register("strong", "/\[b\](.*)\[\/b\]/iUs", [$this, 'bbStrong']);
        $this->register("italic", "/\[i\](.*)\[\/i\]/iUs", [$this, 'bbItalic']);
        $this->register("code", "/\[code\](.*)\[\/code\]/iUs", [$this, 'bbCode']);
        $this->register("blockquote", "/\[quote\](.*)\[\/quote\]/iUs", [$this, 'bbBlockquote']);
        $this->register("quote", "/\[quote=\"([^\"]+)\"\](.*)\[\/quote\]/iUs", [$this, 'bbQuote']);
        $this->register("size", "/\[size=(\d+)\](.*)\[\/size\]/iUs", [$this, 'bbSize']);
        $this->register("del", "/\[s\](.*)\[\/s\]/iUs", [$this, 'bbDel']);
        $this->register("underline", "/\[u\](.*)\[\/u\]/iUs", [$this, 'bbUnderline']);
        $this->register("center", "/\[center\](.*)\[\/center\]/iUs", [$this, 'bbCenter']);
        $this->register("color", "/\[color=([#a-z0-9]+)\](.*)\[\/color\]/iUs", [$this, 'bbColor']);
        $this->register("email", "/\[email=?([^\]]*)\](.*)\[\/email\]/iUs", [$this, 'bbEmail']);
        $this->register("url", "/\[url=?([^\]]*)\](.*)\[\/url\]/iUs", [$this, 'bbUrl']);
        $this->register("img", "/\[img\](.*)\[\/img\]/iUs", [$this, 'bbImg']);
        $this->register("list", "/\[list\](.*)\[\/list\]/iUs", [$this, 'bbList']);
        $this->register("list_ordered", "/\[list=(1|a)\](.*)\[\/list\]/iUs", [$this, 'bbListOrdered']);

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Register a BBCode
     * If the BBCode already exists, it will be overwritten
     *
     * @param string $name
     * @param string $search
     * @param callable $replace
     * @return static
     */
    public function register($name, $search, callable $replace)
    {
        $this->filters[$name] = ['search' => $search, 'replace' => $replace];

        return $this;
    }

    /**
     * Delete a BBCode
     *
     * @param string $name
     * @return static
     */
    public function unregister($name)
    {
        if (isset($this->filters[$name])) {
            unset($this->filters[$name]);
        }

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Replace le BBCode
     *
     * @param string $str
     * @return string
     */
    public function run($str, $nl2br = false)
    {
        if (!$str) {
            return;
        }

        foreach ($this->filters as $filter) {
            $str = preg_replace_callback($filter['search'], $filter['replace'], $str);
        }

        $str = self::stripBBCode($str);

        if ($nl2br) {
            $str = nl2br($str);
        }

        $str = str_replace(self::$backslashn, "\n", $str);

        return $str;
    }

    // -------------------------------------------------------------------------
    // BBCode
    // -------------------------------------------------------------------------

    public static function stripBBCode($str)
    {
        return trim(preg_replace('|[[\/\!]*?[^\[\]]*?]|si', '', $str));
    }

    /**
     * Replace [b]...[/b] with <strong>...</strong>
     *
     * @param array $match
     * @return type
     */
    public function bbStrong(array $match)
    {
        return "<strong>$match[1]</strong>";
    }

    /**
     * Replace [i]...[/i] with <em>...</em>
     *
     * @param array $match
     * @return type
     */
    public function bbItalic(array $match)
    {
        return "<em>$match[1]</em>";
    }

    /**
     * Replace [code]...[/code] with <pre><code>...</code></pre>
     *
     * @param array $match
     * @return type
     */
    public function bbCode(array $match)
    {
        $text = str_replace("\n", self::$backslashn, $match[1]);

        return "<pre><code>$text</code></pre>";
    }

    /**
     * Replace [quote]...[/quote] with <blockquote><p>...</p></blockquote>
     *
     * @param array $match
     * @return type
     */
    public function bbBlockquote(array $match)
    {
        return "<blockquote><p>$match[1]</p></blockquote>";
    }

    /**
     * Replace [quote="person"]...[/quote] with <blockquote><p>...</p></blockquote>
     *
     * @param array $match
     * @return type
     */
    public function bbQuote(array $match)
    {
        return "$match[1] wrote: <blockquote><p>$match[2]</p></blockquote>";
    }

    /**
     * Replace [size=30]...[/size] with <span style="font-size:30%">...</span>
     *
     * @param array $match
     * @return type
     */
    public function bbSize(array $match)
    {
        return "<span style=\"font-size:$match[1]%\">$match[2]</span>";
    }

    /**
     * Replace [s] with <del>
     *
     * @param array $match
     * @return type
     */
    public function bbDel(array $match)
    {
        return "<del>$match[1]</del>";
    }

    /**
     * Replace [u]...[/u] with <span style="text-decoration:underline;">...</span>
     *
     * @param array $match
     * @return type
     */
    public function bbUnderline(array $match)
    {
        return '<span style="text-decoration:underline;">' . $match[1] . '</span>';
    }

    /**
     * Replace [center]...[/center] with <div style="text-align:center;">...</div>
     *
     * @param array $match
     * @return type
     */
    public function bbCenter(array $match)
    {
        return '<div style="text-align:center;">' . $match[1] . '</div>';
    }

    /**
     * Replace [color=somecolor]...[/color] with <span style="color:somecolor">...</span>
     *
     * @param array $match
     * @return type
     */
    public function bbColor(array $match)
    {
        return '<span style="color:' . $match[1] . ';">' . $match[2] . '</span>';
    }

    /**
     * Replace [email]...[/email] with <a href="mailto:...">...</a>
     * Replace [email=someone@somewhere.com]An e-mail link[/email]
     * with <a href="mailto:someone@somewhere.com">An e-mail link</a>
     *
     * @param array $match
     * @return type
     */
    public function bbEmail(array $match)
    {
        $mailto = $match[1] ? $match[1] : $match[2];
        $label  = $match[2];

        return "<a href=\"mailto:$mailto\">$label</a>";
    }

    /**
     * Replace [url]...[/url] with <a href="...">...</a>
     *
     * @param array $match
     * @return type
     */
    public function bbUrl(array $match)
    {
        if (isset($match[2])) {
            return "<a href=\"$match[1]\">$match[2]</a>";
        }
        return "<a href=\"$match[1]\">$match[1]</a>";
    }

    /**
     * Replace [img]...[/img] with <img src="..."/>
     *
     * @param array $match
     * @return type
     */
    public function bbImg(array $match)
    {
        return "<img src=\"$match[1]\"/>";
    }

    /**
     * Replace [list]...[/list] with <ul><li>...</li></ul>
     *
     * @param array $match
     * @return type
     */
    public function bbList(array $match)
    {
        $li = static::li($match[1]);

        if (!$li) {
            return '';
        }

        return "<ul>" . $li . "</ul>";
    }

    /**
     * Replace [list=1|a]...[/list] with <ul|ol><li>...</li></ul|ol>
     *
     * @param array $match
     * @return type
     */
    public function bbListOrdered(array $match)
    {
        if ($match[1] == '1') {
            $list_type = '<ol>';
        } else if ($match[1] == 'a') {
            $list_type = '<ol style="list-style-type: lower-alpha">';
        } else {
            $list_type = '<ol>';
        }

        $li = static::li($match[2]);

        if (!$li) {
            return '';
        }

        return $list_type . $li . "</ol>";
    }

    protected static function li($text)
    {
        $rows = preg_split('/\n\r?/', $text);
        $out  = "";

        foreach ($rows as $row) {
            if (($row = trim($row))) {
                $out .= "<li>" . $row . "</li>";
            }
        }

        return $out;
    }

    // -------------------------------------------------------------------------

    /**
     * Parse options (opt1|opt2:val2|op3:val3)
     *
     * @param string $str
     * @return array
     */
    protected function parseOptions(string $str)
    {
        $options = [];

        if ($str) {
            $str = trim($str);
        }

        if (!$str) {
            return $options;
        }

        foreach (explode('|', $str) as $option) {
            if (mb_strpos($option, ':')) {
                list($key, $value) = explode(':', $option, 2);
                $options[$key] = $value;
            } else {
                $options[$option] = true;
            }
        }

        return $options;
    }

    /**
     * Convert an associative array to an html attribute string
     *
     * @param array $attr
     * @return string
     */
    protected function htmlAttributes(array $attr, string ...$keys)
    {
        $str = '';

        if (!$attr) {
            return $str;
        }

        $format = ' %s="%s"';

        if ($keys) {
            foreach ($keys as $key) {
                if (isset($attr[$key])) {
                    $str .= sprintf($format, $key, Text::htmlEncode($attr[$key]));
                }
            }
        } else {
            foreach ($attr as $key => $value) {
                if (is_string($key)) {
                    $str .= sprintf($format, $key, Text::htmlEncode($value));
                }
            }
        }

        return $str;
    }

    // -------------------------------------------------------------------------
}
