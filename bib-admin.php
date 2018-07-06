<?php

// Add citations column to Bibliography post listing
add_filter( 'manage_bib_posts_columns', 'set_custom_edit_bib_columns' );
function set_custom_edit_bib_columns($columns) {
  $columns['citation'] = __( 'Citation', 'your_text_domain' );

  return $columns;
}

// Show data in citations column
add_action( 'manage_bib_posts_custom_column' , 'custom_bib_column', 10, 2 );
function custom_bib_column( $column, $post_id ) {
  switch ( $column ) {
    case 'citation' :
    $meta = get_post_meta( $post_id, 'bib_fields', true );
    if ($meta['citation'] !== "") {
      echo $meta['citation'];
    }
    break;
  }
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
// Show citation in meta box
function show_citation_meta_box() {
  global $post;
  $meta = get_post_meta( $post->ID, 'bib_fields', true );
  echo $meta['citation'];
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

  global $post;
  $meta = get_post_meta( $post->ID, 'bib_fields', true );
   ?>

  <input type="hidden" name="bib_meta_box_nonce" value="<?php echo wp_create_nonce( basename(__FILE__) ); ?>">

  <p><label for="bib_fields[reference-type]">Reference Type</label></br>
    <i>Select the bibliographic reference type for this entry such as journal article, book, etc.</i></br>
    <select name="bib_fields[reference-type]" id="bib_fields[reference-type]">
      <?php
      	foreach($referencetypes as $value => $title) {
          echo($title);
          echo "<option value='" . $value . "'";
          selected( $meta['reference-type'], $value);
          echo ">" . $title ." (".$value.")</option>";
        }
        echo "</select></p>";

      foreach($textareas as $t) {
        echo "<p><label for='bib_fields[".$t['id']."]'>".$t['title']."</label></br>
        <i>".$t['desc']."</i>
        <textarea name='bib_fields[".$t['id']."]' id='bib_fields[".$t['id']."]' rows='4'>".$meta[$t['id']]."</textarea>
        </p>";
      }

      foreach($textfields as $t) {
        echo "<p><label for='bib_fields[".$t['id']."]'>".$t['title']."</label></br>";
        if ($t['desc'] != "") {
          echo "<i>".$t['desc']."</i></br>";
        }
        echo "<input type='text' name='bib_fields[".$t['id']."]' id='bib_fields[".$t['id']."]' class='regular-text' value='".$meta[$t['id']]."'>
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

      $old = get_post_meta( $post_id, 'bib_fields', true );
      if (isset($_POST['bib_fields'])) {

        //generate citation from bibliography fields
        include( plugin_dir_path( __FILE__ ) . 'generate-citation.php');
        generate_citation();

        // include( plugin_dir_path( __FILE__ ) . 'citation-from-id.php');
        // $_POST['bib_fields']['citation'] = citation_from_id($post_id);


        $new = $_POST['bib_fields'];
        if ( $new && $new !== $old ) {
          update_post_meta( $post_id, 'bib_fields', $new );
        } elseif ( '' === $new && $old ) {
          delete_post_meta( $post_id, 'bib_fields', $old );
        }
      }
    }
    add_action( 'save_post', 'save_bib_fields_meta' );
 ?>
