<?php

//https://wordpress.stackexchange.com/a/246206
//sorting by custom post type meta field

add_shortcode('bibliography', 'display_entries');

function display_entries() {
  ob_start();

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
  "" => "All",
  "Journal Article" => "Journal Article",
  "Book" => "Book",
  "Book Section" => "Book Chapter",
  "Edited Book" => "Edited Book",
  "Thesis" => "Thesis/Dissertation",
  "Report" => "Report/Working Paper",
  "Magazine" => "Magazine Article",
  "Generic" => "Generic/Unpublished",
  "Conference" => "Conference/Presentation"
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

// function change(){
//     document.getElementById("search-sort").submit();
// }

function ignoreEmptySearchFields() {
    var myForm = document.getElementById('search-sort');
    var allInputs = myForm.querySelectorAll('input,textarea,select')
    var input, i;

    for(i = 0; input = allInputs[i]; i++) {
        if(input.getAttribute('name') && !input.value) {
            input.setAttribute('disabled', 'disabled');
        }
    }
}

</script>

<form id="search-sort" method="get" onsubmit="ignoreEmptySearchFields()">
  <input id="adv" name="adv" value="<?php if (isset($_GET['adv'])) echo $_GET['adv']; ?>" type="hidden" />

    <div class="searchbox-row">
  <input type="text" placeholder="Search citations and abstracts..." id="search" name="q" value='<?php if (isset($_GET['q'])) echo $_GET['q']?>'>
  <button type="submit" form="search-sort">Submit</button>
  </div>
  <div class="searchbox-row">
    <div>
  <label for="sort">Sort By: </label>
  <select id="sort" name="sort" onchange="change()">

  <?php
  if (!isset($_GET['sort']) || $_GET['sort']=="") $_GET['sort']="ryat";
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
if (isset($_GET['type']) && $_GET['type']==$key) echo "selected='selected'";
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
    't' => array(
      'label' => 'Title',
      'placeholder' => 'Title of article, chapter, or book'
    ),
    'a' => array(
      'label' => 'Author',
      'placeholder' => 'Name in the format "Last Name, First Name"'
    ),
    'y' => array(
      'label' => 'Year',
      'placeholder' => 'Publication year in the form "2018" or "Forthcoming"'
    ),
    'j' => array(
      'label' => 'Journal',
      'placeholder' => 'Name of journal or book'
    ),
    'p' => array(
      'label' => 'Publisher',
      'placeholder' => 'Name of publisher'
    )
  );

  foreach ($adv_text_fields as $id =>$field) {
    $val = (isset($_GET[$id])) ? $_GET[$id] : "";
    echo "<div class='searchbox-row'>
    <label for='{$id}'>{$field['label']}</label>
    <input type='text' id='{$id}' name='{$id}' placeholder='{$field['placeholder']}' value='$val'>
    </div>
    ";

  }

  ?>
  <div class="searchbox-row searchbox-center">
    <a href='<?php echo get_permalink()."?adv=true";?>'>Reset Search</a>
  </div>
</div>
</form>

<?php
if (isset($_GET['adv']) && $_GET['adv']=='true') {
  echo '<script type="text/javascript">document.getElementById("advanced-search").style.display = "block";</script>';
}

// Prepare WP query from standard search parameters
$meta_query['relation'] = 'AND';
$meta_query['citation-or-abstract'] = array(
    array(
      'key' => 'citation',
      'value' => (isset($_GET['q'])) ? $_GET['q'] : "",
      'compare' => 'LIKE'
    ),
    array(
      'key' => 'abstract',
      'value' => (isset($_GET['q'])) ? $_GET['q'] : "",
      'compare' => 'LIKE'
    ),
    'relation' => 'OR'
  );

// Following 3 clauses must always be present for sorting
$meta_query['author'] = array(
  'key' => 'authorlist',
  'value' => (isset($_GET['a'])) ? $_GET['a'] : "",
  'compare' => 'LIKE'
);
$meta_query['year'] = array(
  'key' => 'year',
  'value' => (isset($_GET['y'])) ? $_GET['y'] : "",
  'compare' => 'LIKE'
);

$meta_query['title'] = array(
  'key' => 'title',
  'value' => (isset($_GET['t'])) ? $_GET['t'] : "",
  'compare' => 'LIKE'
);

if (!empty($_GET['type'])) {
  $type_clause['key'] = 'type';
  $type_clause['value'] = $_GET['type'];
  $type_clause['compare'] = '=';
  $meta_query['type'] = $type_clause;
}

// If additional search fields are present, add them to the query
$additional_fields = array(
  "j" => "title-secondary",
  "p" => "publisher"
);

foreach ($additional_fields as $k => $v) {
  if (!empty($_GET[$k])) {
    $meta_query[$v] = array(
      'key' => $v,
      'value' => $_GET[$k],
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
    global $wp_query;
    $temp_query = $wp_query;
    $wp_query   = NULL;
    $wp_query   = $custom_query;

    echo "<p><div class='searchbox-row'><div><b>";

    if ($wp_query->found_posts == 0) echo "No matching citations were found.";
    else echo "Displaying ".$wp_query->post_count." of ".$wp_query->found_posts." matching citations.";

    echo "</b></div>
    <div class='page-nav'>";
    $big = 999999999; // need an unlikely integer
      echo paginate_links( array(
      'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
      'format' => '?paged=%#%',
      'current' => max( 1, get_query_var('paged') ),
      'total' => $wp_query->max_num_pages
    ) );
    echo "</div></div></p>";

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


    $ReturnString = ob_get_contents();
    ob_end_clean();
    return $ReturnString;
    // return ob_get_contents();

}

?>
