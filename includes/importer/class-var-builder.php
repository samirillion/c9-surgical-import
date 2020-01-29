<?php

namespace IfmImport;

use function Stringy\create as s;


class VarBuilder
{
    // should match names in services/StringEdits.js
    private static $string_functions = '/^(toLower)|(trim)|(toUpper)|(capitalize)|(replace)/';
    private static $string_pattern = '/^\".+?\"/';

    public function __construct()
    {
    }

    public static function evaluate($var_name, $record, $custom_vars)
    {
        $code = self::get_code($var_name, $custom_vars);
        $code_w_csv = self::get_csv_values($code, $record);
        $variable = self::parse("", $code_w_csv);
        return $var_name;
    }

    private static function get_code($var_name, $custom_vars)
    {
        foreach ($custom_vars as $custom_var) {
            if ((isset($custom_var->id) && $custom_var->id === $var_name)) {
                return sanitize_text_field($custom_var->code);
            }
        }
    }

    private static function get_csv_values($code, $record)
    {
        $csv_values = array();
        preg_match_all('/{{(\w+)}}/', $code, $matches, PREG_PATTERN_ORDER);

        foreach ($matches[1] as $index => $csv_value) {
            $csv_values[$matches[0][$index]] = array_key_exists($csv_value, $record) ? $record[$csv_value] : $csv_value;
        }
        foreach ($csv_values as $name => $value) {
            $value = s($value)->replace('"', "'");
            $code = str_replace($name, '"' . $value . '"', $code);
        }
        return $code;
    }

    private static function parse($parsed, $code)
    {
        // if is function, process args
        xdebug_break();
        if (self::get_func($code)) {
            $function_name = self::get_func($code);
            $code = s($code)->slice(strlen($function_name));

            return self::$function_name(self::parse("", $code));
        }

        if (self::get_string($code)) {
            $parsed .= self::get_string($code);
        }

        if ("" === $code) {
            return $parsed;
        }

        return self::parse($parsed, $code->substr(1));
    }

    private static function get_func($code)
    {
        if (preg_match(self::$string_functions, $code)) {
            preg_match(self::$string_functions, $code, $matches, PREG_OFFSET_CAPTURE);
            return $matches[0][0];
        } else {
            return false;
        }
    }

    private static function get_string($code)
    {
        if (preg_match(self::$string_pattern, $code, $matches, PREG_OFFSET_CAPTURE)) {
            return $matches[0][0];
        } else {
            return false;
        }
    }

    private static function humanize($code)
    {
        return s(self::get_string($code))->humanize();
    }
    private static function whitespace($variable)
    {
    }

    private static function evaluateCsv($value)
    {
    }
    private static function trim($variable)
    {
    }
    private static function toLower($variable)
    {
    }
    private static function toUpper($variable)
    {
    }
    private static function replace($variable)
    {
    }
}
