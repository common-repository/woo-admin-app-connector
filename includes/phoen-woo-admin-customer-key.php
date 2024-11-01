<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
if ( current_user_can( 'update_plugins' ) ) {
	if ( isset( $_POST['consumer_save'] ) && check_admin_referer( 'phoen_admin_connector_form_action', 'phoen_admin_connector_form_action_form_nonce_field' ) ) {
	   
		$consumer_key=isset($_POST['consumer_key'])? sanitize_text_field($_POST['consumer_key']):'';
		$consumer_secret=isset($_POST['consumer_secret'])? sanitize_text_field($_POST['consumer_secret']):'';

		update_user_meta( get_current_user_id(), 'consumer_key', $consumer_key); 
		update_user_meta( get_current_user_id(), 'consumer_secret', $consumer_secret);

	}

}

$consumer_key = get_user_meta( get_current_user_id(), 'consumer_key', true);
$consumer_secret = get_user_meta( get_current_user_id(), 'consumer_secret', true);

?>
<form method="post">

<?php wp_nonce_field( 'phoen_admin_connector_form_action', 'phoen_admin_connector_form_action_form_nonce_field' ); ?>

    <table class="consumer_key_table form-table">

        <tr valign="top">
            <!-- Modal Woocommerce API Consumer Key and Woocommerce API Consumer Secret Key  -->
			<th scope="row">
				<label for="consumer_key"><?php _e('Woocommerce API Consumer Key','phoen-admin-connector');?></label>
			</th>
		    <td> 

			    <input type="text" id="consumer_key" value="<?php echo $consumer_key; ?>" name="consumer_key" required><br />
                <label class="tm-epo-field-label"><span class="tooltiptext"><a href="?page=wc-settings&tab=advanced&section=keys"><?php _e('Create a new Woocommerce API Consumer Key.','phoen-admin-connector');?></a></span></label>
				
		    </td>
		</tr>


        <tr valign="top">
			<th scope="row">
						<label for="consumer_secret"><?php _e('Woocommerce API Consumer Secret Key','phoen-admin-connector');?></label>
			</th>
			<td> 
				<input type="text" id="consumer_secret" value="<?php echo $consumer_secret; ?>" name="consumer_secret" required><br />
                  <label class="tm-epo-field-label">
				  <span class="tooltiptext"><a href="?page=wc-settings&tab=advanced&section=keys"><?php _e('Create a new Woocommerce API Consumer Secret Key.','phoen-admin-connector');?></a></span></label>
					
			</td>
		</tr>
       
    </table>
    <input type="submit" name="consumer_save" class="button-primary" value="<?php _e( 'Save', 'phoen-admin-connector' ); ?>"/>
</form>