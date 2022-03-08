<?php
include_once realpath( __DIR__ . '/DBMV_Mark.php');

/*
    Class: MvOperations
        This class contains the basic functions to work with multivalue strings. These functions are locally executed.
*/
class MvOperations
{
    /*
        Function: LkDCount
            Counts the delimited substrings inside a string.
            
        Arguments:
            $str - (string) Source string of delimited fields.
            $delimiter - (string) The separator character(s) used to delimit fields in the string.
        
        Returns:
            number
            
            The number of occurrences found.
            
        Example:
        ---Code
        int result = MvOperations.LkCount("CUSTOMER UPDATE 2þADDRESS 2þ444", "þ");
		---
    */
    public static function LkDCount($str, $delimiter) {
        if (is_null($str) || strlen($str) == 0){
            return 0;
        }
        else if (is_null($delimiter) || strlen($delimiter) == 0){
            return strlen($str);
        }
        else
        {
            $parts = explode($delimiter, $str);
            return count($parts);
        }
    }

    /*
        Function: LkCount
            Counts the occurrences of a substring inside a string.
            
        Arguments:
            $str - (string) Source string of delimited fields.
            $delimiter - (string) The separator character(s) used to delimit fields in the string.

        Returns:
            number
            
            The number of occurrences found.

        Example:
        ---Code
        int result = MvOperations.LkDCount("CUSTOMER UPDATE 2þADDRESS 2þ444", "þ");
		---
    */
    public static function LkCount($str, $delimiter) {
        if (is_null($str) || strlen($str) == 0){
            return 0;
        }
        else if (is_null($delimiter) || strlen($delimiter) == 0){
            return strlen($str);
        }
        else
        {
            return self::LkDCount($str, $delimiter) - 1;
        }
    }

