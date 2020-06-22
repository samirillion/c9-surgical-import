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
            'validate_callback' => function ($param, $request, $key) {
                // ifm_tasklist_add_validation_logic
                return current_user_can('manage_options');
            }
        ));

        register_rest_route('ifm/importer/v1', '/get-progress', array(
            'methods' => 'GET',
            'callback' => [new Import, 'get_progress']
        ));

        register_rest_route($this->namespace, '/methods', array(
            'methods' => 'GET',
            'callback' => [new Import, 'get_available_methods'],
        ));

        register_rest_route($this->namespace, '/getcsv', array(
            'methods' => 'GET',
            'callback' => [new Import, 'get_csv']
        ));

        register_rest_route($this->namespace, '/stage-csv', array(
            'methods' => 'GET',
            'callback' => [new Import, 'stage_csv'],
            'permission_callback' => [$this, 'is_admin']
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
        register_rest_route($this->namespace, '/get-params', array(
            'methods' => 'GET',
            'callback' => [$this, 'get_params'],
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

        register_rest_route($this->namespace, '/preview-custom-var', array(
            'methods' => 'GET',
            'callback' => [new Import, 'preview_custom_var'],
            'permission_callback' => [$this, 'is_admin']
        ));
    }

    public function is_admin($request)
    {
        return current_user_can('manage_options');
    }

    public function get_params()
    {
        $params = array();
        $params['postTypes'] = $this->get_post_types();
        $params['taxonomies'] = $this->get_taxonomies();
        $params['acfFields'] = $this->get_acf_fields();

        return $params;
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
        $tax_obj = array();
        global $wpdb;
        $results = $wpdb->get_results("SELECT DISTINCT taxonomy FROM {$wpdb->prefix}term_taxonomy");
        foreach ($results as $result) {
            $taxonomy = $result->taxonomy;
            $tax_obj[$taxonomy] = $taxonomy;
        }
        return $tax_obj;
    }

    public function get_acf_fields()
    {
        $options = array();
        $field_groups = function_exists('acf_get_field_groups') ? \acf_get_field_groups() : array();
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
                $options[$field->post_name] = $field->post_title;
            }
        }

        return $options;
    }
}
