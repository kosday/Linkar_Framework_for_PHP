<?php
/*
	class: CONVERSION_TYPE
	The conversion type for LkConversion functions.
	
	There are 2 possible options:
	
		ICONV - Perform ICONV type conversions. 
		OCONV - Perform OCONV type conversions. 
	
	Defined constants of CONVERSION_TYPE:
	
		ICONV - 1
		OCONV - 2
*/
abstract class CONVERSION_TYPE {
        const INPUT = 0x01;
        const OUTPUT = 0x02;
}
?>
