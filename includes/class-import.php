<?php

namespace IfmImport;

// use IfmImport\CsvEdit;
use IfmImport\WpImporter;
use IfmImport\VarBuilder;
use IfmImport\EventHandler;

use function Stringy\create as s;


require_once(ABSPATH . 'wp-admin/includes/media.php');
require_once(ABSPATH . 'wp-admin/includes/file.php');
require_once(ABSPATH . 'wp-admin/includes/image.php');
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
        $params = $request->get_params();

        $steps = json_decode($params["import_steps"]);
        $vars = json_decode($params["import_vars"]);

        $file_id = $params["upload_object"]["id"];

        $importer = new WpImporter;

        $importer->setup($file_id, $steps, $vars);

        $importer->run();

        return "success";
    }

    public function get_progress()
    {
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        header('Connection: keep-alive');
        header('X-Accel-Buffering: no'); //Nginx: unbuffered responses suitable for Comet and HTTP streaming applications

        $counter = rand(1, 10); // a random counter
        while (1) {
            // 1 is always true, so repeat the while loop forever (aka event-loop)

            $curDate = date(DATE_ISO8601);
            echo "event: ping\n",
                'data: {"time": "' . $curDate . '"}',
                "\n\n";

            // Send a simple message at random intervals.

            $counter--;

            if (!$counter) {
                echo 'data: This is a message at time ' . $curDate, "\n\n";
                $counter = rand(1, 10); // reset random counter
            }

            // flush the output buffer and send echoed messages to the browser

            while (ob_get_level() > 0) {
                ob_end_flush();
            }
            flush();

            // break the loop if the client aborted the connection (closed the page)

            if (connection_aborted()) break;

            // sleep for 1 second before running the loop again

            sleep(2);
        }
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

        return VarBuilder::parse("");
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
