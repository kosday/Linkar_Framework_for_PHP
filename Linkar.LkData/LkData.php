<?php
include_once realpath( __DIR__ . '/../Linkar.Strings/StringFunctions.php');

/*
	Class: LkData
		Base class with common properties of all derived class.
	
	Property: $OperationResult
	string
	
	The string that is obtained as result from the operation execution.
	
	Property: $Errors
	string array
	
	List of the error of the operation execution.
	
*/
class LkData {
	public $OperationResult;
	public $Errors;
	/*
		Constructor: __constructor
			Initializes a new instance of the LkData class.
		
		Arguments:
			$opResult - (string) The string result of the operation execution.
	*/
	public function __construct($opResult) {
		$this->OperationResult = $opResult;
		$this->Errors = StringFunctions::ExtractErrors($opResult);
	}
}
?>
