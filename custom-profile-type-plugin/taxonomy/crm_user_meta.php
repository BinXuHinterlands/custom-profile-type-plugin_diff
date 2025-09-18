<?php

/**
 * 
 */

 function update_crm_meta($payload,$user_id){
  // 临时禁用邮箱更改确认邮件
  add_filter('send_email_change_email', '__return_false');
  add_filter('send_site_admin_email_change_email', '__return_false');

  $email = find_key_value($payload, 'email');
  $first_name=find_key_value($payload, 'first_name');
  $last_name=find_key_value($payload, 'last_name');
  $username=find_key_value($payload, 'username');
  $can_swap_details=find_key_value($payload, 'can_swap_details');
  $culture_heritage=find_key_value($payload, 'culture_heritage');
  $user = get_userdata($user_id);
  //update email
  if ($email ) {
    $current_email =  $user->user_email;
    if(!is_email($email)){
      return new WP_Error( 'invalid', 'Invalid email address', array( 'status' => 400 ) );
    }else{
      if($current_email!=$email){
         $user_id = wp_update_user(array('ID' => $user_id, 'user_email' => $email));
      }
      if(is_wp_error($user_id)){
        return new WP_Error('email already existed!');
      }
    }
  }
  //update first name
  if($first_name){
    $current_first_name = get_user_meta($user_id, 'first_name', true);
    if($first_name!=$current_first_name){
        update_user_meta($user_id, 'first_name', $first_name);
    }
  
  }
  //update last name
  if($last_name){
    $current_last_name = get_user_meta($user_id, 'last_name', true);
    if($last_name!=$current_last_name){
        update_user_meta($user_id, 'last_name', $last_name);
    }
  }
  //update user name
  if($username){
    $current_username=$user->user_login;
    if($current_username!=$username){
       global $wpdb;
    $result =$wpdb->update(
             $wpdb->users,
             array('user_login' => $username),
             array('user_login' => $user->user_login)
    );
    }
      if ($result === false) {
        // There was an error with the query
       return new WP_Error('something went wrong');
      } else if ($result === 0) {
        // No rows were updated
      } else {
      
      }
  }
  //update can_swap_details
  if($can_swap_details){
    $current_swap = get_user_meta($user_id, 'can_swap_details', true);
    if($can_swap_details!=$current_swap){
      update_user_meta($user_id, 'can_swap_details', $can_swap_details);
    }
    
  }
  //update culture_heritage
  if($culture_heritage){
    $current_heritage=xprofile_get_field_data( "Cultural Identity", $user_id);
    if($current_heritage!=$culture_heritage){
      $culture_result =xprofile_set_field_data( "Cultural Identity", $user_id, $culture_heritage);
      if($culture_result==false){
         return new WP_Error('something went wrong when update cultureal gorups!');
      }
    }
    
  }
  // 恢复默认行为
  remove_filter('send_email_change_email', '__return_false');
  remove_filter('send_site_admin_email_change_email', '__return_false');

  return array(
    'email'=>$user->user_email,
    'username'=>$username?$username:$user->user_login,
    'first_name'=>get_user_meta($user_id, 'first_name', true),
    'last_name'=>get_user_meta($user_id, 'last_name', true),
    'culture_heritage'=>xprofile_get_field_data( "Cultural Identity", $user_id),
    'can_swap_details'=>get_user_meta($user_id, "can_swap_details", true)
  );
 }

 function find_key_value($obj, $key) {
  return $obj[$key];
}