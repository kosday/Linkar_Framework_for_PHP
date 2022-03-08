<?php
include_once realpath( __DIR__ . '/../Linkar.Strings/StringFunctions.php');
include_once realpath( __DIR__ . '/LkData.php');

/*
	Class: LkDataExecute
		Class to management the result of the operations Execute.
		
		Property: $Capturing
		string
		
		The Capturing value of the Execute operation.
		
		Property: $Returning
		string
		
		The Returning value of the Execute operation
*/
class LkDataExecute extends LkData {
	public $Capturing;
	public $Returning;
	/*
		Constructor: __constructor
			Initializes a new instance of the LkDataExecute class.
		
		Arguments:
			$executeResult - (string) The string result of the Execute operation execution.
	*/
	public function __construct($executeResult) {
		parent::__construct($executeResult);
		$this->Capturing = StringFunctions::ExtractCapturing($executeResult);
		$this->Returning = StringFunctions::ExtractReturning($executeResult);
	}
}
?>
