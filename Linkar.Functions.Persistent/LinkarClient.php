<?php

include_once realpath( __DIR__ . '/../Linkar/Linkar.php');
include_once realpath( __DIR__ . '/../Linkar.Functions/LinkarFunctions.php');  

/*
	Class: LinkarClient
		These functions perform synchronous persistent (establishing permanent session) operations with any kind of output format type.
*/
class LinkarClient {

    private $ConnectionInfo;
    private $Linkar;
    private $ReceiveTimeout;
    public function GetSessionId() {
        return $this->ConnectionInfo->SessionId;
    }

	/*
		Constructor: __constructor
			Initializes a new instance of the LinkarClient class.
			
		Arguments:
			$receiveTimeout - (number) Maximum time in seconds that the client will wait for a response from the server. Default = 0 to wait indefinitely. When the receiveTimeout argument is omitted in any operation, the value set here will be applied.
	*/
    public function __construct($receiveTimeout = 0) {
        $this->Linkar = new Linkar();
        if($receiveTimeout < 0)
            $this->ReceiveTimeout = 0;
        else
            $this->ReceiveTimeout = $receiveTimeout;
    }    
    
	/*
		Function: Login
			Starts the communication with a server allowing making use of the rest of functions until the Logout method is executed or the connection with the server gets lost.
		
		Arguments:
			$credentialOptions - (<CredentialOptions>) Object with data necessary to access the Linkar Server: Username, Password, EntryPoint, Language, FreeText.
			$customVars - (string) Free text sent to the database allows management of additional behaviours in SUB.LK.MAIN.CONTROL.CUSTOM, which is called when this parameter is set.
			$receiveTimeout - (number) Maximum time in seconds that the client will wait for a response from the server. Default = 0 to wait indefinitely.
		
		Remarks:
			Login is actually a "virtual" operation which creates a new Client Session ID. No DBMS login is performed unless Linkar SERVER determines new Database Sessions are required - these operations are not related.
 	*/
    public function Login($credentialOptions, $customVars = "", $receiveTimeout = 0) {
        $options = "";
        $loginArgs = ($customVars?$customVars:"") . ASCII_Chars::US_str . $options;
        $byteOpCode = OPERATION_CODE::LOGIN;
        $byteInputFormat = DATAFORMAT_TYPE::MV;
        $byteOutputFormat = DATAFORMAT_TYPE::MV;
        if($receiveTimeout <= 0) {
            if($this->ReceiveTimeout > 0)
                $receiveTimeout = $this->ReceiveTimeout;
            else
                $receiveTimeout = 0;
        }
        $this->ConnectionInfo = new ConnectionInfo("", "", "", $credentialOptions);

        $loginResult = $this->Linkar->LkExecutePersistentOperation($this->ConnectionInfo, $byteOpCode, $loginArgs, $byteInputFormat, $byteOutputFormat, $receiveTimeout);

        return $loginResult;
    }  

	/*
		Function: Logout
			Closes the communication with the server, that previously has been opened with a Login function.
		
		Arguments:
			$customVars - (string) Free text sent to the database allows management of additional behaviours in SUB.LK.MAIN.CONTROL.CUSTOM, which is called when this parameter is set.</param>
			$receiveTimeout - (number) Maximum time in seconds that the client will wait for a response from the server. Default = 0 to wait indefinitely.</param>

		Remarks:
			Logout is actually a "virtual" operation which disposes the current Client Session ID. No DBMS logout is performed.
	*/    public function Logout($customVars = "", $receiveTimeout = 0) {
        $logoutArgs = ($customVars?$customVars:"");
        $byteOpCode = OPERATION_CODE::LOGOUT;
        $byteInputFormat = DATAFORMAT_TYPE::MV;
        $byteOutputFormat = DATAFORMAT_TYPE::MV;
        if($receiveTimeout <= 0) {
            if($this->ReceiveTimeout > 0)
                $receiveTimeout = $this->ReceiveTimeout;
            else
                $receiveTimeout = 0;
        }

        $logoutResult = $this->Linkar->LkExecutePersistentOperation($this->ConnectionInfo, $byteOpCode, $logoutArgs, $byteInputFormat, $byteOutputFormat, $receiveTimeout);
        
        return $logoutResult;
    }

