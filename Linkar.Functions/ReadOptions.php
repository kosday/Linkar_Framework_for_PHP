<?php
include_once realpath( __DIR__ . '/CommonOptions.php');

/*
    Class: ReadOptions
        Object that works as an argument in Read function and defines the function options.
                
    Property: $Calculated
        boolean
        
        Returns the resulting values from the calculated dictionaries.

    Property: $Conversion
        boolean
        
        Executes the defined conversions in the dictionaries before returning.

    Property: $FormatSpec
        string
        
        Executes the defined formats in the dictionaries before returning.

    Property: $OriginalRecords
        boolean
        
        Returns a copy of the records in MV format.
*/
class ReadOptions {
    
    /*
        Constructor: __constructor
            Initializes a new instance of the ReadOptions class
            
        Arguments:
            $calculated - (boolean) Return the resulting values from the calculated dictionaries.
            $conversion - (boolean) Execute the defined conversions in the dictionaries before returning.
            $formatSpec - (boolean) Execute the defined formats in the dictionaries before returning.
            $originalRecords - (boolean) Return a copy of the records in MV format.
        
    */
    private $CommonOptions;
    public $Calculated;
    public $Conversion;
    public $FormatSpec;
    public $OriginalRecords;

    public function __construct($calculated = false, $conversion = false, $formatSpec = false, $originalRecords = false) {
        $this->CommonOptions =  new CommonOptions($calculated,$conversion,$formatSpec,$originalRecords);
        $this->Calculated = $calculated;
        $this->Conversion = $conversion;
        $this->FormatSpec = $formatSpec;
        $this->OriginalRecords = $originalRecords;
    }    
    /*
        Function: GetString
            Composes the Read options string for processing through LinkarSERVER to the database.
            
        Returns:
            string
            
            The string ready to be used by LinkarSERVER
    */
    public function GetString() {
        return $this->CommonOptions->GetStringAM();
    }
}
?>
