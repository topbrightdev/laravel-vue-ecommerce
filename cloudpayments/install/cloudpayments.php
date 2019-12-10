<?php

include($_SERVER['DOCUMENT_ROOT']."/wp-content/plugins/cloudpayments/class/class.cloudpayments_wp_ecommerce.php");

$cp_pay_class = new cloudpayments_wp_ecommerce();
$lang = get_option('cloudpayments_language');  
$nzshpcrt_gateways[$num]=$cp_pay_class->init($lang);
$nzshpcrt_gateways[$num]['function'] = 'gateway_cloudpayments_ini';

function gateway_cloudpayments_ini($separator, $sessionid)
{
      $cp_pay_class = new cloudpayments_wp_ecommerce();
      $cp_pay_class->gateway_cloudpayments($separator, $sessionid);
}
?>
