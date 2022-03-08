<?php
/*
include_once realpath( __DIR__ . '/ASCII_Chars.php');
include_once realpath( __DIR__ . '/DBMV_Mark.php');
include_once realpath( __DIR__ . '/ReadOptions.php');
include_once realpath( __DIR__ . '/UpdateOptions.php');
include_once realpath( __DIR__ . '/SelectOptions.php');
include_once realpath( __DIR__ . '/RecordIdType.php');
*/
/*
    Class: OperationArguments
        Auxiliary static class with functions to obtain the 3 items of every LinkarSERVER operation.
        
        These items are: CUSTOMVARS, OPTIONS and INPUTDATA.
        
        Unit Separator character (31) is used as separator between these items.
        
        - CUSTOMVARS: String for database custom subroutines.
        - OPTIONS: The options of every operation.
        - INPUTDATA: The necessary data for perform every operation.
        
        CUSTOMVARS US_char OPTIONS US_char INPUTDATA
*/
class OperationArguments {

    /*
        Function: GetReadArgs
            Compose the 3 items (CUSTOMVARS, OPTIONS and INPUTDATA) of the Read operation.
        
        Arguments:
            $filename - (string) File name to read.
            $recordIds - (string) A list of item IDs to read, separated by the Record Separator character (30). Use StringFunctions.ComposeRecordIds to compose this string.
            $dictionaries - (string) List of dictionaries to read, separated by space. If this list is not set, all fields are returned.
            $readOptions - (<ReadOptions>) Object that defines the different reading options of the Function: Calculated, dictClause, conversion, formatSpec, originalRecords.
            $customVars - (string) Free text sent to the database allows management of additional behaviours in SUB.LK.MAIN.CONTROL.CUSTOM, which is called when this parameter is set.
        
        Returns:
            string
            
            A string ready to be used in <Linkar.LkExecuteDirectOperation> and <Linkar.LkExecutePersistentOperation>.
    */
    static function GetReadArgs($filename, $recordIds, $dictionaries, $readOptions, $customVars) {
        if (is_null($readOptions))
            $readOptions = new ReadOptions();
    
        $options = $readOptions->GetString();
        $inputData = $filename . DBMV_Mark::AM_str . $recordIds . DBMV_Mark::AM_str . $dictionaries;
    
        $cmdArgs = $customVars . ASCII_Chars::US_str . $options . ASCII_Chars::US_str . $inputData;
        return $cmdArgs;
    }

	/*
		Function: GetUpdateArgs
			Compose the 3 items (CUSTOMVARS, OPTIONS and INPUTDATA) of the GetUpdateArgs operation.
		
		Arguments:
			$filename - (string) Name of the file being updated.
			$records - (string) Buffer of record data to update. Inside this string are the recordIds, the modified records, and the originalRecords. Use StringFunctions.ComposeUpdateBuffer (Linkar.Strings library) function to compose this string.
			$updateOptions - (<UpdateOptions>) Object with write options, including optimisticLockControl, readAfter, calculated, dictionaries, conversion, formatSpec, originalRecords.
			$customVars - (string) Free text sent to the database allows management of additional behaviours in SUB.LK.MAIN.CONTROL.CUSTOM, which is called when this parameter is set.
		
		Returns:
			string
			
			A string ready to be used in <Linkar.LkExecuteDirectOperation> and <Linkar.LkExecutePersistentOperation>.
	*/
	static function GetUpdateArgs($filename, $records, $updateOptions, $customVars) {
		if (is_null($updateOptions))
			$updateOptions = new UpdateOptions();

		$options = $updateOptions->GetString();
		$inputData = $filename . DBMV_Mark::AM_str . $records;
	
		$cmdArgs = $customVars . ASCII_Chars::US_str . $options . ASCII_Chars::US_str . $inputData;
		return $cmdArgs;
	}

