<?php
include_once realpath( __DIR__ . '/../Linkar.Functions/LinkarFunctions.php');  

class LinkarCLib
{
    private $lib_linkar;
    public function __construct()
    {
        // You must change this variable with the correct path where are the C Framework libraries
        $libpath = getcwd() . "/Linkar";

        if(PHP_INT_SIZE===8){$arch='x64';}else{$arch='x32';}
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $path_linkar = $libpath . '/DLL/' . $arch . '/' . 'Linkar.dll';
        } else {
            $path_linkar = $libpath . '/linux.so/' . $arch . '/' . 'libLinkar.so';
        }
        $this->lib_linkar= FFI::cdef(
            "
            char* LkExecutePersistentOperation(char**, char**, uint8_t, char*, uint8_t, uint8_t, uint32_t);
            char* LkExecuteDirectOperation(char**, char*, uint8_t, char*, uint8_t, uint8_t, uint32_t);
            void LkFreeMemory(char*);
            ", 
            $path_linkar
        );
    }

    public function linkar(){return $this->lib_linkar;}
}

/*
    Class: CredentialOptions
        Contains the necessary information to connect to the Database.

        Property: $Host (string)
            Address or hostname where Linkar Server is listening.
            
        Property: $EntryPoint (string)
            The EntryPoint Name defined in Linkar Server.
            
        Property: Port (number)
            Port number where the EntryPoint keeps listening.
            
        Property: $Username (string)
            Linkar Server username.
            
        Property: $Password (string)
            Password of the Linkar Server user.
            
        Property: $Language (string)
            Used to make the database routines know in which language they must answer. The Error messages coming from the Database are in English by default, but you can customize

        Property: $FreeText (string)
            Free text that will appear in the Linkar MANAGER to identify in an easy way who is making the petition. For example if the call is made from a ERP, here we can write "MyERP".
            
        Property: $PluginId (string)
            Internal ID for plugin to enable its use in Linkar Server. Used by Plugin developers.
*/
class CredentialOptions {
    
    public $Host;
    public $EntryPoint;
    public $Port;
    public $Username;
    public $Password;
    public $Language = "";
    public $FreeText = "";
    public $PluginId = "0";

    /*
        Constructor: __constructor
            Initializes a new instance of the CredentialOptions class
            
        Arguments:
            $host - Address or hostname where Linkar Server is listening.
            $entrypoint - The EntryPoint Name defined in Linkar Server.
            $port - Port number where the EntryPoint keeps listening.
            $username - Linkar Server username.
            $password - Password of the Linkar Server user.
            $language - Used to make the database routines know in which language they must answer. The Error messages coming from the Database are in English by default, but you can customize.
            $freeText - Free text that will appear in the Linkar MANAGER to identify in an easy way who is making the petition. For example if the call is made from a ERP, here we can write "MyERP".
            $pluginId - Internal ID for plugin to enable its use in Linkar Server. Used by Plugin developers.
    */
    public function __construct($host, $entrypoint, $port, $username, $password, $language = "", $freeText = "", $pluginId = "") {
        $this->Host = $host;
        $this->EntryPoint = $entrypoint;
        $this->Port = $port;
        $this->Username = $username;
        $this->Password = $password;
        $this->Language = $language;
        $this->FreeText = $freeText;
        $this->PluginId = $pluginId;
    }

    public function toString() {
        $separator = chr(0x1C);
        return $this->Host . ASCII_Chars::FS_str .
            $this->EntryPoint . ASCII_Chars::FS_str .
            $this->Port . ASCII_Chars::FS_str .
            $this->Username . ASCII_Chars::FS_str .
            $this->Password . ASCII_Chars::FS_str .
            $this->Language . ASCII_Chars::FS_str .
            $this->FreeText . ASCII_Chars::FS_str .
            $this->PluginId;
    }
}

