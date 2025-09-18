<?php
// Register the taxonomy
function wporg_register_taxonomy_profile_type() {
  $args = array(
      'public' => true,
      'label'  => 'Profile Type'
  );
  register_taxonomy( 'profile_type', 'user', $args );
}
add_action( 'init', 'wporg_register_taxonomy_profile_type' );

// Add terms to the taxonomy
function wporg_add_profile_type_terms() {
  $taxonomy = 'profile_type';
  $terms = array(
      'Verified',
      'Pending With Access',
      'Pending With No Access'
  );

  foreach ( $terms as $term ) {
      if ( ! term_exists( $term, $taxonomy ) ) {
          wp_insert_term( $term, $taxonomy );
      }
  }
}
add_action( 'init', 'wporg_add_profile_type_terms' );


// Add the taxonomy to the user edit screen
function wporg_add_profile_type_field( $user ) {
  $taxonomy_name ='profile_type';
  $terms = get_terms( $taxonomy_name, array( 'hide_empty' => false ) );
  $user_term = array_shift( wp_get_object_terms( $user->ID, $taxonomy_name, array( 'fields' => 'ids' ) ) );
  ?>
  <h3>Verification Status</h3>
  <table class="form-table">
      <tr>
          <th>
              <label for="profile_type">Verification Type</label>
          </th>
          <td>
              <select id="profile_type" name="profile_type">
                <?php  usort($terms, function($a, $b) {
                        return strcmp($b->name, $a->name);
                  }); ?>
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
add_action( 'show_user_profile', 'wporg_add_profile_type_field' );
add_action( 'edit_user_profile', 'wporg_add_profile_type_field' );

// Save the taxonomy term when the user is saved
function wporg_save_profile_type_field( $user_id ) {
  if ( !current_user_can( 'edit_user', $user_id ) ) {
      return false;
  }
  wp_set_object_terms( $user_id, (int) $_POST['profile_type'], 'profile_type', false );
  clean_object_term_cache( $user_id, 'profile_type' );
}
add_action( 'personal_options_update', 'wporg_save_profile_type_field' );
add_action( 'edit_user_profile_update', 'wporg_save_profile_type_field' );

// Add the new column to the user list
function wporg_add_profile_type_column( $columns ) {
  $columns['profile_type'] = 'Verification Status';
  return $columns;
}
add_filter( 'manage_users_columns', 'wporg_add_profile_type_column' );

// Fill the new column with the associated taxonomy term
function wporg_show_profile_type_column_content( $value, $column_name, $user_id ) {
  if ( 'profile_type' == $column_name ) {
      $terms = wp_get_object_terms( $user_id, 'profile_type', array( 'fields' => 'names' ) );
      $value = ! is_wp_error( $terms ) && ! empty( $terms ) ? join( ', ', $terms ) : 'N/A';
  }
  return $value;
}
add_action( 'manage_users_custom_column', 'wporg_show_profile_type_column_content', 10, 3 );


/**
 * 
 */

 

