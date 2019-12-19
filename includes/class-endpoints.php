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
        register_rest_route($this->namespace, '/author/(?P<id>\d+)', array(
            'methods' => 'GET',
            'callback' => [$this, 'my_awesome_func'],
            'args' => array(
                'id' => array(
                    'validate_callback' => function ($param, $request, $key) {
                        return is_numeric($param);
                    }
                ),
            ),
        ));

        register_rest_route($this->namespace, '/run', array(
            'methods' => 'WP_REST_Server::CREATABLE',
            'callback' => [new Import, 'run'],
            'permission_callback' => [$this, 'is_admin'],
            'validate_callback' => function ($param, $request, $key) {
                // ifm_tasklist_add_validation_logic
                return true;
            }
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
    }

    public function is_admin($request)
    {
        return current_user_can('manage_options');
    }

    public function get_and_parse_csv($attachment_id)
    {
    }

    /**
     * Grab latest post title by an author!
     *
     * @param array $data Options for the function.
     * @return string|null Post title for the latest,  * or null if none.
     */
    public function my_awesome_func($data)
    {
        $posts = get_posts(array(
            'author' => $data['id'],
        ));

        if (empty($posts)) {
            return null;
        }

        return $posts[0]->post_title;
    }
}
