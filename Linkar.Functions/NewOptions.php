<?php
include_once realpath( __DIR__ . '/CommonOptions.php');
include_once realpath( __DIR__ . '/ReadAfterCommonOptions.php');
include_once realpath( __DIR__ . '/RecordIdType.php');

/*
    Class: NewOptions
        Object that works as an argument in New function and defines the function options.
*/
class NewOptions {

    private $RecordIdType;
    private $ActiveTypeLinkar;
    private $Prefix;
    private $Separator;
    private $FormatSpec;
    private $ActiveTypeRandom;
    private $Numeric;
    private $Length;
    private $ActiveTypeCustom;
    private $ReadAfterCommonOptions;
    private $ReadAfter;
    private $Calculated;
    private $Conversion;
    private $OriginalRecords;
    

    /*
        Constructor: __constructor
            Initializes a new instance of the NewOptions class.
            
        Arguments:
            $recordIdType - (<RecordIdType>) Specifies the technique for generating item IDs. Mandatory if no registration codes are indicated in the New functions.
            $readAfter - (boolean) Reads the record again and returns it after the update. Calculated, conversion, formatSpec and originalRecords will only be applied if this option is true.
            $calculated - (boolean) Return the resulting values from the calculated dictionaries.
            $conversion - (boolean) Execute the defined conversions in the dictionaries before returning.
            $formatSpec - (boolean) Execute the defined formats in the dictionaries before returning.
            $originalRecords- (boolean) Return a copy of the records in MV format.
    */
    public function __construct($recordIdType = null, $readAfter = false, $calculated = false, $conversion = false, $formatSpec = false, $originalRecords = false) {
        if(is_null($recordIdType)){
            $recordIdType=new RecordIdType();
        }
        $this->RecordIdType = $recordIdType;
        $this->ActiveTypeLinkar = $this->RecordIdType->ActiveTypeLinkar;
        $this->Prefix = $this->RecordIdType->Prefix;
        $this->Separator = $this->RecordIdType->Separator;
        $this->FormatSpec = $this->RecordIdType->FormatSpec;
        $this->ActiveTypeRandom = $this->RecordIdType->ActiveTypeLinkar;
        $this->Numeric = $this->RecordIdType->Numeric;
        $this->Length = $this->RecordIdType->Length;
        $this->ActiveTypeCustom = $this->RecordIdType->ActiveTypeCustom;

        if ($readAfter){
            $this->ReadAfterCommonOptions = new ReadAfterCommonOptions($readAfter, $calculated, $conversion, $formatSpec, $originalRecords);
        }
        else{
            $this->ReadAfterCommonOptions = new ReadAfterCommonOptions($readAfter, false, false, false, false);
        }
        
        $this->ReadAfter = $this->ReadAfterCommonOptions->ReadAfter;
        $this->Calculated = $this->ReadAfterCommonOptions->Calculated;
        $this->Conversion = $this->ReadAfterCommonOptions->Conversion;
        $this->FormatSpec = $this->ReadAfterCommonOptions->FormatSpec;
        $this->OriginalRecords = $this->ReadAfterCommonOptions->OriginalRecords;
    }

    /*
        Function: GetString
            Composes the New options string for processing through LinkarSERVER to the database.
            
        Returns:
            string
        
            The string ready to be used by LinkarSERVER.
    */
    public function GetString()
    {
        $str = $this->RecordIdType->GetStringAM() . DBMV_Mark::AM_str .
                    $this->ReadAfterCommonOptions->GetStringAM();
        return $str;
    }
}
?>
