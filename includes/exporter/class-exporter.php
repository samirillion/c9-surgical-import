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
        $plugin->prepare_acf_fields();

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
            'post_date',
            'guid'
        );
    }

    private function prepare_taxonomies()
    {
        $this->taxonomies = get_object_taxonomies($this->query_args['post_type']);
    }

    private function prepare_acf_fields()
    {
        $this->acf_fields = acf_get_field_groups(array('post_type' => $this->query_args['post_type']));
        $acf_fields = $this->acf_fields;
    }

    public function build_header()
    {
        $this->header = array_merge($this->post_fields, $this->taxonomies);
    }

    public function write_csv()
    {
        $file_path = IFM_IMPORT_INCLUDES . '/tmp/csv/' . $this->export_id . '.csv';
        if (!file_exists($file_path)) {
            try {
                // Create CSV, Add Header
                $writer = Writer::createFromPath($file_path, 'w+');
                $writer->insertOne($this->header);

                // Add rows
                if ($this->query->have_posts()) :
                    foreach ($this->query->posts as $post) :
                        $row = array();

                        // Add Post Fields
                        foreach ($this->post_fields as $post_field) {
                            $row[$post_field] = $post->$post_field;
                        }

                        // Add Named Taxonomies
                        foreach ($this->taxonomies as $taxonomy) {
                            $post_taxes = wp_get_object_terms($post->ID, $taxonomy);

                            $row[$taxonomy] = implode(", ", array_map(function ($wp_object) {
                                return $wp_object->name;
                            }, $post_taxes));
                        }

                        // Insert Row
                        $writer->insertOne($row);
                    endforeach;
                endif;
            } catch (CannotInsertRecord $e) {
                $e->getRecords(); //returns [1, 2, 3]
            }
        }
    }
}
