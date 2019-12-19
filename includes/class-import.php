<?php

namespace IfmImport;

require_once(IFM_IMPORT_INCLUDES . '/importer/class-wp-importer.php');
require_once(IFM_IMPORT_INCLUDES . '/importer/class-csv-edit.php');

use IfmImport\CsvEdit;
use IfmImport\WpImporter;

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

    public function run()
    {
        $this->edit_steps = $this->get_edit_steps();
        // $this->edit_steps = $this->get_import_steps();
        // $this->csv = $this->get_csv();

        // $transient = 'import_process_0_6';
        // if (current_user_can('manage_options') && !get_transient($transient)) :
        //     set_transient($transient, 'locked', 6000); // lock function for 10 Minutes
        // endif;
        // $importer = new WpImporter();
        // $importer->setup($this->csv, $this->import_steps);
        // $importer->run();

        return "toit";
    }

    public function get_edit_steps()
    {
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
