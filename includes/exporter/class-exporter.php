<?php

namespace IfmImport;

use League\Csv\Writer;
use League\Csv\CannotInsertRecord;

class IfmExporter
{
    private $export_id = null;
    private $header = array();

    private $query = null;

    private $taxonomies = array();
    private $post_fields = array();
    private $acf_fields = array();

    private $query_args = array();

    public static function run($export_id, $query_args)
    {
        $plugin = new self();

        $plugin->export_id = $export_id;
        $plugin->query_args = $query_args;

        // Do Main Query
        $plugin->do_query();

        // 
        $plugin->prepare_taxonomies();
        $plugin->prepare_post_fields();
        $plugin->prepare_acf();

        $plugin->build_header();

        $plugin->write_csv();
    }

    private function do_query()
    {
        $this->query = new \WP_Query($this->query_args);
    }

    private function prepare_post_fields()
    {
        $this->post_fields = array(
            'ID',
            'post_title',
            'post_name',
            'post_content',
            'date',
            'guid'
        );
    }

    private function prepare_taxonomies()
    {
        $this->taxonomies = get_object_taxonomies($this->query_args['post_type']);
    }

    private function prepare_acf()
    {
        $this->acf_fields = acf_get_field_groups($this->query_args);
    }

    function prepare_meta($type)
    {
        global $wpdb;
        $result = $wpdb->get_results($wpdb->prepare(
            "SELECT post_id,meta_key,meta_value FROM wp_posts,wp_postmeta WHERE post_type = %s
              AND wp_posts.ID = wp_postmeta.post_id",
            $type
        ), ARRAY_A);
        return $result;
    }

    public function build_header()
    {
        $this->header = array_merge($this->header, $this->taxonomies, $this->acf_fields);
    }

    public function write_csv()
    {
        $file_path = IFM_IMPORT_INCLUDES . '/exporter/' . $this->export_id . '.csv';
        if (!file_exists($file_path)) {
            try {
                // xdebug_break();
                $writer = Writer::createFromPath($file_path, 'w+');
                $writer->insertOne($this->header);
                if ($this->query->have_posts()) :
                    foreach ($this->query->posts as $post) :

                        // Add Base stuff
                        $row = array(
                            $post->ID,
                            $post->post_title,
                            $post->post_name,
                            $post->post_content,
                            $post->post_date,
                            $post->guid
                        );

                        // Add Named Taxonomies
                        foreach ($this->taxonomies as $taxonomy) {
                            $post_taxes = wp_get_object_terms($post->ID, $taxonomy);

                            $row[$taxonomy] = implode(", ", array_map(function ($wp_object) {
                                return $wp_object->name;
                            }, $post_taxes));
                        }
                        $writer->insertOne($row);
                    endforeach;
                endif;
            } catch (CannotInsertRecord $e) {
                // $e->getRecords(); //returns [1, 2, 3]
            }
        }
    }
}

IfmExporter::run('page-export', array(
    'post_type' => 'page',
    'post_status' => 'publish',
    'posts_per_page' => -1
));
