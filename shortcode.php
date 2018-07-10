<?php

//https://wordpress.stackexchange.com/a/246206
//sorting by custom post type meta field

add_shortcode('bibliography', 'display_entries');

function display_entries() {
  // the query
  $args = array(
  'post_type' => 'bib',
  'post_status'=>'publish',
  'posts_per_page'=>-1,
  'orderby'   => 'title',
  'order' => 'ASC'
  // 'meta_query' => array(
  //        'bibfields' => array(
  //           'key' => 'year-published',
  //           'value' => '2009',
  //           'type' => 'NUMERIC' // unless the field is not a number
  //   ))
);
  $wpb_all_query = new WP_Query($args);

  if ( $wpb_all_query->have_posts() ) :
    echo "<ul>";
    while ( $wpb_all_query->have_posts() ) : $wpb_all_query->the_post();
    global $post;

    if (get_field('citation') !== "") {
      echo "<p>".get_field('citation')."</p>";
    }
  endwhile;
  echo "</ul>";

  wp_reset_postdata();
  else : echo "<p>Sorry, no bibliography entries were found.</p>";
endif;
}

?>
