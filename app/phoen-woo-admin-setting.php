<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

global $wpdb;

$table_phoen_user_data = $wpdb->prefix . 'phoen_admin_connector_user_data';

if(isset($_GET['username']) && isset($_GET['password'])){
                
    $username = sanitize_text_field($_GET['username']);

    $password = sanitize_text_field($_GET['password']);

    $user_admin=$wpdb->get_row($wpdb->prepare("SELECT * from {$table_phoen_user_data} where username=%s AND password=%s",$username,$password ));

    if($user_admin){

        $users = get_users( 'role=administrator' );

        foreach($users as $user){

            $consumer_key = get_user_meta($user->ID, 'consumer_key', true);
            $consumer_secret = get_user_meta($user->ID, 'consumer_secret', true);

    
            if($consumer_key!=null){
                break;
            }
        }

        if($consumer_key==null){

            $response['code']=0;
            $response['message'] = __("Please create customer secret and consumer key with read/write permission from woocommerce.","phoen-woo-admin");
			
        }else{

            $response['code']=1;
            $response['message']= __("Login successfully.","phoen-woo-admin");
            $response['details']['consumer_key']=sanitize_text_field($consumer_key);
            $response['details']['consumer_secret']=sanitize_text_field($consumer_secret);
            $response['details']['id']=intval($user_admin->id);
            $response['details']['status']=sanitize_text_field($user_admin->status);
            $response['details']['username']=sanitize_text_field($user_admin->username);
            $response['details']['password']=sanitize_text_field($user_admin->password);
            $response['details']['new_order_created']=phoen_woo_admin_contains('new_order_created',$user_admin->permissions);
            $response['details']['order_status_changed']=phoen_woo_admin_contains('order_status_changed',$user_admin->permissions);
            $response['details']['new_customer_created']=phoen_woo_admin_contains('new_customer_created',$user_admin->permissions);
            $response['details']['view_order_list']=phoen_woo_admin_contains('view_order_list',$user_admin->permissions);
            $response['details']['view_order_details']=phoen_woo_admin_contains('view_order_details',$user_admin->permissions);
            $response['details']['change_order_status']=phoen_woo_admin_contains('change_order_status',$user_admin->permissions);
            $response['details']['view_customer_list']=phoen_woo_admin_contains('view_customer_list',$user_admin->permissions);
            $response['details']['view_customer_details']=phoen_woo_admin_contains('view_customer_details',$user_admin->permissions);
            $response['details']['view_product_list']=phoen_woo_admin_contains('view_product_list',$user_admin->permissions);
            $response['details']['view_product_details']=phoen_woo_admin_contains('view_product_details',$user_admin->permissions);
            $response['details']['product_editing']=phoen_woo_admin_contains('product_editing',$user_admin->permissions);
        
            
        }

    }else{
        $response['code']=0;
        $response['message']= __("Username and Password Does not Match",'phoen-woo-admin');
    }
}else{
        $response['code'] = 0;
        $response['message']= __("Username and Password is required.",'phoen-woo-admin');

}

function phoen_woo_admin_contains($permission,$permissions){

    $permissions = explode(',',$permissions);

    if(in_array($permission,$permissions)){
		return true;
    }
    
	return false;
}

?>