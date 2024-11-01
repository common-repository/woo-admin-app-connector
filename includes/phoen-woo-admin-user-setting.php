<?php 

if(! defined('ABSPATH')) exit; // Exit if accessed directly

global $wpdb;
$table_phoen_user_data = $wpdb->prefix . 'phoen_admin_connector_user_data';

$users=$wpdb->get_results("SELECT * from $table_phoen_user_data");
?>

<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <script>  
    jQuery( function() {
        jQuery( ".user_div" ).accordion({
            collapsible: true,
            active: true
        });

        jQuery(".user_div").on("submit","form",(e)=>{
            var form=jQuery(e.target);
            
            jQuery.ajax({
                    type: "POST",
                    url: fileurl,
                    data:form.serialize() + "&action=phoen_admin_connector_update", // serializes the form's elements.
                    success: function(data){

                        var all_data = JSON.parse(data);
                        var message = all_data.message;

                           jQuery(form).find('.message_update').html('<h1><b><span>'+all_data.message+'</span></b></h1>');
                           var qr_code = all_data['qr_code'];
                           
                            if(all_data['status']==1){
                                
                                
                                if(all_data['action']=='new'){
                                    jQuery('#qrcode_new').prop('id','qrcode'+all_data.uid);
                                    jQuery('#uid').val(all_data.uid);
                                    jQuery(form).prop('id','form'+all_data.uid);
                                    jQuery('#add_user').removeAttr("disabled");
                                }
                                jQuery('#qrcode'+all_data.uid).empty();
                                jQuery('#qrcode'+all_data.uid).qrcode({width:250,height: 250,text:qr_code});
                                jQuery(form).parent().prev('h3').html('<span class="ui-accordion-header-icon ui-icon ui-icon-triangle-1-e"></span>'+ all_data['username']);

                            }else{
                                jQuery();
                            }

                   }
                });   
                e.preventDefault(); // avoid to execute the actual submit of the form.
        });
        
        var fileurl= '<?php echo admin_url( 'admin-ajax.php' );?>';
        jQuery( ".user_div" ).accordion( "refresh" );
        jQuery('#add_user').click(function(){
            jQuery(this).attr("disabled", "disabled");
            var accordion_html = jQuery('#accordion').html();
            jQuery(".user_div").append(accordion_html).ready(function(){

        jQuery("#create_user").click(function () {
            var password = jQuery(".password").val();
            var confirmPassword = jQuery(".confirm_password").val();
            if(jQuery(".confirm_password").val()!==jQuery(".password").val()){
                    alert("Passwords do not match.");
                    return false;
                }
        });
             });
            jQuery( ".user_div" ).accordion( "refresh" );
        });

    <?php
        foreach($users as  $value){
            $i='#qrcode'.$value->id;
            $data=array(
                'site_url'=>site_url(),
                'username'=>sanitize_text_field($value->username),
                'password'=>sanitize_text_field($value->password)
            );
            $msg=json_encode($data);
            echo "jQuery('$i').qrcode({width:250,height: 250,text: '$msg'});";
        ?>
            
            jQuery("#form<?php echo $value->id; ?>").submit(function(e) {
                var form = jQuery(this);
               
                  
            });
    
    <?php
        }
    ?>
    });
    jQuery(function(){
        var fileurl= '<?php echo admin_url( 'admin-ajax.php' );?>';
        jQuery('.delete_user').click(function(e){
            var id= jQuery(this).attr('id');
            var btn= jQuery(this);
            jQuery.ajax({
                type:"POST",
                url:fileurl,
                data:"action=phoen_admin_connector_delete&id="+id,
                success: function(data){
                        jQuery(btn).parent().parent().prev('h3').addBack().remove();
                      
                    }

                })
            })

       
    });

</script>
</head>

<body>
<br /> 

