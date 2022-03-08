<?php
include_once "Linkar.Functions/LinkarFunctions.php";  
include_once "Linkar.Functions.Persistent/LinkarClient.php";  
include_once "Linkar.Functions.Direct/DirectFunctions.php";  
include_once "Linkar.LkData/LinkarData.php";
include_once "Linkar.Strings/StringFunctions.php";

	function TestNew($withLogin = false) {
		global $linkarClt, $credentialOpt;
		global $lkDataCRUD;
		global $filename, $records, $inputFormat, $outputFormatCRU, $customVars, $receiveTimeout;

		echo "\nNEW. Create two news records with IDs \"TEST_1\" and \"TEST_2\"\n";
		$readAfter = true;
		$calculated = false;
		$conversion = false;
		$formatSpec = false;
		$originalRecords = false;
		$recordIdType = new RecordIdType();
		$newOpt = new NewOptions($recordIdType, $readAfter, $calculated, $conversion, $formatSpec, $originalRecords);
		
		$rec1 = "";
		$rec1 = MvOperations::LkReplace($rec1, "CUSTOMER_TEST1", 1);
		$rec1 = MvOperations::LkReplace($rec1, "ADDRESS_TEST1", 2);
		$rec1 = MvOperations::LkReplace($rec1, "111111111", 3);
		$rec2 = "CUSTOMER_TEST2" . DBMV_Mark::AM_str . "ADDRESS_TEST2" . DBMV_Mark::AM_str . "222222222";
		$records = StringFunctions::ComposeNewBuffer([ "TEST_1", "TEST_2" ], [ $rec1, $rec2]);
				
		if ($withLogin)
			$result = $linkarClt->New($filename, $records, $newOpt, $inputFormat, $outputFormatCRU, $customVars, $receiveTimeout);
		else		
			$result = DirectFunctions::New($credentialOpt, $filename, $records, $newOpt, $inputFormat, $outputFormatCRU, $customVars, $receiveTimeout);
		echo "Raw result:--------------------------------\n" . $result;
		echo "-------------------------------------------\n";

		echo "\nNEW. Create two news records with IDs \"TEST_3\" and \"TEST_4\" using LkData\n";
		$lkDataCRUD->LkRecords->LstDictsId = [ "ID" ];
		$lkDataCRUD->LkRecords->LstDicts = ["NAME", "ADDR", "PHONE"];

		$rec3 = new LkItem("TEST_3");
		$lkDataCRUD->LkRecords->push($rec3);
		$rec3->set("CUSTOMER_TEST3", 1); # attribute 1 (NAME dictionary)
		$rec3->set("ADDRESS_TEST3", "ADDR");
		$rec3->set("333333333", "PHONE");	

		$rec4 =new  LkItem("TEST_4");
		$lkDataCRUD->LkRecords->push($rec4);
		$rec4->set("CUSTOMER_TEST4", "NAME");
		$rec4->set("ADDRESS_TEST4", "ADDR");
		$rec4->set("444444444", "PHONE");

		$records = $lkDataCRUD->LkRecords->ComposeNewBuffer();
		if ($withLogin)
			$result = $linkarClt->New($filename, $records, $newOpt, $inputFormat, $outputFormatCRU, $customVars, $receiveTimeout);
		else
			$result = DirectFunctions::New($credentialOpt, $filename, $records, $newOpt, $inputFormat, $outputFormatCRU, $customVars, $receiveTimeout);
		echo "LkData:------------------------------------\n";
		echo $lkDataCRUD->LkRecords->LstDictsId[0] . "\t";
		foreach ($lkDataCRUD->LkRecords->LstDicts as $dic)
			echo $dic . "\t\t";
		echo "\n";
		foreach ($lkDataCRUD->LkRecords->LkItemsArray as $rec)
			echo $rec->RecordId . "\t" .  $rec->NAME  ."\t" .  $rec->get(2) . "\t" . $rec->PHONE . "\n";
		echo "-------------------------------------------\n";

		$a = readline("Press any key to continue");
	}

	function TestRead($withLogin = false) {
		global $linkarClt, $credentialOpt;
		global $lkDataCRUD;
		global $filename, $outputFormatCRU, $customVars, $receiveTimeout;

		echo "\nREAD. Read record Id \"TEST_1\"\n";
		$calculated = false;
		$conversion = false;
		$formatSpec = false;
		$originalRecords = false;
		$readOpt = new ReadOptions($calculated, $conversion, $formatSpec, $originalRecords);
		$recordIds = "TEST_1";
		$dictionaries = "";
		$inputFormat = DATAFORMAT_TYPE::MV;
		if ($withLogin)
			$result = $linkarClt->Read($filename, $recordIds, $dictionaries, $readOpt, $inputFormat, $outputFormatCRU, $customVars, $receiveTimeout);
		else
			$result = DirectFunctions::Read($credentialOpt, $filename, $recordIds, $dictionaries, $readOpt, $inputFormat, $outputFormatCRU, $customVars, $receiveTimeout);
		echo "Raw result:--------------------------------\n" . $result;
		echo "-------------------------------------------\n";

		echo "READ. Read recordId TEST_1 and TEST_2";
		$recordIds = StringFunctions::ComposeRecordIds(["TEST_1", "TEST_2"]);
		if ($withLogin)
			$result = $linkarClt->Read($filename, $recordIds, $dictionaries, $readOpt, $inputFormat, $outputFormatCRU, $customVars, $receiveTimeout);
		else
			$result = DirectFunctions::Read($credentialOpt, $filename, $recordIds, $dictionaries, $readOpt, $inputFormat, $outputFormatCRU, $customVars, $receiveTimeout);
		echo "Raw result:--------------------------------\n" . $result;
		echo "-------------------------------------------\n";

		echo "\nREAD. Read recordId TEST_3 and TEST_4 using LkData\n";
		$lkDataCRUD->LkRecords->pushIds([ "TEST_3", "TEST_4" ]);
		$recordIds = $lkDataCRUD->LkRecords->ComposeReadBuffer();
		if ($withLogin)
			$result = $linkarClt->Read($filename, $recordIds, $dictionaries, $readOpt, $inputFormat, $outputFormatCRU, $customVars, $receiveTimeout);
		else
			$result = DirectFunctions::Read($credentialOpt, $filename, $recordIds, $dictionaries, $readOpt, $inputFormat, $outputFormatCRU, $customVars, $receiveTimeout);
		echo "LkData:------------------------------------\n";
		$lkDataCRUD = new LkDataCRUD($result);
		echo $lkDataCRUD->LkRecords->LstDictsId[0] . "\t";
		foreach ($lkDataCRUD->LkRecords->LstDicts as $dic)
			echo $dic . "\t\t";
		echo "\n";
		foreach ($lkDataCRUD->LkRecords->LkItemsArray as $rec)
			echo $rec->RecordId . "\t" . $rec->NAME . "\t" .  $rec->ADDR . "\t" .  $rec->PHONE . "\n";

		$lkItem = $lkDataCRUD->LkRecords->LkItemsArray[1];
		echo "\nlkData.LkRecords->LkItemsArray[1]->get(2) --> " . $lkItem->get(2) . "\n";
		echo "\n$ lkDataCRUD->LkRecords->LkItemsArray[1]->get(2) --> " . $lkDataCRUD->LkRecords->LkItemsArray[1]->get(2) . "\n";
		echo "$ lkDataCRUD->LkRecords->TEST_4->get(2) --> " . $lkDataCRUD->LkRecords->TEST_4->get(2) . "\n";
		echo "$ lkDataCRUD->LkRecords->TEST_4->ADDR --> " . $lkDataCRUD->LkRecords->TEST_4->ADDR . "\n";
		echo "$ lkDataCRUD->LkRecords->TEST_4->get(\"ADDR\", 1, 1) --> " . $lkDataCRUD->LkRecords->TEST_4->get("ADDR", 1, 1) . "\n";
		echo "-------------------------------------------\n";
		
		readline("Press any key to continue");
	}

	function TestUpdate($withLogin = false) {
		global $linkarClt, $credentialOpt;
		global $lkDataCRUD;
		global $filename, $outputFormatCRU, $customVars, $receiveTimeout;

		echo "\nUPDATE. Read and Update the record with ID \"TEST_1\"\n";
		$calculated = false;
		$conversion = false;
		$formatSpec = false;
		$originalRecords = false;
		$readOpt = new readOptions($calculated, $conversion, $formatSpec, $originalRecords);
		$recordIds = "TEST_1";
		$dictionaries = "";
		$inputFormat = DATAFORMAT_TYPE::MV;
		if ($withLogin)
			$result = $linkarClt->Read($filename, $recordIds, $dictionaries, $readOpt, $inputFormat, $outputFormatCRU, $customVars, $receiveTimeout);
		else
			$result = DirectFunctions::Read($credentialOpt, $filename, $recordIds, $dictionaries, $readOpt, $inputFormat, $outputFormatCRU, $customVars, $receiveTimeout);

		$orgRec1 = "";
		$rec1 = MvOperations::LkReplace($orgRec1, "UPDATE_ADDRESS_TEST_1", 2);
		$records = StringFunctions::ComposeUpdateBuffer($recordIds, $rec1, $orgRec1);

		$optimisticLockControl = false;
		$readAfter = true;
		$originalRecords = false;
		$updateOpt = new UpdateOptions($optimisticLockControl, $readAfter, $calculated, $conversion, $formatSpec, $originalRecords);
		if ($withLogin)
			$result = $linkarClt->Update($filename, $records, $updateOpt, $inputFormat, $outputFormatCRU, $customVars, $receiveTimeout);
		else
			$result = DirectFunctions::Update($credentialOpt, $filename, $records, $updateOpt, $inputFormat, $outputFormatCRU, $customVars, $receiveTimeout);
		echo "Raw result:--------------------------------\n" . $result . "\n";
		echo "-------------------------------------------\n";

		echo "\nUPDATE. Update the records with ID \"TEST_3\" and \"TEST_4\" using LkData\n";
		echo "LkData:------------------------------------\n";
		$lkDataCRUD->LkRecords->LkItemsArray[0]->ADDR="UPDATE_ADDRESS_TEST_3";
		
		$lkDataCRUD->LkRecords->TEST_4->ADDR = "UPDATE_ADDRESS_TEST_4";
		$records = $lkDataCRUD->LkRecords->ComposeUpdateBuffer();
		if ($withLogin)
			$result = $linkarClt->Update($filename, $records, $updateOpt, $inputFormat, $outputFormatCRU, $customVars, $receiveTimeout);
		else
			$result = DirectFunctions::Update($credentialOpt, $filename, $records, $updateOpt, $inputFormat, $outputFormatCRU, $customVars, $receiveTimeout);

		$lkDataCRUD = new LkDataCRUD($result);
		echo $lkDataCRUD->LkRecords->LstDictsId[0] . "\t";
		foreach ($lkDataCRUD->LkRecords->LstDicts as $dic)
			echo $dic . "\t\t";
		echo "\n";
		foreach ($lkDataCRUD->LkRecords->LkItemsArray as $rec)
			echo $rec->RecordId . "\t" . $rec->NAME . "\t" .  $rec->ADDR . "\t" .  $rec->PHONE . "\n";
		echo "\n-------------------------------------------\n";

		$a = readline("Press any key to continue");
	}

	function TestUpdatePartial($withLogin = false) {
		global $linkarClt, $credentialOpt;
		global $filename, $inputFormat, $outputFormatCRU, $customVars, $receiveTimeout;

		echo "\nUPDATEPARTIAL. Update only the attibute 2 (ADDR) on the record with ID \"TEST_2\"\n";
		$optimisticLockControl = false;
		$readAfter = true;
		$calculated = false;
		$conversion = false;
		$formatSpec = false;
		$originalRecords = false;

		$updateOpt = new UpdateOptions($optimisticLockControl, $readAfter, $calculated, $conversion, $formatSpec, $originalRecords);

		$recordId = "TEST_2";
		$dictionaries = "ADDR";
		$attributeValue = "UPDATE_ADDRESS_TEST_2";
		$orgRecord = "";
		$records = StringFunctions::ComposeUpdateBuffer($recordId, $attributeValue, $orgRecord);
		if ($withLogin)
			$result = $linkarClt->UpdatePartial($filename, $records, $dictionaries, $updateOpt, $inputFormat, $outputFormatCRU, $customVars, $receiveTimeout);
		else
			$result = DirectFunctions::UpdatePartial($credentialOpt, $filename, $records, $dictionaries, $updateOpt, $inputFormat, $outputFormatCRU, $customVars, $receiveTimeout);
		echo "Raw result:--------------------------------\n" . $result . "\n";
		echo "-------------------------------------------\n";

		echo "\nUPDATEPARTIAL. Update only the attibute 2 (ADDR) on the record with ID \"TEST_4\"\n";
		echo "LkData:------------------------------------\n";
		$recordId = "TEST_4";
		$dictionaries = "ADDR";
		$attributeValue = "UPDATE_ADDRESS_TEST_4";
		$orgRecord = "";
		$records = StringFunctions::ComposeUpdateBuffer($recordId, $attributeValue, $orgRecord);
		if ($withLogin)
			$result = $linkarClt->UpdatePartial($filename, $records, $dictionaries, $updateOpt, $inputFormat, $outputFormatCRU, $customVars, $receiveTimeout);
		else
			$result = DirectFunctions::UpdatePArtial($credentialOpt, $filename, $records, $dictionaries, $updateOpt, $inputFormat, $outputFormatCRU, $customVars, $receiveTimeout);
				
		$lkData = new LkDataCRUD($result);
		foreach ($lkData->LkRecords->LkItemsArray as $rec)
			echo "ID: " . $rec->RecordId . "\tNew "  . $dictionaries . " value: " . $rec->ADDR . "\n";
		echo "\n-------------------------------------------\n";

		$a = readline("Press any key to continue");
	}

	function TestDelete($withLogin = false) {
		global $linkarClt, $credentialOpt;
		global $lkDataCRUD;
		global $filename, $inputFormat, $outputFormat, $customVars, $receiveTimeout;

		echo "\nDELETE. Delete the records with IDs \"TEST_1\" and \"TEST_2\"\n";
		$optimisticLockControl = false;
		$recoverIdType = new RecoverIdType();
		$deleteOpt =new  DeleteOptions($optimisticLockControl, $recoverIdType);
		$recordIds = StringFunctions::ComposeRecordIds([ "TEST_1", "TEST_2" ]);
		$originalRecords = "";
		$records = StringFunctions::ComposeDeleteBuffer($recordIds, $originalRecords);
		if ($withLogin)
			$result = $linkarClt->Delete($filename, $records, $deleteOpt, $inputFormat, $outputFormat, $customVars, $receiveTimeout);
		else
			$result = DirectFunctions::Delete($credentialOpt, $filename, $records, $deleteOpt, $inputFormat, $outputFormat, $customVars, $receiveTimeout);
		echo "Raw result:--------------------------------\n" . $result . "\n";
		echo "-------------------------------------------\n";

		echo "\nDELETE. Delete the records with IDs \"TEST_1\" and \"TEST_2\" with LkData\n";
		echo "LkData:------------------------------------\n";
		$records = $lkDataCRUD->LkRecords->ComposeDeleteBuffer();
		if ($withLogin)
			$result = $linkarClt->Delete($filename, $records, $deleteOpt, $inputFormat, $outputFormat, $customVars, $receiveTimeout);
		else
			$result = DirectFunctions::Delete($credentialOpt, $filename, $records, $deleteOpt, $inputFormat, $outputFormat, $customVars, $receiveTimeout);
		$lkDataCRUD = new LkDataCRUD($result);
		foreach ($lkDataCRUD->LkRecords->LkItemsArray as $rec)
			echo $rec->RecordId . "DELETED" . "\t";
		echo "\n-------------------------------------------\n";

		$a = readline("Press any key to continue");
	}

	function TestSelect($withLogin = false) {
		global $linkarClt, $credentialOpt;
		global $filename, $outputFormatCRU, $customVars, $receiveTimeout;

		echo "\nSELECT. Select all records of the LK.CUSTOMERS file\n";
		$onlyRecordId = false;
		$pagination = false;
		$regPage = 10;
		$numPage = 2;
		$calculated = false;
		$conversion = false;
		$formatSpec = false;
		$originalRecords = false;
		$selectOpt = new SelectOptions($onlyRecordId, $pagination, $regPage, $numPage, $calculated, $conversion, $formatSpec, $originalRecords);

		$selectClause = "";
		$sortClause = "BY ID";
		$dictClause = "";
		$preselectClause = "";
		if ($withLogin)
			$result = $linkarClt->Select($filename, $selectClause, $sortClause, $dictClause, $preselectClause, $selectOpt, $outputFormatCRU, $customVars, $receiveTimeout);
		else
			$result = DirectFunctions::Select($credentialOpt, $filename, $selectClause, $sortClause, $dictClause, $preselectClause, $selectOpt, $outputFormatCRU, $customVars, $receiveTimeout);
		echo "Raw result:--------------------------------\n" . $result . "\n";
		echo "-------------------------------------------\n";
		echo "LkData:------------------------------------\n";
		$lkData = new LkDataCRUD($result);
		echo $lkData->LkRecords->LstDictsId[0] . "\t";
		foreach ($lkData->LkRecords->LstDicts as $dic)
			echo $dic . "\t\t";
		echo "\n";
		foreach ($lkData->LkRecords->LkItemsArray as $rec)
			echo $rec->RecordId . " \t" . $rec->NAME . "\t" . $rec->ADDR . "\t" . $rec->PHONE . "\n";
		echo "-------------------------------------------\n";
		
		$a = readline("Press any key to continue");
	}

	function TestSubroutine($withLogin = false) {
		global $linkarClt, $credentialOpt;
		global $outputFormat, $customVars, $receiveTimeout;

		echo "\nSUBROUTINE. Call to SUB.DEMOLIKAR subroutine\n";
		$subroutineName = "SUB.DEMOLINKAR";
		$argsNumber = "3";
		$args = StringFunctions::ComposeSubroutineArgs(["0", "aaaaaaaaa", ""]);
		$inputFormat = DATAFORMAT_TYPE::MV;
		if ($withLogin)
			$result = $linkarClt->Subroutine($subroutineName, $argsNumber, $args, $inputFormat, $outputFormat, $customVars, $receiveTimeout);
		else
			$result = DirectFunctions::Subroutine($credentialOpt, $subroutineName, $argsNumber, $args, $inputFormat, $outputFormat, $customVars, $receiveTimeout);
		echo "\nRaw result:--------------------------------\n" . $result . "\n";
		echo "-------------------------------------------\n";
		echo "LkData:------------------------------------\n";
		$lkData = new LkDataSubroutine($result);
		$i = 1;
		foreach ($lkData->Arguments as $arg) {
			echo "Arg " . $i . ": " . $arg . "\n";
			$i += 1;
		}
		echo "-------------------------------------------\n";
		$a = readline("Press any key to continue");
	}

	function TestConversion($withLogin = false) {
		global $linkarClt, $credentialOpt;
		global $outputFormat, $customVars, $receiveTimeout;

		echo "\nCONVERSION\n";
		$expression = StringFunctions::ComposeExpressions(["31-12-2017", "01-01-2018"]);		
		
		$code = "D2-";
		$conversionType = CONVERSION_TYPE::INPUT;
		if ($withLogin)
			$result = $linkarClt->Conversion($expression, $code, $conversionType, $outputFormat, $customVars, $receiveTimeout);
		else
			$result = DirectFunctions::Conversion($credentialOpt, $expression, $code, $conversionType, $outputFormat, $customVars, $receiveTimeout);
		echo "Raw result:--------------------------------\n" . $result . "\n";
		echo "-------------------------------------------\n";
		echo "LkData:------------------------------------\n";
		$lkData = new LkDataConversion($result);
		echo " Conversion: " . $lkData->Conversion . "\n";
		echo "-------------------------------------------\n";
		
		$a = readline("Press any key to continue");
	}

	function TestFormat($withLogin = false) {
		global $linkarClt, $credentialOpt;
		global $outputFormat, $customVars, $receiveTimeout;

		echo "\nFORMAT\n";
		$expression = "1";
		$formatSpec = "R#10";
		if ($withLogin)
			$result = $linkarClt->Format($expression, $formatSpec, $outputFormat, $customVars, $receiveTimeout);
		else
			$result = DirectFunctions::Format($credentialOpt, $expression, $formatSpec, $outputFormat, $customVars, $receiveTimeout);
		echo "Raw result:--------------------------------\n" . $result . "\n";
		echo "-------------------------------------------\n";
		echo "LkData:-----------------------------------\n";
		$lkData = new LkDataFormat($result);
		echo " Format: " . $lkData->Format . "\n";
		echo "-------------------------------------------\n";
		
		$a = readline("Press any key to continue");
	}

	function TestExecute($withLogin = false) {
		global $linkarClt, $credentialOpt;
		global $outputFormat, $customVars, $receiveTimeout;

		echo "\nEXECUTE\n";
		$statement = "WHO";
		if ($withLogin)
			$result = $linkarClt->Execute($statement, $outputFormat, $customVars, $receiveTimeout);
		else
			$result = DirectFunctions::Execute($credentialOpt, $statement, $outputFormat, $customVars, $receiveTimeout);
		echo "Raw result:--------------------------------\n" . $result . "\n";
		echo "-------------------------------------------\n";
		echo "LkData:------------------------------------\n";
		$lkData = new LkDataExecute($result);
		echo " Capturing: " . $lkData->Capturing . "\n";
		echo " Returning: " . $lkData->Returning . "\n";
		echo "-------------------------------------------\n";
		
		$a = readline("Press any key to continue");
	}

	function TestDictionaries($withLogin = false) {
		global $linkarClt, $credentialOpt;
		global $filename, $outputFormat, $customVars, $receiveTimeout;

		echo "\nDICTIONARIES\n";
		if ($withLogin)
			$result = $linkarClt->Dictionaries($filename, $outputFormat, $customVars, $receiveTimeout);
		else
			$result = DirectFunctions::Dictionaries($credentialOpt, $filename, $outputFormat, $customVars, $receiveTimeout);
		echo "Raw result:--------------------------------\n" . $result . "\n";
		echo "-------------------------------------------\n";
		echo "LkData:------------------------------------\n";
		$lkData = new LkDataCRUD($result);
		foreach ($lkData->LkRecords->LkItemsArray as $rec)
			echo $rec->RecordId . "\t\t" . $rec->Record . "\n";
		echo "-------------------------------------------\n";
		
		$a = readline("Press any key to continue");
	}

	function TestGetVersion($withLogin = false) {
		global $linkarClt, $credentialOpt;
		global $outputFormat, $receiveTimeout;

		echo "\nGETVERSION\n";
		if ($withLogin)
			$result = $linkarClt->GetVersion($outputFormat, $receiveTimeout);
		else
			$result = DirectFunctions::GetVersion($credentialOpt, $outputFormat, $receiveTimeout);
		echo "Raw result:--------------------------------\n" . $result . "\n";
		echo "-------------------------------------------\n";
		$lstTags = StringFunctions::ExtractRecordsDicts($result);
		$lstRec = StringFunctions::ExtractRecords($result);
		$lstValues = StringFunctions::splitArray($lstRec[0], DBMV_Mark::AM_str);
		
		$i = 0;
		foreach ($lstTags as $tag) {
			echo " " . $tag . "\t\t" . $lstValues[$i] . "\n";
			$i += 1;
		}
		echo "-------------------------------------------\n";
		$a = readline("Press any key to continue");
	}

	function TestLkSchemas($withLogin = false) {
		global $linkarClt, $credentialOpt;
		global $outputformatSCH, $outputformat, $customVars, $receiveTimeout;

		echo "\nLKSCHEMAS\n";
		$rowHeader = ROWHEADERS_TYPE::MAINLABEL;
		$rowProperties = true;
		$onlyVisibles = false;
		$pagination = false;
		$regPage = 10;
		$numPage = 1;
		$lkSchemasOpt = new LkSchemasOptions();
		
		echo "\nSchemaType: LKSCHEMAS\n";
		$lkSchemasOpt->LkSchemas($rowHeader, $rowProperties, $onlyVisibles, $pagination, $regPage, $numPage);
		if ($withLogin)
			$result = $linkarClt->LkSchemas($lkSchemasOpt, $outputformatSCH, $customVars, $receiveTimeout);
		else
			$result = DirectFunctions::LkSchemas($credentialOpt, $lkSchemasOpt, $outputformatSCH, $customVars, $receiveTimeout);
		echo "TABLE result:--------------------------------\n" . str_replace("\x0B", "\n", $result) . "\n" ; // Replace standard TABLEROWSEPARATOR (0x1B) by "\n"
		echo "-------------------------------------------\n";

		echo "\nSchemaType: SQL MODE\n";
		$lkSchemasOpt->SqlMode($onlyVisibles, $pagination, $regPage, $numPage);
		if ($withLogin)
			$result = $linkarClt->LkSchemas($lkSchemasOpt, $outputformatSCH, $customVars, $receiveTimeout);
		else
			$result = DirectFunctions::LkSchemas($credentialOpt, $lkSchemasOpt, $outputformatSCH, $customVars, $receiveTimeout);
		echo "TABLE result:--------------------------------\n" . str_replace("\x0B", "\n", $result) . "\n" ; // Replace standard TABLEROWSEPARATOR (0x1B) by "\n"
		echo "-------------------------------------------\n";

		echo "\nSchemaType: DICTIONARIES\n";
		$lkSchemasOpt->Dictionaries($rowHeader, $pagination, $regPage, $numPage);
		if ($withLogin)
			$result = $linkarClt->LkSchemas($lkSchemasOpt, $outputformatSCH, $customVars, $receiveTimeout);
		else
			$result = DirectFunctions::LkSchemas($credentialOpt, $lkSchemasOpt, $outputformatSCH, $customVars, $receiveTimeout);
		echo "TABLE result:--------------------------------\n" . str_replace("\x0B", "\n", $result) . "\n" ; // Replace standard TABLEROWSEPARATOR (0x1B) by "\n"
		echo "-------------------------------------------\n";

		$a = readline("Press any key to continue");
	}

	function TestLkProperties($withLogin = false) {
		global $linkarClt, $credentialOpt;
		global $filename, $outputformatSCH, $customVars, $receiveTimeout;

		echo "\nLKPROPERTIES\n";
		$rowHeader = ROWHEADERS_TYPE::MAINLABEL;
		$rowProperties = false;
		$onlyVisibles = false;
		$usePropertyNames = false;
		$pagination = false;
		$regPage = 10;
		$numPage = 1;
		$lkPropertiesOpt = new LkPropertiesOptions();

		echo "\nSchemaType: LKSCHEMAS\n";
		$lkPropertiesOpt->LkSchemas($rowHeader, $rowProperties, $onlyVisibles, $usePropertyNames, $pagination, $regPage, $numPage);
		if ($withLogin)
			$result = $linkarClt->LkProperties($filename, $lkPropertiesOpt, $outputformatSCH, $customVars, $receiveTimeout);
		else
			$result = DirectFunctions::LkProperties($credentialOpt, $filename, $lkPropertiesOpt, $outputformatSCH, $customVars, $receiveTimeout);
		echo "TABLE result:--------------------------------\n" . str_replace("\x0B", "\n", $result) . "\n" ; // Replace standard TABLEROWSEPARATOR (0x1B) by "\n"
		echo "-------------------------------------------\n";

		echo "\nSchemaType: SQL MODE\n";
		$lkPropertiesOpt->SqlMode($onlyVisibles, $pagination, $regPage, $numPage);
		$filenameSql = "CUSTOMERS";
		if ($withLogin)
			$result = $linkarClt->LkProperties($filenameSql, $lkPropertiesOpt, $outputformatSCH, $customVars, $receiveTimeout);
		else
			$result = DirectFunctions::LkProperties($credentialOpt, $filenameSql, $lkPropertiesOpt, $outputformatSCH, $customVars, $receiveTimeout);
		echo "TABLE result:--------------------------------\n" . str_replace("\x0B", "\n", $result) . "\n" ; // Replace standard TABLEROWSEPARATOR (0x1B) by "\n"
		echo "-------------------------------------------\n";

		echo "\nSchemaType: DICTIONARIES\n";
		$lkPropertiesOpt->Dictionaries($rowHeader, $pagination, $regPage, $numPage);
		if ($withLogin)
			$result = $linkarClt->LkProperties($filename, $lkPropertiesOpt, $outputformatSCH, $customVars, $receiveTimeout);
		else
			$result = DirectFunctions::LkProperties($credentialOpt, $filename, $lkPropertiesOpt, $outputformatSCH, $customVars, $receiveTimeout);
		echo "TABLE result:--------------------------------\n" . str_replace("\x0B", "\n", $result) . "\n" ; // Replace standard TABLEROWSEPARATOR (0x1B) by "\n"
		echo "-------------------------------------------\n ";

		$a = readline("Press any key to continue");
	}

	function TestGetTable($withLogin = false) {
		global $linkarClt, $credentialOpt;
		global $filename, $customVars, $receiveTimeout;

		echo "\nGETTABLE\n";
		$rowHeader = ROWHEADERS_TYPE::MAINLABEL;
		$rowProperties = false;
		$onlyVisibles = false;
		$usePropertyNames = false;
		$repeatValues = false;
		$applyConversion = false;
		$applyFormat = false;
		$calculated = false;
		$pagination = false;
		$regPage = 10;
		$numPage = 1;
		$selectClause = "";
		$dictClause = "";
		$sortClause = "BY ID";
		$tableOpt = new TableOptions();

		echo "\nSchemaType: LKSCHEMAS\n";
		$tableOpt->LkSchemas($rowHeader, $rowProperties, $onlyVisibles, $usePropertyNames, $repeatValues, $applyConversion, $applyFormat, $calculated, $pagination, $regPage, $numPage);
		if ($withLogin)
			$result = $linkarClt->GetTable($filename, $selectClause, $dictClause, $sortClause, $tableOpt, $customVars, $receiveTimeout);
		else
			$result = DirectFunctions::GetTable($credentialOpt, $filename, $selectClause, $dictClause, $sortClause, $tableOpt, $customVars, $receiveTimeout);
		echo "TABLE result:--------------------------------\n" . str_replace("\x0B", "\n", $result) . "\n" ; // Replace standard TABLEROWSEPARATOR (0x1B) by "\n"
		echo "-------------------------------------------\n";

		echo "\nSchemaType: SQL MODE\n";
		$tableOpt->SqlMode($onlyVisibles, $applyConversion, $applyFormat, $calculated, $pagination, $regPage, $numPage);
		$filenameSql = "CUSTOMERS";
		if ($withLogin)
			$result = $linkarClt->GetTable($filenameSql, $selectClause, $dictClause, $sortClause, $tableOpt, $customVars, $receiveTimeout);
		else
			$result = DirectFunctions::GetTable($credentialOpt, $filenameSql, $selectClause, $dictClause, $sortClause, $tableOpt, $customVars, $receiveTimeout);
		echo "TABLE result:--------------------------------\n" . str_replace("\x0B", "\n", $result) . "\n" ; // Replace standard TABLEROWSEPARATOR (0x1B) by "\n"
		echo "-------------------------------------------\n";

		echo "\nSchemaType: DICTIONARIES\n";
		$tableOpt->Dictionaries($rowHeader, $repeatValues, $applyConversion, $applyFormat, $calculated, $pagination, $regPage, $numPage);
		if ($withLogin)
			$result = $linkarClt->GetTable($filename, $selectClause, $dictClause, $sortClause, $tableOpt, $customVars, $receiveTimeout);
		else
			$result = DirectFunctions::GetTable($credentialOpt, $filename, $selectClause, $dictClause, $sortClause, $tableOpt, $customVars, $receiveTimeout);
		echo "TABLE result:--------------------------------\n" . str_replace("\x0B", "\n", $result) . "\n" ; // Replace standard TABLEROWSEPARATOR (0x1B) by "\n"
		echo "-------------------------------------------\n";

		echo "\nSchemaType: NONE\n";
		$tableOpt->None($rowHeader, $repeatValues, $pagination, $regPage, $numPage);
		if ($withLogin)
			$result = $linkarClt->GetTable($filename, $selectClause, $dictClause, $sortClause, $tableOpt, $customVars, $receiveTimeout);
		else
			$result = DirectFunctions::GetTable($credentialOpt, $filename, $selectClause, $dictClause, $sortClause, $tableOpt, $customVars, $receiveTimeout);
		echo "TABLE result:--------------------------------\n" . str_replace("\x0B", "\n", $result) . "\n" ; // Replace standard TABLEROWSEPARATOR (0x1B) by "\n"
		echo "-------------------------------------------\n";

		$a = readline("Press any key to continue");
	}

	function TestResetCommonBlocks($withLogin = false) {
		global $linkarClt, $credentialOpt;
		global $outputFormat, $receiveTimeout;

		if ($withLogin)
			$result = $linkarClt->ResetCommonBlocks($outputFormat, $receiveTimeout);
		else
			$result = DirectFunctions::ResetCommonBlocks($credentialOpt, $outputFormat, $receiveTimeout);
		echo "Raw result:--------------------------------\n" . $result . "\n";
		echo "-------------------------------------------\n";

		$a = readline("Press any key to continue");
	}

	try {

		// You must set the correct credentials for your EntryPoint
		$credentialOpt = new CredentialOptions(
			'192.168.100.100', 		# host
			'QMWINQ', 				# entryPoint
			11300, 					# entryPoint Port
			'admin', 				# username
			'1234', 				# password
			'', 					# lang
			'php_test'				# free text
		);
		
		$filename = "LK.CUSTOMERS";
		$outputFormat = DATAFORMAT_TYPE::MV;
		$outputFormatCRU = DATAFORMATCRU_TYPE::MV;
		$outputformatSCH = DATAFORMATSCH_TYPE::TABLE;
		$inputFormat = DATAFORMAT_TYPE::MV;
		$customVars = "";
		$receiveTimeout = 10;
		$lkDataCRUD = new LkDataCRUD("");
		
   		echo "Test DIRECT Functions\n";
		TestNew();
		TestRead();	
		TestUpdate();
		TestUpdatePartial();		
		TestDelete();
		TestSelect();
		TestSubroutine();		
		TestConversion();		
		TestFormat();
		TestExecute();
		TestDictionaries();
		TestGetVersion();
		TestLkSchemas();
		TestLkProperties();
		TestGetTable();
		TestResetCommonBlocks();

		echo "Test PERSISTENT Functions (with Login)\n";

		$lkDataCRUD = new LkDataCRUD("");
		$linkarClt = new LinkarClient();
		$linkarClt->Login($credentialOpt, $customVars, $receiveTimeout);
		TestNew(true);
		TestRead(true);
		TestUpdate(true);
		TestUpdatePartial(true);
		TestDelete(true);
		TestSelect(true);
		TestSubroutine(true);
		TestConversion(true);
		TestFormat(true);
		TestExecute(true);
		TestDictionaries(true);
		TestGetVersion(true);
		TestLkSchemas(true);
		TestLkProperties(true);
		TestGetTable(true);
		TestResetCommonBlocks(true);
		$linkarClt->Logout($customVars, $receiveTimeout);

	}
	catch(Exception $e) {
		echo "********************************+\n";
		echo "ERROR: " . $e->getMessage() . "\n";
		echo "********************************+\n";
	}
	echo "\n\n\n\n";
	?>