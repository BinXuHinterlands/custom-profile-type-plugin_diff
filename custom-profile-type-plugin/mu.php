<?php
/**
 * Plugin Name: Livewire-CRM-Integration.
 * Description: Livewire-CRM-Integration.
 * Version: 1.0
 * Author: Livewire
 */

 /**
  * 
  */
//  require_once plugin_dir_path( __FILE__ ) . 'taxonomy/activity_status.php';
//  require_once plugin_dir_path( __FILE__ ) . 'taxonomy/blocked_status.php';
//  require_once plugin_dir_path( __FILE__ ) . 'taxonomy/profile_type.php';
 require_once plugin_dir_path( __FILE__ ) . 'taxonomy/lw_crm_sync.php';
 //user status sync endpoints
 require plugin_dir_path( __FILE__ ) . 'taxonomy/endpoints/user_status_sync.php';
 global $lw_userUpdated_count;
 $lw_userUpdated_count = array(
  'count' => 0,
  'user_id' => null 
);

//  function delete_taxonomy_terms() {
//   $taxonomy = 'blocked_status';
//   $terms_to_delete = array('Non-Blocked');
//     foreach ($terms_to_delete as $term) {
//         $term_obj = get_term_by('name', $term, $taxonomy);
//         if ($term_obj) {
//             wp_delete_term($term_obj->term_id, $taxonomy);
//         }
//     }
//   }
// add_action('init', 'delete_taxonomy_terms');


