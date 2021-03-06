<?php
include_once realpath( __DIR__ . '/CommonOptions.php');

/*
	Class: SelectOptions
		Object that works as an argument in Select function and defines the function options.
		
	Property: $OnlyRecordId
		boolean
		
		Returns just the selected records codes.
	
	Property: $Pagination
		boolean
		
		Indicates if pagination is being used or not.
		
	Property: $Pagination_RegPage
		number
		
		In case of pagination indicates the number of records by page. It must be bigger than 0.
		
	Property: $Pagination_NumPage
		number
		
		In case of pagination it indicates the page number to obtain. Must be greater than 0.
		
	Property: $Calculated
		boolean
		
		Returns the resulting values from the calculated dictionaries.
		
	Property: $Conversion
		boolean
		
		Executes the defined conversions in the dictionaries before returning.
	
	Property: $FormatSpec
		boolean
		
		Executes the defined formats in the dictionaries before returning.
	
	Property: $OriginalRecords
		boolean
		
		Returns a copy of the records in MV format.
*/
class SelectOptions {
	
	/*
		Constructor: __constructor
			Initializes a new instance of the SelectOptions class.
			
		Arguments:
			$onlyRecordId - (boolean) Returns just the ID(s) of selected record(s).
			$pagination - (boolean) True if pagination is being used.
			$regPage - (number) For use with pagination, indicates the number of records by page. Must be greater than 0.
			$numPage - (number) For use with pagination, indicates the page number to obtain. Must be greater than 0.
			$calculated - (boolean) Return the resulting values from the calculated dictionaries.
			$conversion - (boolean) Execute the defined conversions in the dictionaries before returning.
			$formatSpec - (boolean) Execute the defined formats in the dictionaries before returning.
			$originalRecords - (boolean) Return a copy of the records in MV format.
	*/
	public function __construct($onlyRecordId = false, $pagination = false, $regPage = 10, $numPage = 0, $calculated = false, $conversion = false, $formatSpec = false, $originalRecords = false) {
		$this->CommonOptions =  new CommonOptions($calculated, $conversion, $formatSpec, $originalRecords);
		$this->OnlyRecordId = $onlyRecordId;
		$this->Pagination = $pagination;
		$this->Pagination_RegPage = $regPage;
		$this->Pagination_NumPage = $numPage;
		$this->Calculated = $this->CommonOptions->Calculated;
		$this->Conversion = $this->CommonOptions->Conversion;
		$this->FormatSpec = $this->CommonOptions->FormatSpec;
		$this->OriginalRecords = $this->CommonOptions->OriginalRecords;
	}

	/*
		Function: GetString
			Composes the Select options string for processing through LinkarSERVER to the database.
			
		Returns:
			string
			
			The string ready to be used by LinkarSERVER.
	*/
	public function GetString() {
		$str = ($this->Pagination ? "1" : "0") . DBMV_Mark::VM_str . $this->Pagination_RegPage . DBMV_Mark::VM_str . $this->Pagination_NumPage . DBMV_Mark::AM_str .
					($this->OnlyRecordId ? "1" : "0") . DBMV_Mark::AM_str .
					$this->CommonOptions->GetStringAM();
		return $str;
	}
}
?>