 	/*
		Function: Read
			Reads one or several records of a file.
			
		Arguments:
			$filename - (string) File name to read.
			$recordIds - (string) A list of item IDs to read.
			$dictionaries - (string) List of dictionaries to read, separated by space. If this list is not set, all fields are returned. You may use the format LKFLDx where x is the attribute number.
			$readOptions - (<ReadOptions>) Object that defines the different reading options of the Function: Calculated, dictClause, conversion, formatSpec, originalRecords.
			$inputFormat - (<DATAFORMAT_TYPE>) Indicates in what format you wish to send the record ids: MV, XML or JSON.
			$outputFormat - (<DATAFORMATCRU_TYPE>) Indicates in what format you want to receive the data resulting from the Read, New, Update and Select operations: MV, XML, XML_DICT, XML_SCH, JSON, JSON_DICT or JSON_SCH.
			$customVars - (string) Free text sent to the database allows management of additional behaviours in SUB.LK.MAIN.CONTROL.CUSTOM, which is called when this parameter is set.
			$receiveTimeout - (number) Maximum time in seconds that the client will wait for a response from the server. Default = 0 to wait indefinitely.

		Returns:
			string
		
			The results of the operation.
	*/
    public function Read($filename, $recordIds, $dictionaries = "", $readOptions = null, $inputFormat = DATAFORMAT_TYPE::MV, $outputFormat = DATAFORMATCRU_TYPE::MV,$customVars = "", $receiveTimeout = 0) {
        if(is_null($readOptions)) {
            $readOptions=new ReadOptions();
        }
        $opArgs = OperationArguments::GetReadArgs($filename, $recordIds, $dictionaries, $readOptions, $customVars);       
        $opCode = OPERATION_CODE::READ;

        $result = $this->Linkar->LkExecutePersistentOperation($this->ConnectionInfo, $opCode, $opArgs, $inputFormat, $outputFormat, $receiveTimeout);
        return $result;
    }

	/*
		Function: Update
			Update one or several records of a file.
			
		Arguments:
			$filename - (string) Name of the file being updated.
			$records - (string) Buffer of record data to update. Inside this string are the recordIds, the modified records, and the originalRecords.
			$updateOptions - (<UpdateOptions>) Object with write options, including optimisticLockControl, readAfter, calculated, dictionaries, conversion, formatSpec, originalRecords.
			$inputFormat - (<DATAFORMAT_TYPE>) Indicates in what format you wish to send the resultant writing data: MV, XML or JSON.
			$outputFormat - (<DATAFORMATCRU_TYPE>) Indicates in what format you want to receive the data resulting from the Read, New, Update and Select operations: MV, XML, XML_DICT, XML_SCH, JSON, JSON_DICT or JSON_SCH.
			$customVars - (string) Free text sent to the database allows management of additional behaviours in SUB.LK.MAIN.CONTROL.CUSTOM, which is called when this parameter is set.
			$receiveTimeout - (number) Maximum time in seconds that the client will wait for a response from the server. Default = 0 to wait indefinitely.

		Returns:
			string
		
			The results of the operation.
			
		Remarks:
		Inside the records argument, the recordIds and the modified records always must be specified. But the originalRecords not always.
		When <UpdateOptions> argument is specified and the <UpdateOptions.OptimisticLockControl> property is set to true, a copy of the record must be provided before the modification (originalRecords argument)
		to use the Optimistic Lock technique. This copy can be obtained from a previous <Read> operation. The database, before executing the modification, 
		reads the record and compares it with the copy in originalRecords, if they are equal the modified record is executed.
		But if they are not equal, it means that the record has been modified by other user and its modification will not be saved.
		The record will have to be read, modified and saved again.
	*/
	public function Update($filename, $records, $updateOptions = null,
		$inputFormat = DATAFORMAT_TYPE::MV, $outputFormat = DATAFORMATCRU_TYPE::MV, $customVars = "", $receiveTimeout = 0) {
        if(is_null($updateOptions)) {
            $opdateOption = new UpdateOptions();
        }
		$opArgs = OperationArguments::GetUpdateArgs($filename, $records, $updateOptions, $customVars);
		$opCode = OPERATION_CODE::UPDATE;
	
		$result = $this->Linkar->LkExecutePersistentOperation($this->ConnectionInfo, $opCode, $opArgs, $inputFormat, $outputFormat, $receiveTimeout);
		return $result;
	}

