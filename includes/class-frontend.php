<?php
namespace IfmImport;

/**
 * Frontend Pages Handler
 */
class Frontend {

    public function __construct() {
        add_shortcode( 'ifm-importer', [ $this, 'render_frontend' ] );
    }

    /**
     * Render frontend app
     *
     * @param  array $atts
     * @param  string $content
     *
     * @return string
     */
    public function render_frontend( $atts, $content = '' ) {
        wp_enqueue_style( 'ifm-importer-frontend' );
        wp_enqueue_script( 'ifm-importer-frontend' );

        $content .= '<div id="vue-frontend-app"></div>';

        return $content;
    }
}
