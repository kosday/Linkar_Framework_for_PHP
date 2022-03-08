<?php
/*
	class: OPERATION_CODE
		Operation codes for LkExecuteDirectOperation and LkExecutePersistentOperation functions of Linkar Library
		
		LOGIN - 1
		READ - 2
		UPDATE - 3
		NEW - 4
		DELETE - 5
		CONVERSION - 6
		FORMAT - 7
		LOGOUT - 8
		GETVERSION - 9
		SELECT - 10
		SUBROUTINE - 11
		EXECUTE - 12
		DICTIONARIES - 13
		LKSCHEMAS - 14
		LKPROPERTIES - 15
		GETTABLE - 16
		RESETCOMMONBLOCKS - 17
		UPDATEPARTIAL - 18
		COMMAND_XML - 150
		COMMAND_JSON - 151
*/
abstract class OPERATION_CODE {
	const LOGIN = 1;
	const READ = 2;
	const UPDATE = 3;
	const NEW = 4;
	const DELETE = 5;
	const CONVERSION = 6;
	const FORMAT = 7;
	const LOGOUT = 8;
	const VERSION = 9;
	const SELECT = 10;
	const SUBROUTINE = 11;
	const EXECUTE = 12;
	const DICTIONARIES = 13;
	const LKSCHEMAS = 14;
	const LKPROPERTIES = 15;
	const GETTABLE = 16;
	const RESETCOMMONBLOCKS = 17;
	const UPDATEPARTIAL = 18;
	const COMMAND_XML = 150;
	const COMMAND_JSON = 151;
}
?>
