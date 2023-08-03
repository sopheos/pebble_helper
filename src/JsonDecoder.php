<?php

namespace Pebble\Helpers;

/**
 * JsonDecoder
 *
 * @author mathieu
 */
class JsonDecoder
{

    /**
     * @return static
     */
    public static function make()
    {
        return new static();
    }

    /**
     * @param string $json
     * @param string $classname
     * @return mixed
     */
    public function decode(string $json, string $classname)
    {
        $data = json_decode($json, true);

        if (JSON_ERROR_NONE !== json_last_error()) {
            return null;
        }

        return $this->decodeArray($data, $classname);
    }

    /**
     * @param array $data
     * @param string $classname
     * @return mixed
     */
    public function decodeArray(array $data, string $classname)
    {
        if (!$data) {
            return null;
        }

        $instance = new $classname();

        foreach ($data as $key => $value) {
            $instance->{$key} = $value;
        }

        return $instance;
    }
}
