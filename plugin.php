<?php
/*
Plugin Name: IFM Surgical Import
Plugin URI: https://github.com/ifm-importer
Description: Upload complex, interrelated data with this very powerful Importer plugin.
Version: 1.0.0
Author: Sam Gates
Author URI: https://idealforum.org/
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: ifm-importer
Domain Path: /languages
*/

/**
 * Copyright (c) 2019 Sam Gates (email: samirillion@protonmail.com). All rights reserved.
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
 * IFM_Importer_Plugin class
 *
 * @class IFM_Importer_Plugin The class that holds the entire IFM_Importer_Plugin plugin
 */
final class IFM_Importer_Plugin
{

    /**
     * Plugin version
     *
     * @var string
     */
    public $version = '0.1.1';

    /**
     * Plugin Name
     *
     * @var string
     */
    public $varname = 'ifm-importer';

    /**
     * Holds various class instances
     *
     * @var array
     */
    private $container = array();

    /**
     * Constructor for the IFM_Importer_Plugin class
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
     * Initializes the IFM_Importer_Plugin() class
     *
     * Checks for an existing IFM_Importer_Plugin() instance
     * and if it doesn't find one, creates it.
     */
    public static function init()
    {
        static $instance = false;

        if (!$instance) {
            $instance = new IFM_Importer_Plugin();
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
        define('IFM_IMPORT_VERSION', $this->version);
        define('IFM_IMPORT_FILE', __FILE__);
        define('IFM_IMPORT_PATH', dirname(IFM_IMPORT_FILE));
        define('IFM_IMPORT_INCLUDES', IFM_IMPORT_PATH . '/includes');
        define('IFM_IMPORT_VENDOR', IFM_IMPORT_PATH . '/vendor');
        define('IFM_IMPORT_URL', plugins_url('', IFM_IMPORT_FILE));
        define('IFM_IMPORT_ASSETS', IFM_IMPORT_URL . '/assets');
        define('IFM_IMPORT_VAR_NAME', $this->varname);
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

        $installed = get_option('ifm_importer_installed');

        if (!$installed) {
            update_option('ifm_importer_installed', time());
        }

        update_option('ifm_importer_version', IFM_IMPORT_VERSION);
    }

    /**
     * Placeholder for deactivation function
     *
     * Nothing being called here yet.
     */
    public function deactivate()
    {
    }

    /**
     * Include the required files
     *
     * @return void
     */
    public function includes()
    {
        require_once IFM_IMPORT_VENDOR . '/autoload.php';

        require_once IFM_IMPORT_INCLUDES . '/class-assets.php';
        require_once IFM_IMPORT_INCLUDES . '/class-endpoints.php';

        if ($this->is_request('admin')) {
            require_once IFM_IMPORT_INCLUDES . '/class-admin.php';
        }

        // if ($this->is_request('ajax')) {
        //     require_once IFM_IMPORT_INCLUDES . '/class-ajax.php';
        // }

        if ($this->is_request('rest')) {
            require_once IFM_IMPORT_INCLUDES . '/class-rest-api.php';
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
            $this->container['admin'] = new IfmImport\Admin();
        }

        // if ($this->is_request('ajax')) {
        //     $this->container['ajax'] =  new IfmImport\Ajax();
        // }

        // if ($this->is_request('rest')) {
        //     $this->container['rest'] = new IfmImport\REST_API();
        // }

        $this->container['Import'] = new IfmImport\Import();


        $this->container['assets'] = new IfmImport\Assets();
        $this->container['endpoints'] = new IfmImport\Endpoints();
    }

    /**
     * Initialize plugin for localization
     *
     * @uses load_plugin_textdomain()
     */
    public function localization_setup()
    {
        load_plugin_textdomain('ifm-importer', false, dirname(plugin_basename(__FILE__)) . '/languages/');
    }

    /**
     * What type of request is this?
     *
     * @param  string $type admin, ajax, or
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
        }
    }
} // IFM_Importer_Plugin

IFM_Importer_Plugin::init();