	/*
		Function: UpdatePartial
			Update one or more attributes of one or more file records.
			
		Arguments:
			$filename - (string) Name of the file being updated.
			$records - (string) Buffer of record data to update. Inside this string are the recordIds, the modified records, and the originalRecords.
			$dictionaries - (string) List of dictionaries to write, separated by space. In MV output format is mandatory. You may use the format LKFLDx where x is the attribute number.
			$updateOptions - (<UpdateOptions>) Object with write options, including optimisticLockControl, readAfter, calculated, dictionaries, conversion, formatSpec, originalRecords.
			$inputFormat - (<DATAFORMAT_TYPE>) Indicates in what format you wish to send the resultant writing data: MV, XML or JSON.
			$outputFormat - (<DATAFORMATCRU_TYPE>) Indicates in what format you want to receive the data resulting from the Read, New, Update and Select operations: MV, XML, XML_DICT, XML_SCH, JSON, JSON_DICT or JSON_SCH.
			$customVars - (string) Free text sent to the database allows management of additional behaviours in SUB.LK.MAIN.CONTROL.CUSTOM, which is called when this parameter is set.
			$receiveTimeout - (number) Maximum time in seconds that the client will wait for a response from the server. Default = 0 to wait indefinitely.

		Returns:
			string
		
			The results of the operation.
			
		Remarks:
		Inside the records argument, the recordIds and the modified records always must be specified. But the originalRecords not always.
		When <UpdateOptions> argument is specified and the <UpdateOptions.OptimisticLockControl> property is set to true, a copy of the record must be provided before the modification (originalRecords argument)
		to use the Optimistic Lock technique. This copy can be obtained from a previous <Read> operation. The database, before executing the modification, 
		reads the record and compares it with the copy in originalRecords, if they are equal the modified record is executed.
		But if they are not equal, it means that the record has been modified by other user and its modification will not be saved.
		The record will have to be read, modified and saved again.
	*/
	public function UpdatePartial($filename, $records, $dictionaries, $updateOptions = null,
		$inputFormat = DATAFORMAT_TYPE::MV, $outputFormat = DATAFORMATCRU_TYPE::MV, $customVars = "", $receiveTimeout = 0) {
		$opArgs = OperationArguments::GetUpdatePartialArgs($filename, $records, $dictionaries, $updateOptions, $customVars);       
		$opCode = OPERATION_CODE::UPDATEPARTIAL;
	
		$result = $this->Linkar->LkExecutePersistentOperation($this->ConnectionInfo, $opCode, $opArgs, $inputFormat, $outputFormat, $receiveTimeout);
		return $result;
	}	
    /*
        Function: New
            Creates one or several records of a file.
        
        Arguments:
            $filename - (string) The file name where the records are going to be created.
            $records - (string) Buffer of records to write. Inside this string are the recordIds, and the records.
            $newOptions - (<NewOptions>) Object with write options for the new record(s), including recordIdType, readAfter, calculated, dictionaries, conversion, formatSpec, originalRecords.
            $inputFormat - (<DATAFORMAT_TYPE>) Indicates in what format you wish to send the resultant writing data: MV, XML or JSON.
            $outputFormat - (<DATAFORMATCRU_TYPE>) Indicates in what format you want to receive the data resulting from the Read, New, Update and Select operations: MV, XML, XML_DICT, XML_SCH, JSON, JSON_DICT or JSON_SCH.
            $customVars - (string) Free text sent to the database allows management of additional behaviours in SUB.LK.MAIN.CONTROL.CUSTOM, which is called when this parameter is set.
            $receiveTimeout - (number) Maximum time in seconds that the client will wait for a response from the server. Default = 0 to wait indefinitely.
        
        Returns:
            string
            
            The results of the operation.

        Remarks:
            Inside the records argument, the records always must be specified.
            But the recordIds only must be specified when <NewOptions> argument is NULL, or when the <RecordIdType> argument of the <NewOptions> constructor is NULL.
    */
    public function New($filename, $records, $newOptions = null,
                        $inputFormat = DATAFORMAT_TYPE::MV, $outputFormat = DATAFORMATCRU_TYPE::MV, $customVars = "", $receiveTimeout = 0) {
        if (is_null($newOptions)){
            $newOptions = new NewOptions();
        }
        $opArgs = OperationArguments::GetNewArgs($filename, $records, $newOptions, $customVars);       
        $opCode = OPERATION_CODE::NEW;
    
        $result = $this->Linkar->LkExecutePersistentOperation($this->ConnectionInfo, $opCode, $opArgs, $inputFormat, $outputFormat, $receiveTimeout);
        return $result;
    }

