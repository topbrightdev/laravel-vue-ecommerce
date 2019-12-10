<?
global $MESS;

$MESS['TEST_MODE']="Тестовый режим";
$MESS['TEST_MODE_DESCR']="Включить тестовый режим работы платежной системы";

$MESS['PUBLIC_ID']="Public ID";
$MESS['PUBLIC_ID_DESCR']="Ключ доступа (из личного кабинета CloudPayments)";

$MESS['API_PASS']="Пароль для API";
$MESS['API_PASS_DESCR']="Пароль доступа (из личного кабинета CloudPayments)";

$MESS['Payment_Schemes']="Схема проведения платежа";
$MESS['Payment_Schemes_DESCR']="";

$MESS['auth']="Двухстадийная оплата (DMS)";

$MESS['charge']="Одностадийная оплата (SMS)";

$MESS['SKIN']="Дизайн виджета";
$MESS['SKIN_DESCR']="";

$MESS['classic']="Classic";

$MESS['modern']="Modern";

$MESS['mini']="Mini";


$MESS['PROCESS_URL']="Сервисный URL";
$MESS['PROCESS_URL_DESCR']="";

$MESS['SUCCESS_URL']="Success URL";
$MESS['SUCCESS_URL_DESCR']="";

$MESS['FAIL_URL']="Fail URL";
$MESS['FAIL_URL_DESCR']="";

$MESS['CURRENCY']="Валюта заказа";
$MESS['CURRENCY_DESCR']="";

$MESS['LANGUAGE']="Язык модуля";
$MESS['LANGUAGE_DESCR']="";

$MESS['LANGUAGE_WIDGET']="Язык виджета";
$MESS['LANGUAGE_WIDGET_DESCR']="";

$MESS['TYPE_NALOG']="Тип системы налогообложения";
$MESS['TYPE_NALOG_DESCR']="Указанная система налогообложения должна совпадать с одним из вариантов, зарегистрированных в ККТ.";

$MESS['VAT']="НДС";
$MESS['VAT_DESCR']="";

$MESS['USE_CLOUD_KASSIR']="Использовать функционал формирования чеков";
$MESS['USE_CLOUD_KASSIR_DESCR']="Данный функционал должен быть включен на стороне CloudPayments";

$MESS['MESSAGE1']="
Приём платежей онлайн с помощью банковской карты через систему CloudPayments<br> 
Зайти в личный кабинет CloudPayments и исправить пути в настройках уведомлений:<br> 
Настройки Сheck уведомлений: http://".$_SERVER['HTTP_HOST']."/?action=check<br>
Настройки Pay уведомлений: http://".$_SERVER['HTTP_HOST']."/?action=pay<br>
Настройки Confirm уведомлений: http://".$_SERVER['HTTP_HOST']."/?action=confirm<br>
Настройки Fail уведомлений: http://".$_SERVER['HTTP_HOST']."/?action=fail<br>
Настройки Cancel уведомлений: http://".$_SERVER['HTTP_HOST']."/?action=cancel<br>
Настройки Refund уведомлений: http://".$_SERVER['HTTP_HOST']."/?action=refund<br>";

$MESS['RUB']='Российский рубль	RUB';
$MESS['EUR']='Евро	EUR';
$MESS['USD']='Доллар США	USD';
$MESS['GBP']='Фунт стерлингов	GBP';
$MESS['UAH']='Украинская гривна	UAH';
$MESS['BYR']='Белорусский рубль (не используется с 1 июля 2016)	BYR';
$MESS['BYN']='Белорусский рубль	BYN';
$MESS['KZT']='Казахский тенге	KZT';
$MESS['AZN']='Азербайджанский манат	AZN';
$MESS['CHF']='Швейцарский франк	CHF';
$MESS['CZK']='Чешская крона	CZK';
$MESS['CAD']='Канадский доллар	CAD';
$MESS['PLN']='Польский злотый	PLN';
$MESS['SEK']='Шведская крона	SEK';
$MESS['TRY']='Турецкая лира	TRY';
$MESS['CNY']='Китайский юань	CNY';
$MESS['INR']='Индийская рупия	INR';
$MESS['BRL']='Бразильский реал	BRL';
$MESS['ZAL']='Южноафриканский рэнд	ZAL';
$MESS['UZS']='Узбекский сум	UZS';

$MESS['STATUS_CHANCEL']='Cp: Возврат оплаты';

$MESS['STATUS_AUTH']='Cp: Авторизован';

$MESS['ru-RU']='Русский	MSK';
$MESS['en-US']='Английский	CET';
$MESS['lv']='Латышский	CET';
$MESS['az']='Азербайджанский	AZT';
$MESS['kk']='Русский	ALMT';
$MESS['kk-KZ']='Казахский	ALMT';
$MESS['uk']='Украинский	EET';
$MESS['pl']='Польский	CET';
$MESS['pt']='Португальский	CET';

$MESS['widget_description']='заказ № #ORDER_ID# на "#SITE_NAME#" от #DATE#';
$MESS['SUCCESS_URL_msg']="Оплата прошла успешно";
$MESS['payButton']="Оплата";
$MESS['DELIVERY_TXT']='Доставка';

$MESS['ENCODING']="Кодировка текста";
$MESS['ENCODING_DESCR']="";

$MESS['TYPE_NALOG1']='Общая система налогообложения';
$MESS['TYPE_NALOG2']='Упрощенная система налогообложения (Доход)';
$MESS['TYPE_NALOG3']='Упрощенная система налогообложения (Доход минус Расход)';
$MESS['TYPE_NALOG4']='Единый налог на вмененный доход';
$MESS['TYPE_NALOG5']='Единый сельскохозяйственный налог';
$MESS['TYPE_NALOG6']='Патентная система налогообложения';

$MESS['cloudpayments_transaction_url']='Transaction url';
$MESS['cloudpayments_transaction_url_DESCR']='Url успешного оформления заказа, с session_id в параметрах<br>По умолчанию /index.php/products-page/transaction-results/?sessionid=#SESSION_ID#, где sessionid=#SESSION_ID# обязательный GET параметр';

$MESS['cloudpayments_change_status']='Статус для возврата платежа';
$MESS['cloudpayments_change_status_DESCR']='Выберите статус, при смене на который, будет осуществлен возврат оплаты через API Cloudpayments';

$MESS['cloudpayments_change_status_auth']='Статус для авторизованного платежа';
$MESS['cloudpayments_change_status_auth_DESCR']='Выберите статус, при смене на который, будет осуществлена авторизация платежа при двухстадийной оплате';
?>