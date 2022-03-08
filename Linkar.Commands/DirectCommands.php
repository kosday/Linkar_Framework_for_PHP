<?php
include_once realpath( __DIR__ . '/../Linkar/Linkar.php');
include_once realpath( __DIR__ . '/../Linkar.Functions/LinkarFunctions.php');  
include_once 'ENVELOPE_FORMAT.php';
/*
	Class: DirectCommands
		These functions perform direct (without establishing permanent session) operations with any kind of output format type.
*/
class DirectCommands {

	/*
		Function: SendCommand
			Allows a variety of direct operations using standard templates (XML, JSON).
		
		Arguments:
			$credentialOptions - (<CredentialOptions>) Object with data necessary to access the Linkar Server: Username, Password, EntryPoint, Language, FreeText.
			$command - (string) Content of the operation you want to send.
			$commandFormat - (string) Indicates in what format you are doing the operation: XML or JSON.
			$receiveTimeout - (number) Maximum time in seconds that the client will wait for a response from the server. Default = 0 to wait indefinitely.
		
		Returns:
		string
		
		The results of the operation.
	*/
	static function SendCommand($credentialOptions, $command, $commandFormat, $receiveTimeout = 0) {
		$customVars = ""; // It's inside the command template
        $options = ""; // It's inside the command template
		$opArgs = $customVars . ASCII_Chars::US_str . $options . ASCII_Chars::US_str . $command;
		if ($commandFormat == ENVELOPE_FORMAT::JSON)
			$opCode = OPERATION_CODE::COMMAND_JSON;
		else
			$opCode = OPERATION_CODE::COMMAND_XML;
		$inputFormat = DATAFORMAT_TYPE::MV;
		$outputFormat = DATAFORMAT_TYPE::MV;
	
		$linkar = new Linkar();
		$result = $linkar->LkExecuteDirectOperation($credentialOptions, $opCode, $opArgs, $inputFormat, $outputFormat, $receiveTimeout);
		return $result;
	}

	/*
		Function: SendJsonCommand
			Allows a variety of direct operations using standard JSON templates.
		
		Arguments:
			$credentialOptions - (<CredentialOptions>) Object with data necessary to access the Linkar Server: Username, Password, EntryPoint, Language, FreeText.
			$command - (string) Content of the operation you want to send.
			$receiveTimeout - (number) Maximum time in seconds that the client will wait for a response from the server. Default = 0 to wait indefinitely. 
		
		Returns:
		string
		
		The results of the operation.
		
		Example:
		---Code
	    <?php
			include_once 'Linkar/Linkar.php';
			include_once 'Linkar.Commands/DirectCommands.php';
			
			function MySendCommand()
			{
				try
				{
					$credentials = new CredentialOptions("127.0.0.1", "EPNAME", 11300, "admin", "admin");
					$command = 
							"{" .
							"	\"NAME\" : \"READ\"," .
							"	\"COMMAND\" :" .
							"	{" .
							"		\"CALCULATED\" : \"True\" ," .
							"		\"OUTPUT_FORMAT\" : \"JSON_DICT\" ," .
							"		\"FILE_NAME\" : \"LK.CUSTOMERS\" ," .
							"		\"RECORDS\" : [" .
							"			{ \"LKITEMID\" : \"2\" }" .
							"		]" .
							"	}" .
							"}";
					$result = DirectCommands::SendJsonCommand($credentials, $command);
				}
				catch(Exception $e)
				{
					// Do something
				}
				return $result;
			}
		?>
		---
	*/
	static function SendJsonCommand($credentialOptions, $command, $receiveTimeout = 0) {
		return DirectCommands::SendCommand($credentialOptions, $command, ENVELOPE_FORMAT::JSON, $receiveTimeout );
	}

	/*
		Function: SendXmlCommand
			Allows a variety of direct operations using standard XML templates.
		
		Arguments:
			$credentialOptions - (<CredentialOptions>) Object with data necessary to access the Linkar Server: Username, Password, EntryPoint, Language, FreeText.
			$command - (string) Content of the operation you want to send.
			$receiveTimeout - (number) Maximum time in seconds that the client will wait for a response from the server. Default = 0 to wait indefinitely.
		
		Returns:
		string
		
		The results of the operation.
		
		Example:
		---Code
	    <?php
			include_once 'Linkar/Linkar.php';
			include_once 'Linkar.Commands/DirectCommands.php';
		
			function MySendCommand()
			{
				try
				{
					$credentials = new $CredentialOptions("127.0.0.1", "EPNAME", 11300, "admin", "admin");
					$command =
							"<COMMAND NAME=\"READ\">" .
							"   <CALCULATED>True</CALCULATED>" .
							"   <OUTPUT_FORMAT>XML_DICT</OUTPUT_FORMAT>" .
							"   <FILE_NAME>LK.CUSTOMERS</FILE_NAME>" .
							"   <RECORDS>" .
							"       <RECORD>" .
							"           <LKITEMID>2</LKITEMID>" .
							"       </RECORD>" .
							"   </RECORDS>" .
							"</COMMAND>"
					$result = DirectCommands::SendXmlCommand($credentials, $command);
				}
				catch(Exception $e)
				{
					// Do something
				}
				return $result;
			}
		?>
		---
	*/
	static function SendXmlCommand($credentialOptions, $command, $receiveTimeout = 0) {
		return DirectCommands::SendCommand($credentialOptions, $command, ENVELOPE_FORMAT::XML, $receiveTimeout );
	}
}
?>