    /*
		Function: Delete
			Deletes one or several records in file.
		
		Arguments:
			$filename - (string) The file name where the records are going to be created.
			$records - (string) Buffer of records to be deleted.
			$deleteOptions - (<DeleteOptions>) Object with options to manage how records are deleted, including optimisticLockControl, recoverRecordIdType.
			$inputFormat - (<DATAFORMAT_TYPE>) Indicates in what format you wish to send the resultant writing data: MV, XML or JSON.
			$outputFormat - (<DATAFORMAT_TYPE>) Indicates in what format you want to receive the data resulting from the operation: MV, XML or JSON.
			$customVars - (string) Free text sent to the database allows management of additional behaviours in SUB.LK.MAIN.CONTROL.CUSTOM, which is called when this parameter is set.
			$receiveTimeout - (number) Maximum time in seconds that the client will wait for a response from the server. Default = 0 to wait indefinitely.

		Returns:
			string
			
			The results of the operation.
			
		Remarks:
			Inside the records argument, the recordIds always must be specified. But the originalRecords not always.
			When <deleteOptions> argument is specified and the <DeleteOptions.OptimisticLockControl> property is set to true,
			a copy of the record must be provided before the deletion to use the Optimistic Lock technique.
			This copy can be obtained from a previous <Read> operation. The database, before executing the deletion, 
			reads the record and compares it with the copy in originalRecords, if they are equal the record is deleted.
			But if they are not equal, it means that the record has been modified by other user and the record will not be deleted.
			The record will have to be read, and deleted again.
	*/
	public function Delete($filename, $records, $deleteOptions = null,
                           $inputFormat = DATAFORMAT_TYPE::MV, $outputFormat = DATAFORMAT_TYPE::MV, $customVars = "", $receiveTimeout = 0) {
        if(is_null($deleteOptions)) {
            $deleteOptions = new DeleteOptions();
        }
		$opArgs = OperationArguments::GetDeleteArgs($filename, $records, $deleteOptions, $customVars);       
		$opCode = OPERATION_CODE::DELETE;
	
		$result = $this->Linkar->LkExecutePersistentOperation($this->ConnectionInfo, $opCode, $opArgs, $inputFormat, $outputFormat, $receiveTimeout);
		return $result;
	}

	/*
		Function: Select
			Executes a Query in the Database.
		
		Arguments:
			$filename - (string) Name of file on which the operation is performed. For example LK.ORDERS
			$selectClause - (string) Statement fragment specifies the selection condition. For example WITH CUSTOMER = '1'
			$sortClause - (string) Statement fragment specifies the selection order. If there is a selection rule, Linkar will execute a SSELECT, otherwise Linkar will execute a SELECT. For example BY CUSTOMER
			$dictClause - (string) Space-delimited list of dictionaries to read. If this list is not set, all fields are returned. For example CUSTOMER DATE ITEM
			$preSelectClause - (string) An optional command that executes before the main Select
			$selectOptions - (<SelectOptions>) Object with options to manage how records are selected, including calculated, dictionaries, conversion, formatSpec, originalRecords, onlyItemId, pagination, regPage, numPage.
			$outputFormat - (<DATAFORMATCRU_TYPE>) Indicates in what format you want to receive the data resulting from the Read, New, Update and Select operations: MV, XML, XML_DICT, XML_SCH, JSON, JSON_DICT or JSON_SCH.
			$customVars - (string) Free text sent to the database allows management of additional behaviours in SUB.LK.MAIN.CONTROL.CUSTOM, which is called when this parameter is set.
			$receiveTimeout - (number) Maximum time in seconds that the client will wait for a response from the server. Default = 0 to wait indefinitely.
		
		Returns:
			string
			
			The results of the operation.
			
		Remarks:
			In the preSelectClause argument these operations can be carried out before executing the Select statement:
			
				- Previously call to a saved list with the GET.LIST command to use it in the Main Select input
				- Make a previous Select to use the result as the Main Select input, with the SELECT or SSELECT commands.In this case the entire sentence must be indicated in the PreselectClause. For example:SSELECT LK.ORDERS WITH CUSTOMER = '1'
				- Exploit a Main File index to use the result as a Main Select input, with the SELECTINDEX command. The syntax for all the databases is SELECTINDEX index.name.value. For example SELECTINDEX ITEM,"101691"
	*/
	public function Select($filename, $selectClause, $sortClause, $dictClause, $preSelectClause, $selectOptions = null,
		$outputFormat = DATAFORMATCRU_TYPE::MV, $customVars = "", $receiveTimeout = 0) {
		if(is_null($selectOptions)) {
            $selectoptions = new SelectOptions();
        }
        $opArgs = OperationArguments::GetSelectArgs($filename, $selectClause, $sortClause, $dictClause, $preSelectClause, $selectOptions, $customVars);       
		$opCode = OPERATION_CODE::SELECT;
		$inputFormat = DATAFORMAT_TYPE::MV;
	
		$result = $this->Linkar->LkExecutePersistentOperation($this->ConnectionInfo, $opCode, $opArgs, $inputFormat, $outputFormat, $receiveTimeout);
		return $result;
	}

