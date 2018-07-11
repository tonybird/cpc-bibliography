<?php

add_action('admin_menu', 'add_ris_importer');

function add_ris_importer() {
	add_management_page('RIS Importer', 'RIS Importer', 'administrator', 'ris-importer', 'ris_importer_page');
}

function ris_importer_page() {

?>
<div class="wrap"><h1>CPC Bibliography Plugin</h1></div>

<?php settings_errors(); ?>

<h2>Display Settings</h2>

<div><form enctype="multipart/form-data" action="" method="post">

		<fieldset>
			<legend>Select the fields that should be displayed to the public:</legend>
		</br>
			<div>
						 <input type="checkbox" name="keywords"/>
						 <label for="keywords">Keywords</label>
					 </div>
					 <div>
						 <input type="checkbox" name="notes" />
						 <label for="notes">Notes</label>
				 </div>

		</fieldset>

		<p><input type="submit" name="options-submit" id="submit" class="button button-default" value="Save" /></p>

	</form></div>
</br>

<h2>RIS Importer</h2>

<div><form enctype="multipart/form-data" action="" method="post">

		<label for="risupload">Upload an RIS file: </label><input type="file" required name="risupload" id="risupload"></input></p>

		<fieldset>
			<div>
				<input type="radio" name="overwrite" value="true" checked>
				<label for="overwrite">Overwrite existing bibliography</label>
			</div>
			<div>
				<input type="radio" name="overwrite" value="false">
				<label for="append">Append to existing bibliography</label>
			</div>
		</fieldset>

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

		require( plugin_dir_path( __FILE__ ) . 'lib/RefLib-master/reflib.php');
		$lib = new RefLib();
		$lib->SetContentsFile($_FILES['risupload']['tmp_name']);
		import_reflib_ris($lib->refs);

}
}

 ?>
