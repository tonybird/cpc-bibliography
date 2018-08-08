<?php

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
  include( plugin_dir_path( __FILE__ ) . 'bib-fields.php');

global $post;
 // Use nonce for verification
echo '<input type="hidden" name="bib_meta_box_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';

     // Begin the field table and loop
     echo '<table class="form-table">';
     foreach ($bibliography_meta_fields as $field) {
       // get value of this field if it exists for this post
       $meta = get_post_meta($post->ID, $field['id'], true);
       if (is_array($meta)) {
         $meta = implode("\n",$meta);
       }
         echo '<tr>
         <th><label for="'.$field['id'].'">'.$field['label'].'</label></th><td>';
         switch($field['type']) {
           // text
           case 'text':
            echo '<input type="text" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" size="30" />
            <br /><span class="description">'.$field['desc'].'</span>';
              break;
           // textarea
           case 'textarea':
            echo '<textarea name="'.$field['id'].'" id="'.$field['id'].'" cols="60" rows="4">'.$meta.'</textarea>
                  <br /><span class="description">'.$field['desc'].'</span>';
                    break;
           // checkbox
            case 'checkbox':
            echo '<input type="checkbox" name="'.$field['id'].'" id="'.$field['id'].'" ',$meta ? ' checked="checked"' : '','/>
                  <label for="'.$field['id'].'">'.$field['desc'].'</label>';
                    break;
           // select
           case 'select':
            echo '<select name="'.$field['id'].'" id="'.$field['id'].'">';
              foreach ($field['options'] as $option) {
                echo '<option', $meta == $option['value'] ? ' selected="selected"' : '', ' value="'.$option['value'].'">'.$option['label'].'</option>';
              }
            echo '</select><br /><span class="description">'.$field['desc'].'</span>';
              break;
          } //end switch
        echo '</td></tr>';
    } // end foreach
 echo '</table>';
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
function save_bib_fields_meta($post_id) {
  include( plugin_dir_path( __FILE__ ) . 'bib-fields.php');

    // verify nonce
    if (!wp_verify_nonce($_POST['bib_meta_box_nonce'], basename(__FILE__)))
        return $post_id;
    // check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return $post_id;
    // check permissions
    if ('page' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id))
            return $post_id;
        } elseif (!current_user_can('edit_post', $post_id)) {
            return $post_id;
    }

    // loop through fields and save the data
    $b = new Bibliography_Entry();
    $b->set_bib_field("title", get_the_title());

    foreach ($bibliography_meta_fields as $field) {
        $old = get_post_meta($post_id, $field['id'], true);
        switch ($field['id']) {
          case "authors":
          case "editors":
          case "keywords":
          case "series-authors":
          case "urls":
            $new = explode("\n", str_replace("\r", "", $_POST[$field['id']]));
            break;
          default:
            $new = $_POST[$field['id']];
        }
        $b->set_bib_field($field['id'],$new);

        if ($new && $new != $old) {
            update_post_meta($post_id, $field['id'], $new);
        } elseif ('' == $new && $old) {
            delete_post_meta($post_id, $field['id'], $old);
        }
    } // end foreach
    update_post_meta($post_id, "citation", $b->generate_citation());
}

    add_action( 'save_post', 'save_bib_fields_meta' );


 ?>
