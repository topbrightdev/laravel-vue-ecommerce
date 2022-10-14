<?
class cloudpayments_wp_ecommerce 
{

	public static $Result = array();

    public function init($lang) 
    {
        $array=array();
        $array['name'] = __( 'Cloudpayments', 'wp-e-commerce' );
        $array['internalname'] = 'cloudpayments';
        $array['form'] = array($this,"form_cloudpayments");
        $array['submit_function'] = array($this,"submit_cloudpayments");
        $array['payment_type'] = "Cloudpayments";
        $array['display_name'] = __( 'Cloudpayments', 'wp-e-commerce' );
	    $array['has_recurring_billing'] = true;
        $array['image'] = WPSC_URL . '/images/cloudpayment.png';  
        add_action('init', array($this, 'nzshpcrt_cloudpayments_callback'));
        add_action('init', array($this, 'nzshpcrt_cloudpayments_results'));  
        return $array;    
    }
     
    function get_lang_message($ID)
    {           
        $lang=get_option('cloudpayments_language'); 
        include($_SERVER['DOCUMENT_ROOT']."/wp-content/plugins/cloudpayments-gateway-for-ecommerce/class/lang/".$lang."/index.php");
        global $MESS;
        if ($encoding=get_option('cloudpayments_encoding'));
        else $encoding='utf-8';
        if ($encoding=='utf-8') return $MESS[$ID];
        else  return iconv("utf-8","cp1251",$MESS[$ID]);
    }
  
    function nzshpcrt_cloudpayments_callback()
    {
      	global $wpdb;
        require_once($_SERVER['DOCUMENT_ROOT']."/wp-content/plugins/cloudpayments-gateway-for-ecommerce/class/class.cloudpayments_wp_work.php");
        $CloudpaymentHandler = new CloudpaymentHandler;
        if ($_GET['action'] && $_POST['InvoiceId']):
            if ($order=$CloudpaymentHandler->get_order($_POST)):
                $CloudpaymentHandler->processRequest($_GET['action'],$_POST,$order);
            endif;      
            die();
        elseif ($_GET['sessionid']): 
            if ($order=$CloudpaymentHandler->get_order_by_session($_GET['sessionid'])):
                if (!$CloudpaymentHandler->isPaid($order)):
                    add_filter("the_content", array($this,"insert_pay_button_in_result"));
                endif;
            endif;
        endif;
    }
      
    function nzshpcrt_cloudpayments_results()
    { 
        add_action( 'wpsc_update_purchase_log_status', array($this,'change_purchase_log_status'), 10, 3 ); 
    }
      
    function ddd1()
    {
        echo '1';die();
    }
      
    function change_purchase_log_status($order_id,$new_status,$status)
    {
        require_once($_SERVER['DOCUMENT_ROOT']."/wp-content/plugins/cloudpayments-gateway-for-ecommerce/class/class.cloudpayments_wp_work.php");
        $CloudpaymentHandler = new CloudpaymentHandler;
        $CloudpaymentHandler->addError("change_purchase_log_status - order_id:".$order_id." old_status:".$status." - new_status:".$new_status);
        $STATUS_CHANGE=$CloudpaymentHandler->get_status_change();
        $delivery_status=$CloudpaymentHandler->get_delivery_status();
        if ($new_status==$STATUS_CHANGE):
            $CloudpaymentHandler->refund($order_id); 
        endif;
        
        $params=self::get_params();
        $method = $params['cloudpayments_calculation_method'];
        $cloud_kassir = $params['cloudpayments_USE_CLOUD_KASSIR'];
        if ($new_status==$delivery_status && $cloud_kassir!='N' && ($method == 1 || $method == 2 || $method == 3)):
            $CloudpaymentHandler->send_receipt($order_id); 
        endif;
    }
      
    function insert_pay_button_in_result($the_Post)
    {
        if ($_GET['sessionid']):
            $button_html=self::get_pay_button($_GET['sessionid']);
            $the_Post.=$button_html;
            return $the_Post;
        endif;
    }    

