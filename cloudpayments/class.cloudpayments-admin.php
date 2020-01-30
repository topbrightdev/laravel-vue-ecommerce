<?
class cloudpayments_admin 
{
	public static $Result = array();

    function get_lang_mess($ID)
    {           
        $lang=get_option('cloudpayments_language'); 
        include($_SERVER['DOCUMENT_ROOT']."/wp-content/plugins/cloudpayments-gateway-for-ecommerce/class/lang/".$lang."/index.php");
        global $MESS;
        return $MESS[$ID];
    }
}
?>