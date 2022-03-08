<?php
include_once realpath( __DIR__ . '/../Linkar/Linkar.php');
//include_once realpath( __DIR__ . '/../Linkar.Functions/LinkarFunctions.php');  
include_once 'ENVELOPE_FORMAT.php';

/*
	Class: LinkarClient
		These functions perform persistent (establishing permanent session) operations with any kind of output format type.
	
	Property: SessionId
		String
		
		SessionID of the connection.
*/
class LinkarClient {

	private $linkar;
	private $ConnectionInfo;
	private $ReceiveTimeout;
	public $SessionId;

	/*
		Constructor: __constructor
			Initializes a new instance of the LinkarClt class.
			
		Arguments:
			$receiveTimeout - Maximum time in seconds that the client will wait for a response from the server. Default = 0 to wait indefinitely. When the receiveTimeout argument is omitted in any operation, the value set here will be applied.
	*/
	public function __construct($receiveTimeout = 0) {
		$this->linkar = new Linkar();
		$this->SessionId = "";		
		$this->ConnectionInfo = null;
        if($receiveTimeout < 0)
            $this->ReceiveTimeout = 0;
        else
            $this->ReceiveTimeout = $receiveTimeout;
	}

	/*
		Function: Login
			Starts the communication with a server allowing making use of the rest of functions until the Close method is executed or the connection with the server gets lost.
		
		Arguments:
			$credentialOptions - (<CredentialOptions>) Object with data necessary to access the Linkar Server: Username, Password, EntryPoint, Language, FreeText.
			$customVars - (string) Free text sent to the database allows management of additional behaviours in SUB.LK.MAIN.CONTROL.CUSTOM, which is called when this parameter is set.
			$receiveTimeout - (number) Maximum time in seconds that the client will wait for a response from the server. Default = 0 to wait indefinitely.

		Remarks:
			Login is actually a "virtual" operation which creates a new Client Session ID. No DBMS login is performed unless Linkar SERVER determines new Database Sessions are required. These operations are not related.
	*/
	public function Login($credentialOptions, $customVars = "", $receiveTimeout = 0) {
		if(is_null($this->ConnectionInfo)) {
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
	
			$connectionInfo = new ConnectionInfo("", "", "", $credentialOptions);
			$loginResult = $this->linkar->LkExecutePersistentOperation($connectionInfo, $byteOpCode, $loginArgs, $byteInputFormat, $byteOutputFormat, $receiveTimeout);
			if (!(is_null($loginResult) || strlen($loginResult) == 0)) {
				$this->ConnectionInfo = $connectionInfo;
				$this->SessionId = $connectionInfo->SessionId;
			}
			else {
				$this->ConnectionInfo = null;
			}
		}
	}

	/*
		Function: Logout
			Closes the communication with the server, that previously has been opened with a Login function.
		
		Arguments:
			$customVars - (string) Free text sent to the database allows management of additional behaviours in SUB.LK.MAIN.CONTROL.CUSTOM, which is called when this parameter is set.</param>
			$receiveTimeout - (number) Maximum time in seconds that the client will wait for a response from the server. Default = 0 to wait indefinitely.</param>
	*/
	public function Logout($customVars = "", $receiveTimeout = 0) {
		$logoutArgs = ($customVars?$customVars:"");
		$byteOpCode = OPERATION_CODE::LOGOUT;
		$byteInputFormat = DATAFORMAT_TYPE::MV;
		$byteOutputFormat = DATAFORMAT_TYPE::MV;
		$receiveTimeout = ($receiveTimeout?$receiveTimeout:0);
		if($receiveTimeout <= 0) {
			if($this->ReceiveTimeout > 0)
				$receiveTimeout = $this->ReceiveTimeout;
			else
				$receiveTimeout = 0;
		}
		$loginResult = $this->linkar->LkExecutePersistentOperation($this->ConnectionInfo, $byteOpCode, $logoutArgs, $byteInputFormat, $byteOutputFormat, $receiveTimeout);
		if (!(is_null($loginResult) || strlen($loginResult) == 0))
			$this->ConnectionInfo = null;
	}

	/*
		Function: SendCommand
			Allows a variety of persistent operations using standard templates (XML, JSON).
		
		Arguments:
			$command - (string) Content of the operation you want to send.
			$commandFormat - (<ENVELOPE_FORMAT>) Indicates in what format you are doing the operation: XML or JSON.
			$receiveTimeout - (number) Maximum time in seconds that the client will wait for a response from the server. Default = 0 to wait indefinitely.

		Returns:
			string
			
			The results of the operation.
	*/
	public function SendCommand($command, $commandFormat, $receiveTimeout = 0) {
		$customVars = ""; // It's inside the command template
        $options = ""; // It's inside the command template
		$opArgs = $customVars . ASCII_Chars::US_str . $options . ASCII_Chars::US_str . $command;    		
		if ($commandFormat == ENVELOPE_FORMAT::JSON)
			$opCode = OPERATION_CODE::COMMAND_JSON;
		else
			$opCode = OPERATION_CODE::COMMAND_XML;
		$inputFormat = DATAFORMAT_TYPE::MV;
		$outputFormat = DATAFORMAT_TYPE::MV;  
	
		$result = $this->linkar->LkExecutePersistentOperation($this->ConnectionInfo, $opCode, $opArgs, $inputFormat, $outputFormat, $receiveTimeout);
		return $result;
	}

	/*
		Function: SendJsonCommand
			Allows a variety of persistent operations using standard JSON templates.
		
		Arguments:
			$command - (string) Content of the operation you want to send.
			$receiveTimeout - (number) Maximum time in seconds that the client will wait for a response from the server. Default = 0 to wait indefinitely.
		
		Returns:
			string
			
			The results of the operation.
			
		Example:
		---Code
	    <?php
			include_once 'Linkar/Linkar.php';
			include_once 'Linkar.Commands/LinkarClient.php';
		
			function MySendCommand()
			{
				try
				{
					$client = new LinkarClient();
					$credentials = new CredentialOptions("127.0.0.1", "EPNAME", 11300, "admin", "admin");
					$client->Login(credentials);
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
					$result = $client->SendJsonCommand($command);
					$client->Logout();
				}
				catch(Exception $e)
				{
					// Do something
				}
				return $result;
			}
		?>
	*/
	public function SendJsonCommand($command, $receiveTimeout = 0) {
		return $this->SendCommand($command, ENVELOPE_FORMAT::JSON, $receiveTimeout);
	}

	/*
		Function: SendXmlCommand
			Allows a variety of persistent operations using standard XML templates.
		
		Arguments:
			$command - (string) Content of the operation you want to send.
			$receiveTimeout - (number) Maximum time in seconds that the client will wait for a response from the server. Default = 0 to wait indefinitely.
		
		Returns:
			string
			
			The results of the operation.
			
		Example:
		---Code
	    <?php
			include_once 'Linkar/Linkar.php';
			include_once 'Linkar.Commands/LinkarClient.php';
		
			function MySendCommand()
			{
				try
				{
					$client = new LinkarClient();
					$credentials = new CredentialOptions("127.0.0.1", "EPNAME", 11300, "admin", "admin");
					$client->Login($credentials);
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
						"</COMMAND>";
					$result = $client->SendXmlCommand($command);
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
	public function SendXmlCommand($command, $receiveTimeout = 0) {
		return $this->SendCommand($command, ENVELOPE_FORMAT::XML, $receiveTimeout);
	}
}
?>
