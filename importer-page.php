<?php

add_action('admin_menu', 'add_ris_importer');

function add_ris_importer() {
	add_management_page('RIS Importer', 'RIS Importer', 'administrator', 'ris-importer', 'ris_importer_page');
}

function ris_importer_page() {

?>
<div class="wrap"><h1>RIS Bibliography Importer</h1></br></div>

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

		<p><input type="submit" name="submit" id="submit" class="button button-primary" value="Import Now" /></p>

	</form></div>

	<?php

	if ( ! empty( $_POST ) ) {

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
