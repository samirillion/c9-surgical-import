<?php
namespace C9Import;

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
        $slug       = 'cortex-importer';

        $hook = add_menu_page(__('Cortex Importer', 'textdomain'), __('Cortex Importer', 'textdomain'), $capability, $slug, [$this, 'plugin_page'], 'dashicons-text');

        if (current_user_can($capability)) {
            $submenu[$slug][] = array(__('Import', 'textdomain'), $capability, 'admin.php?page=' . $slug . '#/');
            $submenu[$slug][] = array(__('Settings', 'textdomain'), $capability, 'admin.php?page=' . $slug . '#/settings');
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
        wp_localize_script('c9importer-admin', 'WP_API_Settings', array(
            // 'root' => esc_url_raw( rest_url() ),
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
        wp_enqueue_style('c9importer-admin');
        wp_enqueue_script('c9importer-admin');
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

    // /**
    //  * Register Rest Endpoints
    //  *
    //  * @param  array $endpoints
    //  *
    //  * @return void
    //  */
    // public function register_rest_routes()
    // {
    //     register_rest_route('c9importer/v1', '/author/(?P<id>\d+)', array(
    //         'methods' => 'GET',
    //         'callback' => $this->my_awesome_func
    //     ));
    // }
}
