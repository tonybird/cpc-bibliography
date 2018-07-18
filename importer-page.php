<?php

class ImportOptions {
  public $keywords = true;
  public $notes = true;
}

add_action('admin_menu', 'add_bib_subpages');

include( plugin_dir_path( __FILE__ ) . 'keyword-search.php');

function add_bib_subpages() {
add_submenu_page('edit.php?post_type=bib', 'Import RIS File', 'Import RIS File', 'manage_options', 'ris-import', 'ris_importer_page');
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
<div class="wrap"><h1>Import RIS File</h1></div>

<?php settings_errors(); ?>

<div><form enctype="multipart/form-data" action="" method="post">

		<label for="risupload">Upload an RIS file: </label><input type="file" required name="risupload" id="risupload"></input></p>

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

		require( plugin_dir_path( __FILE__ ) . 'lib/RefLib-master/reflib.php');
		$lib = new RefLib();
		$lib->SetContentsFile($_FILES['risupload']['tmp_name']);

		import_reflib_ris($lib->refs, $opts);

}
}

 ?>
