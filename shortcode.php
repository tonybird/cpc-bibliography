<?php

//https://wordpress.stackexchange.com/a/246206
//sorting by custom post type meta field

add_shortcode('bibliography', 'display_entries');

function display_entries() {

  //the query
//   $args = array(
//   'post_type' => 'bib',
//   'post_status'=>'publish',
//   'posts_per_page'=>-1,
//   'orderby'   => 'title',
//   'order' => 'ASC'
//   // 'meta_query' => array(
//   //        'bibfields' => array(
//   //           'key' => 'year-published',
//   //           'value' => '2009',
//   //           'type' => 'NUMERIC' // unless the field is not a number
//   //   ))
// );

$args = array(
  'post_type' => 'bib',
  'post_status'=>'publish',
  'posts_per_page'=>-1
);

$args = array(
  'post_type' => 'bib',
  'post_status'=>'publish',
  'posts_per_page'=>10,
    'meta_query' => array(
      'relation' => 'AND',
      'year_clause' => array(
        'key' => 'year'
      ),
      'author_clause' => array(
        //'key' => 'authorstring'
      )
    ),
    'orderby' => array(
      'year_clause' => 'DESC',
       //'author_clause' => 'ASC',
      'title' => 'ASC'
    )
  );

// $args = array(
//   'post_type' => 'bib',
//   'post_status'=>'publish',
//   'posts_per_page'=>-1,
//   'meta_query' => array(
//     'relation' => 'AND',
//     'year_clause' => array(
//       'key' => 'year'
//     ),
//     'author_clause' => array(
//       'key' => 'authorstring'
//     )
//   ),
//   'orderby' => array(
//     'year_clause' => 'DESC',
//     'author_clause' => 'ASC',
//     'title' => 'ASC'
//   )
// );

  // $wpb_all_query = new WP_Query($args);
  //
  //
  //     echo "<table>";
  //     while ( $wpb_all_query->have_posts() ) : $wpb_all_query->the_post();
  //     global $post;
  //
  //     if (get_field('citation') !== "") {
  //       echo "<tr>
  //       <td>$post->citation</td>
  //       </tr>";
  //     }
  //   endwhile;
  //   echo "</table>";
  //

    // Define custom query parameters
    $custom_query_args = $args;

    // Get current page and append to custom query parameters array
    $custom_query_args['paged'] = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;

    // Instantiate custom query
    $custom_query = new WP_Query( $custom_query_args );

    // Pagination fix
    $temp_query = $wp_query;
    $wp_query   = NULL;
    $wp_query   = $custom_query;

    // Output custom query loop
    if ( $custom_query->have_posts() ) :
        while ( $custom_query->have_posts() ) :
            $custom_query->the_post();
            // Loop output goes here
            global $post;
            echo "<p>";
            echo $post->citation;
            echo "</p>";
        endwhile;
    endif;
    // Reset postdata
    wp_reset_postdata();

    // Custom query loop pagination
    previous_posts_link( '< Previous' );
    echo " ";
    next_posts_link( 'Next >', $custom_query->max_num_pages );

    // Reset main query object
    $wp_query = NULL;
    $wp_query = $temp_query;

// DATATABLES
//   if ( $wpb_all_query->have_posts() ) :
//     echo "<link rel='stylesheet' type='text/css' href='https://cdn.datatables.net/v/dt/jq-3.3.1/dt-1.10.18/datatables.min.css'/>
//
// <script type='text/javascript' src='https://cdn.datatables.net/v/dt/jq-3.3.1/dt-1.10.18/datatables.min.js'></script>
//
//
//     ";
//
//     echo "<table id='citations' class='table'>
//            <thead style='display:none'>
//              <tr>
//                <th>Citations</th>
//              </tr>
//            </thead>
//            <tbody>";
//
//     while ( $wpb_all_query->have_posts() ) : $wpb_all_query->the_post();
//     global $post;
//
//     if (get_field('citation') !== "") {
//       echo "<tr>
//       <td>$post->citation</td>
//       </tr>";
//     }
//   endwhile;
//   echo "</tbody>
//   </table>";
//
//   echo "<script type='text/javascript'>
//
//   $('#citations').DataTable();
//
//
//   </script>";
//
//   wp_reset_postdata();
//   else : echo "<p>Sorry, no bibliography entries were found.</p>";
// endif;

}

?>
