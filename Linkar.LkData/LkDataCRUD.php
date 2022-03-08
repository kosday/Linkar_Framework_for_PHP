<?php
include_once realpath( __DIR__ . '/../Linkar.Strings/StringFunctions.php');
include_once realpath( __DIR__ . '/LkData.php');
include_once realpath( __DIR__ . '/LkItems.php');
include_once realpath( __DIR__ . '/LkItem.php');

/*
	Class: LkDataCRUD
		Class to management the result of the operations Read, Update, New, Delete, Select and Dictionaries.
		
		Property: $TotalItems
		(number)
		
		Number of the items.
		
		Property: $LkRecords
		(<LkItems>)
		
		LkItem list from the CRUD operation execution.
		
*/
class LkDataCRUD extends LkData {
	public $TotalItems;
	public $LkRecords;
	/*
		Constructor: __constructor
			Initializes a new instance of the LkDataCRUD class.
			
		Arguments:
			$crudOperationResult - (string) The string result of the CRUD operation execution.
	*/
	public function __construct($crudOperationResult) {
		parent::__construct($crudOperationResult);
		$this->TotalItems = StringFunctions::ExtractTotalRecords($crudOperationResult);
	
			$lstIdDicts = StringFunctions::ExtractRecordsIdDicts($crudOperationResult);
			$lstDictionaries = StringFunctions::ExtractRecordsDicts($crudOperationResult);
			$lstCalculatedDicts = StringFunctions::ExtractRecordsCalculatedDicts($crudOperationResult);
			$this->LkRecords = new LkItems($lstIdDicts, $lstDictionaries, $lstCalculatedDicts);
	
			$lstRecords = StringFunctions::ExtractRecords($crudOperationResult);
			$lstRecordIds = StringFunctions::ExtractRecordIds($crudOperationResult);
			$lstOriginalRecords = StringFunctions::ExtractOriginalRecords($crudOperationResult);
			$lstRecordsCalculated = StringFunctions::ExtractRecordsCalculated($crudOperationResult);

			for ($i = 0; $i < count($lstRecordIds); $i++)
			{
				$record = (count($lstRecords) == count($lstRecordIds) ? $lstRecords[$i] : "");
				$originalRecord = (count($lstOriginalRecords) == count($lstRecordIds) ? $lstOriginalRecords[$i] : "");
				$calculateds = (count($lstRecordsCalculated) == count($lstRecordIds) ? $lstRecordsCalculated[$i] : "");
				$lkRecord = new LkItem($lstRecordIds[$i], $record, $calculateds, $originalRecord);
				$this->LkRecords->push($lkRecord);
			}
	}
}
?>
