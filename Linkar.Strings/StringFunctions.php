<?php

/*
    Class: StringFunctions
        Set of functions that help manipulate the character strings that are used as input and output data in MV type operations 
*/

class StringFunctions {
    /*
        Constant: TOTAL_RECORDS_KEY
        The tag value for "TOTAL_RECORDS_KEY" in Database operation responses of MV type.
    */
    const TOTAL_RECORDS_KEY = "TOTAL_RECORDS";

    /*
        Constant: TOTAL_RECORDS
        The tag value for "TOTAL_RECORDS" in Database operation responses of MV type.
    */
    const RECORD_IDS_KEY = "RECORD_ID";

    /*
        Constant: RECORDS_KEY
        The tag value for "RECORDS_KEY" in Database operation responses of MV type.
    */
    const RECORDS_KEY = "RECORD";

    /*
        Constant: CALCULATED_KEY
        The tag value for "CALCULATED_KEY" in Database operation responses of MV type.
    */
    const CALCULATED_KEY = "CALCULATED";

    /*
        Constant: RECORD_DICTS_KEY
        The tag value for "RECORD_DICTS_KEY" in Database operation responses of MV type.
    */
    const RECORD_DICTS_KEY = "RECORD_DICTS";

    /*
        Constant: RECORD_ID_DICTS_KEY
        The tag value for "RECORD_ID_DICTS_KEY" in Database operation responses of MV type.
    */
    const RECORD_ID_DICTS_KEY = "RECORD_ID_DICTS";

    /*
        Constant: CALCULATED_DICTS_KEY
        The tag value for "CALCULATED_DICTS_KEY" in Database operation responses of MV type.
    */
    const CALCULATED_DICTS_KEY = "CALCULATED_DICTS";

    /*
        Constant: ARGUMENTS_KEY
        The tag value for "ARGUMENTS_KEY" in Database operation responses of MV type.
    */
    const ARGUMENTS_KEY = "ARGUMENTS";

    /*
        Constant: ORIGINAL_RECORDS_KEY
        The tag value for "ORIGINAL_RECORDS_KEY" in Database operation responses of MV type.
    */
    const ORIGINAL_RECORDS_KEY = "ORIGINALRECORD";

    /*
        Constant: FORMAT
        The tag value for "FORMAT" in Database operation responses of MV type.
    */
    const FORMAT_KEY = "FORMAT";

    /*
        Constant: CONVERSION
        The tag value for "CONVERSION" in Database operation responses of MV type.
    */
    const CONVERSION_KEY = "CONVERSION";

    /*
        Constant: CAPTURING
        The tag value for "CAPTURING" in Database operation responses of MV type.
    */
    const CAPTURING_KEY = "CAPTURING";

    /*
        Constant: RETURNING
        The tag value for "RETURNING" in Database operation responses of MV type.
    */
    const RETURNING_KEY = "RETURNING";

    /*
        Constant: ROWHEADERS
        The tag value for "ROWHEADERS" in Database operation responses of MV type.
    */
    const ROWHEADERS_KEY = "ROWHEADERS";

    /*
        Constant: ROWPROPERTIES
        The tag value for "ROWPROPERTIES" in Database operation responses of MV type.
    */
    const ROWPROPERTIES_KEY = "ROWPROPERTIES";

    /*
        Constant: ROWPROPERTIES
        The tag value for "ROWPROPERTIES" in Database operation responses of MV type.
    */
    const ERRORS_KEY = "ERRORS";

    /*
        Constant: DC4
        ASCII character used as separator of the arguments of a subroutine.
    */
    const DC4 = "\x014";

    /*
        Constant: DC4_str
        ASCII character used as separator of the arguments of a subroutine.
    */
    const DC4_str = "\x14";

    /*
        Constant: FS
        When the responses of the operations are of MV type, this character is used to separate the header (the ThisList property in LkData) from the data.
    */
    const FS = "\x1C";

    /*
        Constant: FS_str
        When the responses of the operations are of MV type, this character is used to separate the header (the ThisList property in LkData) from the data.
    */
    const FS_str = "\x1C";

    /*
        Constant: RS
        ASCII character used by Linkar as separator of items in lists. For example, list of records.
    */
    const RS = "\x1E";

    /*
        Constant: RS_str
        ASCII character used by Linkar as separator of items in lists. For example, list of records.
    */
    const RS_str = "\x1E";

    /*
        Constant: AM_str
        Character ASCII 253. VM Multi-value mark.
    */
    const AM_str = "\xFE";

    /*
        Constant: VM_str
        Character ASCII 253. VM Multi-value mark.
    */
    const VM_str = "\xFD";

