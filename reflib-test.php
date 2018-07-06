<?php
add_shortcode('reflib', 'reflib_test');

/**
* still need to make changes in lib/RefLib-master/drivers/ris.php
*/

function reflib_test() {
  require( plugin_dir_path( __FILE__ ) . 'lib/RefLib-master/reflib.php');

  $lib = new RefLib();
  $lib->SetContentsFile(plugin_dir_path( __FILE__ ) . 'lib/RefLib-master/tests/data/cpc-ris.txt');

  //Import first $n entries starting at $start
  $n = 1;
  $start = 0;
  $start = rand(0,5000);
  $testlib = array_slice($lib->refs, $start, $n);

  //Import all entries
  //$testlib = $lib->refs;

  import_reflib_ris($testlib);
}

function import_reflib_ris($lib) {
  //print_r($lib);


  include( plugin_dir_path( __FILE__ ) . 'citation-from-id.php');

  echo "<h4>Importing " .count($lib)." new bibliography entries:</h4>";

  foreach($lib as $key => $field) {
    echo "<b>{$key}</b></br>";

    $bibfields = array(
      'title' => $field['title'],
      'secondary-title' => $field['secondary-title'],
      'authors' => implode("\n",$field['authors']),
      'abstract' => $field['abstract'],
      'year-published' => $field['year'],
      'reference-type' => trim(strtoupper($field['type'])),
      'notes' => $field['notes'],
      'section' => $field['section'],
      'issn-isbn' => $field['isbn'],
      'volume-number' => $field['volume'],
      'issue-number' => $field['number'],
      'editors' => implode("\n",$field['editors']),
      'publication-city' => $field['city'],
      'publisher' => $field['publisher']
    );

    foreach ($bibfields as $field=>$data) {
      echo $field.": ".$data."</br>";
    }

    $new_bib = array(
      'post_title'    => wp_strip_all_tags($bibfields['title']),
      'post_status'   => 'publish',
      'post_type'     => 'bib',
      'post_author'   => 1,
      'meta_input'    => array(
        'bib_fields'  => $bibfields
      )
    );

    $id = wp_insert_post( $new_bib );

    echo("<b>Citation: </b>".citation_from_id($id));
    $bibfields['citation'] = citation_from_id($id);
    update_post_meta( $id, 'bib_fields', $bibfields );

    echo "</br></br>";

  }
}

?>
