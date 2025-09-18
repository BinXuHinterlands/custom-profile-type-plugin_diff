<?php
// Register the taxonomy
function wporg_register_taxonomy_blocked_status() {
  $args = array(
      'public' => true,
      'label'  => 'Blocked Status'
  );
  register_taxonomy( 'blocked_status', 'user', $args );
}
add_action( 'init', 'wporg_register_taxonomy_blocked_status' );

// Add terms to the taxonomy
function wporg_add_blocked_status_terms() {
  $taxonomy = 'blocked_status';
  $terms = array(
      'Not Blocked',
      'Blocked',
      'Blocked Graduated',
      'Blocked Unverified',
      'Blocked ICE Incomplete',
      'Blocked Deceased',
      'Blocked Breached'    
  );

  foreach ( $terms as $term ) {
      if ( ! term_exists( $term, $taxonomy ) ) {
          wp_insert_term( $term, $taxonomy );
      }
  }

}
add_action( 'init', 'wporg_add_blocked_status_terms' );


// Add the taxonomy to the user edit screen
function wporg_add_blocked_status_field( $user ) {
  $terms = get_terms( 'blocked_status', array( 'hide_empty' => false ) );
  $user_term = array_shift( wp_get_object_terms( $user->ID, 'blocked_status', array( 'fields' => 'ids' ) ) );
  ?>
  <h3>Blocked Status</h3>
  <table class="form-table">
      <tr>
          <th>
              <label for="blocked_status">Blocked Status</label>
          </th>
          <td>
              <select id="blocked_status" name="blocked_status">
                <?php  usort($terms, function($a, $b) {
                        return strcmp($b->name, $a->name);
                  }); ?>
                  <?php foreach( $terms as $term ) : ?>
                      <option value="<?php echo $term->term_id; ?>" <?php selected( $term->term_id, $user_term ); ?>>
                          <?php echo $term->name; ?>
                      </option>
                  <?php endforeach; ?>
              </select><br />
              <span class="description">Please select your blocked status.</span>
          </td>
      </tr>
  </table>
  <?php
}
add_action( 'show_user_profile', 'wporg_add_blocked_status_field' );
add_action( 'edit_user_profile', 'wporg_add_blocked_status_field' );

// Save the taxonomy term when the user is saved
function wporg_save_blocked_status_field( $user_id ) {
  if ( !current_user_can( 'edit_user', $user_id ) ) {
      return false;
  }
  wp_set_object_terms( $user_id, (int) $_POST['blocked_status'], 'blocked_status', false );
  clean_object_term_cache( $user_id, 'blocked_status' );
}
add_action( 'personal_options_update', 'wporg_save_blocked_status_field' );
add_action( 'edit_user_profile_update', 'wporg_save_blocked_status_field' );

// Add the new column to the user list
function wporg_add_blocked_status_column( $columns ) {
  $columns['blocked_status'] = 'Blocked Status';
  return $columns;
}
add_filter( 'manage_users_columns', 'wporg_add_blocked_status_column' );

// Fill the new column with the associated taxonomy term
function wporg_show_blocked_status_column_content( $value, $column_name, $user_id ) {
  if ( 'blocked_status' == $column_name ) {
      $terms = wp_get_object_terms( $user_id, 'blocked_status', array( 'fields' => 'names' ) );
      $value = ! is_wp_error( $terms ) && ! empty( $terms ) ? join( ', ', $terms ) : 'N/A';
  }
  return $value;
}
add_action( 'manage_users_custom_column', 'wporg_show_blocked_status_column_content', 10, 3 );


/**
 * 
 */

//  require_once plugin_dir_path( __FILE__ ) . 'endpoints/blocked_status_endpoint.php';

