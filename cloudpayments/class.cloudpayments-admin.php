<?
class cloudpayments_admin 
{
	public static $Result = array();
  
  public function init() 
  {  
      add_menu_page('Cloudpayments settings', 'Cloudpayments settings', 'edit_pages', 'cloudpayments_admin', array('cloudpayments_admin', 'init_view' ), 'dashicons-edit');
 }


	public static function init_view( )
  {
		$file = cloudpayments_DIR . 'views/index.php';  
		include($file);
  }
  
  
	public static function view($name) 
  {
		$file = cloudpayments_DIR . 'views/'. $name . '.php';
		include($file);
	}
  
  
  function get_lang_mess($ID)
  {           
       $lang=get_option('cloudpayments_language'); 
       include($_SERVER['DOCUMENT_ROOT']."/wp-content/plugins/cloudpayments/class/lang/".$lang."/index.php");
       global $MESS;
       return $MESS[$ID];
  }

}


?>