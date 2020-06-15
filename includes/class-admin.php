<?php

namespace IfmImport;

/**
 * Admin Pages Handler
 */
class Admin
{

    public function __construct()
    {
        add_action('admin_menu', [$this, 'admin_menu']);
    }

    /**
     * Register our menu page
     *
     * @return void
     */
    public function admin_menu()
    {
        global $submenu;

        $capability = 'manage_options';
        $slug       = 'ifm-importer';

        $hook = add_menu_page(__('Ifm Importer', 'textdomain'), __('Ifm Importer', 'textdomain'), $capability, $slug, [$this, 'plugin_page'], 'dashicons-networking');

        if (current_user_can($capability)) {
            $submenu[$slug][] = array(__('Import', 'textdomain'), $capability, 'admin.php?page=' . $slug . '#/');
            // $submenu[$slug][] = array(__('Settings', 'textdomain'), $capability, 'admin.php?page=' . $slug . '#/settings');
        }

        add_action('load-' . $hook, [$this, 'init_hooks']);
    }

    /**
     * Initialize our hooks for the admin page
     *
     * @return void
     */
    public function init_hooks()
    {
        add_action('admin_enqueue_scripts', [$this, 'localize_scripts']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
    }

    /**
     * Localize scripts
     *
     * @return void
     */
    public function localize_scripts()
    {
        wp_localize_script('ifm-importer-admin', 'WP_API_Settings', array(
            'root' => esc_url_raw(rest_url()),
            'nonce' => wp_create_nonce('wp_rest')
        ));
    }

    /**
     * Load scripts and styles for the app
     *
     * @return void
     */
    public function enqueue_scripts()
    {
        wp_enqueue_style('ifm-importer-style');
        wp_enqueue_script('ifm-importer-admin');
    }

    /**
     * Render our admin page
     *
     * @return void
     */
    public function plugin_page()
    {
        echo '<div class="wrap"><div id="vue-admin-app"></div></div>';
    }
}
