<?php

namespace IfmImport;

/**
 * Scripts and Styles Class
 */
class Assets
{

    function __construct()
    {

        if (is_admin()) {
            add_action('admin_enqueue_scripts', [$this, 'register'], 5);
        } else {
            add_action('wp_enqueue_scripts', [$this, 'register'], 5);
        }
    }

    /**
     * Register our app scripts and styles
     *
     * @return void
     */
    public function register()
    {
        $this->register_scripts($this->get_scripts());
        $this->register_styles($this->get_styles());
    }

    /**
     * Register scripts
     *
     * @param  array $scripts
     *
     * @return void
     */
    private function register_scripts($scripts)
    {
        foreach ($scripts as $handle => $script) {
            $deps      = isset($script['deps']) ? $script['deps'] : false;
            $in_footer = isset($script['in_footer']) ? $script['in_footer'] : false;
            $version   = isset($script['version']) ? $script['version'] : IFM_IMPORT_VERSION;

            wp_register_script($handle, $script['src'], $deps, $version, $in_footer);
        }
    }

    /**
     * Register styles
     *
     * @param  array $styles
     *
     * @return void
     */
    public function register_styles($styles)
    {
        foreach ($styles as $handle => $style) {
            $deps = isset($style['deps']) ? $style['deps'] : false;

            wp_register_style($handle, $style['src'], $deps, IFM_IMPORT_VERSION);
        }
    }

    /**
     * Get all registered scripts
     *
     * @return array
     */
    public function get_scripts()
    {
        $prefix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '.min' : '';

        $scripts = [
            'ifm-importer-vendor' => [
                'src'       => IFM_IMPORT_ASSETS . '/js/vendor.js',
                'version'   => filemtime(IFM_IMPORT_PATH . '/assets/js/vendor.js'),
                'in_footer' => true
            ],
            'ifm-importer-frontend' => [
                'src'       => IFM_IMPORT_ASSETS . '/js/frontend.js',
                'deps'      => ['jquery', 'ifm-importer-vendor'],
                'version'   => filemtime(IFM_IMPORT_PATH . '/assets/js/frontend.js'),
                'in_footer' => true
            ],
            'ifm-importer-admin' => [
                'src'       => IFM_IMPORT_ASSETS . '/js/admin.js',
                'deps'      => ['jquery', 'ifm-importer-vendor', 'wp-api'],
                'version'   => filemtime(IFM_IMPORT_PATH . '/assets/js/admin.js'),
                'in_footer' => true
            ]
        ];

        return $scripts;
    }

    /**
     * Get registered styles
     *
     * @return array
     */
    public function get_styles()
    {

        $styles = [
            'ifm-importer-style' => [
                'src' =>  IFM_IMPORT_ASSETS . '/css/style.css'
            ],
            'ifm-importer-frontend' => [
                'src' =>  IFM_IMPORT_ASSETS . '/css/frontend.css'
            ],
            'ifm-importer-admin' => [
                'src' =>  IFM_IMPORT_ASSETS . '/css/admin.css'
            ],
        ];

        return $styles;
    }
}
