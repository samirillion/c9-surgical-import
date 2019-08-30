<?php
namespace C9Import;

/**
 * REST_API Handler
 */
class REST_API
{

    public function __construct()
    {
        add_action('rest_api_init', array($this, 'register_endpoint'));
    }

    public function register_endpoint()
    {
        register_rest_route('c9importer/v1', '/author/(?P<id>\d+)', array(
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