<?php
require get_template_directory() . '/client/inc/importer/class-wp-import.php';
require get_template_directory() . '/client/inc/importer/class-csv-edit.php';

use C9Import\CsvEdit;
use C9Import\WpImporter;

$transient = 'import_process_0_6';
if (current_user_can('manage_options') && !get_transient($transient)) :
    set_transient($transient, 'locked', 6000); // lock function for 10 Minutes
endif;

function cea_run_s2member_edit()
{
    $file_in = get_template_directory() . '/client/inc/importer/assets/s2member_records_only.csv';
    $file_out = get_template_directory() . '/client/inc/importer/assets/s2member_records_formatted.csv';

    // $steps pull in here
    require get_template_directory() . '/client/inc/importer/import-steps/pre-s2member-edit.php';

    $editor = new CsvEdit($file_in, $file_out, $steps);
    $editor->run();
}

function cea_run_csv_edit()
{
    $file_in = get_template_directory() . '/client/inc/importer/assets/cea-talent.csv';
    $file_out = get_template_directory() . '/client/inc/importer/assets/cea-talent-formatted.csv';

    // $steps pull in here
    require get_template_directory() . '/client/inc/importer/import-steps/cea-csv-edit.php';

    $editor = new CsvEdit($file_in, $file_out, $steps);
    $editor->run();
}

function cea_run_csv_import()
{
    $cea_csv_filepath = get_template_directory() . '/client/inc/importer/assets/cea-talent-formatted.csv';

    // $steps
    require get_template_directory() . '/client/inc/importer/import-steps/cea-profile-import.php';

    // Steps Applied On Each Record
    // $ refers to csv column names,
    // @ refers to generated values like post ids, 
    // everything else is a plain string
    $importer = new WpImporter();
    $importer->setup($cea_csv_filepath, $steps, 550, 200);
    $importer->run();
}
