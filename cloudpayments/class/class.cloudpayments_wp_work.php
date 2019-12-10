<?php
class CloudpaymentHandler
{
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

  	public function refund($order_id)
  	{
          global $wpdb;
          self::addError("refund");
      		$error = '';
          if (empty($order_id)) return false;
          
          $params=self::get_params();
        	$order_sql = $wpdb->prepare( "SELECT * FROM `wp_wpsc_purchase_logs` WHERE `id`= %s LIMIT 1", $order_id );
        	$order = $wpdb->get_results($order_sql,ARRAY_A) ;
          $order=$order[0];
          self::addError(print_r($order,true));
          if (empty($order['transactid'])) return false;
          
          $request=array(
              'TransactionId'=>$order['transactid'],
              'Amount'=>number_format($order['totalprice'], 2, '.', ''),
          );

          $url = self::getUrlList('return');
          
          self::addError($url);
          
          $accesskey=trim($params['cloudpayments_public_ID']);
          $access_psw=trim($params['cloudpayments_api_password']);
        	$ch = curl_init($url);
          curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
          curl_setopt($ch,CURLOPT_USERPWD,$accesskey . ":" . $access_psw);
          curl_setopt($ch, CURLOPT_URL, $url);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
          curl_setopt($ch, CURLOPT_POST, true);
          curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
      	  $content = curl_exec($ch);
  	      $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      		$curlError = curl_error($ch);
      		curl_close($ch);
          $out=$this->Object_to_array(json_decode($content));
          if ($out['Success'] !== false)
          {
               $this->addError('Success');
          }
          else
          {
              $error .= $out['Message'];
          }
          
      		if ($error !== '')
      		{
      			   $this->addError($error);
      		}
          
  	}
       
    function get_lang_message($ID)
    {           
         $lang=get_option('cloudpayments_language'); 
         include($_SERVER['DOCUMENT_ROOT']."/wp-content/plugins/cloudpayments/class/lang/".$lang."/index.php");
         global $MESS;
         if ($encoding=get_option('cloudpayments_encoding'));
         else $encoding='utf-8';
         
         if ($encoding=='utf-8') return $MESS[$ID];
         else  return iconv("utf-8","cp1251",$MESS[$ID]);
    }
                                          
    function Object_to_array($data)
    {
        if (is_array($data) || is_object($data))
        {
            $result = array();
            foreach ($data as $key => $value)
            {
                $result[$key] = self::Object_to_array($value);
            }
            return $result;
        }
        return $data;
    }
    
    private function CheckHMac($APIPASS)
    {

        $headers = $this->detallheaders();   
        $this->addError(print_r($headers,true));
                        
        if (isset($headers['Content-HMAC']) || isset($headers['Content-Hmac'])) 
        {
            $message = file_get_contents('php://input');
            $s = hash_hmac('sha256', $message, $APIPASS, true);
            $hmac = base64_encode($s); 
            
            $this->addError(print_r($hmac,true));
            if ($headers['Content-HMAC']==$hmac) return true;
            else if($headers['Content-Hmac']==$hmac) return true;                                    
        }
        else return false;
    }
    
    private function detallheaders()
    {
        if (!is_array($_SERVER)) {
            return array();
        }
        $headers = array();
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }

    private function isCorrectOrderID($order, $request)
    {
        $oid = $request['InvoiceId'];
        $paymentid = $order['id'];
  
        return round($paymentSum, 2) == round($sum, 2);
    }

  	private function isCorrectSum($request,$order)
  	{
  		$sum = $request['Amount'];
  		$paymentSum = $order['totalprice'];
  
  		return round($paymentSum, 2) == round($sum, 2);
  	}

  	public function sendResponse(PaySystem\ServiceResult $result, Request $request)
  	{
  		global $APPLICATION;
  		$data = $result->getData();
  		$res['code']=$data['CODE'];
          echo json_encode($res);
  		die();
  	}  

  	public function getPaymentIdFromRequest(Request $request)
  	{
  	    $order=\Bitrix\Sale\Order::load($request->get('InvoiceId'));
  	    foreach($order->getPaymentCollection() as $payment){
  			$l[]=$payment->getField("ID");
  		}
  	    return current($l);
  	}

    public function isPaid($order)
    {
        if ($order['processed']==3) return true;
        else return false;
    }
    
