<?php
 
 if (!defined('ABSPATH')) exit; // Exit if accessed directly

function phoen_admin_connector_update(){
 // wpdb update form  
    global $wpdb;
    $table_name = $wpdb->prefix . 'phoen_admin_connector_user_data';
	if ( ! current_user_can( 'update_plugins' ) ) {
		$response['message']= __( 'You do not have sufficient permissions to update the plugins for this site.', 'phoen-woo-admin' ) ;
		die();
	}
    if (check_admin_referer('phoen_admin_connector_create_form_action', 'phoen_admin_connector_create_form_action_form_nonce_field' ) ) {
        $id=isset($_POST['id']) ? intval($_POST['id']):'';
        $data = array(
        'status'          => isset($_POST['status']) ? sanitize_text_field($_POST['status']):'1',
        'username'        => isset($_POST['username']) ? sanitize_text_field($_POST['username']):'',
        'password'        => isset($_POST['password'])? sanitize_text_field($_POST['password']):'',
        'permissions'     => isset($_POST['permissions']) ? esc_html(trim(implode(',',$_POST['permissions']))) :''
      
    );
        $format = array(
        '%s',  
        '%s',
        '%s',
        '%s' 
    );

        $id = isset($_POST['id']) ? intval($_POST['id']):'';
        $status   = isset($_POST['status'])   ? sanitize_text_field($_POST['status']):'';
        $username = isset($_POST['username']) ? sanitize_text_field($_POST['username']):'';
        $password = isset($_POST['password']) ? sanitize_text_field($_POST['password']):'';

    if($id!=''){
        $user_data = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE  username=%s AND id!=%d",$username,$id));

        if($user_data){
            $response['message']= __("Username is already exist.",'phoen-woo-admin');
            $response['status']=0;
        }else{
        $success = $wpdb->update($table_name, $data,['id' => $id]);
        if ($success) {
            $qrdata=array(
                'site_url'=>site_url(),
                'username'=>$username,
                'password'=>$password
            );
            $msg=json_encode($qrdata);        
            $response = $data;
            $response['message']= __("User has been updated.",'phoen-woo-admin');
            $response['status']= 1;
            $response['qr_code'] = $msg;
            $response['uid'] = intval($id);

        }else{
            $response['message']= __("Nothing Updated.",'phoen-woo-admin');
            $response['status'] = 0;
            }
        }
        
    }else{
        $user_data = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE username=%s",$username));
            if($user_data){
                    $response['message']= __("Username is already exist.",'phoen-woo-admin');
                    $response['status']=0;
                }else{
                 // wpdb insert form 
                    $success = $wpdb->insert($table_name, $data, $format);
                    $user_id = $wpdb->get_var($wpdb->prepare("SELECT id FROM $table_name WHERE username=%s",$username));
                    
                    if ($success) {
                        
                        $qrdata=array(
                            'site_url'=>site_url(),
                            'username'=>$username,
                            'password'=>$password
                        );
                        $msg=json_encode($qrdata);
                        
                        $response = $data;
                        $response['message']= __("User name is registered.",'phoen-woo-admin');
                        $response['status']= 1;
                        $response['qr_code'] = $msg;
                        $response['uid'] = intval($user_id);
                        $response['action']='new';

                    }else{
                        $response['message']= __("User name is not registered.",'phoen-woo-admin');
                        $response['status'] = 0;
                    }

                }
        }
     
    echo json_encode($response);
    die();
    } 
}

function phoen_admin_connector_delete(){
 // wpdb Delete form 
    global $wpdb;
    $table_name = $wpdb->prefix . 'phoen_admin_connector_user_data';

    $id=isset($_POST['id']) ? intval($_POST['id']):'';
    echo $wpdb->delete( $table_name, [ 'id' => $id]);
    die();
}
?>