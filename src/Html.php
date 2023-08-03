<?php

namespace Pebble\Helpers;

/**
 * Html
 *
 * @author Mathieu
 */
class Html
{

    const SELF_CLOSING = [
        'area', 'base', 'br', 'col', 'embed', 'hr', 'img', 'input',
        'keygen', 'link', 'meta', 'param', 'source', 'track', 'wbr'
    ];

    protected $tag  = 'div';
    protected $attr = [];
    protected $text = '';

    // -------------------------------------------------------------------------
    // Construct
    // -------------------------------------------------------------------------

    /**
     * @param string $tag
     */
    public function __construct(string $tag = 'div')
    {
        $this->tag($tag);
    }

    /**
     * @param string $tag
     * @return static
     */
    public static function create(string $tag = 'div')
    {
        return new static($tag);
    }

    // -------------------------------------------------------------------------
    // Setter
    // -------------------------------------------------------------------------

    /**
     * @param string $value
     * @return static
     */
    public function tag(string $value)
    {
        $this->tag = $value;
        return $this;
    }

    /**
     * @param array $value
     * @return static
     */
    public function attrs(array $value)
    {
        $this->attr = $value;
        return $this;
    }

    /**
     * @param string $key
     * @param string $value
     * @return static
     */
    public function attr(string $key, $value)
    {
        $this->attr[$key] = $value;
        return $this;
    }

    /**
     * @param string $value
     * @return static
     */
    public function text($value)
    {
        $this->text = $value;
        return $this;
    }

    /**
     * @param string $value
     * @return static
     */
    public function id($value)
    {
        return $this->attr('id', $value);
    }

    /**
     * @param string $value
     * @return static
     */
    public function classname($value)
    {
        return $this->attr('class', $value);
    }

    /**
     * @param string $key
     * @param string $value
     * @return static
     */
    public function data(string $key, $value)
    {
        return $this->attr('data-' . $key, $value);
    }

    /**
     * @param string $value
     * @return static
     */
    public function title($value)
    {
        return $this->attr('title', $value);
    }

    /**
     * @param string $value
     * @return static
     */
    public function name($value)
    {
        return $this->attr('name', $value);
    }

    /**
     * @param string $value
     * @return static
     */
    public function src($value)
    {
        return $this->attr('src', $value);
    }

    /**
     * @param string $value
     * @return static
     */
    public function href($value)
    {
        return $this->attr('href', $value);
    }

    // -------------------------------------------------------------------------
    // Getter
    // -------------------------------------------------------------------------

    /**
     * @return string
     */
    public function toString(): string
    {
        $close = !in_array($this->tag, self::SELF_CLOSING);
        $attr  = self::attrToString($this->attr);

        $out = '<' . $this->tag . $attr . ($close ? '>' : '/>');

        if ($this->text) {

            if (!$close) {
                $out .= ' ';
            }

            $out .= $this->text;
        }

        if ($close) {
            $out .= '</' . $this->tag . '>';
        }

        return $out;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    /**
     * Convert an array to a string of html attributes
     *
     * @param array $attributes
     * @return string
     */
    public static function attrToString($attributes)
    {
        $attr = '';
        foreach ($attributes as $key => $val) {
            $attr .= " {$key}=\"{$val}\"";
        }
        return $attr;
    }

    // -------------------------------------------------------------------------
}

/* EOF */