<div class="user_div">

    <?php
        if(is_array($users) && !empty($users)){

            foreach($users as $value){

                $id=intval($value->id);
        
                $status=sanitize_text_field($value->status);

                $username=sanitize_text_field($value->username);
    
                $password=sanitize_text_field($value->password);
                
                $permissions= explode(",",$value->permissions);
                            
    
        ?>   
                 
                <h3><?php echo $username;?></h3>
                <div>
                    <form method="post" id="form<?php echo $id;?>">
                    <!-- Modal -->
                    <?php wp_nonce_field( 'phoen_admin_connector_create_form_action', 'phoen_admin_connector_create_form_action_form_nonce_field' ); ?>
            
                    <table class="form-table">
                        <tbody>
                            <!-- Modal Body -->
                            <tr valign="top main-row">
                                <th scope="row">
                                    <label for="status"><?php _e( 'Status', 'phoen-woo-admin' ); ?></label>
                                </th>
                                <td class="	"> 
                                    <select name="status" id="input-status" class="form-control" >
                                        <option value="1" <?php echo $status;?> <?php if($status==1){ echo 'selected';} ?>><?php _e( 'Enabled', 'phoen-woo-admin' ); ?></option>
                                        <option value="0" <?php echo $status;?> <?php if($status==0){ echo 'selected';} ?>><?php _e( 'Disabled', 'phoen-woo-admin' ); ?></option>
                                    </select>
                                </td>
                                <td rowspan="7">
                                <div id="qrcode<?php echo $id; ?>" ></div>
                                </td>
                            </tr>
    
                            <tr valign="top">
                                <th scope="row">
                                    <label for="username"><?php _e( 'Username', 'phoen-woo-admin' ); ?></label>
                                </th>
                                <td> 
                                    <input type="text" id="" value="<?php echo $username;?>" name="username"  required/>
                                </td>
                            </tr>
    
                            <tr valign="top">
                                <th scope="row">
                                    <label for="password"><?php _e( 'Password', 'phoen-woo-admin' ); ?></label>
                                </th>
                                <td> 
                                    <input type="password" id="" value="<?php echo $password;?>" name="password"  required/>
                                </td>
                            </tr>
    
                            <tr valign="top" class="label_row first">
                                <th scope="row">
                                    <label for="permissions"><?php _e( 'Permissions', 'phoen-woo-admin' ); ?></label>
                                <td class="heading_td">
                                    <label for="push_notification"><?php _e( 'Push Notification', 'phoen-woo-admin' ); ?></label>
                                </td>
                                </th>
                            </tr>
    
                            <tr valign="top" class="label_row">
                        
                                <th> </th>
                                <td>
                                    <input type="checkbox" value="new_order_created" <?php echo (in_array('new_order_created',$permissions))?'checked':''; ?>  name="permissions[]" value="true" />
                                    <label for="new_order_created"><?php _e( 'New Order Created', 'phoen-woo-admin' ); ?></label>
                                </td>
                            </tr>
    
                            <tr valign="top" class="label_row">
                        
                                <th> </th>
                                <td>
                                    <input type="checkbox" value="order_status_changed" <?php echo (in_array('order_status_changed',$permissions))?'checked':''; ?>  name="permissions[]" value="true" />
                                    <label for="order_status_changed"><?php _e( 'Order Status Changed', 'phoen-woo-admin' ); ?></label>
                                </td>
                            </tr>
    
                            <tr valign="top" class="label_row">
                        
                                <th> </th>
                                <td>
                                    <input type="checkbox" value="new_customer_created"  <?php echo (in_array('new_customer_created',$permissions))?'checked':''; ?>  name="permissions[]" value="true" />
                                    <label for="new_customer_created"><?php _e( 'New Customer Created', 'phoen-woo-admin' ); ?></label>
                                </td>
                            </tr>
    
                            <tr valign="top" class="label_row">
                                <th scope="row">
                                <td class="heading_td">
                                    <label for="order"><?php _e( 'Orders', 'phoen-woo-admin' ); ?></label>
                                </td>
                                </th>
                            </tr>
    
                            <tr valign="top" class="label_row">
                        
                                 <th> </th>
                                 <td>
                                     <input type="checkbox" value="view_order_list" <?php echo (in_array('view_order_list',$permissions))?'checked':''; ?>  name="permissions[]" value="true" />
                                     <label for="view_order_list"><?php _e( 'View order list', 'phoen-woo-admin' ); ?></label>
                                 </td>
                             </tr>
    
                            <tr valign="top" class="label_row">
                        
                                <th> </th>
                                <td>
                                
                                    <input type="checkbox" value="view_order_details" <?php echo (in_array('view_order_details',$permissions))?'checked':''; ?>  name="permissions[]" value="true" />  
                                    <label for="view_order_details"><?php _e( 'View order details', 'phoen-woo-admin' ); ?></label>
                                </td>
                            </tr>
    
                            <tr valign="top" class="label_row">
    
                                <th> </th>
                                <td>
                                        <input type="checkbox" value="change_order_status" <?php echo (in_array('change_order_status',$permissions))?'checked':''; ?>  name="permissions[]" value="true" />  
                                        <label for="change_order_status"><?php _e( 'Change order status', 'phoen-woo-admin' ); ?></label>
                                </td>
                            </tr>
    
                            <tr valign="top" class="label_row">
                                <th scope="row">
                               <td class="heading_td">
                                    <label for="customer"><?php _e( 'Customers', 'phoen-woo-admin' ); ?></label>
                                </td>
                                </th>
                            </tr>
    
                            <tr valign="top" class="label_row">
                        
                                 <th> </th>
                                 <td>
                                     <input type="checkbox" value="view_customer_list" <?php echo (in_array('view_customer_list',$permissions))?'checked':''; ?>  name="permissions[]" value="true" /> 
                                     <label for="view_customer_list"><?php _e( 'View customer list', 'phoen-woo-admin' ); ?></label>
                                 </td>
                             </tr>
    
                            <tr valign="top" class="label_row">
                        
                                <th> </th>
                                <td>
                                    <input type="checkbox" value="view_customer_details" <?php echo (in_array('view_customer_details',$permissions))?'checked':''; ?>  name="permissions[]" value="true" />
                                    
                                    <label for="view_customer_details"><?php _e( 'View customer details', 'phoen-woo-admin' ); ?></label>
                                </td>
                            </tr>
    
                            <tr valign="top" class="label_row">
                                <th scope="row">
                                <td class="heading_td">
                                    <label for="product"><?php _e( 'Products', 'phoen-woo-admin' ); ?></label>
                                </td>
                                </th>
                            </tr>
    
                            <tr valign="top" class="label_row">
                        
                                 <th> </th>
                                 <td>
                                     <input type="checkbox" value="view_product_list" <?php echo (in_array('view_product_list',$permissions))?'checked':''; ?>  name="permissions[]" value="true" />
                                     
                                     <label for="view_product_list"><?php _e( 'View product list', 'phoen-woo-admin' ); ?></label>
                                 </td>
                             </tr>
    
                            <tr valign="top" class="label_row">
                        
                                <th> </th>
                                <td>
                                    <input type="checkbox" value="view_product_details" <?php echo (in_array('view_product_details',$permissions))?'checked':''; ?>  name="permissions[]" value="true" />
                                    
                                    <label for="view_product_details"><?php _e( 'View product details', 'phoen-woo-admin' ); ?></label>
                                </td>
                            </tr>
    
                            <tr valign="top" class="label_row">
    
                                <th> </th>
                                <td>
                                        <input type="checkbox" value="product_editing" <?php echo (in_array('product_editing',$permissions))?'checked':''; ?>  name="permissions[]" value="true" />
                                        
                                        <label for="product_editing"><?php _e( 'Product editing', 'phoen-woo-admin' ); ?></label>
                                </td>
                            </tr>
    
                
                        </tbody>
            
                    </table>
                    <input type="hidden" value="<?php echo $id;?>" name="id" />
                    <div class="message_update"></div>
                    <input type="submit" name="update_user" class=" button-primary" value="<?php _e( 'Save User', 'phoen-woo-admin' ); ?>" />
                    <button type="button" name="delete_user" class="delete_user button-primary" id="<?php echo $id;?>" class="delete_btn button-primary" style="background-color:red;color:white;text-shadow: none;" ><?php _e('Delete User','');?></button> 
                   
                    </form>
    
                </div>
            <?php
            }
           
        }
        
    ?>
