<?php
/*
    class: DATAFORMATCRU_TYPE
        Specify the output formats of Read, Update, New and Select operations.
        Used only by LkRead, LkUpdate, LkNew and LkSelect
        There are 7 possible options: MV, XML, XML_DICT, XML_SCH, JSON, JSON_DICT and JSON_SCH.
        
    Defined constants of DATAFORMATCRU_TYPE:
    
            MV - 0x01
            XML - 0x02
            JSON - 0x03
            XML_DICT - 0x05
            XML_SCH - 0x06
            JSON_DICT - 0x07
            JSON_SCH - 0x08
*/
abstract class DATAFORMATCRU_TYPE {
    const MV = 0x01;
    const XML = 0x02;
    const JSON = 0x03;
    const XML_DICT = 0x05;
    const XML_SCH = 0x06;
    const JSON_DICT = 0x07;
    const JSON_SCH = 0x08;
}    
?>
