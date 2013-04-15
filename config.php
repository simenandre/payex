<?php

return array(
/*
	|--------------------------------------------------------------------------
	| Account Number
	|--------------------------------------------------------------------------
	|
	| The Merchant account number. Provided by Payex. Remember to set this,
	| You wouldn't be able to run the script without it.
	|
*/
	'accountNumber' => '',
/*
	|--------------------------------------------------------------------------
	| Encryption Key
	|--------------------------------------------------------------------------
	|
	| The encryption key. Accessable trough PayEx Admin. Remember to set this,
	| You wouldn't be able to run the script without it.
	|
*/
	'encryptionKey' => '',
/*
	|--------------------------------------------------------------------------
	| Currency
	|--------------------------------------------------------------------------
	|
	| What currency do you run you'r prices?
	|
	| Can be changed trough runtime! Just do
	| Payex::setConfig('currency', '<currency>').
	|
*/ 
	'currency' => 'NOK',
/*
	|--------------------------------------------------------------------------
	| Default return URL
	|--------------------------------------------------------------------------
	|
	| Where do you want to send the customers after a completed transaction?
	| 
	| Btw, you can change it trough runtime! Just do:
	| Payex::setConfig('returnURL', '<returnURL>');
	|
*/
	'returnURL' => 'http://test.no/?haha',
/*
	|--------------------------------------------------------------------------
	| Default cancel URL
	|--------------------------------------------------------------------------
	|
	| Let's say a user cancels the transaction, where do you want to send the
	| user?
	| 
	| Btw, you can change it trough runtime! Just do:
	| Payex::setConfig('cancelURL', '<cancelURL>');
	|
*/
	'cancelURL' => 'http://localhost/',
/*
	|--------------------------------------------------------------------------
	| Default VAT
	|--------------------------------------------------------------------------
	|
	| Please set a default VAT, PayEx needs this to calculate a preview for the
	| user.
	| 
	| Btw, you can change it trough runtime! Just do:
	| Payex::setConfig('vat', '<vat>');
	| 
	| Example, if the vat is supposed to be 25%, its defined as 2500. It's as
	| simple as that.
	|
*/
	'vat' => '2500',
/*
	|--------------------------------------------------------------------------
	| Client Language
	|--------------------------------------------------------------------------
	|
	| The language used in the redirect purchase dialog with the client.
	| Available languages depend on the merchant configuration.
	|
	| Supported languages:
	| nb-NO, da-DK, en-US, sv-SE, es-ES, de-DE, fi-FI, fr-FR, pl-PL, cs-CZ, hu-HU 
	|
	| If no language is specified, the default language for client UI is used.
	| 
	| Btw, you can change it trough runtime! Just do:
	| Payex::setConfig('vat', '<vat>');
	|
*/
	'clientLanguage' => 'nb-NO',
/*
	|--------------------------------------------------------------------------
	| PxOrderWSDL & PxConfinedWSDL
	|--------------------------------------------------------------------------
	|
	| The URLs thats used to connect to PayEx, a set of 2 urls are needed. 
	| For simplicity we've added both the test URLs and production URLs.
	| 
*/	
	// Production Environment
	#'PxOrderWSDL' => 'https://external.payex.com/pxorder/pxorder.asmx?wsdl',
	#'PxConfinedWSDL' => 'https://confined.payex.com/PxConfined/pxorder.asmx?wsdl'

	// Test Environment
	'PxOrderWSDL' => 'https://test-external.payex.com/pxorder/pxorder.asmx?wsdl',
	'PxConfinedWSDL' => 'https://test-confined.payex.com/PxConfined/pxorder.asmx?wsdl'
);
?>

