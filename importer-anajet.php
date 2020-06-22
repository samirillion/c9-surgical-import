<?php

namespace IfmImport;

require_once IFM_IMPORT_INCLUDES . "/importer/class-importer.php";

$ana_steps = json_decode('[
  {
    "id": "create_post_1",
    "action": "create_post",
    "getMap": [{}],
    "setMap": [
      {
        "left": "post_type",
        "type": "postType",
        "right": "epkb_post_type_1"
      },
      {
        "left": "post_title",
        "type": "csvValue",
        "right": "post_title"
      },
      {
        "left": "post_status",
        "type": "string",
        "right": "publish"
      },
      {
        "left": "post_content",
        "type": "csvValue",
        "right": "post_content"
      },
      {
        "left": "post_name",
        "type": "csvValue",
        "right": "post_name"
      },
      {
        "left": "post_date",
        "type": "csvValue",
        "right": "post_date"
      }
    ]
  },
  {
    "id": "add_post_terms_3",
    "action": "add_post_terms",
    "getMap": [
      {
        "left": "ID",
        "type": "stepId",
        "right": "create_post_1"
      }
    ],
    "setMap": [
      {
        "left": "epkb_post_type_1_category",
        "type": "csvValue",
        "right": "printer_type"
      },
      {
        "left": "epkb_post_type_1_category",
        "type": "csvValue",
        "right": "printer_type"
      }
    ]
  }
]');


// $ana_transient = 'my_onetime_check_2';
// if (!get_transient($ana_transient)) {

//   set_transient($ana_transient, 'locked', 600); // lock function for 10 Minutes

//   $ana_importer = new IfmImporter(
//     array(
//       'file_path' => IFM_IMPORT_PATH . "/tmp/csv/support-export.csv",
//       'steps' => $ana_steps,
//       'limit' => 10,
//     )
//   );
//   $ana_importer->run();
// }
