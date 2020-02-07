<?php

namespace IfmImport;

// use IfmImport\CsvEdit;
use IfmImport\WpImporter;
use IfmImport\VarBuilder;
use IfmImport\EventHandler;

use Hhxsv5\SSE\SSE;
use Hhxsv5\SSE\Update;

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
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        header('Connection: keep-alive');
        header('X-Accel-Buffering: no'); //Nginx: unbuffered responses suitable for Comet and HTTP streaming applications

        (new SSE())->start(new Update(function () {
            $id = mt_rand(1, 1000);
            $newMsgs = [
                [
                    'id'      => $id,
                    'title'   => 'title' . $id,
                    'content' => 'content' . $id,
                ],
            ]; //get data from database or service.
            if (!empty($newMsgs)) {
                echo json_encode(['newMsgs' => $newMsgs]);
            }
            echo false; //return false if no new messages
        }), 'new-msgs');



        // Function to send data in format "ticket:price".

        // $sse = new SSE(); //create a libSSE instance
        // $sse->addEventListener('hello_world', new EventHandler()); //register your event handler
        // $sse->start(); //start the event loop
        // $params = $request->get_params();

        // $steps = json_decode($params["import_steps"]);
        // $vars = json_decode($params["import_vars"]);

        // $file_id = $params["upload_object"]["id"];

        // $importer = new WpImporter;

        // $importer->setup($file_id, $steps, $vars);

        // $importer->run();

        // return "success";
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
