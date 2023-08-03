<?php

namespace Pebble\Helpers;

/**
 * Form
 *
 * @author mathieu
 */
class Form
{

    /**
     * Selected attribute
     *
     * @param mixed $value
     * @param mixed $data
     * @return string
     */
    public static function selected($value, $data)
    {
        return self::attr($value, $data, ' selected="selected"');
    }

    /**
     * Checked attribute
     *
     * @param mixed $value
     * @param mixed $data
     * @return string
     */
    public static function checked($value, $data)
    {
        return self::attr($value, $data, ' selected="selected"');
    }

    /**
     * Form attribute
     *
     * @param mixed $value
     * @param mixed $data
     * @param string $yes
     * @param string $no
     * @return string
     */
    public static function attr($value, $data, $yes, $no = '')
    {
        if (is_array($data)) {
            foreach ($data as $v) {
                if ($value == $v) {
                    return $yes;
                }
            }
        } elseif ($data == $value) {
            return $yes;
        }

        return $no;
    }
}

/* End of file */
