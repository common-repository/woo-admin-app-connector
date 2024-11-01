<?php 
/*
Plugin Name: Woocommerce Admin App Connector

Plugin URI: https://phoeniixx.com/product/woo-admin-connector/

Description: This plugin will help the administrator to handle all the task from our mobile app.

Version: 1.0.3

Text Domain: phoen-woo-admin

Domain Path: /i18n/languages/

Author: phoeniixx

Author URI: https://www.phoeniixx.com

WC requires at least: 2.6.0

WC tested up to: 3.9.1

*/

if (!defined('ABSPATH')) exit; // Exit if accessed directly

if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {

    define("WOO_ADMIN_PLUGIN_URL", esc_url(plugin_dir_url(__FILE__)));

    define("WOO_ADMIN_PLUGIN_PATH", esc_url(plugin_dir_path(__FILE__)));

    include_once(WOO_ADMIN_PLUGIN_PATH.'includes/phoen-woo-admin-dbhelper.php');

    add_action('admin_enqueue_scripts', 'phoen_admin_app_script_main');

    add_action('wp_ajax_phoen_admin_connector_update','phoen_admin_connector_update');

    add_action('wp_ajax_phoen_admin_connector_delete','phoen_admin_connector_delete');

    function phoen_admin_app_script_main($hook)
    {
        if($hook=='woocommerce_page_phoeniixx_woo_admin_app'){
            wp_enqueue_style( 'phoen-woo-app-admin-jquery-ui-accordiion', WOO_ADMIN_PLUGIN_URL.'/assets/css/jquery-ui.min.css'); 
            wp_enqueue_script('jquery-ui-accordion');
            wp_enqueue_script('phoen-woo-app-admin-jquery-qrcode', WOO_ADMIN_PLUGIN_URL.'/assets/js/jquery.qrcode.min.js',array('jquery'),'1.0.0');
			wp_enqueue_style('phoen-admin-app-custom-style',WOO_ADMIN_PLUGIN_URL.'/assets/css/phoen-admin-app-backend.css');
        }
        
    }

    add_action('admin_menu', 'phoe_admin_app_menu',100);

    function phoe_admin_app_menu()
    {

        $app = __('Woocommerce Admin App', 'phoen-woo-admin-app');

        add_submenu_page('woocommerce', 'phoeniixx_woo_admin_app', $app, 'manage_options', 'phoeniixx_woo_admin_app', 'phoen_admin_app_backend_func');
        
    }

    register_activation_hook(__FILE__, 'phoen_admin_app_plugin_activation');

    function phoen_admin_app_plugin_activation()
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $table_phoen_user_data = $wpdb->prefix . 'phoen_admin_connector_user_data';
      
        $sql = "";

        #Check to see if the table exists already, if not, then create it
        if ($wpdb->get_var("show tables like '{$table_phoen_user_data}'") != $table_phoen_user_data) {
            $sql .= "CREATE TABLE $table_phoen_user_data (
                    id int(9) NOT NULL AUTO_INCREMENT,
                    status varchar(255) NOT NULL,
                    username varchar(255) NOT NULL,
                    password varchar(255) NOT NULL,
                    permissions varchar(1000) NOT NULL,
                    PRIMARY KEY  (id)
                    )$charset_collate; ";
        }
        
        if ($sql != '') {
            require_once(ABSPATH . '/wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }
    }   

    add_action('rest_api_init', 'phoen_woo_admin_app_template');

    function phoen_woo_admin_app_template()
    {

        /*---------------- Weight_unit Api List -------------------*/

        register_rest_route('wc/v2', '/general_setting', array(
            'methods' => WP_REST_Server::ALLMETHODS,
            'callback' => 'phoen_woo_admin_app_unit',
            'args' => array(),
        ));

        /*---------------- Weight_unit API List End -------------------*/

        /*---------------- Admin connector Api List -------------------*/

        register_rest_route('wc/v2', '/admin_connector_login', array(
            'methods' => WP_REST_Server::ALLMETHODS,
            'callback' => 'phoen_woo_admin_app_connector_login',
            'args' => array(),
        ));

        /*---------------- Admin connector Api List End -------------------*/

        /*---------------- Admin customer_roles Api List ------------------*/   

        register_rest_route('wc/v2', '/customer_role', array(
            'methods' => WP_REST_Server::ALLMETHODS,
            'callback' => 'phoen_woo_admin_app_connector_customer_role',
            'args' => array(),
        ));      

        /*---------------- Admin customer_roles Api List End -------------------*/

    }

    function phoen_woo_admin_app_unit()
    {
        include_once(WOO_ADMIN_PLUGIN_PATH. 'app/phoen-woo-admin-general-settings.php');

        return new WP_REST_Response($response, 200);
    }

    function phoen_woo_admin_app_connector_login()
    {

        include_once(WOO_ADMIN_PLUGIN_PATH. 'app/phoen-woo-admin-setting.php');

        return new WP_REST_Response($response, 200);
    }

    function phoen_woo_admin_app_connector_customer_role()
    {

        include_once(WOO_ADMIN_PLUGIN_PATH. 'app/phoen-woo-admin-customer_role.php');

        return new WP_REST_Response($response, 200);
    }

    function phoen_admin_app_backend_func()
    { ?>

        <div id="profile-page" class="wrap">
            <?php
                $tab = isset($_GET['tab']) ? trim(sanitize_text_field($_GET['tab'])) : '';
            ?>
            <h2> <?php _e('Woocommerce Mobile Admin Connector', 'phoen-woo-admin-app'); ?></h2>
                    
            <h2 class="nav-tab-wrapper woo-nav-tab-wrapper">
                    
            <a class="nav-tab <?php if ($tab == 'users' || $tab == '') { echo esc_attr("nav-tab-active"); } ?>" href="?page=phoeniixx_woo_admin_app&amp;tab=users"><?php echo esc_html('Users', 'phoen-woo-admin-app'); ?></a>

            <a class="nav-tab <?php if ($tab == 'customer_key') { echo esc_attr("nav-tab-active");} ?>" href="?page=phoeniixx_woo_admin_app&amp;tab=customer_key"><?php echo esc_html('API Key', 'phoen-woo-admin-app'); ?></a>
                        
            </h2>
                    
        </div>
        <?php

        if ($tab == '' || $tab == 'users') {

            include_once(WOO_ADMIN_PLUGIN_PATH.'includes/phoen-woo-admin-user-setting.php');
            
        } 
        if ($tab == 'customer_key') {

          include_once(WOO_ADMIN_PLUGIN_PATH.'includes/phoen-woo-admin-customer-key.php');
          
        } 
    }

}
?>
