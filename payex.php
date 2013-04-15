<?php
class PayEx
{
	//$respons is xml
	static private $respons;
	static private $orderRef;

	static public $result;
	static public $status;

	static private $purchaseOperation = 'SALE'; // AUTHORIZATION or SALE
	static private $price = 0; // Product price, in lowest monetary unit (=1 NOK)
	static private $priceArgList = ''; // No CPA, VISA,
	static private $orderID = '0'; // Local order id
	static private $productNumber = '0'; // Local product number
	static private $description = ''; // Product description
	static private $clientIPAddress = '';
	static private $clientIdentifier = '';
	static private $additionalValues = '';
	static private $externalID = '';
	static private $agreementRef = '';

	static private $config;
	static private $isConstructed;
	static private $configFile = "config.php";

	function __construct(){ PayEx::construct(); }

	static function construct(){
		if(!self::$isConstructed){
			self::$config = new stdClass();

			$config = include(self::$configFile);
			foreach($config as $name => $value){
				self::setConfig($name, $value);
			}
			self::$isConstructed = true;
		}
	}

	static function setConfigFile($filepath){
		self::$configFile = $filepath;
	}

	static function setConfig($name, $value){
		self::$config->{$name} = $value;
	}

	static function getConfig($name){
		if(isset(self::$config->{$name}))
			return self::$config->{$name};
		else return null;
	}

	static function getPrice(){
		return self::$price;
	}

	static function setPrice($price){
		if(!is_numeric($price)) return false;
		self::$price = number_format($price, 2, "", "");
	}

	static function setOrderID($orderID){
		self::$orderID = $orderID;
	}

	static function getOrderID(){
		return self::$orderID ? self::$orderID : 0;
	}
	
	static function setProductNumber($productNumber){
		self::$productNumber = $productNumber;
	}

	static function getProductNumber(){
		return self::$productNumber ? self::$productNumber : 0;
	}

	static function setDescription($description){
		self::$description = $description;
	}

	static function getDescription(){
		return self::$description ? self::$description : "Undefined";
	}

	static function setExternalID($externalID){
		self::$externalID = $externalID;
	}

	static function getExternalID(){
		return self::$externalID ? self::$externalID : 0;
	}

	static function initialization()
	{		
		self::construct();

		//$_server won't work if run from console.
		self::$clientIPAddress = $_SERVER['REMOTE_ADDR'];		
		self::$clientIdentifier = "USERAGENT=".$_SERVER['HTTP_USER_AGENT'];
		$params = array(
			'accountNumber' => self::getConfig('accountNumber'),
			'purchaseOperation' => self::$purchaseOperation,
			'price' => self::getPrice(),
			'priceArgList' => self::$priceArgList,
			'currency' => self::getConfig('currency'),
			'vat' => self::getConfig('vat'),
			'orderID' => self::getOrderID(),
			'productNumber' => self::getProductNumber(),
			'description' => self::getDescription(),
			'clientIPAddress' => self::$clientIPAddress,
			'clientIdentifier' => self::$clientIdentifier,
			'additionalValues' => self::$additionalValues,
			'externalID' => self::getExternalID(),
			'returnUrl' => self::getConfig('returnURL'),
			'view' => self::$view,
			'agreementRef' => self::$agreementRef,
			'cancelUrl' => self::getConfig('cancelURL'),
			'clientLanguage' => self::getConfig('clientLanguage')
		);
		
		return $params;
	}

	static function transaction($orderID, $price, $productNumber=0, $description=""){
		self::setPrice($price);
		self::setOrderID($orderID);
		self::setProductNumber($productNumber);
		self::setDescription($description);

		self::TwoPhaseTransaction();

		return new Payex;
	}

	static function getStatus(){
		return json_decode(json_encode(self::$status));
	}

	static function isOK(){
		return self::getStatus()->code == "OK" ? true : false;
	}

	/* this function is used when selling physical merchandieses, books, cars... */
	static function TwoPhaseTransaction()
	{	
		$params = self::initialization();
		self::$result = self::initialize7($params);
		self::$status = self::checkStatus(self::$result);
	}
	
	static function Redirect()
	{
		// if code & description & errorCode is OK, redirect the user
		if(self::$status['code'] == "OK" && self::$status['errorCode'] == "OK" && self::$status['description'] == "OK")
		{
			header('Location: '.self::$status['redirectUrl']);
		}else {
			foreach(self::$status as $error => $value)
			{ 
				echo "$error, $value"."\n"; 
			}
		}
	}
	
	static function CompleteIt($orderRef)
	{
		$orderRef = stripcslashes( $orderRef );
		
		$params = array
		(
			'accountNumber' => self::$accountNumber,
			'orderRef' => $orderRef
		);
				
		$completeResponse = self::CompleteOrder($params);		
		$result = self::complete($completeResponse);
		return $result;
	}