	/*
		Function: Subroutine
			Executes a subroutine.
		
		Arguments:
			$subroutineName - (string) Name of BASIC subroutine to execute.
			$argsNumber - (number) Number of arguments.
			$arguments - (string) The subroutine arguments list. Each argument is a substring separated with the ASCII char DC4 (20).
			$inputFormat - (<DATAFORMAT_TYPE>) Indicates in what format you wish to send the subroutine arguments: MV, XML or JSON.
			$outputFormat - (<DATAFORMAT_TYPE>) Indicates in what format you want to receive the data resulting from the operation: MV, XML or JSON.
			$customVars - (string) Free text sent to the database allows management of additional behaviours in SUB.LK.MAIN.CONTROL.CUSTOM, which is called when this parameter is set.
			$receiveTimeout - (number) Maximum time in seconds that the client will wait for a response from the server. Default = 0 to wait indefinitely.
		
		Returns:
			string
			
			The results of the operation.
	*/
	public function Subroutine($subroutineName, $argsNumber, $args,
		$inputFormat = DATAFORMAT_TYPE::MV, $outputFormat = DATAFORMAT_TYPE::MV, $customVars = "", $receiveTimeout = 0) {
		$opArgs = OperationArguments::GetSubroutineArgs($subroutineName, $argsNumber, $args, $customVars);     
		$opCode = OPERATION_CODE::SUBROUTINE;
	
		$result = $this->Linkar->LkExecutePersistentOperation($this->ConnectionInfo, $opCode, $opArgs, $inputFormat, $outputFormat, $receiveTimeout);
		return $result;
	}
	
	/*
		Function: Conversion
			Returns the result of executing ICONV() or OCONV() functions from a expression list in the Database.
		
		Arguments:
			$conversionType - (<CONVERSION_TYPE>) Indicates the conversion type, input or output: INPUT=ICONV(); OUTPUT=OCONV()
			$expression - (string) The data or expression to convert. May include MV marks (value delimiters), in which case the conversion will execute in each value obeying the original MV mark.
			$code - (string) The conversion code. Must obey the Database conversions specifications.
			$outputFormat - (<DATAFORMAT_TYPE>) Indicates in what format you want to receive the data resulting from the operation: MV, XML or JSON.
			$customVars - (string) Free text sent to the database allows management of additional behaviours in SUB.LK.MAIN.CONTROL.CUSTOM, which is called when this parameter is set.
			$receiveTimeout - (number) Maximum time in seconds that the client will wait for a response from the server. Default = 0 to wait indefinitely.
		
		Returns:
			string
			
			The results of the operation.
	*/
	public function Conversion($expression, $code, $conversionType,
		$outputFormat = DATAFORMAT_TYPE::MV, $customVars = "", $receiveTimeout = 0) {
		$opArgs = OperationArguments::GetConversionArgs($expression, $code, $conversionType, $customVars);
		$opCode = OPERATION_CODE::CONVERSION;
		$inputFormat = DATAFORMAT_TYPE::MV;
	
		$result = $this->Linkar->LkExecutePersistentOperation($this->ConnectionInfo, $opCode, $opArgs, $inputFormat, $outputFormat, $receiveTimeout);
		return $result;
	}
	
