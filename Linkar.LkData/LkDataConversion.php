<?php
include_once realpath( __DIR__ . '/../Linkar.Strings/StringFunctions.php');
include_once realpath( __DIR__ . '/LkData.php');

/*
	Class: LkDataConversion
		Class to management the result of the operations Conversion.
		
		Property: $Conversion
		string
		
		The value of the Conversion operation
*/
class LkDataConversion extends LkData {
	public $Conversion;
	/*
		Constructor: __constructor
		
		
		Arguments:
			$conversionResult - (string) The string result of the Conversion operation execution.
	*/
	public function __construct($conversionResult) {
		parent::__construct($conversionResult);
		$this->Conversion = StringFunctions::ExtractConversion($conversionResult);
	}
}
?>
