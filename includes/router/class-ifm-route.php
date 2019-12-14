<?php
require('web/class-web.php');
require('api/class-api.php');
require('class-query-vars.php');

/**
 * Facade class for Orchestrating The Web and API Routers
 */
class IfmRoute
{
    public static function register()
    {
        IfmWeb::register();
        IfmApi::register();
        IfmQueryVars::register();
    }
    public static function get($uri, $callback)
    {
        // Default to Routing to the Index Template
        IfmWeb::render($uri, $callback);
        IfmApi::get($uri, $callback);
    }
    public static function render($uri, $callback)
    {
        IfmWeb::render($uri, $callback);
        return __CLASS__;
    }
    public static function json_api($uri, $callback, $method = "WP_REST_Server::READABLE")
    {
        IfmApi::{$method}($uri, $callback);
        return __CLASS__;
    }
    public static function add_query_var($query_var)
    {
        IfmQueryVars::add_var($query_var);
    }
    public static function add_query_vars(array $query_vars)
    {
        IfmQueryVars::add_vars($query_vars);
    }
    public static function permission_callback(string $minimum_level)
    {
        $min_level = $minimum_level;
        return $min_level;
    }
}
