<?php
namespace LinkarClientTABLE;
include_once realpath( __DIR__ . '/../Linkar/Linkar.php');
include_once realpath( __DIR__ . '/../Linkar.Functions/LinkarFunctions.php');  
include_once realpath( __DIR__ . '/../Linkar.Functions.Persistent/LinkarClient.php');
use LinkarCLient as LinkarClientBase;
use DATAFORMATSCH_TYPE;

/*
	Class: LinkarClient
		These functions perform persistent (establishing permanent session) operations with output format type TABLE.
*/
class LinkarClient {

	private $linkarClient;

	/*
		Constructor: __constructor
			Initializes a new instance of the LinkarClient class.
			
		Arguments:
			$receiveTimeout - (number) Maximum time in seconds that the client will wait for a response from the server. Default = 0 to wait indefinitely. When the receiveTimeout argument is omitted in any operation, the value set here will be applied.
	*/
	public function __construct($receiveTimeout) {
		$this->linkarClient = new LinkarClientBase($receiveTimeout);
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
		return $this->linkarClient->Login($credentialOptions, $customVars, $receiveTimeout);
	}

	/*
		Function: Logout
			Closes the communication with the server, that previously has been opened with a Login function.
		
		Arguments:
			$customVars - (string) Free text sent to the database allows management of additional behaviours in SUB.LK.MAIN.CONTROL.CUSTOM, which is called when this parameter is set.</param>
			$receiveTimeout - (number) Maximum time in seconds that the client will wait for a response from the server. Default = 0 to wait indefinitely.</param>

		Remarks:
			Logout is actually a "virtual" operation which disposes the current Client Session ID. No DBMS logout is performed.
	*/
	public function Logout($customVars = "", $receiveTimeout = 0) {
		return $this->linkarClient->Logout($customVars, $receiveTimeout);
}

	/*
		Function: LkSchemas
			Returns a list of all the Schemas defined in Linkar Schemas, or the EntryPoint account data files, with TABLE output format.
		
		Arguments:
			$lkSchemasOptions - (<LkSchemasOptions>) This object defines the different options in base of the asked Schema Type: LKSCHEMAS, SQLMODE o DICTIONARIES.
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

		Example:
		--- Code
		<?php
			include_once 'Linkar/Linkar.php';
			include_once 'Linkar.Functions.Persistent.TABLE/LinkarClient.php';
		
			function MyLkSchemas()
			{
				try
				{
					$client = new LinkarClient();
					$credentials = new CredentialOptions("127.0.0.1", "EPNAME", 11300, "admin", "admin");
					$client->Login(credentials);

					$result = $client->LkSchemas();

					$client->Logout();
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
	public function LkSchemas($lkSchemasOptions = null,
		$customVars = "", $receiveTimeout = 0) {
		return $this->linkarClient->LkSchemas($lkSchemasOptions,
			DATAFORMATSCH_TYPE::TABLE, $customVars, $receiveTimeout);
	}
	
	/*
		Function: LkProperties
			Returns the Schema properties list defined in Linkar Schemas or the file dictionaries, with TABLE output format.
		
		Arguments:
			$filename - (string) File name to LkProperties.
			$lkPropertiesOptions - (<LkPropertiesOptions>) This object defines the different options in base of the asked Schema Type: LKSCHEMAS, SQLMODE o DICTIONARIES.
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

		Example:
		--- Code
		<?php
			include_once 'Linkar/Linkar.php';
			include_once 'Linkar.Functions.Persistent.TABLE/LinkarClient.php';
		
			function MyLkProperties()
			{
				try
				{
					$client = new LinkarClient();
					$credentials = new CredentialOptions("127.0.0.1", "EPNAME", 11300, "admin", "admin");
					$client->Login(credentials);

					$result = $client->LkProperties("LK.CUSTOMERS");

					$client->Logout();
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
	public function LkProperties($filename, $lkPropertiesOptions = null,
		$customVars = "", $receiveTimeout = 0) {
		return $this->linkarClient->LkProperties($filename, $lkPropertiesOptions,
			DATAFORMATSCH_TYPE::TABLE, $customVars, $receiveTimeout);
	}
	
	/*
		Function: GetTable
			Returns a query result in a table format.
		
		Arguments:
			$filename - (string) File or table name defined in Linkar Schemas. Table notation is: MainTable[.MVTable[.SVTable]]
			$selectClause - (string) Statement fragment specifies the selection condition. For example WITH CUSTOMER = '1'
			$dictClause - (string) Space-delimited list of dictionaries to read. If this list is not set, all fields are returned. For example CUSTOMER DATE ITEM. In NONE mode you may use the format LKFLDx where x is the attribute number.
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

		Example:
		--- Code
		<?php
			include_once 'Linkar/Linkar.php';
			include_once 'Linkar.Functions.Persistent.TABLE/LinkarClient.php';
		
			function MyGetTable()
			{
				try
				{
					$client = new LinkarClient();
					$credentials = new CredentialOptions("127.0.0.1", "EPNAME", 11300, "admin", "admin");
					$client->Login(credentials);

					$result = $client->GetTable("LK.CUSTOMERS");

					$client->Logout();
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
	public function GetTable($filename, $selectClause = "", $dictClause = "", $sortClause = "", $tableOptions = null,
		$customVars = "", $receiveTimeout = 0) {
		return $this->linkarClient->GetTable($filename, $selectClause, $dictClause, $sortClause, $tableOptions,
			$customVars, $receiveTimeout);
	}

}
?>
