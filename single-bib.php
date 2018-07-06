<?php
/**
* Template for displaying single bibliographic entries
*
* @package WordPress
* @subpackage Twenty_Sixteen
* @since Twenty Sixteen 1.0
*/

get_header();

$meta = get_post_meta( $post->ID, 'bib_fields', true );

?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">

		<h2><?php the_title(); ?></h2>

		<?php

				include 'bib-fields.php';
				foreach($frontendfields as $fieldtitle => $fieldid) {
					switch ($fieldid) {
						case "url":
							if (strpos($meta['url'], 'http') !== false) {
								$val = "<a href='".$meta['url']."'>".$meta['url']."</a>";
							} else {
								$val = "<a href='http://doi.org/".$meta['url']."'>".$meta['url']."</a>";
							}
								break;
						case "reference-type":
						//make reference type pretty (JOUR -> Journal)
							$val = $referencetypes[$meta[$fieldid]];
								break;
						case "keywords":
						case "authors":
						case "editors":
						case "series-authors":
						//change new lines to <br> tags for text areas
							$val = nl2br($meta[$fieldid]);
								break;
						default:
							$val = $meta[$fieldid];
					}

					//do not display empty fields
					if ($meta[$fieldid] != "") {
						echo "<p><h4>" . $fieldtitle . "</h4>" . $val . "</p>";
					}
				}

				//Generate .RIS download
				require( plugin_dir_path( __FILE__ ) . 'lib/RefLib-master/reflib.php');
				$newlib = new RefLib();
				$newlib->SetContentsFile('.ris');
				$newlib->refs[0] = array(
					"abstract" => $meta['abstract'],
					"type" => $meta['reference-type'],
					"title" => $meta['title'],
					"year" => $meta['year-published'],
					"publisher" => $meta['publisher'],
					"city" => $meta['publication-city'],
					"secondary-title" => $meta['secondary-title']
				);
				$tofile = $newlib->GetContents(); // Output file to the browser
				$tofile = str_replace("\\n", '', $tofile);

				if(strlen($meta['title']) > 25) {
					$filename = substr($meta['title'], 0, strpos($meta['title'], ' ', 25));
				} else {
					$filename = $meta['title'];
				}
				$filename = str_replace(' ', '-', $filename); // Replaces all spaces with hyphens.
				$filename =  preg_replace('/[^A-Za-z0-9\-]/', '', $filename); // Removes special chars.

				?>
				<form action="http://wp-test.cpc.unc.edu/tonybird/wp-content/plugins/cpc-bibliography/download-ris.php" method="post">
					<input type="hidden" name="filename" value="<?php echo $filename; ?>">
					<input type="hidden" name="tofile" value="<?php echo $tofile; ?>">
				<input type="submit" name="submit" id="submit" value="Download as .RIS" />
				</form>

			</main><!-- .site-main -->

			<?php get_sidebar( 'content-bottom' ); ?>

		</div><!-- .content-area -->

		<?php get_sidebar(); ?>
		<?php get_footer(); ?>