    /*
        Function: LkExtract
            Extracts a field, value or subvalue from a dynamic array.
            
        Arguments:
            $str - (string) The source string from which data is extracted.
            $field - (number) The position of the attribute to extract.
            $value - (number) The multivalue position to extract.
            $subvalue - (number) The subvalue position to extract.

        Returns:
            string
            
            A new string with the extracted value.

        Example:
        ---Code
        string result = MvOperations.LkExtract("CUSTOMER UPDATE 2þADDRESS 2þ444", 1);
        ---
        */
    public static function LkExtract($str, $field, $value = 0, $subvalue = 0) {
        $aux = "";
    
        if ($field > 0)
        {
            $parts = explode(DBMV_Mark::AM_str, $str);
            if ($field <= count($parts)){
                $str = $aux = $parts[$field - 1];
            }
        }
    
        if ($value > 0)
        {
            $parts = explode(DBMV_Mark::VM_str, $str);
            if ($value <= count($parts))
                $str = $aux = $parts[$value - 1];
        }
    
        if ($subvalue > 0)
        {
            $parts = explode(DBMV_Mark::SM_str, $str);
            if ($subvalue <= count($parts))
                $aux = $parts[$subvalue - 1];
        }
    
        return $aux;
    }

/*
        Function: LkChange
            Replaces the occurrences of a substring inside a string, by other substring.
            
        Arguments:
            str - (string) The string on which the value is going to change.
            strOld - (string) The value to change. 
            strNew - (string) The new value.
            occurrence - (number) The number of times it will change.
            start - (number) The position from which you are going to start changing values.

        Returns:
            string
            
            A new string with replaced text.

        Example:
        ---Code
        string result = MvOperations.LkChange("CUSTOMER UPDATE 2þADDRESS 2þ444", "UPDATE", "MYTEXT", 1, 1);
        ---*/
    public static function LkChange($str, $strOld, $strNew, $occurrence = 0, $start = 0) {
        if (strlen($str) > 0)
        {    
            if (!$start || $start < 1)
                $start = 1;
            if (!$occurrence || $occurrence < 0)
                $occurrence = 0;
            $index = strpos($str, $strOld);
            if ($index >= 0)
            {
                $subindex = 0;
                $next = true;
                $count = 0;
                while($next)
                {    
                    $subindex = strpos($str, $strOld,$subindex);
                    $count++;
                    if ($subindex == -1 || $count >= $start){
                        $next = false;
                    }
                    else{
                        $subindex = $subindex + strlen($strOld);
                    }
                }
                if ($subindex >= 0)
                {
                    $initstr = substr($str,0,$subindex);
                    $endstr = substr($str,$subindex);
                    $maxocc = count(explode($strOld, $endstr));
                    if ($occurrence && $occurrence > 0)
                    {
                        for ($occ = 0; $occ < $occurrence; $occ++)
                        {
                            if ($occ > $maxocc){
                                break;
                            }
                            $endstr = str_replace($strOld, $strNew, $endstr);
                        }
                    }
                    else
                    {
                        $re = '/'.$strOld.'/';
                        $endstr = preg_replace($re, $strNew, $endstr);
                    }
                    return $initstr . $endstr;
                }
                else
                {
                    return $str;
                }
            }
            else
            {
                return $str;
            }

        }
        else 
            return $str;
    }

/*
        Function: LkReplace
            Replaces a field, value or subvalue from a dynamic array, returning the result.
            
        Arguments:
            str - (string) The string on which you are going to replace a value.
            newVal - (string) New value that will be replaced in the indicated string.
            field - (number) The position of the attribute where you want to replace.
            value - (number) The multivalue position where you want to replace.
            subvalue - (number) The subvalue position where you want to replace.
        
        Returns
            string
            
            A new string with the replaced value.

        Example:
        ---Code
        string result = MvOperations.LkReplace("CUSTOMER UPDATE 2þADDRESS 2þ444", "MYTEXT", 1);
        ---
    */
    public static function LkReplace($str, $newVal, $field, $value = 0, $subvalue = 0) {
        $result = "";
    
        $len = strlen($str);
        $i = 0;
    
        $field--;
        while ($field > 0 && $len > 0)
        {
            if ($str[$i] == DBMV_Mark::AM_str){
                $field--;
            }
            $i++;
            $len--;
        }
        if ($field > 0)
        {
            $createdstr = "";
            $cstr = DBMV_Mark::AM_str;
            for ($index = 0; $index < $field; $index++){
                $createdstr .= $cstr;
            }
            $str .= $createdstr;
            $i += $field;
        }
    
        $value--;
        while ($value > 0 && $len > 0)
        {
            if ($str[$i] == DBMV_Mark::AM_str){
                break;
            }
    
            if ($str[$i] == DBMV_Mark::VM_str){
                $value--;
            }
            $i++;
            $len--;
        }
        if ($value > 0)
        {
            $createdstr = "";
            $cstr = DBMV_Mark::VM_str;
            for ($index = 0; $index < $value; $index++){
                $createdstr .= $cstr;
            }
            $str = substr($str,0,$i) . $createdstr . substr($str,$i);
            $i += $value;
        }
    
        $subvalue--;
        while ($subvalue > 0 && $len > 0)
        {
            if ($str[$i] == DBMV_Mark::VM_str || $str[$i] == DBMV_Mark::AM_str){
                break;
            }
    
            if ($str[$i] == DBMV_Mark::SM_str){
                $subvalue--;
            }
    
            $i++;
            $len--;
        }
        if ($subvalue > 0)
        {
            $createdstr = "";
            $cstr = DBMV_Mark::SM_str;
            for ($index = 0; $index < $subvalue; $index++){
                $createdstr .= $cstr;
            }
            $str = substr($str,0, $i) . $createdstr . substr($str,$i);
            $i += $subvalue;
        }
    
        if ($i >= strlen($str)){
            $result = $str . $newVal;
        }
        else
        {
            $nextAM = strpos($str, DBMV_Mark::AM_str,$i);
            if ($nextAM === false){
                $nextAM = PHP_INT_MAX;
            }
            $nextVM = strpos($str, DBMV_Mark::VM_str,$i);
            if ($nextVM === false){
                $nextVM = PHP_INT_MAX;
            }
            $nextSM = strpos($str, DBMV_Mark::SM_str,$i);
            if ($nextSM === false){
                $nextSM = PHP_INT_MAX;
            }
            $j = min($nextAM, $nextVM, $nextSM);
            if ($j == PHP_INT_MAX){
                $j = strlen($str);
            }
    
            $part1 = substr($str,0, $i);
            $part2 = substr($str,$j);
            $result = $part1 . $newVal . $part2;
        }
    
        return $result;
    }
}
?>