    public function isCanceled($order)
    {
        if ($order['processed']==6) return true;
        else return false;
    }
  
   
  	public function processCheckAction($request,$order)
  	{
          $this->addError('processCheckAction');
      
          $accesskey=trim(get_option('cloudpayments_api_password'));
          if($this->CheckHMac($accesskey))
          {
              if ($this->isCorrectSum($request,$order))
              {
                  $data['CODE'] = 0;
                  $this->addError('CorrectSum');
              }
              else
              {
                  $data['CODE'] = 11;
                  $errorMessage = 'Incorrect payment sum';
  
                  $this->addError($errorMessage);
              }
              
              $this->addError("Проверка заказа");
              
              $STATUS_CHANCEL= self::get_status_change();
              
              if($this->isCorrectOrderID($order, $request))
              {
                  $data['CODE'] = 0;
              }else
              {
                  $data['CODE'] = 10;
                  $errorMessage = 'Incorrect order ID';
  
                  $this->addError($errorMessage);
              }
  
              
              $orderID=$request['InvoiceId'];
  
              if($this->isPaid($order)):
                  $data['CODE'] = 13;
                  $errorMessage = 'Order already paid';
                  $this->addError($errorMessage);
              else:
                  $data['CODE'] = 0;
              endif;
  
               
              if ($this->isPaid($order) || $this->isCanceled($order) || $order['processed']==$STATUS_CHANCEL)
              {
                 $data['CODE'] = 13;
              }  
          }
          else
          {
              $errorMessage='ERROR HMAC RECORDS';
              $this->addError($errorMessage);  
              $data['CODE']=5204;            
          }
          
          $this->addError(json_encode($data));    
          
  		    echo json_encode($data);
  	}                       

    private function processFailAction($request,$order)    //ok
    {

        $data['CODE'] = 0;
        $purchase_log = new WPSC_Purchase_Log($order['sessionid'], 'sessionid' );
      	$purchase_log->set('processed', WPSC_Purchase_Log::INCOMPLETE_SALE );
      	$purchase_log->save();
        return $result;
    }
    
    private function processSuccessAction($request,$order)
    {
       
        global $wpdb;
        $TYPE_SYSTEM = get_option('cloudpayments_payment_schemes');
        $TYPE_Status = $request['Status'];
        if ($TYPE_SYSTEM == 'auth' && $TYPE_Status == 'Authorized') {
            $STATUS_AUTH = self::get_status_change_auth();
            $purchase_log = new WPSC_Purchase_Log($order['sessionid'], 'sessionid' );
      	    $purchase_log->set('processed', WPSC_Purchase_Log::INCOMPLETE_SALE );
      	    $purchase_log->save();
            $wpdb->update('wp_wpsc_purchase_logs',array('processed'=>$STATUS_AUTH,'transactid'=>$request['TransactionId'],'date'=>time()),array('id' => $order['id']));
            $data['CODE'] = 0;
            echo json_encode($data);
        }
        else {
       
            $data['CODE'] = 0;
            $purchase_log = new WPSC_Purchase_Log($order['sessionid'], 'sessionid' );
      	    $purchase_log->set('processed', WPSC_Purchase_Log::ACCEPTED_PAYMENT );
      	    $purchase_log->save();
            $wpdb->update('wp_wpsc_purchase_logs',array('transactid'=>$request['TransactionId'],'date'=>time()),array('id' => $order['id']));
        
            $this->addError('PAY_COMPLETE');
            $params=self::get_params();
            $this->addError(print_r($request,true));
            $this->addError(print_r($order,true));
        
            $data_kkt=array(
                "TYPE"=>"Income",
                "calculationPlace"=>"www.".$_SERVER["SERVER_NAME"],
                "EMAIL"=>$request["Email"],
                "PHONE"=>"",
                "ORDER_ID"=>$order['id'],
                "USER_ID"=>$order["user_ID"],
            );
          
            if($params['cloudpayments_USE_CLOUD_KASSIR']!='N'):
                if (!self::isPaid($order)):
                    $items=array();
                    $local_currency_shipping=0;
                	$cart_sql = "SELECT * FROM `wp_wpsc_cart_contents` WHERE `purchaseid`='".$order['id']."'";
                	$cart = $wpdb->get_results($cart_sql,ARRAY_A);
                  
                	foreach($cart as $item):
                        $items[]=array(
                              'label'=>$item['name'],
                              'price'=>number_format($item['price'],2,".",''),
                              'quantity'=>$item['quantity'],
                              'amount'=>number_format(floatval($item['price']*$item['quantity']),2,".",''),
                              'vat'=>$params['cloudpayments_vat'], ///
                              'ean13'=>null
                        ); 
                        $local_currency_shipping = $local_currency_shipping+$item['pnp'];   
                    endforeach; 
                  
                  //Добавляем доставку
                    if ($local_currency_shipping > 0): 
                        $items[] = array(
                            'label' => self::get_lang_message('DELIVERY_TXT'),
                            'price' => number_format($local_currency_shipping, 2, ".", ''),
                            'quantity' => 1,
                            'amount' => number_format($local_currency_shipping, 2, ".", ''),
                            'vat' => $params['cloudpayments_vat'],   ///
                            'ean13' => null
                        );
                    endif;      
                    $data_kkt['ITEMS']=$items;
                endif;
            endif;

            echo json_encode($data);
        }
    }
    
    private function processRefundAction($request, $order)
    {
        global $wpdb;
        $STATUS_CHANCEL= self::get_status_change();
        $purchase_log = new WPSC_Purchase_Log($order['sessionid'], 'sessionid' );
      	$purchase_log->set('processed', WPSC_Purchase_Log::INCOMPLETE_SALE );
      	$purchase_log->save();
        $wpdb->update('wp_wpsc_purchase_logs',array('processed'=>$STATUS_CHANCEL,'transactid'=>$request['TransactionId'],'date'=>time()),array('id' => $order['id']));

        $data['CODE'] = 0;
        echo json_encode($data);
    }
    
