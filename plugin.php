<?php
 /*
Plugin Name: Cortex Import Plugin
Plugin URI: https://example.com/
Description: Plugin for importing things
Version: 0.1
Author: Your Name
Author URI: https://covertnine.com/
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: corteximporter
Domain Path: /languages
*/

/**
 * Copyright (c) YEAR Your Name (email: Email). All rights reserved.
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 * **********************************************************************
 */

// don't call the file directly
if (!defined('ABSPATH')) exit;

/**
 * C9_Importer_Plugin class
 *
 * @class C9_Importer_Plugin The class that holds the entire C9_Importer_Plugin plugin
 */
final class C9_Importer_Plugin
{

    /**
     * Plugin version
     *
     * @var string
     */
    public $version = '0.1.0';

    /**
     * Plugin Name
     *
     * @var string
     */
    public $varname = 'c9importer';

    /**
     * Holds various class instances
     *
     * @var array
     */
    private $container = array();

    /**
     * Constructor for the C9_Importer_Plugin class
     *
     * Sets up all the appropriate hooks and actions
     * within our plugin.
     */
    public function __construct()
    {

        $this->define_constants();

        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));

        add_action('plugins_loaded', array($this, 'init_plugin'));
    }

    /**
     * Initializes the C9_Importer_Plugin() class
     *
     * Checks for an existing C9_Importer_Plugin() instance
     * and if it doesn't find one, creates it.
     */
    public static function init()
    {
        static $instance = false;

        if (!$instance) {
            $instance = new C9_Importer_Plugin();
        }

        return $instance;
    }

    /**
     * Magic getter to bypass referencing plugin.
     *
     * @param $prop
     *
     * @return mixed
     */
    public function __get($prop)
    {
        if (array_key_exists($prop, $this->container)) {
            return $this->container[$prop];
        }

        return $this->{$prop};
    }

    /**
     * Magic isset to bypass referencing plugin.
     *
     * @param $prop
     *
     * @return mixed
     */
    public function __isset($prop)
    {
        return isset($this->{$prop}) || isset($this->container[$prop]);
    }

    /**
     * Define the constants
     *
     * @return void
     */
    public function define_constants()
    {
        define('C9IMPORTER_VERSION', $this->version);
        define('C9IMPORTER_FILE', __FILE__);
        define('C9IMPORTER_PATH', dirname(C9IMPORTER_FILE));
        define('C9IMPORTER_INCLUDES', C9IMPORTER_PATH . '/includes');
        define('C9IMPORTER_URL', plugins_url('', C9IMPORTER_FILE));
        define('C9IMPORTER_ASSETS', C9IMPORTER_URL . '/assets');
        define('C9IMPORTER_VAR_NAME', $this->varname);
    }

    /**
     * Load the plugin after all plugis are loaded
     *
     * @return void
     */
    public function init_plugin()
    {
        $this->includes();
        $this->init_hooks();
    }

    /**
     * Placeholder for activation function
     *
     * Nothing being called here yet.
     */
    public function activate()
    {

        $installed = get_option('c9importer_installed');

        if (!$installed) {
            update_option('c9importer_installed', time());
        }

        update_option('c9importer_version', C9IMPORTER_VERSION);
    }

    /**
     * Placeholder for deactivation function
     *
     * Nothing being called here yet.
     */
    public function deactivate()
    { }

    /**
     * Include the required files
     *
     * @return void
     */
    public function includes()
    {

        require_once C9IMPORTER_INCLUDES . '/class-assets.php';
        require_once C9IMPORTER_INCLUDES . '/class-endpoints.php';

        if ($this->is_request('admin')) {
            require_once C9IMPORTER_INCLUDES . '/class-admin.php';
        }

        if ($this->is_request('frontend')) {
            require_once C9IMPORTER_INCLUDES . '/class-frontend.php';
        }

        if ($this->is_request('ajax')) {
            // require_once C9IMPORTER_INCLUDES . '/class-ajax.php';
        }

        if ($this->is_request('rest')) {
            require_once C9IMPORTER_INCLUDES . '/class-rest-api.php';
        }
    }

    /**
     * Initialize the hooks
     *
     * @return void
     */
    public function init_hooks()
    {

        add_action('init', array($this, 'init_classes'));

        // Localize our plugin
        add_action('init', array($this, 'localization_setup'));
    }

    /**
     * Instantiate the required classes
     *
     * @return void
     */
    public function init_classes()
    {

        if ($this->is_request('admin')) {
            $this->container['admin'] = new C9Import\Admin();
        }

        if ($this->is_request('frontend')) {
            $this->container['frontend'] = new C9Import\Frontend();
        }

        if ($this->is_request('ajax')) {
            // $this->container['ajax'] =  new C9Import\Ajax();
        }

        if ($this->is_request('rest')) {
            $this->container['rest'] = new C9Import\REST_API();
        }

        $this->container['assets'] = new C9Import\Assets();
        $this->container['endpoints'] = new C9Import\Endpoints();
    }

    /**
     * Initialize plugin for localization
     *
     * @uses load_plugin_textdomain()
     */
    public function localization_setup()
    {
        load_plugin_textdomain('c9importer', false, dirname(plugin_basename(__FILE__)) . '/languages/');
    }

    /**
     * What type of request is this?
     *
     * @param  string $type admin, ajax, cron or frontend.
     *
     * @return bool
     */
    private function is_request($type)
    {
        switch ($type) {
            case 'admin':
                return is_admin();

            case 'ajax':
                return defined('DOING_AJAX');

            case 'rest':
                return defined('REST_REQUEST');

            case 'cron':
                return defined('DOING_CRON');

            case 'frontend':
                return (!is_admin() || defined('DOING_AJAX')) && !defined('DOING_CRON');
        }
    }
} // C9_Importer_Plugin

$c9importer = C9_Importer_Plugin::init();