/*
    Class: ConnectionInfo
        Contains the necessary information to stablished a permanent session with LinkarSERVER. Used by Login operation and Permanent operations.
*/
class ConnectionInfo {
    
    public $SessionId;
    public $LkConnectionId;
    public $PublicKey;
    public $CredentialOptions;
    public $ReceiveTimeout=0;

    /*
        Constructor: __constructor
            Initializes a new instance of the CredentialOptions class
        
        Arguments:
            $sessionId - A unique Identifier for the stablished session in LinkarSERVER. This value is set after Login operation.
            $lkConnectionId - Internal LinkarSERVER ID to keep the session. This value is set after Login operation.
            $publicKey - The public key used to encrypt transmission data between LinkarCLIENT and LinkarSERVER. This value is set after Login operation.
            $crdOptions - The CredentialOptions object with the necessary information to connect to the LinkarSERVER.

    */
    public function __construct($sessionId, $lkConnectionId, $publicKey, $crdOptions) {
        $this->SessionId = $sessionId;
        $this->LkConnectionId = $lkConnectionId;
        $this->PublicKey = $publicKey;
        $this->CredentialOptions = $crdOptions;
        $this->ReceiveTimeout = 0;
        $this->PointerFFI = null;
    }

    public function fromString($str) {
        $arr = explode(ASCII_Chars::FS_str, $str);
        $this->CredentialOptions = new CredentialOptions($arr[0], $arr[1], $arr[2], $arr[3], $arr[4], $arr[5], $arr[6]);
        $this->SessionId = $arr[8];
        $this->LkConnectionId = $arr[9];
        $this->PublicKey = $arr[10];
        $this->ReceiveTimeout = $arr[11];
    }

    public function toString() {
        $separator = chr(0x1C);
        return $this->CredentialOptions->toString() . ASCII_Chars::FS_str .
            $this->SessionId . ASCII_Chars::FS_str .
            $this->LkConnectionId . ASCII_Chars::FS_str .
            $this->PublicKey . ASCII_Chars::FS_str .
            $this->ReceiveTimeout;
    }
}

/*
    Class: Linkar
        Class with two static functions to perform Direct and Persistent operation with Linkar Server
*/
class Linkar {

    private $lib_linkar;

    function __construct(){
        $linkar = new LinkarCLib();
        $this->lib_linkar=$linkar->linkar();
    }
    
    public function LkCloneAndFree($c_value, $free = true) {
        $value=FFI::string($c_value);
        if($free)
            $this->lib_linkar->LkFreeMemory($c_value);
        return $value;
    }

    public function LkCreateConnectionInfo($connectionInfo)
    {
        $connectionInfoStr = $connectionInfo->toString();
        $bufferLength = strlen($connectionInfoStr);
        $type = "char[" . ($bufferLength + 1) . "]";
        $connectionInfoPointer = FFI::new($type, false); // This memory block must be release by PHP with FFI::free
        FFI::memset($connectionInfoPointer, 0, ($bufferLength + 1));
        FFI::memcpy($connectionInfoPointer, $connectionInfoStr, $bufferLength);
        return FFI::addr($connectionInfoPointer[0]);
    }

    /* Linkar */

