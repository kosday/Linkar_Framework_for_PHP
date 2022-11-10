<?php
/*
    class: DATAFORMATSCHPROP_TYPE
        Indicates the format of the data returned by LkProperties functions: MV, XML, JSON,JSON_DICT, JSON_SCH, XML_DICT, XML_SCH or TABLE
        
    Defined constants of DATAFORMATSCHPROP_TYPE:
    
            MV - 0x01
            XML - 0x02
            JSON - 0x03
            TABLE - 0x04
            XML_DICT - 0x05
            XML_SCH - 0x06
            JSON_DICT - 0x07
            JSON_SCH - 0x08
*/
abstract class DATAFORMATSCHPROP_TYPE {
    const MV = 0x01;
    const XML = 0x02;
    const JSON = 0x03;
    const TABLE = 0x04;
    const XML_DICT = 0x05;
    const XML_SCH = 0x06;
    const JSON_DICT = 0x07;
    const JSON_SCH = 0x08;
}    
?>