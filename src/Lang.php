<?php

namespace Pebble\Helpers;

/**
 * Lang
 *
 * @author mathieu
 */
class Lang
{

    private static $instance = NULL;
    private $files           = [];
    private $lang            = [];

    // -------------------------------------------------------------------------

    /**
     * Get instance
     *
     * @param array $config
     * @return \Pebble\Helpers\Lang
     */
    public static function getInstance()
    {
        if (self::$instance === NULL) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    // -------------------------------------------------------------------------

    /**
     * Add lang files
     *
     * @param string $namespace
     * @param string $files
     * @return \Pebble\Helpers\Lang
     */
    public function load($files)
    {

        $lang = [];

        foreach ($this->_getFiles($files) as $file) {
            if (!isset($this->files[$file])) {
                include_once $file;
                $this->files[$file] = 1;
            }
        }

        if ($lang) {
            $this->lang = $lang + $this->lang;
        }

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Get a value
     *
     * @param string $namespace
     * @param string $key
     * @param mixed $default
     * @return string
     */
    public function get($key)
    {
        return isset($this->lang[$key]) ? $this->lang[$key] : '';
    }

    public function all()
    {
        return $this->lang;
    }

    // -------------------------------------------------------------------------

    /**
     * Get files
     *
     * @param mixed $files
     * @return array
     */
    private function _getFiles($files)
    {
        $_files = [];

        if (is_array($files)) {
            foreach ($files as $file) {
                array_merge($_files, $this->_getGlob($file));
            }
        } else {
            $_files = $this->_getGlob($files);
        }

        return $_files;
    }

    // -------------------------------------------------------------------------

    /**
     * Get files from glob
     *
     * @param string $pattern
     * @return array
     */
    private function _getGlob($pattern)
    {
        if (mb_strpos($pattern, '*') !== FALSE && ($glob = glob($pattern))) {
            return $glob;
        } elseif (is_file($pattern)) {
            return [$pattern];
        }

        return [];
    }

    // -------------------------------------------------------------------------
}

/* End of file */
