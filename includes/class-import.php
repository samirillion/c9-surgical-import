<?php

namespace IfmImport;

// use IfmImport\CsvEdit;
require_once 'importer/class-wp-importer.php';
require_once 'importer/class-var-builder.php';

use IfmImport\WpImporter;
use IfmImport\VarBuilder;

use function Stringy\create as s;


require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/image.php';
ini_set('memory_limit', '1024M');

/**
 * REST_API Handler
 */
class Import
{

    protected $csv = null;
    protected $edit_steps = [];
    protected $import_steps = [];
    protected $first_line = 0;
    protected $last_line = -1;

    public function run(\WP_REST_Request $request)
    {
        xdebug_break();
        delete_transient("ifm_progress");
        $params = $request->get_params();

        $steps = json_decode($params["import_steps"]);
        $vars = json_decode($params["import_vars"]);

        $file_id = $params["upload_object"]["id"];

        if (!$file_id) {
            return 'Looks like you dont have any csv file uploaded';
        }

        $importer = new WpImporter;

        try {
            set_transient("ifm_progress", "pending", 0);
            $importer->setup($file_id, $steps, $vars);
            $importer->run();
            set_transient("ifm_progress", "complete", 0);
        } catch (\Exception $e) {
            set_transient("ifm_progress", "error", 0);
        }
    }

    public function get_progress()
    {
        return json_encode(get_transient("ifm_progress"));
    }

    public function preview_custom_var(\WP_REST_Request $request)
    {

        $params = $request->get_params();
        $file_id = $params["upload_id"];
        $limit = 1;
        $offset = intval($params["record_index"]);
        $code = $params["var_code"];


        $importer = new WpImporter;

        $records = $importer->readCSV($file_id, $limit, $offset);

        // stupid way to get val from limit iterator
        foreach ($records as $record) {
            $record = $record;
        }
        if ($record) {
            $code = VarBuilder::get_csv_values($code, $record);
        }

        VarBuilder::$code = s($code);

        $output = VarBuilder::parse("");

        return $output ? $output : _e("Something went wrong or you're passing an empty string");
    }

    public function get_edit_steps()
    {
        // example steps
        return array(
            'method' => 'create_post',
            'id' => 'profile_id',
            'map' => array(
                'post_title' => '$Display Name',
                'post_content' => '$html_data',
                'post_type' => 'profile',
                'post_status' => 'publish'
            ),
        );
    }
    public function get_import_steps()
    {
        return ['step1', 'step2'];
    }

    public function get_csv()
    {
        return 'cool';
        // return get_attached_file($data['id']);
    }
}
