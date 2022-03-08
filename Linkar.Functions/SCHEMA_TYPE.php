<?php
/*
	class: SCHEMA_TYPE
		The schemas type for <LkSchemasOptions>, <LkPropertiesOptions> and <TableOptions> functions
		
	Defined constants of SCHEMA_TYPE:
	
	LKSCHEMAS - 0x01
	DICTIONARIES - 0x02
	NONE - 0x03
*/
abstract class SCHEMA_TYPE {
    const LKSCHEMAS = 0x01;
	const DICTIONARIES = 0x02;
	const NONE = 0x03;  }

?>
