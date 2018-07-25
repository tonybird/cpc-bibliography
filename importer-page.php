<?php

class ImportOptions {
  public $keywords = true;
  public $notes = true;
}

add_action('admin_menu', 'add_bib_subpages');

include( plugin_dir_path( __FILE__ ) . 'keyword-search.php');

function add_bib_subpages() {
add_submenu_page('edit.php?post_type=bib', 'Import Bibliography File', 'Import Bibliography', 'manage_options', 'ris-import', 'ris_importer_page');
add_submenu_page('edit.php?post_type=bib', 'Keyword Search', 'Keyword Search', 'manage_options', 'keyword-search', 'bib_keyword_search');
add_submenu_page('edit.php?post_type=bib', 'Bibliography Settings', 'Settings', 'manage_options', 'bib-settings', 'bib_settings_page');
}

function bib_settings_page() {
  ?>
  <div class="wrap"><h1>Bibliography Settings</h1></div>
  <h2>Display Options</h2>

  <div><form enctype="multipart/form-data" action="" method="post">

  		<fieldset>
  			<legend><b>Hide selected fields</b> (if imported)</legend>
  			<div>
  						 <input type="checkbox" name="keywords" id="keywords"/>
  						 <label for="keywords">Keywords</label>
  					 </div>
  					 <div>
  						 <input type="checkbox" name="notes" id="notes" />
  						 <label for="notes">Notes</label>
  				 </div>

  		</fieldset>

  		<p><input type="submit" name="options-submit" id="submit" class="button button-default" value="Save" /></p>

  	</form></div>
  <?php
}
function ris_importer_page() {

?>
<div class="wrap"><h1>Import Bibliography File</h1></div>

<?php settings_errors(); ?>

<div><form enctype="multipart/form-data" action="" method="post">

    <p><b>Preferred format:</b> Endnote (XML)</b></br><b>Accepted formats:</b> RIS, CSV, MEDLINE (PubMed .nbib)</p>
		<label for="bibupload">Upload a bibliography file: </label><input type="file" required name="bibupload" id="bibupload"></input></p>

		<fieldset>
			<div>
				<input type="radio" name="overwrite" value="true" id="overwrite" checked>
				<label for="overwrite">Overwrite existing bibliography</label>
			</div>
			<div>
				<input type="radio" name="overwrite" value="false" id="append">
				<label for="append">Append to existing bibliography</label>
			</div>
		</fieldset>

</br>
		<fieldset>
			<legend><b>Import optional fields</b></legend>
			<div>
						 <input type="checkbox" name="keywords" id="importkeywords" checked/>
						 <label for="importkeywords">Keywords</label>
					 </div>
					 <div>
						 <input type="checkbox" name="notes" id="importnotes" checked/>
						 <label for="importnotes">Notes</label>
				 </div>

		</fieldset>

		<p><input type="submit" name="import-submit" id="submit" class="button button-primary" value="Import Now" /></p>

	</form></div>


	<?php

	if ( ! empty( $_POST['options-submit'] ) ) {
		if ($_POST['notes']) {
			echo "Displaying notes!";
		}
		if ($_POST['keywords']) {
			echo "Displaying keywords!";
		}
}


	if ( ! empty( $_POST['import-submit'] ) ) {

		if ($_POST['overwrite'] === 'true') {
			$bibentries = get_posts( array( 'post_type' => 'bib', 'numberposts' => 10000));
			foreach( $bibentries as $bibentry ) {
    		wp_delete_post( $bibentry->ID, true);
    		// true= delete immediately / false= send to Trash.
   		}
			echo "<h4>Deleted ". count($bibentries) . " existing bibliography entries.</h4>";
		}

		$opts = new ImportOptions();
		if (!$_POST['notes']) $opts->notes = false;
		if (!$_POST['keywords']) $opts->keywords = false;

    // //Fix multiline abstracts
    // $str = file_get_contents($_FILES['risupload']['tmp_name']);
    // function remove_breaks($matches) {
    //   return preg_replace( "/\r|\n/", " ", $matches[1] )."\nAD  -";
    // }
    // $str = preg_replace_callback ( "/(AB  - (.*[\n]){2,}?)[A-Z]{2}  -/" , "remove_breaks" , $str );
    // file_put_contents($_FILES['risupload']['tmp_name'], $str);


    add_filter( 'mime_types', 'wpse_mime_types' );
    function wpse_mime_types( $existing_mimes ) {
        // Add csv to the list of allowed mime types
        $existing_mimes['xml'] = 'text/xml';

        return $existing_mimes;
    }

		require( plugin_dir_path( __FILE__ ) . 'lib/RefLib-master/reflib.php');
		$lib = new RefLib();

    $tmpfile = $_FILES['bibupload']['tmp_name'];
    // echo $_FILES['bibupload']['type'];

    $name = basename($_FILES["bibupload"]["name"]);

    $lib->SetContentsFile($tmpfile);
    // print_r($lib);


    // $destination = plugin_dir_path( __FILE__ ) . basename($_FILES["bibupload"]["name"]);
    // echo "Moving $source to $destination</br>";
    // $success = move_uploaded_file($source, $destination);
    // echo "$success";
    // $filename = $_FILES['bibupload']['tmp_name'];
    // echo $filename;
    // //$filename =  plugin_dir_path( __FILE__ ) . 'lib/RefLib-master/tests/data/cpc-endnote.xml';
    // //print_r($_FILES);
		// $lib->SetContentsFile($filename);
    // //$lib->SetContentsFile(plugin_dir_path( __FILE__ ) . 'lib/RefLib-master/tests/data/cpc-endnote.xml');
    //

    // include( plugin_dir_path( __FILE__ ) . 'bib-class.php');

    echo "<h4>Importing " .count($lib->refs)." bibliography entries:</h4>";

    foreach($lib->refs as $num => $fields) {
      $b = new Bibliography_Entry();
      $b->add_reflib_meta($fields);

      $id = wp_insert_post($b->get_bib_post_data());
      if(is_wp_error($id)){
        echo $id->get_error_message();
      }
      echo "</br>";
    }

		// import_reflib_ris($lib->refs, $opts);

}
}

 ?>
