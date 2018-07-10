<?php
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

function import_reflib_ris($lib) {
  remove_action('save_post', 'save_bib_fields_meta');
  echo "<h4>Importing " .count($lib)." bibliography entries:</h4>";

  foreach($lib as $num => $fields) {
    echo "<b>Importing #" . ($num+1)."</br></b>";

    $new_bib = array(
      'post_title'    =>  wp_strip_all_tags($fields['title']),
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

    $citation = citation_from_id($id);
    echo("<b>Citation: </b>".$citation);
    update_post_meta( $id, 'citation', $citation );

    echo "</br></br>";
   }
   add_action( 'save_post', 'save_bib_fields_meta' );

}

?>
