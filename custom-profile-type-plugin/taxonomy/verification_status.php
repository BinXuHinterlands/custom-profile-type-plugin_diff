<?php
// Register the taxonomy
// function wporg_register_taxonomy_verification_status() {
//   $args = array(
//       'public' => true,
//       'label'  => 'verification Status'
//   );
//   register_taxonomy( 'verification_status', 'user', $args );
// }
// add_action( 'init', 'wporg_register_taxonomy_verification_status' );

// // Add terms to the taxonomy
// function wporg_add_verification_status_terms() {
//   $taxonomy = 'verification_status';
//   $terms = array(
//       'Verified',
//       'Pending With Access',
//       'Pending No Access',
//   );

//   foreach ( $terms as $term ) {
//       if ( ! term_exists( $term, $taxonomy ) ) {
//           wp_insert_term( $term, $taxonomy );
//       }
//   }
  
// }
// add_action( 'init', 'wporg_add_verification_status_terms' );


// Add the taxonomy to the user edit screen
function wporg_add_verification_status_field( $user ) {
    delete_taxonomy_terms();
  $terms = get_terms( 'verification_status', array( 'hide_empty' => false ) );
  $user_term = array_shift( wp_get_object_terms( $user->ID, 'verification_status', array( 'fields' => 'ids' ) ) );
  ?>
  <h3>verification Status</h3>
  <table class="form-table">
      <tr>
          <th>
              <label for="verification_status">verification Status</label>
          </th>
          <td>
              <select id="verification_status" name="verification_status">
                  <?php foreach( $terms as $term ) : ?>
                      <option value="<?php echo $term->term_id; ?>" <?php selected( $term->term_id, $user_term ); ?>>
                          <?php echo $term->name; ?>
                      </option>
                  <?php endforeach; ?>
              </select><br />
              <span class="description">Please select your verification status.</span>
          </td>
      </tr>
  </table>
  <?php
}
add_action( 'show_user_profile', 'wporg_add_verification_status_field' );
add_action( 'edit_user_profile', 'wporg_add_verification_status_field' );

// Save the taxonomy term when the user is saved
function wporg_save_verification_status_field( $user_id ) {
  if ( !current_user_can( 'edit_user', $user_id ) ) {
      return false;
  }
  wp_set_object_terms( $user_id, (int) $_POST['verification_status'], 'verification_status', false );
  clean_object_term_cache( $user_id, 'verification_status' );
}
add_action( 'personal_options_update', 'wporg_save_verification_status_field' );
add_action( 'edit_user_profile_update', 'wporg_save_verification_status_field' );

// Add the new column to the user list
function wporg_add_verification_status_column( $columns ) {
  $columns['verification_status'] = 'verification Status';
  return $columns;
}
add_filter( 'manage_users_columns', 'wporg_add_verification_status_column' );

// Fill the new column with the associated taxonomy term
function wporg_show_verification_status_column_content( $value, $column_name, $user_id ) {
  if ( 'verification_status' == $column_name ) {
      $terms = wp_get_object_terms( $user_id, 'verification_status', array( 'fields' => 'names' ) );
      $value = ! is_wp_error( $terms ) && ! empty( $terms ) ? join( ', ', $terms ) : 'N/A';
  }
  return $value;
}
add_action( 'manage_users_custom_column', 'wporg_show_verification_status_column_content', 10, 3 );


/**
 * 
 */

//  require_once plugin_dir_path( __FILE__ ) . 'endpoints/verification_status_endpoint.php';

function delete_taxonomy_terms() {
    $taxonomy = 'blocked_status';
    $terms_to_delete = array('Verified', 'Pending With Access','Pending With No Access',
    );
  
    foreach ($terms_to_delete as $term) {
        $term_obj = get_term_by('name', $term, $taxonomy);
  
        if ($term_obj) {
            wp_delete_term($term_obj->term_id, $taxonomy);
        }
    }
  }