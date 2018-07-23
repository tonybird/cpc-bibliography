<?php

//      Regex for matching entire abstract including paragraph breaks
//      /(AB  - (.|\n)*?)[A-Z0-9]{2}  -/
//      Only match when abstract includes more than one paragraph break
//      /(AB  - (.*[\n]){2,}?)AD  -/

add_shortcode('reflib', 'reflib_test');

function reflib_test() {
  // [reflib] shortcode is just used for testing import- calls the same import
  // function as the importer page

  require( plugin_dir_path( __FILE__ ) . 'lib/RefLib-master/reflib.php');

  $lib = new RefLib();
  $lib->SetContentsFile(plugin_dir_path( __FILE__ ) . 'lib/RefLib-master/tests/data/cpc-ris.txt');

  $n = 10; //number of entries to import
  $start = rand(0,5000);  //starting entry number
  $smalllib = array_slice($lib->refs, $start, $n);
  // $wholelib = $lib->refs;

  import_reflib_ris($smalllib);
}


function import_reflib_ris($lib, $opts) {
  remove_action('save_post', 'save_bib_fields_meta');

  echo "<h4>Importing " .count($lib)." bibliography entries:</h4>";

  if ($opts->keywords == 1) {
    echo "<b>Importing keywords</b></br>";
    $importkeywords = true;
  }
  if ($opts->notes == 1) {
    echo "<b>Importing notes</b></br>";
    $importnotes = true;
  }
  echo "</br>";

  foreach($lib as $num => $fields) {
    echo "<b>Importing #" . ($num+1)."</br></b>";

    $new_bib = array(
      'post_title'    =>  wp_strip_all_tags($fields['title']),
      'post_name'     =>  $fields['id'],
      'post_status'   => 'publish',
      'post_type'     => 'bib'
    );

    $id = wp_insert_post( $new_bib );
    if(is_wp_error($id)){
      //there was an error in the post insertion,
      echo $post_id->get_error_message();
    }

   foreach ($fields as $fieldname => $fieldvalue) {
       echo "<b>{$fieldname}:</b> ";
       print_r($fieldvalue);
       echo "</br>";
       if (is_string($fieldvalue)) $fieldvalue = trim($fieldvalue);
       if (is_array($fieldvalue)) $fieldvalue = array_map('trim', $fieldvalue);

    update_post_meta($id, $fieldname, $fieldvalue);
   }
   if (!$importkeywords) {
     update_post_meta($id, "keywords", "");
   }
   if (!$importnotes) {
     update_post_meta($id, "notes", "");
   }

    $citation = citation_from_id($id);
    echo("<b>Citation: </b>".$citation);
    update_post_meta( $id, 'citation', $citation );

    echo "</br></br>";
   }
   add_action( 'save_post', 'save_bib_fields_meta' );

}

?>