    function submit_cloudpayments() 
    {
        if(isset($_POST['cloudpayments_public_ID']))
        {
            update_option('cloudpayments_public_ID', sanitize_text_field( $_POST['cloudpayments_public_ID'] ) );
        }
          
      	if(isset($_POST['cloudpayments_curcode']))
        {
          	update_option('cloudpayments_curcode', sanitize_text_field( $_POST['cloudpayments_curcode'] ) );
        }
          
        if(isset($_POST['cloudpayments_language']))
        {
          	update_option('cloudpayments_language', sanitize_text_field( $_POST['cloudpayments_language'] ) );
        }
          
        if(isset($_POST['cloudpayments_language_widget']))
        {
          	update_option('cloudpayments_language_widget', sanitize_text_field( $_POST['cloudpayments_language_widget'] ) );
        }
          
      	if(isset($_POST['cloudpayments_transaction_url']))
        {
          	update_option('cloudpayments_transaction_url', sanitize_text_field( $_POST['cloudpayments_transaction_url'] ) );
        }
          
      	if(isset($_POST['cloudpayments_success_url']))
        {
          	update_option('cloudpayments_success_url', sanitize_text_field( $_POST['cloudpayments_success_url'] ) );
        }
          
        if(isset($_POST['cloudpayments_fail_url']))
        {
          	update_option('cloudpayments_fail_url', sanitize_text_field( $_POST['cloudpayments_fail_url'] ) );
        }
          
      	if(isset($_POST['cloudpayments_api_password']))
        {
          	update_option('cloudpayments_api_password', sanitize_text_field( $_POST['cloudpayments_api_password'] ) );
        }
          
        if(isset($_POST['cloudpayments_type_nalog']))
        {
          	update_option('cloudpayments_type_nalog', sanitize_text_field( $_POST['cloudpayments_type_nalog'] ) );
        }
          
        if(isset($_POST['cloudpayments_vat']))
        {
          	update_option('cloudpayments_vat', sanitize_text_field( $_POST['cloudpayments_vat'] ) );
        }
          
        if(isset($_POST['cloudpayments_payment_schemes']))
        {
          	update_option('cloudpayments_payment_schemes', sanitize_text_field( $_POST['cloudpayments_payment_schemes'] ) );
        }
          
        if(isset($_POST['cloudpayments_skin']))
        {
          	update_option('cloudpayments_skin', sanitize_text_field( $_POST['cloudpayments_skin'] ) );
        }
          
        if(isset($_POST['cloudpayments_inn']))
        {
          	update_option('cloudpayments_inn', sanitize_text_field( $_POST['cloudpayments_inn'] ) );
        }
          
        if(isset($_POST['cloudpayments_calculation_method']))
        {
          	update_option('cloudpayments_calculation_method', sanitize_text_field( $_POST['cloudpayments_calculation_method'] ) );
        }
          
        if(isset($_POST['cloudpayments_calculation_object']))
        {
          	update_option('cloudpayments_calculation_object', sanitize_text_field( $_POST['cloudpayments_calculation_object'] ) );
        }
          
        if(isset($_POST['cloudpayments_delivery_status']))
        {
          	update_option('cloudpayments_delivery_status', sanitize_text_field( $_POST['cloudpayments_delivery_status'] ) );
        }
          
        if(isset($_POST['cloudpayments_change_status']))
        {
          	update_option('cloudpayments_change_status', sanitize_text_field( $_POST['cloudpayments_change_status'] ) );
        }
          
        if(isset($_POST['cloudpayments_change_status_auth']))
        {
          	update_option('cloudpayments_change_status_auth', sanitize_text_field( $_POST['cloudpayments_change_status_auth'] ) );
        }
          
        if (!isset($_POST['cloudpayments_ps_is_test'])) $_POST['cloudpayments_ps_is_test']="N";
          
      	if(isset($_POST['cloudpayments_ps_is_test']))
        {
          	update_option('cloudpayments_ps_is_test', sanitize_text_field( $_POST['cloudpayments_ps_is_test'] ) );
        }
          
        if (!isset($_POST['cloudpayments_USE_CLOUD_KASSIR'])) $_POST['cloudpayments_USE_CLOUD_KASSIR']="N";
          
      	if(isset($_POST['cloudpayments_USE_CLOUD_KASSIR']))
        {
          	update_option('cloudpayments_USE_CLOUD_KASSIR', sanitize_text_field( $_POST['cloudpayments_USE_CLOUD_KASSIR'] ) );
        }

        if(isset($_POST['cloudpayments_encoding']))
        {
          	update_option('cloudpayments_encoding', sanitize_text_field( $_POST['cloudpayments_encoding'] ) );
        }
                
      	return true;
    }
      
