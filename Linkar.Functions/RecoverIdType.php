<?php
include_once realpath( __DIR__ . '/DBMV_Mark.php');

/*
	Class: RecoverIdType
		Object that works as an argument in <DeleteOptions> function and defines the technique for recovering deleted item IDs.

	Property: $ActiveTypeLinkar
		boolean
		
		Indicates that the RecoverIdType *Linkar* is enabled.

	Property: $ActiveTypeCustom
		boolean
		
		Indicates that the RecoverIdType *Custom* is enabled.

	Property: $Prefix
		string
		
		(For RecoverIdType *Linkar*)
		A prefix to the code.

	Property: $Separator
		string
		
		(For RecoverIdType *Linkar*)
		The separator between the prefix and the code.
		The allowed separators list is: ! " # $ % & ' ( ) * + , - . / : ; < = > ? @ [ \ ] ^ _ ` { | } ~.		
*/
class RecoverIdType {

    public $ActiveTypeLinkar=false;
    public $Prefix = "";
    public $Separator = "";

    public $ActiveTypeCustom=false;

    // NONE
	/*
		Constructor: __constructor
			No id recovery technique will be used.
	*/
    public function __construct() {        
    }
    
    // LINKAR
	/*
		Function: Linkar
			Use this constructor for recovering items ids that used *Linkar* <RecordIdType>.
		
		Arguments:
			$prefix - (string) Adding a prefix to the item ID.
			$separator - (string) The separator between the prefix and the ID. Valid delimiters: ! " # $ % & ' ( ) * + , - . / : ; < = > ? @ [ \ ] ^ _ ` { | } ~

	*/
    public function Linkar($prefix, $separator) {
        $this->ActiveTypeLinkar = true;
        $this->Prefix = $prefix;
        $this->Separator = $separator;
    }
    
    // CUSTOM
	/*
		Function: Custom
			Use this function to recovering items ids that used *Custom* RecordIdType.
	*/
    public function Custom(){
        $this->ActiveTypeCustom = true;
    }

	/*
		Function: GetStringAM
			Composes the RecoverIdType options string for processing through LinkarSERVER to the database.
			
		Returns:
			string
			
			The string ready to be used by LinkarSERVER.
	*/
	public function GetStringAM() {
        if ($this->ActiveTypeLinkar)
            $opLinkar = "1" . DBMV_Mark::VM_str . $this->Prefix . DBMV_Mark::VM_str . $this->Separator;
        else
            $opLinkar = "0" . DBMV_Mark::VM_str . "" . DBMV_Mark::VM_str . "";

        if ($this->ActiveTypeCustom)
            $opCustom = "1";
        else
            $opCustom = "0";

        $str = $opLinkar . DBMV_Mark::AM_str . $opCustom;
        return $str;
    }	
}
?>
