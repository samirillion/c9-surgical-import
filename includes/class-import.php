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

        $params = $request->get_params();

        $steps = json_decode($params["import_steps"]);
        $vars = json_decode($params["import_vars"]);

        $file_id = $params["upload_object"]["id"];

        $importer = new WpImporter;

        $importer->setup($file_id, $steps, $vars);

        $importer->run();

        return json_encode("success");
    }

    public function get_progress()
    {
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        header('Connection: keep-alive');
        header('X-Accel-Buffering: no'); //Nginx: unbuffered responses suitable for Comet and HTTP streaming applications
        xdebug_break();
        $x = 0;

        while ($x < 2) {
            $x++;

            $step = WpImporter::$step;
            // 1 is always true, so repeat the while loop forever (aka event-loop)
            // $step = "toit";
            echo "event: ping\n",
                'data: {"time": "' . $step . '"}',
                "\n\n";

            // Send a simple message at random intervals.

            echo 'data: This is a message at time ' . $step, "\n\n";

            // flush the output buffer and send echoed messages to the browser

            while (ob_get_level() > 0) {
                ob_end_flush();
            }
            flush();

            // break the loop if the client aborted the connection (closed the page)

            if (connection_aborted()) break;

            // sleep for 1 second before running the loop again

            sleep(1);
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
