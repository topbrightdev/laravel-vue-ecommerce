<?
global $MESS;

$MESS['TEST_MODE']="Test mode";
$MESS['TEST_MODE_DESCR']="Enable test mode";


$MESS['PUBLIC_ID']="Public ID";
$MESS['PUBLIC_ID_DESCR']="";

$MESS['API_PASS']="API password";
$MESS['API_PASS_DESCR']="";

$MESS['Payment_Schemes']="Payment Schemes";
$MESS['Payment_Schemes_DESCR']="";

$MESS['auth']="Dual message scheme (DMS)";

$MESS['charge']="Single message scheme (SMS)";

$MESS['SKIN']="Widget design";
$MESS['SKIN_DESCR']="";
$MESS['classic']="Classic";
$MESS['modern']="Modern";
$MESS['mini']="Mini";

$MESS['Calculation_Objects']="Предмет расчета";
$MESS['Calculation_Objects_DESCR']="";
$MESS['Unknown_object']="Предмет расчета не передается";
$MESS['Commodity']="Товар";
$MESS['ExcisedCommodity']="Подакцизный товар";
$MESS['Job']="Работа";
$MESS['Service']="Услуга";
$MESS['GamblingBet']="Ставка азартной игры";
$MESS['GamblingWin']="Выигрыш азартной игры";
$MESS['LotteryTicket']="Лотерейный билет";
$MESS['LotteryWin']="Выигрыш лотереи";
$MESS['RidAccess']="Предоставление РИД";
$MESS['Payment']="Платеж";
$MESS['AgentReward']="Агентское вознаграждение";
$MESS['Composite']="Составной предмет расчета";
$MESS['Another']="Иной предмет расчета";

$MESS['Calculation_Methods']="Способ расчета";
$MESS['Calculation_Methods_DESCR']="";
$MESS['Unknown_method']="Неизвестный способ расчета";
$MESS['FullPrepayment']="Предоплата 100%";
$MESS['PartialPrepayment']="Предоплата";
$MESS['AdvancePay']="Аванс";
$MESS['FullPay']="Полный расчёт";
$MESS['PartialPayAndCredit']="Частичный расчёт и кредит";
$MESS['Credit']="Передача в кредит";
$MESS['CreditPayment']="Оплата кредита";

$MESS['delivery_status']="Статус доставки";
$MESS['delivery_status_DESCR']="Отдельный статус доставки необходим при формировании двух чеков: один чек - при поступлении денег от покупателя, второй при отгрузке товара. Отправка второго чека возможна при следующих способах расчета: Предоплата, Предоплата 100%, Аванс";

$MESS['delivered']="Delivered";

$MESS['inn']="ИНН организации";

$MESS['TYPE_SYSTEM']="Payment scheme";
$MESS['TYPE_SYSTEM_DESCR']="";

$MESS['PROCESS_URL']="Processing URL";
$MESS['PROCESS_URL_DESCR']="";

$MESS['SUCCESS_URL']="Success URL";
$MESS['SUCCESS_URL_DESCR']="";

$MESS['FAIL_URL']="Fail URL";
$MESS['FAIL_URL_DESCR']="";

$MESS['CURRENCY']="Accepted Currency";
$MESS['CURRENCY_DESCR']="";

$MESS['LANGUAGE']="Language";
$MESS['LANGUAGE_DESCR']="";

$MESS['LANGUAGE_WIDGET']="Widget language";
$MESS['LANGUAGE_WIDGET_DESCR']="";

$MESS['TYPE_NALOG']="Type of taxation system";
$MESS['TYPE_NALOG_DESCR']="";

$MESS['VAT']="VAT";
$MESS['VAT_DESCR']="";

$MESS['USE_CLOUD_KASSIR']="Use check generation functionality";
$MESS['USE_CLOUD_KASSIR_DESCR']="";

