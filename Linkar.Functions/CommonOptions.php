<?php
include_once realpath( __DIR__ . '/DBMV_Mark.php');

/*
    class: CommonOptions
        Auxiliary class with the common options for <ReadOptions>, <SelectOptions> and <ReadAfterCommonOptions> classes

        Property: $Calculated 
            boolean
            
            Returns the resulting values from the calculated dictionaries.

        Property: $Conversion
            boolean
            
            Executes the defined conversions in the dictionaries before returning.

        Property: $FormatSpec
            boolean
            
            Executes the defined formats in the dictionaries before returning.

        Property: $OriginalRecords
            boolean
            
            Returns a copy of the records in MV format.
*/
class CommonOptions {
    /*
        Constructor: __constructor
            Initializes a new instance of the CommonOptions class.
        
        Arguments:
            $calculated - (boolean) Return the resulting values from the calculated dictionaries.
            $conversion - (boolean) Execute the defined conversions in the dictionaries before returning.
            $formatSpec - (boolean) Execute the defined formats in the dictionaries before returning.
            $originalRecords - (boolean) Return a copy of the records in MV format.
    */
    public $Calculated;
    public $Conversion;
    public $FormatSpec;
    public $OriginalRecords;

    public function __construct($calculated = false, $conversion = false, $formatSpec = false, $originalRecords = false) {
        $this->Calculated = $calculated;
        $this->Conversion = $conversion;
        $this->FormatSpec = $formatSpec;
        $this->OriginalRecords = $originalRecords;
    }    
    
     /*
        Function: GetStringAM
            Composes the CommonOptions options string for use with <ReadOptions>, <SelectOptions> and <ReadAfterCommonOptions> classes.
            
        Return:
            string
            
            The string ready to be used by <ReadOptions>, <SelectOptions> and <ReadAfterCommonOptions> classes.
    */
    public function GetStringAM()
    {
        $str = ($this->Calculated ? "1" : "0") . DBMV_Mark::AM_str .
                     "" . DBMV_Mark::AM_str . 
                     ($this->Conversion ? "1" : "0") . DBMV_Mark::AM_str .
                     ($this->FormatSpec ? "1" : "0") . DBMV_Mark::AM_str .
                     ($this->OriginalRecords ? "1" : "0");
        return $str;
    }
}
?>
