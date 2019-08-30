<?php
namespace C9Import;

/**
 * Frontend Pages Handler
 */
class Frontend {

    public function __construct() {
        add_shortcode( 'cortex-importer', [ $this, 'render_frontend' ] );
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
        wp_enqueue_style( 'c9importer-frontend' );
        wp_enqueue_script( 'c9importer-frontend' );

        $content .= '<div id="vue-frontend-app"></div>';

        return $content;
    }
}
