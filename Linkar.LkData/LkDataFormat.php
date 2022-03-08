<?php
include_once realpath( __DIR__ . '/../Linkar.Strings/StringFunctions.php');
include_once realpath( __DIR__ . '/LkData.php');

/*
	Class: LkDataFormat
		Class to management the result of the operations Format.
	
		Property: $Format
		string
		
		The value of Format operation.
*/
class LkDataFormat extends LkData {
	public $Format;
	/*
		Constructor: __constructor
			Initializes a new instance of the LkDataCRUD class.
			
		Arguments:
			$formatResult - (string) The string result of the Format operation execution.
	*/
	public function __construct($formatResult) {
		parent::__construct($formatResult);
		$this->Format = StringFunctions::ExtractFormat($formatResult);
	}
}
?>