    public function __construct() {
    }    
    
    /*
        Function: ExtractTotalRecords
            Looks for the TOTAL_RECORDS_KEY tag inside "<lkString>", and extracts its value.
        
        Arguments:
            $lkString - (string) A string obtained as a result of executing an operation.
        
        Return:
            number
        
        The value of TOTAL_RECORDS_KEY tag.
    */
    static function ExtractTotalRecords($lkString) {
        $block = StringFunctions::GetData($lkString, StringFunctions::TOTAL_RECORDS_KEY, StringFunctions::FS_str, StringFunctions::AM_str);
        try {  
            $result = intval($block);  
            return $result;  
        } catch (Exception $e) {  
            return 0;  
        }  
    }

    /*
        Function: ExtractRecordIds
            Looks for the RECORD_IDS_KEY tag inside "<lkString>", and extracts its value.
        
        Arguments:
            $lkString - (string) A string obtained as a result of executing an operation.
        
        Return:
            string
        
            The value of RECORD_IDS_KEY tag.
    */
    static function ExtractRecordIds($lkString) {
        $valueTag = StringFunctions::GetData($lkString, StringFunctions::RECORD_IDS_KEY, StringFunctions::FS_str, StringFunctions::AM_str);
        return StringFunctions::splitArray($valueTag, StringFunctions::RS_str);
    }

    /*
        Function: ExtractRecords
            Looks for the RECORDS_KEY tag inside "<lkString>", and extracts its value.
        
        Arguments:
            $lkString - (string) A string obtained as a result of executing an operation.
        
        Return:
            string
        
            The value of RECORDS_KEY tag.
    */
    static function ExtractRecords($lkString) {
        $valueTag = StringFunctions::GetData($lkString, StringFunctions::RECORDS_KEY, StringFunctions::FS_str, StringFunctions::AM_str);
        return StringFunctions::splitArray($valueTag, StringFunctions::RS_str);
    }

    /*
        Function: ExtractErrors
            Looks for the ERRORS_KEY tag inside "<lkString>", and extracts its value.
        
        Arguments:
            $lkString - (string) A string obtained as a result of executing an operation.
        
        Return:
            string
        
            The value of ERRORS_KEY tag.
    */
    static function ExtractErrors($lkString) {
        $valueTag = StringFunctions::GetData($lkString, StringFunctions::ERRORS_KEY, StringFunctions::FS_str, StringFunctions::AM_str);
        return StringFunctions::splitArray($valueTag, StringFunctions::AM_str);
    }

    /*
        Function: FormatError
            This function formats the message error by split into Error Code and Error Message.
        
        Arguments:
            $error - (string) The value of ERRORS_KEY tag.
        
        Return:
            string
        
            The error formated.
    */
    static function FormatError($error) {
        $result = $error;
        $items = explode(StringFunctions::VM_str, $error);
        if(count($items) == 2)
            $result = "ERROR CODE: " + $items[0] + " ERROR MESSAGE: " + $items[1];
    
        return $result;
    }

    /*
        Function: ExtractRecordsCalculated
            Looks for the CALCULATED_KEY tag inside "<lkString>", and extracts its value.
        
        Arguments:
            $lkString - (string) A string obtained as a result of executing an operation.
        
        Return:
            string
        
            The value of CALCULATED_KEY tag.
    */
    static function ExtractRecordsCalculated($lkString) {
        $valueTag = StringFunctions::GetData($lkString, StringFunctions::CALCULATED_KEY, StringFunctions::FS_str, StringFunctions::AM_str);
        return StringFunctions::splitArray($valueTag, StringFunctions::RS_str);
    }

    /*
        Function: ExtractRecordsDicts
            Looks for the RECORD_DICTS_KEY tag inside "<lkString>", and extracts its value.
        
        Arguments:
            $lkString - (string) A string obtained as a result of executing an operation.
        
        Return:
            string
        
            The value of RECORD_DICTS_KEY tag.
    */
    static function ExtractRecordsDicts($lkString) {
        $valueTag = StringFunctions::GetData($lkString, StringFunctions::RECORD_DICTS_KEY, StringFunctions::FS_str, StringFunctions::AM_str);
        return StringFunctions::splitArray($valueTag, StringFunctions::AM_str);
    }

    /*
        Function: ExtractRecordsCalculatedDicts
            Looks for the CALCULATED_DICTS_KEY tag inside "<lkString>", and extracts its value.
        
        Arguments:
            $lkString - (string) A string obtained as a result of executing an operation.
        
        Return:
            string
        
            The value of CALCULATED_DICTS_KEY tag.
    */
    static function ExtractRecordsCalculatedDicts($lkString) {
        $valueTag = StringFunctions::GetData($lkString, StringFunctions::CALCULATED_DICTS_KEY, StringFunctions::FS_str, StringFunctions::AM_str);
        return StringFunctions::splitArray($valueTag, StringFunctions::AM_str);
    }

