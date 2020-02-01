<?php

namespace IfmImport;

use function Stringy\create as s;


class VarBuilder
{
    // should match names in services/StringEdits.js
    private static $string_functions = '/^((toLower)|(trim)|(toUpper)|(humanize)|(replace)|(htmlDecode)|(htmlEncode)|(titleize))/';
    private static $string_pattern = '/^\"([\s\S]*?)\"/';
    private static $csv_value_regex = '/{{([\w|\s|-]+?)}}/';
    public static $code = "";

    public function __construct()
    {
    }

    public static function evaluate($var_name, $record, $custom_vars)
    {
        self::$code = "";
        $code = self::get_code($var_name, $custom_vars);
        $code_w_csv = self::get_csv_values($code, $record);
        self::$code = s($code_w_csv);
        return self::parse("");
    }

    private static function get_code($var_name, $custom_vars)
    {
        foreach ($custom_vars as $custom_var) {
            if ((isset($custom_var->id) && $custom_var->id === $var_name)) {
                return sanitize_text_field($custom_var->code);
            }
        }
    }

    public static function get_csv_values($code, $record)
    {
        xdebug_break();

        $csv_values = array();
        preg_match_all(self::$csv_value_regex, $code, $matches, PREG_PATTERN_ORDER);

        foreach ($matches[1] as $index => $csv_value) {
            $csv_values[$matches[0][$index]] = array_key_exists($csv_value, $record) ? $record[$csv_value] : $csv_value;
        }
        foreach ($csv_values as $name => $value) {
            $value = s($value)->replace('"', "'");
            $code = str_replace($name, '"' . $value . '"', $code);
        }
        return $code;
    }

    public static function parse($parsed)
    {

        if (")" == self::$code->first(1)) {

            self::$code = self::$code->substr(1);
            return $parsed;
        } elseif (self::get_func()) {

            $function_name = self::get_func();
            self::$code = self::$code->slice(strlen($function_name) + 1);

            $parsed .= self::$function_name(self::parse(""));
        } elseif (self::get_string(self::$code)) {

            $string = self::get_string(self::$code);
            $parsed .= $string;

            self::$code = self::$code->slice(strlen($string) + 2);
        } elseif (self::$code->startsWith(",")) {

            self::$code = self::$code->substr(1);
            return self::parse($parsed . ",,args,,");
        } else {

            self::$code = self::$code->substr(1);
        }


        if ("" == self::$code) {

            return $parsed;
        } else {

            return self::parse($parsed);
        }
    }

    private static function get_func()
    {
        if (preg_match(self::$string_functions, self::$code)) {
            preg_match(self::$string_functions, self::$code, $matches, PREG_OFFSET_CAPTURE);
            return $matches[0][0];
        } else {
            return false;
        }
    }

    private static function get_string()
    {
        if (preg_match(self::$string_pattern, self::$code, $matches, PREG_OFFSET_CAPTURE)) {
            return s($matches[0][0])->between('"', '"');
        } else {
            return false;
        }
    }

    private static function replace($args)
    {
        $array = s($args)->split(",,args,,");
        if (is_array($array) && count($array) === 3) {
            return s($array[0])->replace($array[1], $array[2]);
        } else {
            return "";
        }
    }
    private static function htmlDecode($string)
    {
        return s($string)->htmlDecode();
    }
    private static function htmlEncode($string)
    {
        return s($string)->htmlEncode();
    }

    private static function titleize($string)
    {
        $ignore = ['at', 'by', 'for', 'in', 'of', 'on', 'out', 'to', 'the'];
        return s($string)->titleize($ignore)->humanize();
    }

    private static function humanize($string)
    {
        return s($string)->humanize();
    }

    private static function trim($variable)
    {
    }
    private static function toLower($string)
    {
        return s($string)->toLowerCase();
    }
    private static function toUpper($string)
    {
        return s($string)->toUpperCase();
    }
}
