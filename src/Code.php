<?php

namespace Pebble\Helpers;

/**
 * Code Class
 */
class Code
{

    /**
     * Convert error constants to string
     *
     * @param int $err
     * @return string
     */
    public static function errorToString($err)
    {

        if ($err === E_PARSE) {
            return 'Parse error';
        }

        if ($err === E_STRICT) {
            return 'Strict Standards';
        }

        if ($err === E_NOTICE || $err === E_USER_NOTICE) {
            return 'Notice';
        }

        if ($err === E_DEPRECATED || $err === E_USER_DEPRECATED) {
            return 'Deprecated';
        }

        if ($err === E_WARNING || $err === E_COMPILE_WARNING || $err === E_CORE_WARNING || $err === E_USER_WARNING) {
            return 'Warning';
        }

        if ($err === E_ERROR || $err === E_COMPILE_ERROR || $err === E_CORE_ERROR || $err === E_RECOVERABLE_ERROR || $err === E_USER_ERROR) {
            return 'Fatal error';
        }

        return $err;
    }

    // -------------------------------------------------------------------------

    /**
     * Convert a backtrace to a string
     *
     * @param array $backtrace
     * @param bool $protectArgs
     * @return string
     */
    public static function backtraceToString($backtrace = NULL, $protectArgs = FALSE)
    {

        // Backtrace not given
        if ($backtrace === NULL) {
            $backtrace = debug_backtrace();
            if ($backtrace) {
                array_shift($backtrace);
            }
        }

        $trace = [];
        foreach ($backtrace as $k => $v) {
            $msg = "";

            // An object
            if (isset($v['class']) && $v['class']) {
                $msg .= $v['class'];

                if (isset($v['type']) && $v['type']) {
                    $msg .= $v['type'];
                } else {
                    $msg .= " ";
                }
            }

            // Function or method
            if (isset($v['function']) && $v['function']) {
                $msg .= $v['function'] . '(';

                // Arguments
                if (isset($v['args']) && $v['args']) {
                    $args = [];
                    foreach ($v['args'] as $arg) {
                        if (is_array($arg)) {
                            $args[] = 'Array';
                        } elseif (is_object($arg)) {
                            $args[] = 'Object : ' . get_class($arg);
                        } elseif (is_resource($arg)) {
                            $args[] = 'Resource : ' . get_resource_type($arg);
                        } else {
                            $args[] = $protectArgs && is_string($arg) ? Text::htmlEncode($arg) : $arg;
                        }
                    }

                    $msg .= implode(', ', $args);
                }

                $msg .= ')';
            }

            if (isset($v['file']) && isset($v['line'])) {
                $trace[] = sprintf("\r\n#%s %s(%s): %s", $k, $v['file'], $v['line'], $msg);
            } else {
                $trace[] = sprintf("\r\n#%s: %s", $k, $msg);
            }
        }

        return implode("
", $trace);
    }

    // -------------------------------------------------------------------------

    /**
     * Write array
     */
    public static function writeArray($data, $varname, $dept = 0)
    {
        $out = '';

        if ($dept === 0) {
            $out .= "\${$varname} = [";
        }

        if (is_array($data)) {
            foreach ($data as $k => $v) {
                $key = is_int($k) ? $k : "'" . str_replace("'", "\'", $k) . "'";
                $sep = "
" . str_repeat("    ", ($dept + 1));

                $values = self::writeArray($v, $varname, $dept + 1);
                if (is_array($v)) {
                    $out .= $sep . "$key => [{$values}";
                    $out .= $sep . "],";
                } else {
                    $out .= $sep . "$key => {$values},";
                }
            }
        } elseif (is_int($data)) {
            $out .= $data;
        } elseif ($data === true) {
            $out .= "true";
        } elseif ($data === false) {
            $out .= "false";
        } else {
            $data = str_replace('"', '\"', $data);
            $out .= "\"{$data}\"";
        }

        if ($dept === 0) {
            $out = rtrim($out, ', ');
            $out .= "
" . "];" . "
" . "
";
        }

        return rtrim($out, ', ');
    }

    // -------------------------------------------------------------------------

    public static function writeObject($data, $varname)
    {
        return self::writeArray(json_decode(json_encode($data), TRUE), $varname);
    }

    // -------------------------------------------------------------------------
}

/* End of file */
