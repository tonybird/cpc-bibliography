<?php
include( plugin_dir_path( __FILE__ ) . 'citation-from-id.php');

// Add citations column to Bibliography post listing
add_filter( 'manage_bib_posts_columns', 'set_custom_edit_bib_columns' );
function set_custom_edit_bib_columns($columns) {
  $columns['citation'] = "Citation";
  return $columns;
}

// Show data in citations column
add_action( 'manage_bib_posts_custom_column' , 'custom_bib_column');
function custom_bib_column() {
    echo get_field("citation");
}

// Add meta box for viewing generated citation to post editor
add_action( 'add_meta_boxes', 'add_citation_meta_box' );
function add_citation_meta_box() {
  add_meta_box(
    'citation_meta_box', // $id
    'Citation', // $title
    'show_citation_meta_box', // $callback
    'bib', // $screen
    'normal', // $context
    'high' // $priority
  );
}

function get_field($str, $id=NULL) {
   global $post;
   if ($id!==NULL) {
     return get_post_meta($id, $str, true);
   } else {
     return get_post_meta($post->ID, $str, true);
   }
  //$meta = get_post_meta( $post->ID, 'bib_fields', true );
//  return $meta[$str];
}

function set_field($key, $val) {
  global $post;
  //set_post_meta($post->ID, $key, $val);
  $bibfieldsarr = get_post_meta( $post->ID, 'bib_fields', true );
  $bibfieldsarr[$key] = $val;
  set_post_meta ($post->ID, 'bib_fields', $bibfieldsarr);
}

// Show citation in meta box
function show_citation_meta_box() {
  echo get_field("citation");
}

// Add meta box for editing bibliography fields
add_action( 'add_meta_boxes', 'add_bib_fields_meta_box' );
function add_bib_fields_meta_box() {
  add_meta_box(
    'bib_fields_meta_box', // $id
    'Bibliography Fields', // $title
    'show_bib_fields_meta_box', // $callback
    'bib', // $screen
    'normal', // $context
    'high' // $priority
  );
}
// Show bibliography field editing form
function show_bib_fields_meta_box() {

  // Import ID, title, and description for text fields and text areas
  include( plugin_dir_path( __FILE__ ) . 'bib-fields.php');

   ?>

  <input type="hidden" name="bib_meta_box_nonce" value="<?php echo wp_create_nonce( basename(__FILE__) ); ?>">

  <p><label for="reference-type">Reference Type</label></br>
    <i>Select the bibliographic reference type for this entry such as journal article, book, etc.</i></br>
    <select name="type" id="type">
      <?php
      	foreach($referencetypes as $value => $title) {
          echo($title);
          echo "<option value='" . $value . "'";
          selected( trim(get_field('type')), $value);
          echo ">" . $title ." (".$value.")</option>";
        }
        echo "</select></p>";

      foreach($textareas as $t) {

        if (is_array(get_field($t['id']))) {
          $contents = implode("\n",get_field($t['id']));
        } else {
          $contents = get_field($t['id']);
        }
        echo "<p><label for='".$t['id']."'>".$t['title']."</label></br>
        <i>".$t['desc']."</i>
        <textarea name='".$t['id']."' id='".$t['id']."' rows='4'>".$contents."</textarea>
        </p>";
      }

      foreach($textfields as $t) {
        echo "<p><label for='".$t['id']."'>".$t['title']."</label></br>";
        if ($t['desc'] != "") {
          echo "<i>".$t['desc']."</i></br>";
        }
        echo "<input type='text' name='".$t['id']."' id='".$t['id']."' class='regular-text' value='".get_field($t['id'])."'>
        </p>";
      }
    }

  add_action( 'admin_print_styles-post-new.php', 'bibliography_admin_style', 11 );
  add_action( 'admin_print_styles-post.php', 'bibliography_admin_style', 11 );

//Add CSS to admin panel
function bibliography_admin_style() {
    global $post_type;
    if( 'bib' == $post_type )
        wp_enqueue_style( 'bib-admin-style', plugin_dir_url(__FILE__) . '/css/bib-admin.css' );
}

// Update bibliography fields in database
    function save_bib_fields_meta( $post_id ) {

      if ('bib' !== get_post_type($post_id) ) {
        return $post_id;
      }

      // verify nonce
      if ( isset($_POST['bib_meta_box_nonce'])
      && !wp_verify_nonce( $_POST['bib_meta_box_nonce'], basename(__FILE__) ) ) {
        return $post_id;
      }
      // check autosave
      if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return $post_id;
      }
      // check permissions
      if (isset($_POST['post_type'])) {
        if ( 'page' === $_POST['post_type'] ) {
          if ( !current_user_can( 'edit_page', $post_id ) ) {
            return $post_id;
          } elseif ( !current_user_can( 'edit_post', $post_id ) ) {
            return $post_id;
          }
        }
      }

      include( plugin_dir_path( __FILE__ ) . 'bib-fields.php');

      update_post_meta($post_id, 'type', $_POST['type']);
      foreach ($textfields as $t) {
        if (isset($_POST[$t['id']])) {
          update_post_meta($post_id, $t['id'], $_POST[$t['id']]);
        }
      }
      foreach ($textareas as $t) {
        if (isset($_POST[$t['id']])) {
          if ($t['id'] == 'authors' || $t['id'] == 'editors' || $t['id'] == 'keywords' || $t['id'] == 'series-authors') {
            $textareaarray = explode("\n", str_replace("\r", "", $_POST[$t['id']]));
            update_post_meta($post_id, $t['id'], $textareaarray);
          } else {
            update_post_meta($post_id, $t['id'], $_POST[$t['id']]);
          }
        }
      }

        //generate citation from bibliography fields
      //  include( plugin_dir_path( __FILE__ ) . 'generate-citation.php');
    //    generate_citation();

      //  include( plugin_dir_path( __FILE__ ) . 'citation-from-id.php');
        update_post_meta($post_id, "citation", citation_from_id($post_id));



    }
    add_action( 'save_post', 'save_bib_fields_meta' );
 ?>
