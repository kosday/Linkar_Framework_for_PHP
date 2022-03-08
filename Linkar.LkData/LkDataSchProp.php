<?php
include_once realpath( __DIR__ . '/../Linkar.Strings/StringFunctions.php');
include_once realpath( __DIR__ . '/LkData.php');

/*
	Class: LkDataSchProp
		Class to management the result of the operations LkSchemas and LkProperties.
		
		Property: $RowProperties
		string
		
		The RowProperties value of the LkSchemas or LkLkProperties operations.
		
		Property: $RowHeaders
		string
		
		The RowHeaders value of the LkSchemas or LkProperties operations.
*/
class LkDataSchProp extends LkData {
	public $RowProperties;
	public $RowHeaders;
	/*
		Constructor: __constructor
			Initializes a new instance of the LkDataSchProp class.
		
		Arguments:
			$lkSchemasResult - (string) The string result of the Lkchemas or LkProperties operation execution.
	*/
	public function __construct($lkSchemasResult) {
		parent::__construct($lkSchemasResult);
		$this->RowProperties = StringFunctions::ExtractRowProperties($lkSchemasResult);
		$this->RowHeaders = StringFunctions::ExtractRowHeaders($lkSchemasResult);
	}
}
?>
