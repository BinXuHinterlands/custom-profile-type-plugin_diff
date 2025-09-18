<?php

/***
 * delete wp_terms
 */

 function delete_taxonomy_terms() {
  $taxonomy = 'blocked_status';
  $terms_to_delete = array('Blocked_Graduated', 'Blocked_Unverified','Blocked_ICE_Incomplete',
  'Blocked_Deceased','Blocked_Breached');

  foreach ($terms_to_delete as $term) {
      $term_obj = get_term_by('name', $term, $taxonomy);

      if ($term_obj) {
          wp_delete_term($term_obj->term_id, $taxonomy);
      }
  }
}

function check_request_error(){}