$MESS['MESSAGE1']="
Accepting payments online with a bank card through CloudPayments <br>
Log in to the personal area of CloudPayments and fix the paths in the notification settings: <br>
Settings Check notifications: http://".$_SERVER['HTTP_HOST']."/?action=check<br>
Pay notification settings: http://".$_SERVER['HTTP_HOST']."/?action=pay<br>
Confirm notification settings: http://".$_SERVER['HTTP_HOST']."/?action=confirm<br>
Notification Fail settings: http://".$_SERVER['HTTP_HOST']."/?action=fail<br>
Cancel notification settings: http://".$_SERVER['HTTP_HOST']."/?action=cancel<br>
Refund notifications settings: http://".$_SERVER['HTTP_HOST']."/?action=refund<br> ";

$MESS['RUB'] = 'Russian ruble RUB';
$MESS['EUR'] = 'Euro EUR';
$MESS['USD'] = 'US Dollar USD';
$MESS['GBP'] = 'Pound sterling GBP';
$MESS['UAH'] = 'Ukrainian Hryvnia UAH';
$MESS['BYR'] = 'The Belarusian ruble (not used since July 1, 2016) BYR';
$MESS['BYN'] = 'Belarusian ruble BYN';
$MESS['KZT'] = 'Kazakh tenge KZT';
$MESS['AZN'] = 'Azeri manat AZN';
$MESS['CHF'] = 'Swiss franc CHF';
$MESS['CZK'] = 'The Czech crown CZK';
$MESS['CAD'] = 'Canadian CAD dollar';
$MESS['PLN'] = 'Polish zloty PLN';
$MESS['SEK'] = 'Swedish krona SEK';
$MESS['TRY'] = 'Turkish lira TRY';
$MESS['CNY'] = 'Chinese yuan CNY';
$MESS['INR'] = 'Indian Rupee INR';
$MESS['BRL'] = 'Brazilian real BRL';
$MESS['ZAL'] = 'South African Rand ZAL';
$MESS['UZS'] = 'Uzbek sum UZS';

$MESS['STATUS_CHANCEL']='Cp: Refund';

$MESS['STATUS_AUTH']='Cp: Authorized';

$MESS['en-RU'] = 'Russian MSK';
$MESS['en-US'] = 'English CET';
$MESS['lv'] = 'Latvian CET';
$MESS['az'] = 'Azeri AZT';
$MESS['kk'] = 'Russian ALMT';
$MESS['kk-KZ'] = 'Kazakh ALMT';
$MESS['uk'] = 'Ukrainian EET';
$MESS['pl'] = 'Polish CET';
$MESS['pt'] = 'Portuguese CET';

$MESS['widget_description']='Order № #ORDER_ID# on "#SITE_NAME#" from #DATE#';
$MESS['SUCCESS_URL_msg']="Payment was successful";
$MESS['payButton']="Pay button";
$MESS['DELIVERY_TXT']='Delivery';

$MESS['ENCODING']="Text encoding";
$MESS['ENCODING_DESCR']="";


$MESS['TYPE_NALOG1'] = 'General taxation system';
$MESS['TYPE_NALOG2'] = 'Simplified Taxation System (Income)';
$MESS['TYPE_NALOG3'] = 'Simplified taxation system (Income minus Expenditure)';
$MESS['TYPE_NALOG4'] = 'Single tax on imputed income';
$MESS['TYPE_NALOG5'] = 'Unified Agricultural Tax';
$MESS['TYPE_NALOG6'] = 'Patent system of taxation';

$MESS['cloudpayments_transaction_url']='Transaction url';
$MESS['cloudpayments_transaction_url_DESCR']='Url successful ordering, with session_id in parameters<br> By default /index.php/products-page/transaction-results/?sessionid=#SESSION_ID#, where sessionid = #SESSION_ID# required GET parameter';

$MESS['cloudpayments_change_status'] = 'Status for refund of payment';
$MESS['cloudpayments_change_status_DESCR'] = 'Select the status, when changing to which, the payment will be refunded through the API Cloudpayments';

$MESS['cloudpayments_change_status_auth']='Authorized Payment Status';
$MESS['cloudpayments_change_status_auth_DESCR']='Select the status, upon change to which, payment will be authorized for two-stage payment';
?>