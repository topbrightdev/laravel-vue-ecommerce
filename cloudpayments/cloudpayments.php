<?php
/*
Plugin Name: Cloudpayments
Description:   Платежная система Cloudpayments
Version: 1.1
Author: Web-sputnik
License: GPL2
*/

define('cloudpayments_DIR', plugin_dir_path(__FILE__)); 
define('cloudpayments_URL', plugin_dir_url(__FILE__));

require_once( cloudpayments_DIR . 'class.cloudpayments-admin.php' );

global $cloudpayments_admin_class;
$cloudpayments_admin_class = new cloudpayments_admin;

register_activation_hook(__FILE__, 'cloud_activation');
register_deactivation_hook(__FILE__, 'cloud_deactivation');
register_uninstall_hook(__FILE__, 'cloud_deactivation');

add_filter( 'wpsc_set_purchlog_statuses','add_custom_status');
add_filter( 'wpsc_set_purchlog_statuses','add_custom_status_auth');    
   
function add_custom_status( $statuses ) 
{
  global $cloudpayments_admin_class;
   $new_statuses=array(
  	array(
  		'internalname'  =>	'STATUS_CHANCEL',
  		'label'         =>	$cloudpayments_admin_class->get_lang_mess('STATUS_CHANCEL'),
  		'order'         => 	31,
  	),
  );
	
	return array_merge( $statuses, $new_statuses );
}

function add_custom_status_auth( $statuses ) 
{
  global $cloudpayments_admin_class;
   $new_statuses=array(
  	array(
  		'internalname'  =>	'STATUS_AUTH',
  		'label'         =>	$cloudpayments_admin_class->get_lang_mess('STATUS_AUTH'),
  		'order'         => 	32,
  	),
  );
	
	return array_merge( $statuses, $new_statuses );
}

if (is_admin()) 
{ 
      add_action('admin_menu', array('cloudpayments_admin', 'init'));
} 

function cloud_activation()
{
      if (is_dir($_SERVER['DOCUMENT_ROOT'].'/wp-content/plugins/wp-e-commerce/wpsc-merchants')) 
      {
              copy(cloudpayments_DIR."install/cloudpayments.php", $_SERVER['DOCUMENT_ROOT']."/wp-content/plugins/wp-e-commerce/wpsc-merchants/cloudpayments.php");
      }       
      global $wpdb;    
      $wpdb->insert('wp_options',array('option_name' => 'cloudpayments_public_ID', 'option_value' => '','autoload'=>'yes'),array('%s','%s','%s'));
      $wpdb->insert('wp_options',array('option_name' => 'cloudpayments_curcode', 'option_value' => 'RUB','autoload'=>'yes'),array('%s','%s','%s'));
      $wpdb->insert('wp_options',array('option_name' => 'cloudpayments_language', 'option_value' => 'ru','autoload'=>'yes'),array('%s','%s','%s'));
      $wpdb->insert('wp_options',array('option_name' => 'cloudpayments_skin', 'option_value' => 'classic','autoload'=>'yes'),array('%s','%s','%s'));
      $wpdb->insert('wp_options',array('option_name' => 'cloudpayments_payment_schemes', 'option_value' => 'charge','autoload'=>'yes'),array('%s','%s','%s'));
      $wpdb->insert('wp_options',array('option_name' => 'cloudpayments_url', 'option_value' => 'https://api.cloudpayments.ru','autoload'=>'yes'),array('%s','%s','%s'));
      $wpdb->insert('wp_options',array('option_name' => 'cloudpayments_success_url', 'option_value' => '','autoload'=>'yes'),array('%s','%s','%s'));
      $wpdb->insert('wp_options',array('option_name' => 'cloudpayments_fail_url', 'option_value' => '','autoload'=>'yes'),array('%s','%s','%s'));
      $wpdb->insert('wp_options',array('option_name' => 'cloudpayments_api_password', 'option_value' => '','autoload'=>'yes'),array('%s','%s','%s'));
      $wpdb->insert('wp_options',array('option_name' => 'cloudpayments_type_nalog', 'option_value' => '','autoload'=>'yes'),array('%s','%s','%s'));
      $wpdb->insert('wp_options',array('option_name' => 'cloudpayments_type_system', 'option_value' => '','autoload'=>'yes'),array('%s','%s','%s'));
      $wpdb->insert('wp_options',array('option_name' => 'cloudpayments_ps_is_test', 'option_value' => 'Y','autoload'=>'yes'),array('%s','%s','%s'));
      $wpdb->insert('wp_options',array('option_name' => 'cloudpayments_vat', 'option_value' => '','autoload'=>'yes'),array('%s','%s','%s'));
      $wpdb->insert('wp_options',array('option_name' => 'cloudpayments_USE_CLOUD_KASSIR', 'option_value' => 'N','autoload'=>'yes'),array('%s','%s','%s'));
      $wpdb->insert('wp_options',array('option_name' => 'cloudpayments_language_widget', 'option_value' => 'ru-RU','autoload'=>'yes'),array('%s','%s','%s'));
      $wpdb->insert('wp_options',array('option_name' => 'cloudpayments_encoding', 'option_value' => 'utf-8','autoload'=>'yes'),array('%s','%s','%s'));
      $wpdb->insert('wp_options',array('option_name' => 'cloudpayments_transaction_url', 'option_value' => '/index.php/products-page/transaction-results/?sessionid=#SESSION_ID#','autoload'=>'yes'),array('%s','%s','%s'));
      $wpdb->insert('wp_options',array('option_name' => 'cloudpayments_change_status', 'option_value' => '31','autoload'=>'yes'),array('%s','%s','%s'));   
      $wpdb->insert('wp_options',array('option_name' => 'cloudpayments_change_status_auth', 'option_value' => '32','autoload'=>'yes'),array('%s','%s','%s'));
}

function cloud_deactivation()
{
    global $wpdb;
    unlink($_SERVER['DOCUMENT_ROOT']."/wp-content/plugins/wp-e-commerce/wpsc-merchants/cloudpayments.php"); 

    $file=$_SERVER['DOCUMENT_ROOT'].'/wp-content/plugins/cloudpayments/log.txt';
    $current = file_get_contents($file);
    $current .= date("d-m-Y H:i:s").":____cloud_deactivation\n";
    $link = $wpdb->esc_like("cloudpayments_").'%';
    $cloud_params_res = $wpdb->get_results($wpdb->prepare("SELECT * FROM `wp_options` WHERE `option_name` LIKE %s", $link));
    foreach((array)$cloud_params_res as $res):
        $wpdb->delete('wp_options', array('option_id' => $res->option_id)); 
        $current .= date("d-m-Y H:i:s").":____".$res->option_id."\n";
        file_put_contents($file, $current);
    endforeach;
}

?>