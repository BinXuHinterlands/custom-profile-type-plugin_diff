<?php
// Register the taxonomy
function wporg_register_taxonomy_activity_status() {
  $args = array(
      'public' => true,
      'label'  => 'Activity Status'
  );
  register_taxonomy( 'activity_status', 'user', $args );
}
add_action( 'init', 'wporg_register_taxonomy_activity_status' );

// Add terms to the taxonomy
function wporg_add_activity_status_terms() {
  $taxonomy = 'activity_status';
  $terms = array(
      'Active',
      'Inactive',
  );

  foreach ( $terms as $term ) {
      if ( ! term_exists( $term, $taxonomy ) ) {
          wp_insert_term( $term, $taxonomy );
      }
  }
}
add_action( 'init', 'wporg_add_activity_status_terms' );




// Save the taxonomy term when the user is saved
// function wporg_save_activity_status_field( $user_id ) {
//   if ( !current_user_can( 'edit_user', $user_id ) ) {
//       return false;
//   }
//   wp_set_object_terms( $user_id, (int) $_POST['activity_status'], 'activity_status', false );
//   clean_object_term_cache( $user_id, 'activity_status' );
// }
// add_action( 'personal_options_update', 'wporg_save_activity_status_field' );
// add_action( 'edit_user_profile_update', 'wporg_save_activity_status_field' );

// Add the new column to the user list
function wporg_add_activity_status_column( $columns ) {
  $columns['activity_status'] = 'Activity Status';
  return $columns;
}
add_filter( 'manage_users_columns', 'wporg_add_activity_status_column' );

// Fill the new column with the associated taxonomy term
function wporg_show_activity_status_column_content( $value, $column_name, $user_id ) {
  if ( 'activity_status' == $column_name ) {
      $terms = wp_get_object_terms( $user_id, 'activity_status', array( 'fields' => 'names' ) );
      $value = ! is_wp_error( $terms ) && ! empty( $terms ) ? join( ', ', $terms ) : 'N/A';
  }
  return $value;
}
add_action( 'manage_users_custom_column', 'wporg_show_activity_status_column_content', 10, 3 );


/**
 * 
 */

 