	/*
		Function: GetUpdatePartialArgs
			Compose the 3 items (CUSTOMVARS, OPTIONS and INPUTDATA) of the GetUpdateArgs operation.
		
		Arguments:
			$filename - (string) Name of the file being updated.
			$records - (string) Buffer of record data to update. Inside this string are the recordIds, the modified records, and the originalRecords. Use StringFunctions.ComposeUpdateBuffer (Linkar.Strings library) function to compose this string.
			$dictionaries - (string) List of dictionaries to write, separated by space. In MV output format is mandatory.
			$updateOptions - (<UpdateOptions>) Object with write options, including optimisticLockControl, readAfter, calculated, dictionaries, conversion, formatSpec, originalRecords.
			$customVars - (string) Free text sent to the database allows management of additional behaviours in SUB.LK.MAIN.CONTROL.CUSTOM, which is called when this parameter is set.
		
		Returns:
			string
			
			A string ready to be used in <Linkar.LkExecuteDirectOperation> and <Linkar.LkExecutePersistentOperation>.
	*/
	static function GetUpdatePartialArgs($filename, $records, $dictionaries, $updateOptions, $customVars) {
		if (is_null($updateOptions)) {
			$updateOptions = new UpdateOptions();
		}
	
		$options = $updateOptions->GetString();
		$inputData = $filename . DBMV_Mark::AM_str . $records . ASCII_Chars::FS_str . $dictionaries;
	
		$cmdArgs = $customVars . ASCII_Chars::US_str . $options . ASCII_Chars::US_str . $inputData;
		return $cmdArgs;
	}
	
    /*
        Function: GetNewArgs
            Compose the 3 items (CUSTOMVARS, OPTIONS and INPUTDATA) of the ResetCommonBlocks operation.
        
        Arguments:
            $filename - (string) The file name where the records are going to be created.
            $records - (string) Buffer of records to write. Inside this string are the recordIds, and the records. Use StringFunctions.ComposeNewBuffer (Linkar.Strings library) function to compose this string.
            $newOptions - (<NewOptions>) Object with write options for the new record(s), including recordIdType, readAfter, calculated, dictionaries, conversion, formatSpec, originalRecords.
            $customVars - (string) Free text sent to the database allows management of additional behaviours in SUB.LK.MAIN.CONTROL.CUSTOM, which is called when this parameter is set.
        
        Returns:
            string
            
            A string ready to be used in <Linkar.LkExecuteDirectOperation> and <Linkar.LkExecutePersistentOperation>.    
    */
    static function GetNewArgs($filename, $records, $newOptions, $customVars) {
        if (!$newOptions)
            $newOptions = new NewOptions();
    
        $options = $newOptions->GetString();
        $inputData = $filename . DBMV_Mark::AM_str . $records;
    
        $cmdArgs = $customVars . ASCII_Chars::US_str . $options . ASCII_Chars::US_str . $inputData;
        return $cmdArgs;
    }

	/*
		Function: GetDeleteArgs
			Compose the 3 items (CUSTOMVARS, OPTIONS and INPUTDATA) of the GetDeleteArgs operation.
		
		Arguments:
			$filename - (string) The file name where the records are going to be deleted. DICT in case of deleting a record that belongs to a dictionary.
			$records - (string) Buffer of records to be deleted. Use StringFunctions.ComposeDeleteBuffer (Linkar.Strings library) function to compose this string.
			$deleteOptions - (<DeleteOptions>) Object with options to manage how records are deleted, including optimisticLockControl, recoverRecordIdType.
			$customVars - (string) Free text sent to the database allows management of additional behaviours in SUB.LK.MAIN.CONTROL.CUSTOM, which is called when this parameter is set.
		
		Returns:
			string
			
			A string ready to be used in <Linkar.LkExecuteDirectOperation> and <Linkar.LkExecutePersistentOperation>.
	*/
	static function GetDeleteArgs($filename, $records, $deleteOptions, $customVars) {
		if (!$deleteOptions)
			$deleteOptions = new DeleteOptions();
	
		$options = $deleteOptions->GetString();
		$inputData = $filename . DBMV_Mark::AM_str . $records;
	
		$cmdArgs = $customVars . ASCII_Chars::US_str . $options . ASCII_Chars::US_str . $inputData;
		return $cmdArgs;        
	} 