	/*
		Function: Format
			Returns the result of executing the FMT function in a expressions list in the Database.
		
		Arguments:
			$expression - (string) The data or expression to format. If multiple values are present, the operation will be performed individually on all values in the expression.
			$formatSpec - (string) Specified format.
			$outputFormat - (<DATAFORMAT_TYPE>) Indicates in what format you want to receive the data resulting from the operation: MV, XML or JSON.
			$customVars - (string) Free text sent to the database allows management of additional behaviours in SUB.LK.MAIN.CONTROL.CUSTOM, which is called when this parameter is set.
			$receiveTimeout - (number) Maximum time in seconds that the client will wait for a response from the server. Default = 0 to wait indefinitely.
		
		Returns:
			string
			
			The results of the operation.
	*/
	public function Format($expression, $formatSpec,
		$outputFormat = DATAFORMAT_TYPE::MV, $customVars = "", $receiveTimeout = 0) {
		$opArgs = OperationArguments::GetFormatArgs($expression, $formatSpec, $customVars);      
		$opCode = OPERATION_CODE::FORMAT;
		$inputFormat = DATAFORMAT_TYPE::MV;
	
		$result = $this->Linkar->LkExecutePersistentOperation($this->ConnectionInfo, $opCode, $opArgs, $inputFormat, $outputFormat, $receiveTimeout);
		return $result;
	}
	
	/*
		Function: Dictionaries
			Returns all the dictionaries of a file.
		
		Arguments:
			$filename - (string) File name.
			$outputFormat - (<DATAFORMAT_TYPE>) Indicates in what format you want to receive the data resulting from the operation: MV, XML or JSON.
			$customVars - (string) Free text sent to the database allows management of additional behaviours in SUB.LK.MAIN.CONTROL.CUSTOM, which is called when this parameter is set.
			$receiveTimeout - (number) Maximum time in seconds that the client will wait for a response from the server. Default = 0 to wait indefinitely.
		
		Returns:
			string
			
			The results of the operation.
	*/
	public function Dictionaries($filename,
		$outputFormat = DATAFORMAT_TYPE::MV, $customVars = "", $receiveTimeout = 0) {
		$opArgs = OperationArguments::GetDictionariesArgs($filename, $customVars);      
		$opCode = OPERATION_CODE::DICTIONARIES;
		$inputFormat = DATAFORMAT_TYPE::MV;
	
		$result = $this->Linkar->LkExecutePersistentOperation($this->ConnectionInfo, $opCode, $opArgs, $inputFormat, $outputFormat, $receiveTimeout);
		return $result;
	}
	
	/*
		Function: Execute
			Allows the execution of any command from the Database.
		
		Arguments:
			$statement - (string) The command you want to execute in the Database.
			$outputFormat - (<DATAFORMAT_TYPE>) Indicates in what format you want to receive the data resulting from the operation: MV, XML or JSON.
			$customVars - (string) Free text sent to the database allows management of additional behaviours in SUB.LK.MAIN.CONTROL.CUSTOM, which is called when this parameter is set.
			$receiveTimeout - (number) Maximum time in seconds that the client will wait for a response from the server. Default = 0 to wait indefinitely.
		
		Returns:
			string
			
			The results of the operation.
	*/
	public function Execute($statement,
		$outputFormat = DATAFORMAT_TYPE::MV, $customVars = "", $receiveTimeout = 0) {
		$opArgs = OperationArguments::GetExecuteArgs($statement, $customVars);
		$opCode = OPERATION_CODE::EXECUTE;
		$inputFormat = DATAFORMAT_TYPE::MV;
	
		$result = $this->Linkar->LkExecutePersistentOperation($this->ConnectionInfo, $opCode, $opArgs, $inputFormat, $outputFormat, $receiveTimeout);
		return $result;
	}
	