</div>

<div class="accordion_div" style="display:none;">
    <div id="accordion">
    <h3><?php _e('New User','phoen-woo-admin');?></h3>
        <div>
             <!-- Modal Body -->
            <form method="post" id="new_user_form">
		        <?php wp_nonce_field( 'phoen_admin_connector_create_form_action', 'phoen_admin_connector_create_form_action_form_nonce_field' ); ?>
        
		        <table class="form-table">
			        <tbody> 

                        <tr valign="top">
                            <th scope="row">
                                <label for="username"><?php _e( 'Username', 'phoen-woo-admin' ); ?></label>
                            </th>

                            <td> 
                                <input type="text" id="" value="" name="username"  required />
                            </td>
                            <td rowspan="7">
                                <div id="qrcode_new" ></div>
                            </td>   
                        </tr>

                        <tr valign="top">
                            <th scope="row">
                                <label for="password"><?php _e( 'Password', 'phoen-woo-admin' ); ?></label>
                            </th>
                            <td> 
                                <input type="password" id="" value="" name="password" class="password" required/>
                            </td>
                        </tr>

                        <tr valign="top">
                            <th scope="row">
                                <label for="confirm_password"><?php _e( 'Confirm Password', 'phoen-woo-admin' ); ?></label>
                            </th>
                            <td> 
                                <input type="password" id="" value="" name="confirm_password" class="confirm_password" required />
                            </td>
                        </tr>

                        <tr valign="top" class="label_row first">
					        <th scope="row">
						        <label for="permissions"><?php _e( 'Permissions', 'phoen-woo-admin' ); ?></label>
                            <td class="heading_td">
                                <label for="push_notification"><?php _e( 'Push Notification', 'phoen-woo-admin' ); ?></label>
					        </td>
					        </th>
                        </tr>

                        <tr valign="top" class="label_row">
					
                            <th> </th>
                            <td>
                                <input type="checkbox" name="permissions[]" class="" value="new_order_created" />
                                <label for="new_order_created"><?php _e( 'New Order Created', 'phoen-woo-admin' ); ?></label>
                            </td>
                        </tr>

                        <tr valign="top" class="label_row">
					
                            <th> </th>
                            <td>
                                <input type="checkbox" name="permissions[]" class=""  value="order_status_changed" />
                                <label for="order_status_changed"><?php _e( 'Order Status Changed', 'phoen-woo-admin' ); ?></label>
                            </td>
                        </tr>

                        <tr valign="top" class="label_row">
					
                            <th> </th>
                            <td>
                                <input type="checkbox" name="permissions[]" class=""  value="new_customer_created" />
                                <label for="new_customer_created"><?php _e( 'New Customer Created', 'phoen-woo-admin' ); ?></label>
                            </td>

                        </tr>

                         <tr valign="top" class="label_row">
					        <th scope="row">
                            <td class="heading_td">
                                <label for="order"><?php _e( 'Orders', 'phoen-woo-admin' ); ?></label>
					        </td>
                            </th>
                        </tr>

                        <tr valign="top" class="label_row">
					
                             <th> </th>
                             <td>
                                 <input type="checkbox" name="permissions[]" class="" value="view_order_list" />
                                 <label for="view_order_list"><?php _e( 'View order list', 'phoen-woo-admin' ); ?></label>
                             </td>
                         </tr>

                        <tr valign="top" class="label_row">
					
                            <th> </th>
                            <td>
                                <input type="checkbox" name="permissions[]" class="" value="view_order_details" />
                                <label for="view_order_details"><?php _e( 'View order details', 'phoen-woo-admin' ); ?></label>
                            </td>
                        </tr>

                        <tr valign="top" class="label_row">

                            <th> </th>
                            <td>
                                    <input type="checkbox" name="permissions[]" class="" value="change_order_status" />
                                    <label for="change_order_status"><?php _e( 'Change order status', 'phoen-woo-admin' ); ?></label>
                            </td>
                        </tr>

                        <tr valign="top" class="label_row">
					        <th scope="row">
                          <td class="heading_td">
                                <label for="customer"><?php _e( 'Customers', 'phoen-woo-admin' ); ?></label>
					        </td>
                            </th>
                        </tr>

                        <tr valign="top" class="label_row">
					
                             <th> </th>
                             <td>
                                 <input type="checkbox" name="permissions[]" class="" value="view_customer_list" />
                                 <label for="view_customer_list"><?php _e( 'View customer list', 'phoen-woo-admin' ); ?></label>
                             </td>
                         </tr>

                        <tr valign="top" class="label_row">
					
                            <th> </th>
                            <td>
                                <input type="checkbox" name="permissions[]" class="" value="view_customer_details" />
                                <label for="view_customer_details"><?php _e( 'View customer details', 'phoen-woo-admin' ); ?></label>
                            </td>
                        </tr>

                        <tr valign="top" class="label_row">
					        <th scope="row">
                            <td class="heading_td">
                                <label for="product"><?php _e( 'Products', 'phoen-woo-admin' ); ?></label>
					        </td>
                            </th>
                        </tr>

                        <tr valign="top" class="label_row">
					
                             <th> </th>
                             <td>
                                 <input type="checkbox" name="permissions[]" class="" value="view_product_list" />
                                 <label for="view_product_list"><?php _e( 'View product list', 'phoen-woo-admin' ); ?></label>
                             </td>
                         </tr>

                        <tr valign="top" class="label_row">
					
                            <th> </th>
                            <td>
                                <input type="checkbox" name="permissions[]" class="" value="view_product_details" />
                                <label for="view_product_details"><?php _e( 'View product details', 'phoen-woo-admin' ); ?></label>
                            </td>
                        </tr>

                        <tr valign="top" class="label_row">

                            <th> </th> 
                            <td>
                                    <input type="checkbox" name="permissions[]" class="" value="product_editing" />
                                    <label for="product_editing"><?php _e( 'Product editing', 'phoen-woo-admin' ); ?></label>
                            </td>
                        </tr>

                    </tbody>

                </table>
                
                <div  class="message_update"></div>
                <input type="hidden" name="id" value="" id="uid">
                <input type="submit" id="create_user" name="create_user" class="button-primary" value="<?php _e( 'Save', 'phoen-woo-admin' ); ?>" />
            </form>
        </div>
    </div>
</div>

<br />
<input type="button" id="add_user" class="button-primary" value="Add User"></button>

</body>

</html>