    /*
        Function:   LkExecuteDirectOperation
            Execute "direct operations". These operations don't stablish a permanent session. Once the operations is finished, the session is closed.
        
        Arguments:
            $credentialOptions - (<CredentialOptions>) The credentials for access to LinkarSERVER.
            $operationCode - (<OPERATION_CODE>) Code of the operation to be performed.
            $operationArgs - (string) Specific arguments of every operation.
            $inputDataFormat - (number <DATAFORMAT_TYPE>, <DATAFORMATCRU_TYPE> or <DATAFORMATSCH_TYPE>) Format of the input data.
            $outputDataFormat - (number <DATAFORMAT_TYPE>, <DATAFORMATCRU_TYPE> or <DATAFORMATSCH_TYPE>) Format of the output data.
            $receiveTimeout - (number) Maximum time in seconds to wait the response from LinkarSERVER. A value less or equal to 0, wait for response indefinitely.
            
        Returns:
            Complex string with the result of the operation.
    */
    public function LkExecuteDirectOperation($credentialOptions, $operationCode, $operationArgs, $inputDataFormat, $outputDataFormat, $receiveTimeout) {
        $c_error=FFI::new('char*');
        
        $credentialOptionsStr = $credentialOptions->toString();        
        $c_result = $this->lib_linkar->LkExecuteDirectOperation(FFI::addr($c_error), $credentialOptionsStr, $operationCode, $operationArgs, $inputDataFormat, $outputDataFormat, $receiveTimeout);
        
        if(!FFI::isNull($c_error)) {
            $error = $this->LkCloneAndFree($c_error);
            if(!is_null($error) && $error != "") {
                throw new Exception($error);
            }
        }
        else {
            $result = $this->LkCloneAndFree($c_result);
            return $result;        
        }
    }

    /*
        Function:   LkExecutePersistentOperation
            Execute "persistent operations". These operations required that a session will be stablished previously with Login operation.
        
        Arguments:
            $connectionInfo - (<ConnectionInfo>) Contains the data necessary to access an established LinkarSERVER session.
            $operationCode - (<OPERATION_CODE>) Code of the operation to be performed.
            $operationArgs - (string) Specific arguments of every operation.
            $inputDataFormat - (number <DATAFORMAT_TYPE>, <DATAFORMATCRU_TYPE> or <DATAFORMATSCH_TYPE>) Format of the input data.
            $outputDataFormat - (number <DATAFORMAT_TYPE>, <DATAFORMATCRU_TYPE> or <DATAFORMATSCH_TYPE>) Format of the output data.
            $receiveTimeout - (number) Maximum time in seconds to wait the response from LinkarSERVER. A value less or equal to 0, wait for response indefinitely.
            
        Returns:
            Complex string with the result of the operation.
    */
    public function LkExecutePersistentOperation($connectionInfo, $operationCode, $operationArgs, $inputDataFormat, $outputDataFormat, $receiveTimeout) {
        $c_error=FFI::new('char*');
        
        if($receiveTimeout <= 0)  {
            if($connectionInfo->ReceiveTimeout > 0)
                $receiveTimeout = $connectionInfo->ReceiveTimeout;
        }

        $connectionInfoPointerFFI = $this->LkCreateConnectionInfo($connectionInfo);
        if($operationCode == 0x01) {
            // LOGIN operation wiil return a new ConnectionInfo with more data, so after calling C function,
            // the original ConnectionInfo must be release with FFI::free
            $connectionInfoPointerFFI_original = clone $connectionInfoPointerFFI;
        }

        $c_result = $this->lib_linkar->LkExecutePersistentOperation(FFI::addr($c_error), FFI::addr($connectionInfoPointerFFI), $operationCode, $operationArgs, $inputDataFormat, $outputDataFormat, $receiveTimeout);

        if(!FFI::isNull($c_error)){
            FFI::free($connectionInfoPointerFFI);
            $error = $this->LkCloneAndFree($c_error);
            if(!is_null($error) && $error != "") {
                throw new Exception($error);
            }
        }
        else {
            if($operationCode == 0x01) { // LOGIN Operation
                FFI::free($connectionInfoPointerFFI_original);
                $str_connectionInfo = $this->LkCloneAndFree($connectionInfoPointerFFI);
                //Update the ConnectionInfo object from the string returned with new data
                $connectionInfo->fromString($str_connectionInfo);
            } else {
                //In other operations, ConnectionInfo object is not changed, so just free PHP memory
                FFI::free($connectionInfoPointerFFI);
            }
            
            $result = $this->LkCloneAndFree($c_result);
            return $result;
        }
    }
}
?>