	static function createHash($params)
	{
		return md5($params.self::getConfig('encryptionKey'));
	}

	static function checkStatus($xml)
	{
	 $returnXml = new SimpleXMLElement($xml);
	 $code = strtoupper($returnXml->status->code);
	 $errorCode = strtoupper($returnXml->status->errorCode);
	 $description = strtoupper($returnXml->status->description);
	 $orderRef = strtoupper($returnXml->orderRef);
	 $authenticationRequired = strtoupper($returnXml->authenticationRequired);

	 	return $status = array(
	 	'code'=>$code,
	 	'errorCode'=>$errorCode,
	 	'description'=>$description,
	 	'redirectUrl'=>$returnXml->redirectUrl,
	 	'orderRef'=>$orderRef,
	 	'authenticationRequired'=>$authenticationRequired);
	 	
	}

	static function complete($params)
	{
	 $returnXml = new SimpleXMLElement($params);
	 $code = strtoupper($returnXml->status->code);
	 $errorCode = strtoupper($returnXml->status->errorCode);
	 $description = strtoupper($returnXml->status->description);
	 $transactionStatus = strtoupper($returnXml->transactionStatus);


	 	return $status = array(
	 	'code'=>$code,
	 	'errorCode'=>$errorCode,
	 	'description'=>$description,
	 	'transactionStatus'=>$transactionStatus);
	}

	static function initialize7($params)
	{
		$PayEx = new SoapClient(self::getConfig('PxOrderWSDL'),array("trace" => 1, "exceptions" => 0));
		
		$hash = self::createHash(trim(implode("", $params)));
		$params['hash'] = $hash;

		try{
			//defining which initialize version to run, this one is 7.
			$respons = $PayEx->Initialize7($params);
			/* NB: SHOULD BE EDITED TO NOT SHOW THE CUSTOMER THIS MESSAGE, BUT SHOW A GENERIC ERROR MESSAGE FOR THE USER, BUT YOU SHOULD BE INFORMED OF THE ERROR. "*/
		}catch (SoapFault $error){
			echo "Error: {$error->faultstring}";
		}
		return $respons->{'Initialize7Result'};
		//print_r($respons->{'Initialize7Result'}."\n");
	}


	static function CompleteOrder($params)
	{
		$PayEx = new SoapClient(self::getConfig('PxOrderWSDL'),array("trace" => 1, "exceptions" => 0));

		$hash = self::createHash(trim(implode("", $params)));
		//append the hash to the parameters
		$params['hash'] = $hash;

		try{
			//defining which complete
			$respons = $PayEx->Complete($params);
			/* NB: SHOULD BE EDITED TO NOT SHOW THE CUSTOMER THIS MESSAGE, BUT SHOW A GENERIC ERROR MESSAGE FOR THE USER, BUT YOU SHOULD BE INFORMED OF THE ERROR. "*/
		}catch (SoapFault $error){
			echo "Error: {$error->faultstring}";
		}
		return $respons->{'CompleteResult'};

	}

	static function saleCC3($params)
	{
		$PayEx = new SoapClient(self::getConfig('PxConfinedWSDL'),array("trace" => 1, "exceptions" => 0));		
		
		$hash = self::createHash(trim(implode("", $params)));		
		//append the hash to the parameters
		$params['hash'] = $hash;

		try{
			//defining which initialize version to run, this one is 6.
			$respons = $PayEx->SaleCC3($params);

			/* NB: SHOULD BE EDITED TO NOT SHOW THE CUSTOMER THIS MESSAGE, BUT SHOW A GENERIC ERROR MESSAGE FOR THE USER, BUT YOU SHOULD BE INFORMED OF THE ERROR. "*/
		}catch (SoapFault $error){
			echo "Error: {$error->faultstring}";
		}
		return $respons->{'SaleCC3Result'};
		
	}
	
	static function prepareSaleCC2($params)
	{
		$PayEx = new SoapClient(self::getConfig('PxConfinedWSDL'),array("trace" => 1, "exceptions" => 0));		
		unset($params['transactionType']);
		
		//create the hash
		$hash = self::createHash(trim(implode("", $params)));
		
		$params['hash'] = $hash;
	
		try{
			//defining which initialize version to run, this one is 6.
			$respons = $PayEx->PrepareSaleCC2($params);
			/* NB: SHOULD BE EDITED TO NOT SHOW THE CUSTOMER THIS MESSAGE, BUT SHOW A GENERIC ERROR MESSAGE FOR THE USER, BUT YOU SHOULD BE INFORMED OF THE ERROR. "*/
		}catch (SoapFault $error){
			echo "Error: {$error->faultstring}";
		}
	
		return $respons->{'PrepareSaleCC2Result'};

		
	}


}
?>