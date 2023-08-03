<?php

namespace Pebble\Helpers;

use ReflectionClass;
use ReflectionException;

class PHP
{
    public static function isCallable($value, bool $syntax_only = false, string &$callable_name = null)
    {
        if (is_array($value) && isset($value[0], $value[1]) && is_string($value[0]) && is_string($value[1])) {
            try {
                $class = new ReflectionClass($value[0]);
                $method = $class->getMethod($value[1]);

                if (!$method->isStatic()) {
                    trigger_error("PHP 8.0 {$class->getName()}::{$method->getName()} ne pas être appellé statiquement", E_USER_DEPRECATED);
                }
            } catch (ReflectionException $ex) {
            }
        }


        return $callable_name ? is_callable($value, $syntax_only, $callable_name) : is_callable($value, $syntax_only);
    }
}
