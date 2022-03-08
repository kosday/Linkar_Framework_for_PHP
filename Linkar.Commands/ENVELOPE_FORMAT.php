<?php
	/*
		abstract class: ENVELOPE_FORMAT
		Indicates in what format you want to send the command for LkSendCommnad operation.
		There are 2 possible options: XML and JSON.
			
		Defined constants of ENVELOPE_FORMAT:
		
			XML - 0x02
			JSON - 0x03
	*/	
	abstract class ENVELOPE_FORMAT {
		const XML = 0x02;
		const JSON = 0x03;
	}
?>
