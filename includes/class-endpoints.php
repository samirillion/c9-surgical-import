<?php

namespace IfmImport;

require_once('class-import.php');

/**
 * REST_API Handler
 */
class ENDPOINTS
{

    public function __construct()
    {
        $this->namespace = 'ifm/importer/v1';
        add_action('rest_api_init', array($this, 'register_endpoints'));
    }

    public function register_endpoints()
    {
        register_rest_route($this->namespace, '/run', array(
            'methods' => 'GET',
            'callback' => [new Import, 'run'],
            'permission_callback' => [$this, 'is_admin'],
            'validate_callback' => function ($param, $request, $key) {
                // ifm_tasklist_add_validation_logic
                return true;
            }
        ));

        register_rest_route($this->namespace, '/methods', array(
            'methods' => 'GET',
            'callback' => [new Import, 'get_available_methods'],
        ));

        register_rest_route($this->namespace, '/getcsv', array(
            'methods' => 'GET',
            'callback' => [new Import, 'get_csv']
        ));

        register_rest_route($this->namespace, '/import-posts', array(
            'methods' => 'GET',
            'callback' => [new Import, 'run_import']
        ));

        register_rest_route($this->namespace, '/download-image', array(
            'methods' => 'GET',
            'callback' => [new Import, 'download_image']
        ));

        register_rest_route($this->namespace, '/returnparsedcsv/(?P<id>\d+)', array(
            'methods' => 'GET',
            'callback' => [$this, 'get_and_parse_csv'],
            'args' => array(
                'id' => array(
                    'validate_callback' => function ($param, $request, $key) {
                        return is_numeric($param);
                    }
                ),
            ),
        ));

        register_rest_route($this->namespace, '/get-post-types', array(
            'methods' => 'GET',
            'callback' => [$this, 'get_post_types'],
        ));

        register_rest_route($this->namespace, '/get-acf-fields', array(
            'methods' => 'GET',
            'callback' => [$this, 'get_acf_fields'],
        ));

        register_rest_route($this->namespace, '/get-taxonomies', array(
            'methods' => 'GET',
            'callback' => [$this, 'get_taxonomies'],
        ));
    }

    public function is_admin($request)
    {
        return current_user_can('manage_options');
    }

    public function get_and_parse_csv($attachment_id)
    {
    }

    public function get_post_types()
    {
        return get_post_types(
            array(
                'public'   => true
            )
        );
    }

    public function get_taxonomies()
    {
        global $wpdb;
        $results = $wpdb->get_results("SELECT DISTINCT taxonomy FROM {$wpdb->prefix}term_taxonomy");
        return $results;
    }

    public function get_acf_fields()
    {
        $options = array();
        $field_groups = acf_get_field_groups();
        foreach ($field_groups as $group) {
            // DO NOT USE here: $fields = acf_get_fields($group['key']);
            // because it causes repeater field bugs and returns "trashed" fields
            $fields = get_posts(array(
                'posts_per_page'   => -1,
                'post_type'        => 'acf-field',
                'orderby'          => 'menu_order',
                'order'            => 'ASC',
                'suppress_filters' => true, // DO NOT allow WPML to modify the query
                'post_parent'      => $group['ID'],
                'post_status'      => 'any',
                'update_post_meta_cache' => false
            ));
            foreach ($fields as $field) {
                $options[$field->post_title] = $field->post_name;
            }
        }

        return $options;
    }
}
