<?php

/*
Template Name: Bibliography Entry
Template Post Type: bib
*/

get_header(); ?>

<div class="wrap">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php
			/* Start the Loop */
			while ( have_posts() ) :
				the_post();
        ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        	<?php
        	if ( is_sticky() && is_home() ) :
        		echo twentyseventeen_get_svg( array( 'icon' => 'thumb-tack' ) );
        	endif;
        	?>
        	<header class="entry-header">
        		<?php
        			the_title( '<h1 class="entry-title">', '</h1>' );
        		?>
        	</header><!-- .entry-header -->

        	<div class="entry-content">
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
        						// case "type":
        						// //make reference type pretty (JOUR -> Journal)
        						// 	$val = $referencetypes[$post->type];
        						// 		break;
        						case "keywords":
        						case "authors":
        						case "editors":
        						case "series-authors":
        							$val = implode("</br>",$post->$fieldid);
        								break;
        						case "urls":
        							$val = implode("</br>", array_map(
        								function ($url) { return "<a href='".$url."'>$url</a>"; },
        								$post->urls)
        							);
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

        				?>
        				<form action="http://wp-test.cpc.unc.edu/tonybird/wp-content/plugins/cpc-bibliography/download.php" method="post">
        					<input type="hidden" name="filename" value="<?php echo $post->id.".ris"; ?>">
        					<input type="hidden" name="tofile" value="<?php echo $tofile; ?>">
        				<input type="submit" name="submit" id="submit" value="Download as .RIS" />
        				</form>

        	</div><!-- .entry-content -->

        </article><!-- #post-## -->
<?php
			endwhile; // End of the loop.
			?>

		</main><!-- #main -->
	</div><!-- #primary -->
	<?php get_sidebar(); ?>
</div><!-- .wrap -->

<?php
get_footer();

 ?>