    /*
        Function: ExtractRecordsIdDicts
            Looks for the RECORD_ID_DICTS_KEY tag inside "<lkString>", and extracts its value.
        
        Arguments:
            $lkString - (string) A string obtained as a result of executing an operation.
        
        Return:
            string
        
            The value of RECORD_ID_DICTS_KEY tag.
    */
    static function ExtractRecordsIdDicts($lkString) {
        $valueTag = StringFunctions::GetData($lkString, StringFunctions::RECORD_ID_DICTS_KEY, StringFunctions::FS_str, StringFunctions::AM_str);
        return StringFunctions::splitArray($valueTag, StringFunctions::AM_str);
    }

    /*
        Function: ExtractOriginalRecords
            Looks for the ORIGINAL_RECORDS_KEY tag inside "<lkString>", and extracts its value.
        
        Arguments:
            $lkString - (string) A string obtained as a result of executing an operation.
        
        Return:
            string
        
            The value of ORIGINAL_RECORDS_KEY tag.
    */
    static function ExtractOriginalRecords($lkString) {
        $valueTag = StringFunctions::GetData($lkString, StringFunctions::ORIGINAL_RECORDS_KEY, StringFunctions::FS_str, StringFunctions::AM_str);
        return StringFunctions::splitArray($valueTag, StringFunctions::RS_str);
    }

    /*
        Function: ExtractDictionaries
            Looks for the RECORDS_KEY tag inside "<lkString>", and extracts its value.
        
        Arguments:
            lkString - (string) A string obtained as a result of executing an operation.
        
        Return:
            string
        
            The value of RECORDS_KEY tag.
    */
    static function ExtractDictionaries($lkString) {
        $valueTag = StringFunctions::GetData($lkString, StringFunctions::RECORDS_KEY, StringFunctions::FS_str, StringFunctions::AM_str);
        return StringFunctions::splitArray($valueTag, StringFunctions::RS_str);
    }

    /*
        Function: ExtractConversion
            Looks for the CONVERSION_KEY tag inside "<lkString>", and extracts its value.
        
        Arguments:
            $lkString - (string) A string obtained as a result of executing an operation.
        
        Return:
            string
        
            The value of CONVERSION_KEY tag.
    */
    static function ExtractConversion($lkString) {
        return StringFunctions::GetData($lkString, StringFunctions::CONVERSION_KEY, StringFunctions::FS_str, StringFunctions::AM_str);
    }

    /*
        Function: ExtractFormat
            Looks for the FORMAT_KEY tag inside "<lkString>", and extracts its value.
        
        Arguments:
            $lkString - (string) A string obtained as a result of executing an operation.
        
        Return:
            string
        
            The value of FORMAT_KEY tag.
    */
    static function ExtractFormat($lkString) {
        return StringFunctions::GetData($lkString, StringFunctions::FORMAT_KEY, StringFunctions::FS_str, StringFunctions::AM_str);
    }

    /*
        Function: ExtractCapturing
            Looks for the CAPTURING_KEY tag inside "<lkString>", and extracts its value.
        
        Arguments:
            $lkString - (string) A string obtained as a result of executing an operation.
        
        Return:
            string
        
            The value of CAPTURING_KEY tag.
    */
    static function ExtractCapturing($lkString) {
        return StringFunctions::GetData($lkString, StringFunctions::CAPTURING_KEY, StringFunctions::FS_str, StringFunctions::AM_str);
    }

    /*
        Function: ExtractReturning
            Looks for the RETURNING_KEY tag inside "<lkString>", and extracts its value.
        
        Arguments:
            $lkString - (string) A string obtained as a result of executing an operation.
        
        Return:
            string
        
            The value of RETURNING_KEY tag.
    */
    static function ExtractReturning($lkString) {
        return StringFunctions::GetData($lkString, StringFunctions::RETURNING_KEY, StringFunctions::FS_str, StringFunctions::AM_str);
    }

    /*
        Function: ExtractSubroutineArgs
            Looks for the ARGUMENTS_KEY tag inside "<lkString>", and extracts its value.
        
        Arguments:
            $lkString - (string) A string obtained as a result of executing an operation.
        
        Return:
            string
        
            The value of ARGUMENTS_KEY tag.
    */
    static function ExtractSubroutineArgs($lkString) {
        $args = StringFunctions::GetData($lkString, StringFunctions::ARGUMENTS_KEY, StringFunctions::FS_str, StringFunctions::AM_str);
        return StringFunctions::splitArray($args, StringFunctions::DC4_str);
    }