	/*
		Function: GetVersion
			Allows getting the server version.
		
		Arguments:
			$outputFormat - (<DATAFORMAT_TYPE>) Indicates in what format you want to receive the data resulting from the operation: MV, XML or JSON.
			$receiveTimeout - (number) Maximum time in seconds that the client will wait for a response from the server. Default = 0 to wait indefinitely.
		
		Returns:
			string
			
			The results of the operation.
		
		Remarks:
			This function returns the following information:
			
				LKMVCOMPONENTSVERSION - MV Components version.
				LKSERVERVERSION - Linkar SERVER version.
				LKCLIENTVERSION - Used client library version.
				DATABASE - Database.
				OS - Operating system.
				DATEZERO - Date zero base in YYYYMMDD format.
				DATEOUTPUTCONVERSION - Output conversion for date used by Linkar Schemas.
				TIMEOUTPUTCONVERSION - Output conversion for time used by Linkar Schemas.
				MVDATETIMESEPARATOR - DateTime used separator used by Linkar Schemas, for instance 18325,23000.
				MVBOOLTRUE - Database used char for the Boolean true value used by Linkar Schemas.
				MVBOOLFALSE - Database used char for the Boolean false value used by Linkar Schemas.
				OUTPUTBOOLTRUE - Used char for the Boolean true value out of the database used by Linkar Schemas.
				OUTPUTBOOLFALSE - Used char for the Boolean false value out of the database used by Linkar Schemas.
				MVDECIMALSEPARATOR - Decimal separator in the database. May be point, comma or none when the database does not store decimal numbers. Used by Linkar Schemas.
				OTHERLANGUAGES - Languages list separated by commas.
				TABLEROWSEPARATOR - It is the decimal char that you use to separate the rows in the output table format. By default 11.
				TABLECOLSEPARATOR - It is the decimal char that you use to separate the columns in the output table format. By default 9.
				CONVERTNUMBOOLJSON - Switch to create numeric and boolean data in JSON strings. Default is false.
	*/
	public function GetVersion($outputFormat = DATAFORMAT_TYPE::MV, $receiveTimeout = 0) {
		$opArgs = OperationArguments::GetVersionArgs();        
		$opCode = OPERATION_CODE::VERSION;
		$inputFormat = DATAFORMAT_TYPE::MV;
	
		$result = $this->Linkar->LkExecutePersistentOperation($this->ConnectionInfo, $opCode, $opArgs, $inputFormat, $outputFormat, $receiveTimeout);
		return $result;
	}
	
	/*
		Function: LkSchemas
			Returns a list of all the Schemas defined in Linkar Schemas, or the EntryPoint account data files.
		
		Arguments:
			$lkSchemasOptions - (<LkSchemasOptions>) This object defines the different options in base of the asked Schema Type: LKSCHEMAS, SQLMODE o DICTIONARIES.
			$outputFormat - (<DATAFORMATSCH_TYPE>) Indicates in what format you want to receive the data resulting from the operation: MV, XML, JSON or TABLE.
			$customVars - (string) Free text sent to the database allows management of additional behaviours in SUB.LK.MAIN.CONTROL.CUSTOM, which is called when this parameter is set.
			$receiveTimeout - (number) Maximum time in seconds that the client will wait for a response from the server. Default = 0 to wait indefinitely.
		
		Returns:
			string
			
			The results of the operation.
		
		Remarks:
			TABLE output format uses the defined control characters in <EntryPoints Parameters: http://kosday.com/Manuals/en_web_linkar/lk_schemas_ep_parameters.html> Table Row Separator and Column Row Separator.
			
			By default:
			
				TAB - char (9) for columns.
				VT - char (11) for rows.
	*/
	public function LkSchemas($lkSchemasOptions = null,
		$outputFormat = DATAFORMATSCH_TYPE::MV, $customVars = "", $receiveTimeout = 0) {
        if(is_null($lkSchemasOptions)) {
            $lkSchemasOptions = new LkSchemasOptions();
        }
		$opArgs = OperationArguments::GetLkSchemasArgs($lkSchemasOptions, $customVars);
		$opCode = OPERATION_CODE::LKSCHEMAS;
		$inputFormat = DATAFORMAT_TYPE::MV;
	
		$result = $this->Linkar->LkExecutePersistentOperation($this->ConnectionInfo, $opCode, $opArgs, $inputFormat, $outputFormat, $receiveTimeout);
		return $result;
	}
	
	/*
		Function: LkProperties
			Returns the Schema properties list defined in Linkar Schemas or the file dictionaries.
		
		Arguments:
			$filename - (string) File name to LkProperties.
			$lkPropertiesOptions - (<LkPropertiesOptions>) This object defines the different options in base of the asked Schema Type: LKSCHEMAS, SQLMODE o DICTIONARIES.
			$outputFormat - (<DATAFORMATSCHPROP_TYPE>) Indicates in what format you want to receive the data resulting from the operation: MV, XML, JSON or TABLE.
			$customVars - (string) Free text sent to the database allows management of additional behaviours in SUB.LK.MAIN.CONTROL.CUSTOM, which is called when this parameter is set.
			$receiveTimeout - (number) Maximum time in seconds that the client will wait for a response from the server. Default = 0 to wait indefinitely.
		
		Returns:
			string
			
			The results of the operation.
		
		Remarks:
			TABLE output format uses the defined control characters in <EntryPoints Parameters: http://kosday.com/Manuals/en_web_linkar/lk_schemas_ep_parameters.html> Table Row Separator and Column Row Separator.
			
			By default:
			
				TAB - char (9) for columns.
				VT - char (11) for rows.
	*/
	public function LkProperties($filename, $lkPropertiesOptions = null,
		$outputFormat = DATAFORMATSCHPROP_TYPE::MV, $customVars = "", $receiveTimeout = 0) {
        if(is_null($lkPropertiesOptions)) {
            $lkPropertiesOptions = new LkPropertiesOptions();
        }
		$opArgs = OperationArguments::GetLkPropertiesArgs($filename, $lkPropertiesOptions, $customVars);  
		$opCode = OPERATION_CODE::LKPROPERTIES;
		$inputFormat = DATAFORMAT_TYPE::MV;
	
		$result = $this->Linkar->LkExecutePersistentOperation($this->ConnectionInfo, $opCode, $opArgs, $inputFormat, $outputFormat, $receiveTimeout);
		return $result;
	}
	
