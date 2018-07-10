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
		//the_meta();
				include 'bib-fields.php';
				//print_r(get_post_meta($post->ID));
				foreach($frontendfields as $fieldtitle => $fieldid) {
					switch ($fieldid) {
						case "url":
						if (!empty(get_field('url'))){
							if (strpos(get_field('url'), 'http') !== false) {
								$val = "<a href='".$post->url."'>".$post->url."</a>";
							} else {
								$val = "<a href='http://doi.org/".$post->url."'>".$post->url."</a>";
							}
						} else {
							$val = "";
						}
								break;
						case "type":
						//make reference type pretty (JOUR -> Journal)
							$val = $referencetypes[$post->type];
								break;
						case "keywords":
						case "authors":
						case "editors":
						case "series-authors":
							$val = implode("</br>",$post->$fieldid);
								break;
						default:
							$val = $post->$fieldid;
					}

					//do not display empty fields OR arrays that only contain another empty array
					if (!empty($val) && !empty($val[0])) {
						 echo "<p><h4>" . $fieldtitle . "</h4>" . $val . "</p>";
				 }
				}

				//Generate .RIS download
				require( plugin_dir_path( __FILE__ ) . 'lib/RefLib-master/reflib.php');
				$newlib = new RefLib();
				$newlib->SetContentsFile('.ris');
				$newlib->refs[0]["title"] = $post->title;
				foreach ($frontendfields as $title => $key) {
					if ($post->$key) {
						$newlib->refs[0][$key] = $post->$key;
					}
				}
				$tofile = $newlib->GetContents(); // Output file to the browser
				$tofile = str_replace("\\n", '', $tofile);

				//.RIS Filename
				if(strlen($post->title) > 25) {
					$filename = substr($post->title, 0, strpos($post->title, ' ', 25));
				} else {
					$filename = $post->title;
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