    /*
        Function: ExtractRowProperties
            Looks for the ROWPROPERTIES_KEY tag inside "<lkString>", and extracts its value.
        
        Arguments:
            $lkString - (string) A string obtained as a result of executing an operation.
        
        Return:
            string
        
            The value of ROWPROPERTIES_KEY tag.
    */
    static function ExtractRowProperties($lkString) {
        $rowProperties = StringFunctions::GetData($lkString, StringFunctions::ROWPROPERTIES_KEY, StringFunctions::FS_str, StringFunctions::AM_str);
        return StringFunctions::splitArray($rowProperties, StringFunctions::AM_str);
    }

    /*
        Function: ExtractRowHeaders
            Looks for the ROWHEADERS_KEY tag inside "<lkString>", and extracts its value.
        
        Arguments:
            $lkString - (string) A string obtained as a result of executing an operation.
        
        Return:
            string
        
            The value of ROWHEADERS_KEY tag.
    */
    static function ExtractRowHeaders($lkString) {
        $rowHeaders = StringFunctions::GetData($lkString, StringFunctions::ROWHEADERS_KEY, StringFunctions::FS_str, StringFunctions::AM_str);
        return StringFunctions::splitArray($rowHeaders, StringFunctions::AM_str);
    }

    /*
        Function: GetData
            Looks for the "tag" inside the "lkString", and extracts its value.
        
        Arguments:
            $lkString - (string) A string obtained as a result of executing an operation.
            $tag - (string) The tag to looking for
            $delimiter - (string) Delimiter char of every main items in "lkString".
            $delimiterThisList - (string) Delimiter char inside the first item of "lkString". The first item of "lkString" is always the header tags (THISLIST).
        
        Return:
            string
        
            The value of tag.
    */
    static function GetData($lkString, $tag, $delimiter, $delimiterThisList) {       
        $block = "";
        $parts = explode($delimiter, $lkString);
        if (count($parts) >= 1)
        {
            $headersList = explode($delimiterThisList, $parts[0]);
            for ($i = 1; $i < count($headersList); $i++)
            {
                if (strtoupper($tag) == strtoupper($headersList[$i]))
                {                    
                    $block = $parts[$i];
                    break;
                }
            }
        }
        return $block;
    }

    /*
        Function: splitArray
            Auxiliary function to extract arrays inside a tag value.
        
        Arguments:
            $valueTag - (string) The string to be splitted.
            $delimiter - (string) The char to use for split.
        
        Return:
            string
        
            The array extracted.
    */
    static function splitArray($valueTag, $delimiter) {
        if ((is_null($valueTag) || strlen($valueTag) == 0)){
            return [];
        }
        else{
            return explode($delimiter, $valueTag);
        }
    }

    /* Composition Functions */

    /*
        Function: ComposeRecordIds
            Composes the final string of various "recordsIds". Used by CRUD Operations.
        
        Arguments:
            $recordIds - (array) Array with the "recordIds" to be joined</param>
        
        Return:
            string
        
            The final string of "recordIds" to be used by CRUD Operations.
    */
    static function ComposeRecordIds($recordIds) {
        return  StringFunctions::JoinArray($recordIds, StringFunctions::RS_str);
    }

    /*
        Function: ComposeRecords
            Composes the final string of various "records". Used by CRUD Operations.
        
        Arguments:
            $records - (array) Array with the "records" to be joined.
        
        Return:
            string
        
            The final string of "records" to be used by CRUD Operations.
    */
    static function ComposeRecords($records) {
        return  StringFunctions::JoinArray($records, StringFunctions::RS_str);
    }

    /*
        Function: ComposeOriginalRecords
            Composes the final string of various "originalRecords". Used by CRUD Operations.
        
        Arguments:
            $originalRecords - (array) Array with the "originalRecords" to be joined.
        
        Return:
            string
        
            The final string of "originalRecords" to be used by CRUD Operations.
    */
    static function ComposeOriginalRecords($originalRecords) {
        return  StringFunctions::JoinArray($originalRecords, StringFunctions::RS_str);
    }

    /*
        Function: ComposeDictionaries
            Composes the final string of various "dictionaries". Used by Read and Select Operations.
        
        Arguments:
            $dictionaries - (array) Array with the "dictionaries" to be joined.
        
        Return:
            string
        
            The final string of "dictionaries" to be used by Read and Select Operations.
    */
    static function ComposeDictionaries($dictionaries) {
        return  StringFunctions::JoinArray($dictionaries, " ");
    }

