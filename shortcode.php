<?php

//https://wordpress.stackexchange.com/a/246206
//sorting by custom post type meta field

//https://itsmereal.com/datatables-server-side-processing-in-wordpress/

add_shortcode('bibliography', 'display_entries');

function display_entries() {

  wp_enqueue_style( 'bib-list-style', plugin_dir_url(__FILE__) . '/css/bib-list.css' );

$sort_options = array(
  "aty" => "Author, Title, Year",
  "ayt" => "Author, Year, Title",
  "tay" => "Title, Author, Year",
  "tya" => "Title, Year, Author",
  "yat" => "Year, Author, Title",
  "yta" => "Year, Title, Author",
  "ryat" => "Year (Reversed), Author, Title",
  "ryta" => "Year (Reversed), Title, Author"
);
$type_options = array(
  "" => "---- All ----",
  "Journal Article" => "Journal Article",
  "Book" => "Book",
  "Book Section" => "Book Chapter",
  "Edited Book" => "Edited Book"
);

function get_meta_values($key) {
    global $wpdb;
	$result = $wpdb->get_col(
		$wpdb->prepare( "
			SELECT DISTINCT pm.meta_value FROM {$wpdb->postmeta} pm
			LEFT JOIN {$wpdb->posts} p ON p.ID = pm.post_id
			WHERE pm.meta_key = '%s'
			AND p.post_status = 'publish'
			ORDER BY pm.meta_value",
			$key
		)
	);
	return $result;
}

?>
<script>

  function toggleAdvSearch() {
      var x = document.getElementById("advanced-search");
      if (x.style.display === "block") {
          x.style.display = "none";
          document.getElementById('adv').value = 'false';
      } else {
          x.style.display = "block";
          document.getElementById('adv').value = 'true';
      }
  }

function change(){
    document.getElementById("search-sort").submit();
}
</script>

<form id="search-sort" method="get">
  <input id="adv" name="adv" type="hidden" />

    <div class="searchbox-row">
  <input type="text" placeholder="Search citations and abstracts..." id="search" name="search" value='<?php echo $_GET['search']?>'>
  <button type="submit" form="search-sort">Submit</button>
  </div>
  <div class="searchbox-row">
    <!-- <div><input type="reset" value="Reset" onClick="window.location.reload()">
</div> -->
    <div>
  <label for="sort">Sort By: </label>
  <select id="sort" name="sort" onchange="change()">

  <?php
  if ($_GET['sort']=="") $_GET['sort']="ryat";
foreach ($sort_options as $key =>$value) {
  echo "<option value='".$key."'";
  if ($_GET['sort']==$key) echo "selected='selected'";
  echo ">".$value."</option>";
}
?>

</select>
</div>
<div>
<label for="type">Reference Type: </label>
<select id="type" name="type" onchange="change()">

<?php
foreach ($type_options as $key =>$value) {
echo "<option value='".$key."'";
if ($_GET['type']==$key) echo "selected='selected'";
echo ">".$value."</option>";
}
?>

</select>
</div>
<div>
  <a href="#" onclick="toggleAdvSearch();return false;">Advanced Search</a>
</div></div>

<div id="advanced-search">
  <?php
  $adv_text_fields = array(
    'title' => array(
      'label' => 'Title',
      'placeholder' => 'Enter a word or phrase that must be present in the title field'
    ),
    'author' => array(
      'label' => 'Author',
      'placeholder' => 'Enter an author name in the format "Last Name, First Name"'
    ),
    'year' => array(
      'label' => 'Year',
      'placeholder' => 'Enter a year of publication in the form "2018 or "Forthcoming"'
    ),
    'title-secondary' => array(
      'label' => 'Journal',
      'placeholder' => 'Limit to a specific journal (or book title, for chapters)'
    ),
    'publisher' => array(
      'label' => 'Publisher',
      'placeholder' => 'Limit to a specific publisher'
    )
  );

  foreach ($adv_text_fields as $id =>$field) {
    echo "<div class='searchbox-row'>
    <label for='{$id}'>{$field['label']}</label>
    <input type='text' id='{$id}' name='{$id}' placeholder='{$field['placeholder']}' value='{$_GET[$id]}'>
    </div>
    ";

  }

  ?>

  <div class="searchbox-row searchbox-center"><a href='<?php echo get_permalink()."?adv=true";?>'>Reset Search</a></div>

</div>

</form>

<?php
if ($_GET['adv']=='true') {
  echo '<script type="text/javascript">document.getElementById("advanced-search").style.display = "block";</script>';
}

// Prepare WP query from standard search parameters

$meta_query['relation'] = 'AND';

$meta_query['citation-or-abstract'] = array(
    array(
      'key' => 'citation',
      'value' => $_GET['search'],
      'compare' => 'LIKE'
    ),
    array(
      'key' => 'abstract',
      'value' => $_GET['search'],
      'compare' => 'LIKE'
    ),
    'relation' => 'OR'
  );

// Following 3 clauses must always be present for sorting
$meta_query['author'] = array(
  'key' => 'authorlist',
  'value' => $_GET['author'],
  'compare' => 'LIKE'
);
$meta_query['year'] = array(
  'key' => 'year',
  'value' => $_GET['year'],
  'compare' => 'LIKE'
);
$meta_query['title'] = array(
  'key' => 'title',
  'value' => $_GET['title'],
  'compare' => 'LIKE'
);

if (!empty($_GET['type'])) {
  $type_clause['key'] = 'type';
  $type_clause['value'] = $_GET['type'];
  $type_clause['compare'] = '=';
  $meta_query['type'] = $type_clause;
}

// If additional search fields are present, add them to the query
foreach (array('title-secondary','publisher') as $field) {
  if (!empty($_GET[$field])) {
    $meta_query[$field] = array(
      'key' => $field,
      'value' => $_GET[$field],
      'compare' => 'LIKE'
    );
  }
}

$sort_orders = array(
  "aty" => array('author' => 'ASC', 'title' => 'ASC', 'year' => 'ASC'),
  "ayt" => array('author' => 'ASC', 'year' => 'ASC', 'title' => 'ASC'),
  "tay" => array('title' => 'ASC', 'author' => 'ASC', 'year' => 'ASC'),
  "tya" => array('title' => 'ASC', 'author' => 'ASC', 'year' => 'ASC'),
  "yat" => array('year' => 'ASC', 'author' => 'ASC', 'title' => 'ASC'),
  "yta" => array('year' => 'ASC', 'title' => 'ASC', 'author' => 'ASC'),
  "ryat" => array('year' => 'DESC','author' => 'ASC', 'title' => 'ASC'),
  "ryta" => array('year' => 'DESC', 'title' => 'ASC', 'author' => 'ASC')
);

$args = array(
  'post_type' => 'bib',
  'post_status'=>'publish',
  'posts_per_page'=>10,
    'meta_query' => $meta_query,
    'orderby' => $sort_orders[$_GET['sort']]
  );

  // echo "<pre><center>\$custom_query_args =</center>";
  // print_r($args);
  // echo "</pre>";

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

    echo "<h6>Displaying ".$wp_query->post_count." of ".$wp_query->found_posts." entries.</h6>";
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
    $big = 999999999; // need an unlikely integer
    echo "<div class='page-nav'>";
      echo paginate_links( array(
      'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
      'format' => '?paged=%#%',
      'current' => max( 1, get_query_var('paged') ),
      'total' => $wp_query->max_num_pages
    ) );
    echo "</div>";

    // Reset main query object
    $wp_query = NULL;
    $wp_query = $temp_query;

}


?>