	/*
		Function: GetTable
			Returns a query result in a table format.
		
		Arguments:
			$filename - (string) File or table name defined in Linkar Schemas. Table notation is: MainTable[.MVTable[.SVTable]]
			$selectClause - (string) Statement fragment specifies the selection condition. For example WITH CUSTOMER = '1'
			$dictClause - (string) Space-delimited list of dictionaries to read. If this list is not set, all fields are returned. For example CUSTOMER DATE ITEM
			$sortClause - (string) Statement fragment specifies the selection order. If there is a selection rule Linkar will execute a SSELECT, otherwise Linkar will execute a SELECT. For example BY CUSTOMER
			$tableOptions - (<TableOptions>) Detailed options to be used, including: rowHeaders, rowProperties, onlyVisibe, usePropertyNames, repeatValues, applyConversion, applyFormat, calculated, pagination, regPage, numPage.
			$customVars - (string) Free text sent to the database allows management of additional behaviours in SUB.LK.MAIN.CONTROL.CUSTOM, which is called when this parameter is set.
			$receiveTimeout - (number) Maximum time in seconds that the client will wait for a response from the server. Default = 0 to wait indefinitely.
		
		Returns:
			string
			
			The results of the operation.
		
		Remarks:
			TABLE output format uses the defined control characters in <EntryPoints Parameters: http://kosday.com/Manuals/en_web_linkar/lk_schemas_ep_parameters.html> Table Row Separator and Column Row Separator.
			
			By default:
			
				TAB - char (9) for columns.
				VT - char (11) for rows.
	*/
	public function GetTable($filename, $selectClause = "", $dictClause = "", $sortClause = "", $tableOptions = null,
		$customVars = "", $receiveTimeout = 0) {
        if(is_null($tableOptions)) {
            $tableOptions = new TableOptions();
        }
		$opArgs = OperationArguments::GetGetTableArgs($filename, $selectClause, $dictClause, $sortClause, $tableOptions, $customVars);       
		$opCode = OPERATION_CODE::GETTABLE;
		$inputFormat = DATAFORMAT_TYPE::MV;
		$outputFormat = DATAFORMATSCH_TYPE::TABLE;
	
		$result = $this->Linkar->LkExecutePersistentOperation($this->ConnectionInfo, $opCode, $opArgs, $inputFormat, $outputFormat, $receiveTimeout);
		return $result;
	}
	
	/*
		Function: ResetCommonBlocks
			Resets the COMMON variables with the 100 most used files.
		
		Arguments:
			$outputFormat - (<DATAFORMAT_TYPE>) Indicates in what format you want to receive the data resulting from the operation: MV, XML or JSON.
			$receiveTimeout - (number) Maximum time in seconds that the client will wait for a response from the server. Default = 0 to wait indefinitely.
		
		Returns:
			string
			
			The results of the operation.
	*/
	public function ResetCommonBlocks($outputFormat = DATAFORMAT_TYPE::MV, $receiveTimeout = 0) {
		$opArgs = OperationArguments::GetResetCommonBlocksArgs();       
		$opCode = OPERATION_CODE::RESETCOMMONBLOCKS;
		$inputFormat = DATAFORMAT_TYPE::MV;
	
		$result = $this->Linkar->LkExecutePersistentOperation($this->ConnectionInfo, $opCode, $opArgs, $inputFormat, $outputFormat, $receiveTimeout);
		return $result;
	}
}
?>