    /*
        Function: ComposeExpressions
            Composes the final string of various "expressions". Used by Format and Conversion Operations.
        
        Arguments:
            $expressions- (array) >Array with the "expressions" to be joined.
        
        Return:
            string
        
            The final string of "expressions" to be used in Format and Conversion Operations.
    */
    static function ComposeExpressions($expressions) {
        return  StringFunctions::JoinArray($expressions, StringFunctions::AM_str);
    }

    /*
        Function: ComposeSubroutineArgs
            Composes the final string of various "arguments" of a subroutine.
        
        Arguments:
            $args- (array) >Array with the "arguments" to be joined.
        
        Return:
            string
        
            The final string to be used in Subroutine Operations.
    */
    static function ComposeSubroutineArgs($args) {
        return  StringFunctions::JoinArray($args, StringFunctions::DC4_str);
    }

    /*
        Function: JoinArray
            Auxiliary function to compose the final string of multiple items using "delimiter" as separation char.
        
        Arguments:
            $items - (array) The "items" to be joined.
            $delimiter - (string) The "delimiter" char between the "items".
        
        Return:
            string
        
            The final string with the different items joined by "delimiter" char.
    */
    static function JoinArray($items, $delimiter) {
        if (!is_null($items) && count($items) > 0){
            return implode($delimiter, $items);
        }
        else{
            return "";
        }
    }

    /*
        Function: ComposeUpdateBuffer
            Compose the fully buffer of the Update Operations with the block of "recordIds", "records" and "originalRecords".
        
        Arguments:
            $recordIds - (string or array) Block of "recordIds". You can obtain this block with <ComposeRecordIds> function or directly using an array.
            $records - (string or array) Block of "records". You can obtain this block with <ComposeRecords> function or directly using an array.
            $originalRecords - (string or array) Block of "originalRecords". You can obtain this block with <ComposeRecords> function or directly using an array.
        
        Return:
            string
        
        The buffer to be used by Update Operations.
    */
    static function ComposeUpdateBuffer($recordIds, $records, $originalRecords = null)  {
        if (is_array($recordIds))
        {
            if ((count($recordIds) != count($records) && is_null($originalRecords)) || (count($recordIds) != count($originalRecords))){
                throw "The arrays must have the same length";
            }
    
            return StringFunctions::ComposeRecordIds($recordIds) . StringFunctions::FS . StringFunctions::ComposeRecords($records) . StringFunctions::FS . ($originalRecords?StringFunctions::ComposeRecords($originalRecords):"");
        }
        else{
            return $recordIds . StringFunctions::FS . $records . StringFunctions::FS . ($originalRecords?$originalRecords:"");
        }
    }

    /*
        Function: ComposeNewBuffer
            Compose the fully buffer of the New Operations with the block of "recordIds" and "records".
        
        Arguments:
            $recordIds - (string or array) Block of "recordIds". You can obtain this block with ComposeRecordIds function or directly using an array.
            $records (string or array) Block of "records". You can obtain this block with ComposeRecords function or directly using an array.
        
        Return:
            string
        
            The buffer to be used by New Operations.
    */
    static function ComposeNewBuffer($recordIds, $records) {
        if (is_array($recordIds))
        {
            if (count($recordIds) != count($records)){
                throw "The arrays must have the same length";
            }
            return StringFunctions::ComposeRecordIds($recordIds) . StringFunctions::FS . StringFunctions::ComposeRecords($records);            
        }
        else{
            return $recordIds . StringFunctions::FS . $records;
        }
    }

    /*
        Function: ComposeDeleteBuffer
            Compose the fully buffer of the Delete Operations with the block of "recordIds" and "originalRecords".
        
        Arguments:
            $recordIds - Block of "recordIds". You can obtain this block with ComposeRecordIds function or directly using an array.
            $originalRecords - Block of "originalRecords". You can obtain this block with ComposeRecords function or directly using an array.
        
        Return:
            string
        
            The buffer to be used by Delete Operations.
    */
    static function ComposeDeleteBuffer($recordIds, $originalRecords = null) {
        if (is_array($recordIds))
        {
            if (!is_null($originalRecords) && count($recordIds) != count($originalRecords)){
                throw "The arrays must have the same length";
            }
    
            return StringFunctions::ComposeRecordIds($recordIds) . StringFunctions::FS . ($originalRecords?StringFunctions::ComposeRecords($originalRecords):"");
        }
        else{
            return $recordIds . StringFunctions::FS . ($originalRecords?$originalRecords:"");
        }
    }
}
?>

