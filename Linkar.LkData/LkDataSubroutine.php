<?php
include_once realpath( __DIR__ . '/../Linkar.Strings/StringFunctions.php');
include_once realpath( __DIR__ . '/LkData.php');

/*
	Class: LkDataSubroutine
		Class to management the result of the operations Subroutine.
		
		Property: $Arguments
		string array
		
		Argument list of the Subroutine operation execution.
*/
class LkDataSubroutine extends LkData {
	public $Arguments;
	/*
		Constructor: __constructor
			Initializes a new instance of the LkDataCRUD class.
			
		Arguments:
			$subroutineResult - (string) The string result of the Subroutine operation execution.
	*/
	public function __construct($subroutineResult) {
		parent::__construct($subroutineResult);
		$this->Arguments = StringFunctions::ExtractSubroutineArgs($subroutineResult);
	}
}
?>
