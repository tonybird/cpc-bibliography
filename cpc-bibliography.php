<?php
/*
Plugin Name: CPC Bibliography
Description: View list of CPC publications on WordPress
Version: 1.0
Author: Tony Bird
*/




//include( plugin_dir_path( __FILE__ ) . 'reflib-test.php');
//include( plugin_dir_path( __FILE__ ) . 'citation-on-publish.php');
include( plugin_dir_path( __FILE__ ) . 'importer-page.php');

include( plugin_dir_path( __FILE__ ) . 'bib-class.php');

add_action( 'init', 'bibliography_post_type', 0 );
function bibliography_post_type() {
  $biblabels = array(
    'name'                => _x( 'Bibliography', 'Post Type General Name', 'twentythirteen' ),
    'singular_name'       => _x( 'Citation', 'Post Type Singular Name', 'twentythirteen' ),
    'menu_name'           => __( 'Bibliography', 'twentythirteen' ),
    'parent_item_colon'   => __( 'Parent  Item', 'twentythirteen' ),
    'all_items'           => __( 'All Citations', 'twentythirteen' ),
    'view_item'           => __( 'View Citation', 'twentythirteen' ),
    'add_new_item'        => __( 'Add New Citation', 'twentythirteen' ),
    'add_new'             => __( 'Add New Citation', 'twentythirteen' ),
    'edit_item'           => __( 'Edit Citation', 'twentythirteen' ),
    'update_item'         => __( 'Update Citation', 'twentythirteen' ),
    'search_items'        => __( 'Search Bibliography', 'twentythirteen' ),
    'not_found'           => __( 'Not Found', 'twentythirteen' ),
    'not_found_in_trash'  => __( 'Not found in Trash', 'twentythirteen' ),
  );

  $bibargs = array(
    'label'               => __( 'bib', 'twentythirteen' ),
    'description'         => __( 'Store bibliography as individual bibliographic entries', 'twentythirteen' ),
    'labels'              => $biblabels,
    'supports'            => array( 'title' ),
    'taxonomies'          => array( 'post-tag'),
    'hierarchical'        => false,
    'public'              => true,
    'show_ui'             => true,
    'show_in_menu'        => true,
    'show_in_nav_menus'   => true,
    'show_in_admin_bar'   => true,
    'menu_position'       => 45,
    'can_export'          => true,
    'has_archive'         => true,
    'exclude_from_search' => false,
    'publicly_queryable'  => true,
    'capability_type'     => 'post',
  );
  register_post_type( 'bib', $bibargs );
}

// Bibliography editor, citation generation, admin styles
include( plugin_dir_path( __FILE__ ) . 'bib-admin.php');

// [bibliography] shortcode
include( plugin_dir_path( __FILE__ ) . 'shortcode.php');

// // Single bibliography entry template
// add_filter('single_template', 'load_bib_template');
// function load_bib_template($template) {
//   //Load template to display the bibliography on the front-end
//   global $post;
//   if ($post->post_type == "bib" && $template !== locate_template(array("single-bib.php"))){
//     // This is a "bib" post AND a 'single bib template' is not found on
//     // theme or child theme directories, so load it from plugin directory
//     return plugin_dir_path( __FILE__ ) . "single-bib.php";
//   }
//   return $template;
// }

/* Filter the single_template with our custom function*/
add_filter('single_template', 'my_custom_template');

function my_custom_template($single) {
    global $wp_query, $post;
    /* Checks for single template by post type */
    if ( $post->post_type == 'bib' ) {
        if ( file_exists( plugin_dir_path( __FILE__ ) . '/citation-template.php' ) ) {
            return plugin_dir_path( __FILE__ ) . '/citation-template.php';
        }
    }
    return $single;
}

?>