    function get_pay_button($sessionid)
    {
    	global $wpdb;
        require_once($_SERVER['DOCUMENT_ROOT']."/wp-content/plugins/cloudpayments-gateway-for-ecommerce/class/class.cloudpayments_wp_work.php");
        $CloudpaymentHandler = new CloudpaymentHandler;
              
        $order_sql = $wpdb->prepare( "SELECT * FROM `".WPSC_TABLE_PURCHASE_LOGS."` WHERE `sessionid`= %s LIMIT 1", $sessionid );
        $order = $wpdb->get_results($order_sql,ARRAY_A) ;
        $order=$order[0];
              
        $STATUS_CHANCEL= $CloudpaymentHandler->get_status_change();
        $delivery_status=$CloudpaymentHandler->get_delivery_status();
        if ($order['processed']!=$STATUS_CHANCEL || $order['processed']!=$delivery_status):
          	$cart_sql = "SELECT * FROM `".WPSC_TABLE_CART_CONTENTS."` WHERE `purchaseid`='".$order['id']."'";
          	$cart = $wpdb->get_results($cart_sql,ARRAY_A) ;
                    
            if ($order['gateway']=='cloudpayments'):
                $Ar_params=self::get_params();
                  
                if (empty($Ar_params['cloudpayments_success_url'])) $Ar_params['cloudpayments_success_url']=str_replace("#SESSION_ID#",$sessionid,$Ar_params['cloudpayments_transaction_url']);
                if (empty($Ar_params['cloudpayments_fail_url'])) $Ar_params['cloudpayments_fail_url']=str_replace("#SESSION_ID#",$sessionid,$Ar_params['cloudpayments_transaction_url']);
                if (empty($Ar_params['cloudpayments_language_widget'])) $Ar_params['cloudpayments_language_widget']="ru-RU";
                if (empty($Ar_params['cloudpayments_payment_schemes'])) $Ar_params['cloudpayments_payment_schemes']="charge";
                if (empty($Ar_params['cloudpayments_skin'])) $Ar_params['cloudpayments_skin']="classic";
                         
                $Ar_params['description']=str_replace("#ORDER_ID#",$order['id'],self::get_lang_message('widget_description'));
                $Ar_params['description']=str_replace("#SITE_NAME#",'http://'.$_SERVER['HTTP_HOST'],$Ar_params['description']);
                $Ar_params['description']=str_replace("#DATE#",date("d-m-Y H:i:s"),$Ar_params['description']);
                  
                $email_data = $wpdb->get_results("SELECT `id` FROM `wp_wpsc_checkout_forms` WHERE `name` = 'email'",ARRAY_A);
                foreach((array)$email_data as $email)
                {
                  	$email_data_ = $wpdb->get_results("SELECT * FROM `wp_wpsc_submited_form_data` WHERE `log_id`=".$order['id']." and `form_id` = ".$email['id'],ARRAY_A);
                    foreach((array)$email_data_ as $email_)
                    {
                        $Ar_params['email']=$email_['value'];
                    }
                }
                $phone_data = $wpdb->get_results("SELECT `id` FROM `wp_wpsc_checkout_forms` WHERE `name` = 'phone'",ARRAY_A);
                foreach((array)$phone_data as $phone)
                {  
                  	$phone_data_ = $wpdb->get_results("SELECT * FROM `wp_wpsc_submited_form_data` WHERE `log_id`=".$order['id']." and `form_id` = ".$phone['id'],ARRAY_A);
                    foreach((array)$phone_data_ as $phone_)
                    {        
                        $Ar_params['phone']=$phone_['value'];
                    }
                }
                          
                if($Ar_params['cloudpayments_USE_CLOUD_KASSIR']!='N'):
                    if (!$CloudpaymentHandler->isPaid($order)):
                        $data=array();
                        $items=array();
                        
                      	foreach($cart as $item):
                            $items[]=array(
                                'label'=>$item['name'],
                                'price'=>number_format($item['price'],2,".",''),
                                'quantity'=>$item['quantity'],
                                'amount'=>number_format(floatval($item['price']*$item['quantity']),2,".",''),
                                'vat'=>$Ar_params['cloudpayments_vat'],
                                'method'=>$Ar_params['cloudpayments_calculation_method'],
                                'object'=>$Ar_params['cloudpayments_calculation_object'],
                                'ean13'=>null
                            ); 
                        endforeach; 
                                    
                        //Добавляем доставку
                        if ($order['base_shipping'] > 0): 
                            $items[] = array(
                                'label' => 'Доставка',
                                'price' => number_format($order['base_shipping'], 2, ".", ''),
                                'quantity' => 1,
                                'amount' => number_format($order['base_shipping'], 2, ".", ''),
                                'vat' => $Ar_params['cloudpayments_vat'],   ///
                                'method'=>$Ar_params['cloudpayments_calculation_method'],
                                'object'=>4,
                                'ean13' => null
                            );
                        endif;      
                          
                        $data['cloudPayments']['customerReceipt']['Items']=$items;
                        $data['cloudPayments']['customerReceipt']['taxationSystem']=$Ar_params['cloudpayments_type_nalog'];
                        $data['cloudPayments']['customerReceipt']['calculationPlace']='www.'.$_SERVER['SERVER_NAME'];
                        $data['cloudPayments']['customerReceipt']['email']=$Ar_params['email']; 
                        $data['cloudPayments']['customerReceipt']['phone']=$Ar_params['phone'];
                        $data['cloudPayments']['customerReceipt']['amounts']['electronic']=number_format($order['totalprice'], 2, '.', '');
                    endif;
                endif;
            
                $params=array(
                    "lang_widget"=>$Ar_params['cloudpayments_language_widget'],
                    "payment_schemes"=>$Ar_params['cloudpayments_payment_schemes'],
                    "skin"=>$Ar_params['cloudpayments_skin'],
                    "publicId"=>$Ar_params['cloudpayments_public_ID'],
                    "description"=>$Ar_params['description'],
                    "sum"=>$order['totalprice'],
                    "PAYMENT_CURRENCY"=>$Ar_params['cloudpayments_curcode'],//"RUB",
                    "PAYMENT_BUYER_EMAIL"=>$Ar_params['email'],    
                    "PAYMENT_ID"=>$order['id'],
                    "PAYMENT_BUYER_ID"=>$order['user_ID'],          
                    "CHECKONLINE"=>$Ar_params['cloudpayments_USE_CLOUD_KASSIR'],//"N",
                    "SUCCESS_URL"=>$Ar_params['cloudpayments_success_url'],
                    "FAIL_URL"=>$Ar_params['cloudpayments_fail_url'],
                );    
                            
                $output = '
                    <script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>    
                    <script src="https://widget.cloudpayments.ru/bundles/cloudpayments?cms=WordPress_ecommerce"></script>
                    <button class="cloudpay_button" id="payButton">'.self::get_lang_message('payButton').'</button>
                    <div id="result" style="display:none"></div>
                    <script type="text/javascript">
                        var payHandler = function () {
                        var widget = new cp.CloudPayments({'."language:'".$params['lang_widget']."'});
                        widget.".$params["payment_schemes"]."({ // options
                            publicId: '".trim(htmlspecialchars($params["publicId"]))."',
                            description: '".$params['description']."', 
                            amount: ".number_format($params['sum'], 2, '.', '').",
                            currency: '".$params['PAYMENT_CURRENCY']."',
                            email: '".$params['PAYMENT_BUYER_EMAIL']."',
                            skin: '".$params['skin']."',
                            invoiceId: '".htmlspecialchars($params["PAYMENT_ID"])."',
                            accountId: '".htmlspecialchars($params["PAYMENT_BUYER_ID"])."',";
                            if($params['CHECKONLINE']!='N'):
                                $output .="data: ".$CloudpaymentHandler->cur_json_encode($data).",";
                            endif;
                          
                            $output .="},function (options) { ";
                            if ($params['SUCCESS_URL']):
                                $output .=' window.location.href="'.$params['SUCCESS_URL'].'"';
                            else:
                                $output .='BX("result").innerHTML="'.self::get_lang_message('SUCCESS_URL_msg').'";
                                BX.style(BX("result"),"color","green");
                                BX.style(BX("result"),"display","block");';
                            endif;
                            $output .='},
                            function (reason, options) { ';
                                if ($params['FAIL_URL']):
                                $output .='window.location.href="'.$params['FAIL_URL'].'"';
                                else:
                                    $output .='BX("result").innerHTML=reason;
                                    BX.style(BX("result"),"color","red");
                                    BX.style(BX("result"),"display","block");';
                                endif;
                                $output .='});
                            };
                            $("#payButton").on("click", payHandler); 
                        </script>';
                return $output;
            endif; 
        endif;       
    }
      
    function gateway_cloudpayments($separator, $sessionid)
    {         
        if (!$sessionid) return false;
        global $wpdb;
        $order_sql = $wpdb->prepare( "SELECT * FROM `".WPSC_TABLE_PURCHASE_LOGS."` WHERE `sessionid`= %s LIMIT 1", $sessionid );
        $order = $wpdb->get_results($order_sql,ARRAY_A) ;
        $order=$order[0];
        if ($order['gateway']=='cloudpayments'):
            $purchase_log = new WPSC_Purchase_Log($sessionid, 'sessionid' );
            $purchase_log->set( 'processed', WPSC_Purchase_Log::ORDER_RECEIVED );
            $purchase_log->save();
            header('HTTP/1.1 301 Moved Permanently');
            header('Location: '.$_SERVER['HTTP_HOST'].str_replace("#SESSION_ID#",$sessionid,get_option('cloudpayments_transaction_url')));
        endif;
    }
      
    function get_params()
    {
        global $wpdb;
        $cloud_params=array();
        $link = $wpdb->esc_like("cloudpayments_").'%';
        $cloud_params_res = $wpdb->get_results($wpdb->prepare("SELECT * FROM `wp_options` WHERE `option_name` LIKE %s", $link));
                
        foreach((array)$cloud_params_res as $params1):
             $cloud_params[$params1->option_name]=$params1->option_value; 
        endforeach;
        
        return $cloud_params;
    }
      
    function form_cloudpayments()
    {  
        global $wpdb;
        global $wpsc_purchlog_statuses;
        $select_calculation_object[get_option('cloudpayments_calculation_object')] = "selected='selected'";
        $select_calculation_method[get_option('cloudpayments_calculation_method')] = "selected='selected'";
        $select_delivery_status[get_option('cloudpayments_delivery_status')] = "selected='selected'";
      	$select_currency[get_option('cloudpayments_curcode')] = "selected='selected'";
      	$select_language[get_option('cloudpayments_language')] = "selected='selected'";
      	$select_skin[get_option('cloudpayments_skin')] = "selected='selected'";
      	$select_payment_schemes[get_option('cloudpayments_payment_schemes')] = "selected='selected'";
      	$select_widget_language[get_option('cloudpayments_language_widget')] = "selected='selected'";
      	$ENCODING_SELECT[get_option('cloudpayments_encoding')] = "selected='selected'";
        
        $cloud_params=self::get_params();
        
        if (isset($cloud_params['cloudpayments_type_nalog'])):
            $cloudpayments_type_nalog_select[$cloud_params['cloudpayments_type_nalog']]="selected";
        endif;
        
        if ($cloud_params['cloudpayments_USE_CLOUD_KASSIR']=="Y"):
            $cloud_params['cloudpayments_USE_CLOUD_KASSIR']="checked";
        endif;
        
        if ($cloud_params['cloudpayments_type_nalog']=="Y"):
            $cloud_params['cloudpayments_type_nalog']="checked";
        endif;

      	if (!isset($select_currency['USD'])) $select_currency['USD'] = '';
      	if (!isset($select_currency['EUR'])) $select_currency['EUR'] = '';
        
      	if (!isset($select_language['en'])) $select_language['en'] = '';
      	if (!isset($select_language['ru'])) $select_language['ru'] = '';
      
      	$output = "
      		<tr>
      			<td></td>
      			<td>
      				<p class='description'>".self::get_lang_message('MESSAGE1')."</p>
      		</tr>
      		<tr>
      			<td>" . __(self::get_lang_message('cloudpayments_transaction_url'), 'wp-e-commerce' ) . "</td>
      			<td>
      				<input type='text' style='width:550px;' size='80' value='" . $cloud_params['cloudpayments_transaction_url'] . "' name='cloudpayments_transaction_url' />
      				<p class='description'>
      					" . __(self::get_lang_message('cloudpayments_transaction_url_DESCR'), 'wp-e-commerce' ) . "
      				</p>
      			</td>
      		</tr>        
      		<tr>
      			<td>" . __(self::get_lang_message('PUBLIC_ID'), 'wp-e-commerce' ) . "</td>
      			<td>
      				<input type='text' style='width:550px;' size='40' value='" . $cloud_params['cloudpayments_public_ID'] . "' name='cloudpayments_public_ID' />
      				<p class='description'>
      					" . __(self::get_lang_message('PUBLIC_ID_DESCR'), 'wp-e-commerce' ) . "
      				</p>
      			</td>
      		</tr>
      		<tr>
      			<td>" . __(self::get_lang_message('API_PASS'), 'wp-e-commerce' ) . "</td>
      			<td>
      				<input type='text' style='width:550px;' size='40' value='" . $cloud_params['cloudpayments_api_password'] . "' name='cloudpayments_api_password' />
      				<p class='description'>
      					" . __(self::get_lang_message('API_PASS_DESCR'), 'wp-e-commerce' ) . "
      				</p>
      			</td>
      		</tr>
      			<tr>
      			<td>" . __(self::get_lang_message('Payment_Schemes'), 'wp-e-commerce' ) . "</td>
      			<td>
      				<select name='cloudpayments_payment_schemes'>
                  <option " . $select_payment_schemes['charge'] . " value='charge'>".self::get_lang_message("charge")."</option>
                  <option " . $select_payment_schemes['auth'] . " value='auth'>".self::get_lang_message("auth")."</option>
      				</select>
      				<p class='description'>
      					" . __(self::get_lang_message('Payment_Schemes_DESCR'), 'wp-e-commerce' ) . "
      				</p>
      		</tr>
      		<tr>
      			<td>" . __(self::get_lang_message('SKIN'), 'wp-e-commerce' ) . "</td>
      			<td>
      				<select name='cloudpayments_skin'>
                  <option " . $select_skin['classic'] . " value='classic'>".self::get_lang_message("classic")."</option>
                  <option " . $select_skin['modern'] . " value='modern'>".self::get_lang_message("modern")."</option>
                  <option " . $select_skin['mini'] . " value='mini'>".self::get_lang_message("mini")."</option>
      				</select>
      				<p class='description'>
      					" . __(self::get_lang_message('SKIN_DESCR'), 'wp-e-commerce' ) . "
      				</p>
      		</tr>
      		<tr style='display:none'>
      			<td>" . __(self::get_lang_message('ENCODING'), 'wp-e-commerce' ) . "</td>
      			<td>
      				<select name='cloudpayments_encoding'>
      					<option ".$ENCODING_SELECT['utf-8']." value='utf-8'>UTF-8</option>
      					<option ".$ENCODING_SELECT['CP1251']." value='CP1251'>CP1251</option>
      				</select>
      				<p class='description'>
      					" . __(self::get_lang_message('ENCODING_DESCR'), 'wp-e-commerce' ) . "
      				</p>
      		</tr>
      		<tr>
      			<td>" . __(self::get_lang_message('USE_CLOUD_KASSIR'), 'wp-e-commerce' ) . "</td>
      			<td>
      				<input type='checkbox' size='40' ".$cloud_params['cloudpayments_USE_CLOUD_KASSIR']." value='Y' name='cloudpayments_USE_CLOUD_KASSIR' />
      				<p class='description'>
      					" . __(self::get_lang_message('USE_CLOUD_KASSIR_DESCR'), 'wp-e-commerce' ) . "
      				</p>
      		</tr>
      		<tr>
      			<td>" . __(self::get_lang_message('cloudpayments_change_status'), 'wp-e-commerce' ) . "</td>
      			<td>
      				<select name='cloudpayments_change_status'>";
                if ($wpsc_purchlog_statuses):
                    foreach ($wpsc_purchlog_statuses as $status1):
      					         $output.="<option ".($status1['order']==$cloud_params['cloudpayments_change_status'] ? "selected": "")." value='".$status1['order']."'>".$status1['label']."</option>";
                    endforeach;
                endif;

      				$output.="</select>
      				<p class='description'>
      					" . __(self::get_lang_message('cloudpayments_change_status_DESCR'), 'wp-e-commerce' ) . "
      				</p>
      		</tr>
      		<tr>
      			<td>" . __(self::get_lang_message('cloudpayments_change_status_auth'), 'wp-e-commerce' ) . "</td>
      			<td>
      				<select name='cloudpayments_change_status_auth'>";
                if ($wpsc_purchlog_statuses):
                    foreach ($wpsc_purchlog_statuses as $status1):
      					         $output.="<option ".($status1['order']==$cloud_params['cloudpayments_change_status_auth'] ? "selected": "")." value='".$status1['order']."'>".$status1['label']."</option>";
                    endforeach;
                endif;

      				$output.="</select>
      				<p class='description'>
      					" . __(self::get_lang_message('cloudpayments_change_status_auth_DESCR'), 'wp-e-commerce' ) . "
      				</p>
      		</tr>
      		<tr>
      			<td>" . __(self::get_lang_message('SUCCESS_URL'), 'wp-e-commerce' ) . "</td>
      			<td>
      				<input type='text' size='40'  style='width:550px;' value='".$cloud_params['cloudpayments_success_url']."' name='cloudpayments_success_url' />
      				<p class='description'>
      					" . __(self::get_lang_message('SUCCESS_URL_DESCR'), 'wp-e-commerce' ) . "
      				</p>
      		</tr>
      		<tr>
      			<td>" . __(self::get_lang_message('FAIL_URL'), 'wp-e-commerce' ) . "</td>
      			<td>
      				<input type='text'  style='width:550px;' size='40' value='".$cloud_params['cloudpayments_fail_url']."' name='cloudpayments_fail_url' />
      				<p class='description'>
      					" . __(self::get_lang_message('FAIL_URL_DESCR'), 'wp-e-commerce' ) . "
      				</p>
      		</tr>
      		<tr>
      			<td>" . __(self::get_lang_message('VAT'), 'wp-e-commerce' ) . "</td>
      			<td>
      				<select name='cloudpayments_vat'>";
      					$output.="<option value=''></option><option ";
                if ($cloud_params['cloudpayments_vat']==0) $output.="selected ";
                $output.=" value='0'>0%</option>";
      					$output.="<option ";
                if ($cloud_params['cloudpayments_vat']==10) $output.="selected ";
                $output.=" value='10'>10%</option>";
      					$output.="<option ";                
                if ($cloud_params['cloudpayments_vat']==20) $output.="selected ";
                $output.=" value='20'>20%</option>";
      					$output.="<option ";
                if ($cloud_params['cloudpayments_vat']==110) $output.="selected ";
                $output.=" value='110'>10/110</option>";
      					$output.="<option ";
                if ($cloud_params['cloudpayments_vat']==120) $output.="selected ";
                $output.=" value='120'>20/120</option>";
                
      				$output.="</select>
      				<p class='description'>
      					" . __(self::get_lang_message('VAT_DESCR'), 'wp-e-commerce' ) . "
      				</p>
      		</tr>
      		<tr>
      			<td>" . __(self::get_lang_message('CURRENCY'), 'wp-e-commerce' ) . "</td>
      			<td>
      				<select name='cloudpayments_curcode'>
                <option value=''></option>
      					<option " . $select_currency['RUB'] . " value='RUB'>".self::get_lang_message('RUB')."</option>
      					<option " . $select_currency['EUR'] . " value='EUR'>".self::get_lang_message('EUR')."</option>
      					<option " . $select_currency['USD'] . " value='USD'>".self::get_lang_message('USD')."</option>
      					<option " . $select_currency['GBP'] . " value='GBP'>".self::get_lang_message('GBP')."</option>
      					<option " . $select_currency['UAH'] . " value='UAH'>".self::get_lang_message('UAH')."</option>
      					<option " . $select_currency['BYR'] . " value='BYR'>".self::get_lang_message('BYR')."</option>
      					<option " . $select_currency['BYN'] . " value='BYN'>".self::get_lang_message('BYN')."</option>
      					<option " . $select_currency['KZT'] . " value='KZT'>".self::get_lang_message('KZT')."</option>
      					<option " . $select_currency['AZN'] . " value='AZN'>".self::get_lang_message('AZN')."</option>
      					<option " . $select_currency['CHF'] . " value='CHF'>".self::get_lang_message('CHF')."</option>
      					<option " . $select_currency['CZK'] . " value='CZK'>".self::get_lang_message('CZK')."</option>
      					<option " . $select_currency['CAD'] . " value='CAD'>".self::get_lang_message('CAD')."</option>
      					<option " . $select_currency['PLN'] . " value='PLN'>".self::get_lang_message('PLN')."</option>
      					<option " . $select_currency['SEK'] . " value='SEK'>".self::get_lang_message('SEK')."</option>
      					<option " . $select_currency['TRY'] . " value='TRY'>".self::get_lang_message('TRY')."</option>
      					<option " . $select_currency['CNY'] . " value='CNY'>".self::get_lang_message('CNY')."</option>
      					<option " . $select_currency['INR'] . " value='INR'>".self::get_lang_message('INR')."</option>
      					<option " . $select_currency['BRL'] . " value='BRL'>".self::get_lang_message('BRL')."</option>
      					<option " . $select_currency['ZAL'] . " value='ZAL'>".self::get_lang_message('ZAL')."</option>
      					<option " . $select_currency['UZS'] . " value='UZS'>".self::get_lang_message('UZS')."</option>
      				</select>
      				<p class='description'>
      					" . __(self::get_lang_message('CURRENCY_DESCR'), 'wp-e-commerce' ) . "
      				</p>
      		</tr>
      		<tr>
      			<td>" . __(self::get_lang_message('LANGUAGE'), 'wp-e-commerce' ) . "</td>
      			<td>
      				<select name='cloudpayments_language'>
                <option value=''></option>
      					<option " . $select_language['en'] . " value='en'>Engish</option>
      					<option " . $select_language['ru'] . " value='ru'>Russian</option>
      				</select>
      				<p class='description'>
      					" . __(self::get_lang_message('LANGUAGE_DESCR'), 'wp-e-commerce' ) . "
      				</p>
      		</tr>
      		<tr>
      			<td>" . __(self::get_lang_message('LANGUAGE_WIDGET'), 'wp-e-commerce' ) . "</td>
      			<td>
      				<select name='cloudpayments_language_widget'>
                  <option value=''></option>
                  <option " . $select_widget_language['ru-RU'] . " value='ru-RU'>".self::get_lang_message("ru-RU")."</option>
                  <option " . $select_widget_language['en-US'] . " value='en-US'>".self::get_lang_message("en-US")."</option>
                  <option " . $select_widget_language['lv'] . " value='lv'>".self::get_lang_message("lv")."</option>
                  <option " . $select_widget_language['az'] . " value='az'>".self::get_lang_message("az")."</option>
                  <option " . $select_widget_language['kk'] . " value='kk'>".self::get_lang_message("kk")."</option>
                  <option " . $select_widget_language['kk-KZ'] . " value='kk-KZ'>".self::get_lang_message("kk-KZ")."</option>
                  <option " . $select_widget_language['uk'] . " value='uk'>".self::get_lang_message("uk")."</option>
                  <option " . $select_widget_language['pl'] . " value='pl'>".self::get_lang_message("pl")."</option>
                  <option " . $select_widget_language['pt'] . " value='pt'>".self::get_lang_message("pt")."</option>
      				</select>
      				<p class='description'>
      					" . __(self::get_lang_message('LANGUAGE_WIDGET_DESCR'), 'wp-e-commerce' ) . "
      				</p>
      		</tr>
      		<tr>
      			<td>" . __(self::get_lang_message('TYPE_NALOG'), 'wp-e-commerce' ) . "</td>
      			<td>".'
              <select name="cloudpayments_type_nalog">
                  <option value=""></option>
                  <option '.$cloudpayments_type_nalog_select[0].' value="0" selected="">'.self::get_lang_message('TYPE_NALOG1').'</option>
                  <option '.$cloudpayments_type_nalog_select[1].' value="1">'.self::get_lang_message('TYPE_NALOG2').'</option>
                  <option '.$cloudpayments_type_nalog_select[2].' value="2">'.self::get_lang_message('TYPE_NALOG3').'</option>
                  <option '.$cloudpayments_type_nalog_select[3].' value="3">'.self::get_lang_message('TYPE_NALOG4').'</option>
                  <option '.$cloudpayments_type_nalog_select[4].' value="4">'.self::get_lang_message('TYPE_NALOG5').'</option>
                  <option '.$cloudpayments_type_nalog_select[5].' value="5">'.self::get_lang_message('TYPE_NALOG6').'</option>
              </select>'."
      				<p class='description'>
      					" . __(self::get_lang_message('TYPE_NALOG_DESCR'), 'wp-e-commerce' ) . "
      				</p>
      		</tr>
      		<tr>
      			<td>" . __(self::get_lang_message('Calculation_Methods'), 'wp-e-commerce' ) . "</td>
      			<td>
      			<select name='cloudpayments_calculation_method'>
                  <option " . $select_calculation_method['0'] . " value='0'>".self::get_lang_message("Unknown_method")."</option>
                  <option " . $select_calculation_method['1'] . " value='1'>".self::get_lang_message("FullPrepayment")."</option>
                  <option " . $select_calculation_method['2'] . " value='2'>".self::get_lang_message("PartialPrepayment")."</option>
                  <option " . $select_calculation_method['3'] . " value='3'>".self::get_lang_message("AdvancePay")."</option>
                  <option " . $select_calculation_method['4'] . " value='4'>".self::get_lang_message("FullPay")."</option>
                  <option " . $select_calculation_method['5'] . " value='5'>".self::get_lang_message("PartialPayAndCredit")."</option>
                  <option " . $select_calculation_method['6'] . " value='6'>".self::get_lang_message("Credit")."</option>
                  <option " . $select_calculation_method['7'] . " value='7'>".self::get_lang_message("CreditPayment")."</option>
      				</select>
      				<p class='description'>
      					" . __(self::get_lang_message('Calculation_Methods_DESCR'), 'wp-e-commerce' ) . "
      				</p>
      		</tr>
      		<tr>
      			<td>" . __(self::get_lang_message('Calculation_Objects'), 'wp-e-commerce' ) . "</td>
      			<td>
      			<select name='cloudpayments_calculation_object'>
                  <option " . $select_calculation_object['0'] . " value='0'>".self::get_lang_message("Unknown_object")."</option>
                  <option " . $select_calculation_object['1'] . " value='1'>".self::get_lang_message("Commodity")."</option>
                  <option " . $select_calculation_object['2'] . " value='2'>".self::get_lang_message("ExcisedCommodity")."</option>
                  <option " . $select_calculation_object['3'] . " value='3'>".self::get_lang_message("Job")."</option>
                  <option " . $select_calculation_object['4'] . " value='4'>".self::get_lang_message("Service")."</option>
                  <option " . $select_calculation_object['5'] . " value='5'>".self::get_lang_message("GamblingBet")."</option>
                  <option " . $select_calculation_object['6'] . " value='6'>".self::get_lang_message("GamblingWin")."</option>
                  <option " . $select_calculation_object['7'] . " value='7'>".self::get_lang_message("LotteryTicket")."</option>
                  <option " . $select_calculation_object['8'] . " value='8'>".self::get_lang_message("LotteryWin")."</option>
                  <option " . $select_calculation_object['9'] . " value='9'>".self::get_lang_message("RidAccess")."</option>
                  <option " . $select_calculation_object['10'] . " value='10'>".self::get_lang_message("Payment")."</option>
                  <option " . $select_calculation_object['11'] . " value='11'>".self::get_lang_message("AgentReward")."</option>
                  <option " . $select_calculation_object['12'] . " value='12'>".self::get_lang_message("Composite")."</option>
                  <option " . $select_calculation_object['13'] . " value='13'>".self::get_lang_message("Another")."</option>
      				</select>
      				<p class='description'>
      					" . __(self::get_lang_message('Calculation_Objects_DESCR'), 'wp-e-commerce' ) . "
      				</p>
      		</tr>
      		<tr>
      			<td>" . __(self::get_lang_message('delivery_status'), 'wp-e-commerce' ) . "</td>
      			<td>
      				<select name='cloudpayments_delivery_status'>";
                if ($wpsc_purchlog_statuses):
                    foreach ($wpsc_purchlog_statuses as $status1):
      					         $output.="<option ".($status1['order']==$cloud_params['cloudpayments_delivery_status'] ? "selected": "")." value='".$status1['order']."'>".$status1['label']."</option>";
                    endforeach;
                endif;

      				$output.="</select>
      				<p class='description'>
      					" . __(self::get_lang_message('delivery_status_DESCR'), 'wp-e-commerce' ) . "
      				</p>
      		</tr>
      		<tr>
      			<td>" . __(self::get_lang_message('inn'), 'wp-e-commerce' ) . "</td>
      			<td>
      				<input type='text' style='width:150px;' size='40' value='" . $cloud_params['cloudpayments_inn'] . "' name='cloudpayments_inn' />
      			</td>
      		</tr>
      		";
      
      	return $output;
    }
  
}
?>