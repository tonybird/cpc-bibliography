<?php

//https://wordpress.stackexchange.com/a/246206
//sorting by custom post type meta field

add_shortcode('bibliography', 'display_entries');

function display_entries() {
  echo "<h3>Using DataTables:</h3>";
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
  'posts_per_page'=>-1,
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





  $wpb_all_query = new WP_Query($args);
  if ( $wpb_all_query->have_posts() ) :
    echo "<link rel='stylesheet' type='text/css' href='https://cdn.datatables.net/v/dt/jq-3.3.1/dt-1.10.18/datatables.min.css'/>

<script type='text/javascript' src='https://cdn.datatables.net/v/dt/jq-3.3.1/dt-1.10.18/datatables.min.js'></script>


    ";

    echo "<table id='citations' class='table'>
           <thead style='display:none'>
             <tr>
               <th>Citations</th>
             </tr>
           </thead>
           <tbody>";

    while ( $wpb_all_query->have_posts() ) : $wpb_all_query->the_post();
    global $post;

    if (get_field('citation') !== "") {
      echo "<tr>
      <td>$post->citation</td>
      </tr>";
    }
  endwhile;
  echo "</tbody>
  </table>

  <script type='text/javascript'>

  $('#citations').DataTable();


  </script>";

  wp_reset_postdata();
  else : echo "<p>Sorry, no bibliography entries were found.</p>";
endif;

}

?>