	/*
		Function: GetSelectArgs
			Compose the 3 items (CUSTOMVARS, OPTIONS and INPUTDATA) of the GetSelectArgs operation.
		
		Arguments:
			$filename - (string) Name of file on which the operation is performed. For example LK.ORDERS
			$selectClause - (string) Statement fragment specifies the selection condition. For example WITH CUSTOMER = '1'
			$sortClause - (string) Statement fragment specifies the selection order. If there is a selection rule, Linkar will execute a SSELECT, otherwise Linkar will execute a SELECT. For example BY CUSTOMER
			$dictClause - (string) Space-delimited list of dictionaries to read. If this list is not set, all fields are returned. For example CUSTOMER DATE ITEM
			$preSelectClause - (string) An optional command that executes before the main Select
			$selectOptions - (<SelectOptions>) Object with options to manage how records are selected, including calculated, dictionaries, conversion, formatSpec, originalRecords, onlyItemId, pagination, regPage, numPage.
			$customVars - (string) Free text sent to the database allows management of additional behaviours in SUB.LK.MAIN.CONTROL.CUSTOM, which is called when this parameter is set.
		
		Returns:
			string
			
			A string ready to be used in <Linkar.LkExecuteDirectOperation> and <Linkar.LkExecutePersistentOperation>.
	*/
	static function GetSelectArgs($filename, $selectClause, $sortClause, $dictClause, $preSelectClause, $selectOptions, $customVars) {
		if (is_null($selectOptions))
			$selectOptions = new SelectOptions();
	
		$options = $selectOptions->GetString();
		$inputData = $filename . DBMV_Mark::AM_str .
			$selectClause . DBMV_Mark::AM_str .
			$sortClause . DBMV_Mark::AM_str .
			$dictClause . DBMV_Mark::AM_str .
			$preSelectClause;
	
		$cmdArgs = $customVars . ASCII_Chars::US_str . $options . ASCII_Chars::US_str . $inputData;
		return $cmdArgs;
	}

	/*
		Function: GetSubroutineArgs
			Compose the 3 items (CUSTOMVARS, OPTIONS and INPUTDATA) of the GetSubroutineArgs operation.
		
		Arguments:
			$subroutineName - (string) Name of BASIC subroutine to execute.
			$argsNumber - (number) Number of arguments
			$arguments - (string) The subroutine arguments list. Use StringFunctions.ComposeSubroutineArgs function to compose this string.
			$customVars - (string) Free text sent to the database allows management of additional behaviours in SUB.LK.MAIN.CONTROL.CUSTOM, which is called when this parameter is set.
		
		Returns:
			string
			
			A string ready to be used in <Linkar.LkExecuteDirectOperation> and <Linkar.LkExecutePersistentOperation>.
	*/
	static function GetSubroutineArgs($subroutineName, $argsNumber, $args, $customVars) {
		$options = "";
		$inputData1 = $subroutineName . DBMV_Mark::AM_str . $argsNumber;
		$inputData2 = $args;
		$inputData = $inputData1 . ASCII_Chars::FS_str . $inputData2;
	
		$cmdArgs = $customVars . ASCII_Chars::US_str . $options . ASCII_Chars::US_str . $inputData;
		return $cmdArgs;
	}

	/*
		Function: GetConversionArgs
			Compose the 3 items (CUSTOMVARS, OPTIONS and INPUTDATA) of the GetConversionArgs operation.
		
		Arguments:
			$expression - (string) The data or expression to convert. May include MV marks (value delimiters), in which case the conversion will execute in each value obeying the original MV mark.
			$code - (string) The conversion code. Must obey the Database conversions specifications.
			$conversionOptions - (string) Indicates the conversion type, input or output: INPUT=ICONV(); OUTPUT=OCONV()
			$customVars - (string) Free text sent to the database allows management of additional behaviours in SUB.LK.MAIN.CONTROL.CUSTOM, which is called when this parameter is set.
		
		Returns:
			string
			
			A string ready to be used in <Linkar.LkExecuteDirectOperation> and <Linkar.LkExecutePersistentOperation>.
	*/
	static function GetConversionArgs($expression, $code, $conversionType, $customVars) {
		$options = ($conversionType == CONVERSION_TYPE::INPUT ? "I" : "O");
		$inputData = $code . ASCII_Chars::FS_str . $expression;
	
		$cmdArgs = $customVars . ASCII_Chars::US_str . $options . ASCII_Chars::US_str . $inputData;
		return $cmdArgs;
	}