    public function get_status_change()
    {
       return get_option("cloudpayments_change_status");
    }
    
    public function get_status_change_auth()
    {
       return get_option("cloudpayments_change_status_auth");
    }

	private function extractDataFromRequest($request) 
	{
		return array(
			'HEAD' => $request->get('action').'Response',
			'INVOICE_ID' =>  $request->get('InvoiceId')
		);
	}

  function cur_json_encode($a=false)
  {
      if (is_null($a) || is_resource($a)) {
          return 'null';
      }
      if ($a === false) {
          return 'false';
      }
      if ($a === true) {
          return 'true';
      }
      
      if (is_scalar($a)) {
          if (is_float($a)) {
              $a = str_replace(',', '.', strval($a));
          }

          static $jsonReplaces = array(
              array("\\", "/", "\n", "\t", "\r", "\b", "\f", '"'),
              array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"')
          );
  
          return '"' . str_replace($jsonReplaces[0], $jsonReplaces[1], $a) . '"';
      }
  
      $isList = true;
  
      for ($i = 0, reset($a); $i < count($a); $i++, next($a)) {
          if (key($a) !== $i) {
              $isList = false;
              break;
          }
      }
  
      $result = array();
      
      if ($isList) {
          foreach ($a as $v) {
              $result[] = self::cur_json_encode($v);
          }
      
          return '[ ' . join(', ', $result) . ' ]';
      } else {
          foreach ($a as $k => $v) {
              $result[] = self::cur_json_encode($k) . ': ' . self::cur_json_encode($v);
          }
  
          return '{ ' . join(', ', $result) . ' }';
      }
  }

	private function processCancelAction($request,$order)
	{
	        global $wpdb;
            $purchase_log = new WPSC_Purchase_Log($order['sessionid'], 'sessionid' );
      	    $purchase_log->set('processed', WPSC_Purchase_Log::PAYMENT_DECLINED );
      	    $purchase_log->save();
            $wpdb->update('wp_wpsc_purchase_logs',array('transactid'=>$request['TransactionId'],'date'=>time()),array('id' => $order['id']));
            $data['CODE'] = 0;
            echo json_encode($data);
	}

	protected function getUrlList($action)
	{
		$urls=array(
			'confirm' => 'https://api.cloudpayments.ru/payments/confirm ',
			'cancel'  => 'https://api.cloudpayments.ru/payments/void',
			'return'  => 'https://api.cloudpayments.ru/payments/refund',
      'get'     => 'https://api.cloudpayments.ru/payments/find',
		);
    
    return $urls[$action];
	}

	public function get_order($request)
  {
        $this->addError('check_payment');

        global $wpdb;
        if ($request['InvoiceId']):
            $cloud_order_res = $wpdb->get_results($wpdb->prepare("SELECT * FROM `wp_wpsc_purchase_logs` WHERE `id`=%d && `gateway`='cloudpayments'", $request['InvoiceId']));
            if($cloud_order_res[0]->id):
                   return $this->Object_to_array($cloud_order_res[0]);
            else: return false;       
            endif;
        else: return false;    
        endif;  
  }
  
	public function get_order_by_session($sessionid)
  {
        global $wpdb;
        if ($sessionid):
            $cloud_order_res = $wpdb->get_results($wpdb->prepare("SELECT * FROM `wp_wpsc_purchase_logs` WHERE `sessionid`=%d && `gateway`='cloudpayments'", $sessionid));
            if($cloud_order_res[0]->id):
                   return $this->Object_to_array($cloud_order_res[0]);
            else: return false;       
            endif;
        else: return false;    
        endif;  
  }
   
	public function processRequest($action,$request,$order)
	{
        $this->addError('processRequest - '.$action);
        $this->addError(print_r($request,true));
        
        if ($action == 'check')
        {
            return $this->processCheckAction($request,$order);
            die();
        }
        else if ($action == 'fail')
        {
            return $this->processFailAction($request, $order); 
            die();
        }
        else if ($action == 'pay')
        {
            return $this->processSuccessAction($request, $order);
            die();
        }
        else if ($action == 'refund')
        {
            return $this->processrefundAction($request, $order);
            die();
        }
        else if ($action == 'confirm')
        {
            return $this->processSuccessAction($request, $order);
            die();
        }
        else if ($action == 'cancel')
        {
            return $this->processCancelAction($request, $order);
            die();
        }
        else
        {

            $data['TECH_MESSAGE'] = 'Unknown action: '.$action;
            $this->addError('Unknown action: '.$action.'. Request='.print_r($request,true));
        }

	}
  
  public function addError($text)
  {
        $file=$_SERVER['DOCUMENT_ROOT'].'/wp-content/plugins/cloudpayments/log.txt';
        $current = file_get_contents($file);
        $current .= date("d-m-Y H:i:s").":".$text."\n";
        file_put_contents($file, $current);
  }
}