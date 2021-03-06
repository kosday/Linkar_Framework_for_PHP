<?php
include_once 'SCHEMA_TYPE.php';

/*
	Class: LkSchemasOptions
		Contains the options to obtain different types of schemas with the LkSchemas function.
		
		Property: $SchemaType
			<SCHEMA_TYPE>
			
			Indicates the type of LkSchemas used.

		Property: $RowHeaders
			<ROWHEADERS_TYPE>
			
			Include headings in first row MAINLABEL (main headings), SHORTLABEL (short label headings), and NONE (without headings).

		Property: $SqlMode
			boolean
			
			SQLMODE type schemas

		Property: $RowProperties
			boolean

			First row contains property names.

		Property: $OnlyVisibles
			boolean
			
			Use only Visible Schemas and Properties.

		Property: $Pagination
			boolean
			
			Indicates if pagination is being used or not.

		Property: $Pagination_RegPage
			number
			
			In case of pagination indicates the number of records by page. It must be bigger than 0.

		Property: $Pagination_NumPage
			number
			
			In case of pagination it indicates the page number to obtain. Must be greater than 0.
*/
class LkSchemasOptions
{
	/*
		Constructor: __constructor
			Initializes a new instance of the LkSchemasOptions class.
			
			The object is created with the default values for LKSCHEMAS type schemas.
	*/
	public function __construct() {
		$this->SchemaType = SCHEMA_TYPE::LKSCHEMAS;
		$this->SqlMode = false;
		$this->RowHeader = ROWHEADERS_TYPE::MAINLABEL;
		$this->RowProperties = false;
		$this->OnlyVisibles = false;
		$this->Pagination = false;
		$this->Pagination_RegPage = 10;
		$this->Pagination_NumPage = 1;
	}

	//LK.SCHEMAS
	/*
		Function: LkSchemas
			Constructor of object used to obtain LKSCHEMAS type schemas.
			
			Initializes a new instance of the LkSchemasOptions class.
		
		Arguments:
			$rowHeaders - (<ROWHEADERS_TYPE>) Include headings in first row MAINLABEL (main headings), SHORTLABEL (short label headings), and NONE (without headings).
			$rowProperties - (boolean) First row contains property names.
			$onlyVisibles - (boolean) Use only Visible Schemas and Properties.
			$pagination - (boolean) True if pagination is being used.
			$regPage - (number) For use with pagination, indicates the number of records by page. Must be greater than 0.
			$numPage - (number) For use with pagination, indicates the page number to obtain. Must be greater than 0.
	*/
	public function LkSchemas($rowHeader = ROWHEADERS_TYPE::MAINLABEL, $rowProperties = false, $onlyVisibles = false, $pagination = false, $regPage = 10, $numPage = 1) {
		$this->SchemaType = SCHEMA_TYPE::LKSCHEMAS;
		$this->SqlMode = false;
		$this->RowHeader = $rowHeader;
		$this->RowProperties = $rowProperties;
		$this->OnlyVisibles = $onlyVisibles;
		$this->Pagination = $pagination;
		$this->Pagination_RegPage = $regPage;
		$this->Pagination_NumPage = $numPage;
}

	//SQLMODE
	/*
		Function: SqlMode
			Initializes the instance of the LkSchemasOptions class.

			Constructor of object used to obtain SQLMODE type schemas. Creation options are allowed for SQLMODE type schemas.
		
		Arguments:
			$onlyVisibles - (boolean) Use only Visible Schemas and Properties.
			$pagination - (boolean) True if pagination is being used.
			$regPage - (number) For use with pagination, indicates the number of records by page. Must be greater than 0.
			$numPage - (number) For use with pagination, indicates the page number to obtain. Must be greater than 0.
	*/
	public function SqlMode($onlyVisibles = false, $pagination = false, $regPage = 10, $numPage = 1) {
		$this->SchemaType = SCHEMA_TYPE::LKSCHEMAS;
		$this->SqlMode = true;
		$this->RowHeader = ROWHEADERS_TYPE::NONE;
		$this->RowProperties = true;
		$this->OnlyVisibles = $onlyVisibles;
		$this->Pagination = $pagination;
		$this->Pagination_RegPage = $regPage;
		$this->Pagination_NumPage = $numPage;
	}

	//DICTIONARIES
	/*
		Function: Dictionaries
			Initializes the instance of the LkSchemasOptions class.
			
			Constructor of object used to obtain DICTIONARIES type schemas. Creation options are allowed for DICTIONARIES type schemas.
		
		Arguments:
			$rowHeaders - (ROWHEADERS_TYPE) Include headings in first row MAINLABEL (main headings), SHORTLABEL (short label headings), and NONE (without headings).
			$pagination - (boolean) True if pagination is being used.
			$regPage - (number) For use with pagination, indicates the number of records by page. Must be greater than 0.
			$numPage - (number) For use with pagination, indicates the page number to obtain. Must be greater than 0.
	*/
	public function Dictionaries($rowHeader = ROWHEADERS_TYPE::MAINLABEL, $pagination = false, $regPage = 10, $numPage = 1) {
		$this->SchemaType = SCHEMA_TYPE::DICTIONARIES;
		$this->SqlMode = false;
		$this->RowHeader = $rowHeader;
		$this->RowProperties = true;
		$this->OnlyVisibles = true;
		$this->Pagination = $pagination;
		$this->Pagination_RegPage = $regPage;
		$this->Pagination_NumPage = $numPage;
	}

	/*
		Function: GetString
			Composes the LkSchemas options string for processing through LinkarSERVER to the database.
			
		Returns:
			string
		
			The string ready to be used by LinkarSERVER.
	*/
	public function GetString() {
		$str = $this->SchemaType . DBMV_Mark::AM_str .
			($this->SqlMode ? "1" : "0") . DBMV_Mark::AM_str .
			($this->RowProperties ? "1" : "0") . DBMV_Mark::AM_str .
			($this->OnlyVisibles ? "1" : "0") . DBMV_Mark::AM_str .
		$this->RowHeader . DBMV_Mark::AM_str .
		($this->Pagination ? "1" : "0") . DBMV_Mark::VM_str .
		$this->Pagination_RegPage . DBMV_Mark::VM_str .
		$this->Pagination_NumPage;
		
		return $str;
	}
}
?>