	/*
		Function: GetFormatArgs
			Compose the 3 items (CUSTOMVARS, OPTIONS and INPUTDATA) of the GetFormatArgs operation.
		
		Arguments:
			$expression - (string) The data or expression to format. If multiple values are present, the operation will be performed individually on all values in the expression.
			$formatSpec - (string) Specified format
			$customVars - (string) Free text sent to the database allows management of additional behaviours in SUB.LK.MAIN.CONTROL.CUSTOM, which is called when this parameter is set.
		
		Returns:
			string
			
			A string ready to be used in <Linkar.LkExecuteDirectOperation> and <Linkar.LkExecutePersistentOperation>.
	*/
	static function GetFormatArgs($expression, $formatSpec, $customVars) {
		$options = "";
		$inputData = $formatSpec . ASCII_Chars::FS_str . $expression;
	
		$cmdArgs = $customVars . ASCII_Chars::US_str . $options . ASCII_Chars::US_str . $inputData;
		return $cmdArgs;
	}

	/*
		Function: GetDictionariesArgs
			Compose the 3 items (CUSTOMVARS, OPTIONS and INPUTDATA) of the GetDictionariesArgs operation.
		
		Arguments:
			$filename - (string) File name
			$customVars - (string) Free text sent to the database allows management of additional behaviours in SUB.LK.MAIN.CONTROL.CUSTOM, which is called when this parameter is set.
		
		Returns:
			string
			
			A string ready to be used in <Linkar.LkExecuteDirectOperation> and <Linkar.LkExecutePersistentOperation>.
	*/
	static function GetDictionariesArgs($filename, $customVars) {
		$options = "";
	
		$cmdArgs = $customVars . ASCII_Chars::US_str . $options . ASCII_Chars::US_str . $filename;
		return $cmdArgs;
	}

	/*
		Function: GetExecuteArgs
			Compose the 3 items (CUSTOMVARS, OPTIONS and INPUTDATA) of the GetExecuteArgs operation.
		
		Arguments:
			$statement - (string) The command you want to execute in the Database.
			$customVars - (string) Free text sent to the database allows management of additional behaviours in SUB.LK.MAIN.CONTROL.CUSTOM, which is called when this parameter is set.
		
		Returns:
			string
			
			A string ready to be used in <Linkar.LkExecuteDirectOperation> and <Linkar.LkExecutePersistentOperation>.
	*/
	static function GetExecuteArgs($statement, $customVars) {
		$options = "";
	
		$cmdArgs = $customVars . ASCII_Chars::US_str . $options . ASCII_Chars::US_str . $statement;
		return $cmdArgs;
	}

	/*
		Function: GetSendCommandArgs
			Compose the 3 items (CUSTOMVARS, OPTIONS and INPUTDATA) of the GetSendCommandArgs operation.
		
		Arguments:
			$command - (string) Content of the operation you want to send.
		
		Returns:
			string
			
			A string ready to be used in <Linkar.LkExecuteDirectOperation> and <Linkar.LkExecutePersistentOperation>.
	*/
	static function GetSendCommandArgs($command) {
		$options = "";
	
		$customVars = "";
		$cmdArgs = $customVars . ASCII_Chars::US_str . $options  . ASCII_Chars::US_str . $command;
		return $cmdArgs;
	}

	/*
		Function: GetVersionArgs
			Compose the 3 items (CUSTOMVARS, OPTIONS and INPUTDATA) of the Version operation.
				
		Returns:
			string
			
			A string ready to be used in <Linkar.LkExecuteDirectOperation> and <Linkar.LkExecutePersistentOperation>.
	*/
	static function GetVersionArgs() {
		$options = "";

		$cmdArgs = "" . ASCII_Chars::US_str . $options . ASCII_Chars::US_str . "";
		return $cmdArgs;
	}

