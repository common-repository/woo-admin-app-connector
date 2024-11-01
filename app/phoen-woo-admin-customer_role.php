
<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

$roles = wp_roles()->get_names();

$response=array();

foreach ($roles as $key => $value) {
    
    $response['roles'][]=array(
        "id"=> $key,
        "label"=> $value
    );
 
}

$order_status = wc_get_order_statuses();

foreach ($order_status as $key => $value) {
    
    $response['order_status'][]=array(
        "id"=> $key,
        "label"=> $value
    );
 
}