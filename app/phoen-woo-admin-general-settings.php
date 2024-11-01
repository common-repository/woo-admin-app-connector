<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

    $response['measurement']['weight_unit']=get_option('woocommerce_weight_unit');
    $response['measurement']['dimension_unit']=get_option('woocommerce_dimension_unit');
    $response['review']['enable_product_reviews']=get_option('woocommerce_enable_reviews');
    $response['review']['review_rating_verification_label']=get_option('woocommerce_review_rating_verification_label');
    $response['review']['review_rating_verification_required']=get_option('woocommerce_review_rating_verification_required');

    $response['rating']['enable_review_rating']=get_option('woocommerce_enable_review_rating');
    $response['rating']['review_rating_required']=get_option('woocommerce_review_rating_required');

    $response['currency_option']['currency']=get_option('woocommerce_currency');
    $response['currency_option']['currency_pos']=get_option('woocommerce_currency_pos');
    $response['currency_option']['price_thousand_sep']=get_option('woocommerce_price_thousand_sep');
    $response['currency_option']['price_decimal_sep']=get_option('woocommerce_price_decimal_sep');
    $response['currency_option']['price_num_decimals']=get_option('woocommerce_price_num_decimals');

    $response['inventory']['manage_stock']=get_option('woocommerce_manage_stock');
    $response['inventory']['hold_stock_minutes']=get_option('woocommerce_hold_stock_minutes');
    $response['inventory']['notification']['notify_low_stock']=get_option('woocommerce_notify_low_stock');
    $response['inventory']['notification']['notify_no_stock']=get_option('woocommerce_notify_no_stock');
    $response['inventory']['stock_email_recipient']=get_option('woocommerce_stock_email_recipient');
    $response['inventory']['notify_low_stock_amount']=get_option('woocommerce_notify_low_stock_amount');
    $response['inventory']['notify_no_stock_amount']=get_option('woocommerce_notify_no_stock_amount');
    $response['inventory']['hide_out_of_stock_items']=get_option('woocommerce_hide_out_of_stock_items');
    $response['inventory']['stock_format']=get_option('woocommerce_stock_format');

?>