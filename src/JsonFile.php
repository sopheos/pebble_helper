<?php

namespace Pebble\Helpers;

use InvalidArgumentException;

class JsonFile
{
    private static array $instances = [];
    protected array $data = [];

    /**
     * @param string $file
     */
    protected function __construct(string $file = '')
    {
        if (!is_file($file)) {
            throw new InvalidArgumentException("{$file} not found");
        }

        $content = file_get_contents($file);
        $this->data = json_decode($content, true);
    }

    /**
     * Create instance
     *
     * @param string $file
     * @return \static
     */
    public static function create(string $file = '')
    {
        $key = static::class . $file;
        if (!isset(self::$instances[$key])) {
            self::$instances[$key] = new static($file);
        }

        return self::$instances[$key];
    }

    /**
     * Get all data
     *
     * @return array
     */
    public function export(): array
    {
        return $this->data;
    }
}