	/*
		Function: GetLkSchemasArgs
			Compose the 3 items (CUSTOMVARS, OPTIONS and INPUTDATA) of the GetLkSchemasArgs operation.
		
		Arguments:
			$lkSchemasOptions - (<LkSchemasOptions>) This object defines the different options in base of the asked Schema Type: LKSCHEMAS, SQLMODE o DICTIONARIES.
			$customVars - (string) Free text sent to the database allows management of additional behaviours in SUB.LK.MAIN.CONTROL.CUSTOM, which is called when this parameter is set.
		
		Returns:
			string
			
			A string ready to be used in <Linkar.LkExecuteDirectOperation> and <Linkar.LkExecutePersistentOperation>.
	*/
	static function GetLkSchemasArgs($lkSchemasOptions, $customVars) {
		$options = $lkSchemasOptions->GetString();

		$cmdArgs = $customVars . ASCII_Chars::US_str . $options . ASCII_Chars::US_str . "";
		return $cmdArgs;
	}

	/*
		Function: GetLkPropertiesArgs
			Compose the 3 items (CUSTOMVARS, OPTIONS and INPUTDATA) of the GetLkPropertiesArgs operation.
		
		Arguments:
			$filename - (string) File name to LkProperties
			$lkPropertiesOptions - (<LkPropertiesOptions>) This object defines the different options in base of the asked Schema Type: LKSCHEMAS, SQLMODE o DICTIONARIES.
			$customVars - (string) Free text sent to the database allows management of additional behaviours in SUB.LK.MAIN.CONTROL.CUSTOM, which is called when this parameter is set.
		
		Returns:
			string
			
			A string ready to be used in <Linkar.LkExecuteDirectOperation> and <Linkar.LkExecutePersistentOperation>.
	*/
	static function GetLkPropertiesArgs($filename, $lkPropertiesOptions, $customVars) {
		$options = $lkPropertiesOptions->GetString();

		$cmdArgs = $customVars . ASCII_Chars::US_str . $options . ASCII_Chars::US_str . $filename;
		return $cmdArgs;
	}

	/*
		Function: GetGetTableArgs
			Compose the 3 items (CUSTOMVARS, OPTIONS and INPUTDATA) of the GetGetTableArgs operation.
		
		Arguments:
			$filename - (string) File or table name defined in Linkar Schemas. Table notation is: MainTable[.MVTable[.SVTable]]
			$selectClause - (string) Statement fragment specifies the selection condition. For example WITH CUSTOMER = '1'
			$dictClause - (string) Space-delimited list of dictionaries to read. If this list is not set, all fields are returned. For example CUSTOMER DATE ITEM
			$sortClause - (string) Statement fragment specifies the selection order. If there is a selection rule Linkar will execute a SSELECT, otherwise Linkar will execute a SELECT. For example BY CUSTOMER
			$tableOptions - (<TableOptions>) Detailed options to be used, including: rowHeaders, rowProperties, onlyVisibe, usePropertyNames, repeatValues, applyConversion, applyFormat, calculated, pagination, regPage, numPage.
			$customVars - (string) Free text sent to the database allows management of additional behaviours in SUB.LK.MAIN.CONTROL.CUSTOM, which is called when this parameter is set.
		
		Returns:
			string
			
			A string ready to be used in <Linkar.LkExecuteDirectOperation> and <Linkar.LkExecutePersistentOperation>.
	*/
	static function GetGetTableArgs($filename, $selectClause, $dictClause, $sortClause, $tableOptions, $customVars) {
		$options = $tableOptions->GetString();
		$inputData = $filename . DBMV_Mark::AM_str .
			$selectClause . DBMV_Mark::AM_str .
			$dictClause . DBMV_Mark::AM_str .
			$sortClause;

		$cmdArgs = $customVars . ASCII_Chars::US_str . $options . ASCII_Chars::US_str . $inputData;
		return $cmdArgs;
	}

	/*
		Function: GetResetCommonBlocksArgs
			Compose the 3 items (CUSTOMVARS, OPTIONS and INPUTDATA) of the ResetCommonBlocks operation.
		
		Returns:
			string
			
			A string ready to be used in <Linkar.LkExecuteDirectOperation> and <Linkar.LkExecutePersistentOperation>.
	*/
	static function GetResetCommonBlocksArgs() {
		$options = "";

		$cmdArgs = "" . ASCII_Chars::US_str . $options . ASCII_Chars::US_str . "";
		return $cmdArgs;
	}

}
?>
