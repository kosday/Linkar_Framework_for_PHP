<?php
include_once realpath( __DIR__ . '/../Linkar.Functions/LinkarFunctions.php');
include_once realpath( __DIR__ . '/LkItem.php');

/*
	Class: LkItems
			This class is to implement List of the <LkItem> elements.
			
		Property: $LstDictsId
		string array
		
		Array with the dictionary names for record Ids.
		The same array for each LkItem that is stored in the list.

		Property: $LstDicts
		string array
		
		Array with the dictionary names for record fields.
		The same array for each LkItem that is stored in the list.
		
		Property: $LstDictsCalculated
		string array
		
		Array with the dictionary names for calculated fields of the record.
		The same array for each LkItem that is stored in the list.
*/
class LkItems {
	public $LkItemsArray=[];
	public $LstDictsId;
	public $LstDicts;
	public $LstDictsCalculated;

	/*
		Constructor: __constructor
			Initializes a new instance of the LkItem class.
			
		Arguments:
			$lstDictsId - (string array) Array with the dictionary names for record Ids. The same array for each LkItem that is stored in the list.</param>
			$lstDicts - (string array) Array with the dictionarty names for record fields. The same array for each LkItem that is stored in the list.</param>
			$lstDictsCalculated - (string array) Array with the dictionary names for calculated fields of the record. The same array for each LkItem that is stored in the list.</param>
	*/
	public function __construct($lstDictsId = [], $lstDicts = [], $lstDictsCalculated = []) {
		$this->LkItemsArray=[];
		$this->LstDictsId = $lstDictsId;
		$this->LstDicts = $lstDicts;
		$this->LstDictsCalculated = $lstDictsCalculated; 
	}

	/*
		Function: __get
			Get a <LkItem> using its RecordId.
			
		Arguments:
			$id - (string) The record Id of the LkItem.
		
		Return:
			<LkItem>
			
			The LkItem extracted.
		
	*/
	public function __get($id) {
		if(is_numeric($id)) {
			return $this->LkItemsArray[$id];
		 }
		 else {
        	for ($i = 0; $i < count($this->LkItemsArray); $i++) {
				if ($this->LkItemsArray[$i]->RecordId == $id)
					return $this->LkItemsArray[$i];
        	}
			return null;
        }
    }

	/*
		Function: push
			Adds a new LkItem to the list. The dictionaries arrays of the list, will be copied to the LkItem added.
			
		Arguments:
			$lkItem - (LkItem) The LkItem to be added.
	*/
	public function push($lkItem) {
		$duplicateId = false;
		for ($i = 0; $i < count($this->LkItemsArray); $i++) {
			if($this->LkItemsArray[$i]->RecordId == $lkItem->RecordId) {
				$duplicateId = true;
				break;
			}
		}
		if ($lkItem->RecordId && !$duplicateId)
		{
			$lkItem->LstDictsId = $this->LstDictsId;
			$lkItem->LstDicts = $this->LstDicts;
			$lkItem->LstDictsCalculated = $this->LstDictsCalculated;
			array_push($this->LkItemsArray,$lkItem);
		}        
	}

	/*
		Function: pushId
			Creates and adds LkItem with specific recordIds to the list.
			
		Arguments:
			$recordIds - (string array) Array with the list of recordIds.
	*/
	public function pushIds($recordIds) { //Array
		for ($i = 0; $i < count($recordIds); $i++)
		{
			$lkRecord = new LkItem($recordIds[$i]);
			array_push($this->LkItemsArray,$lkRecord);
		} 
	}

	/*
		Function: removeId
			Removes the LkItem specified by its recordID from the list.
			
		Arguments:
			$recordId - (string) The recordId of the LkItem to be removed.
	*/
	public function removeId($recordId) {
		for ($i = 0; $i < count($this->LkItemsArray); $i++)
		{
			if ($this->LkItemsArray[$i]->RecordId == $recordId)
			{
				array_splice($this->LkItemsArray, $i,1);
				break;
			}
		}
}
	public function length(){
		return count($this->LkItemsArray);
	}

	/*
		Function: ComposeReadBuffer
			Composes the final buffer string for one or more records to be read in MV Read operations, with the RecordId information.
			
		Returns:
		string
		
		The final string buffer for MV Read operations.
	*/
	public function ComposeReadBuffer() {
		$buf = "";
		for ($i = 0; $i < count($this->LkItemsArray); $i++)
		{
			if ($i > 0){
				$buf .= ASCII_Chars::RS_str;
			}
			$buf .= $this->LkItemsArray[$i]->RecordId;
		}
	
		return $buf;
	}

	/*
		Function: ComposeUpdateBuffer
			Composes the final buffer string for one or more records to be updated in MV Update operations, with the RecordId, the Record,
			and optionally the OriginalRecord information.
			
		Arguments:
			$includeOriginalBuffer - (boolean) Determines if the OriginalRecord must be include or not in the final buffer string.
			
		Returns:
		string
		
		The final string buffer for MV Update operations.
	*/
	public function ComposeUpdateBuffer($includeOriginalBuffer = false) {
		$buf = "";
		for ($i = 0; $i < count($this->LkItemsArray); $i++)
		{
			if ($i > 0){
				$buf .= ASCII_Chars::RS_str;
			}
			$buf .= $this->LkItemsArray[$i]->RecordId;
		}
	
		$buf .= ASCII_Chars::FS_str;
	
		for ($i = 0; $i < count($this->LkItemsArray); $i++)
		{
			if ($i > 0)
				$buf .= ASCII_Chars::RS_str;
			$buf .= $this->LkItemsArray[$i]->Record;
		}
	
		if ($includeOriginalBuffer)
		{
			$buf .= ASCII_Chars::FS_str;
	
			for ($i = 0; $i < count($this->LkItemsArray); $i++)
			{
				if ($i > 0){
					$buf .= ASCII_Chars::RS_str;
				}
				$buf .= $this->LkItemsArray[$i]->OriginalRecord;
			}
		}
	
		return $buf;
	}

	/*
		Function: ComposeNewBuffer
			Composes the final buffer string for one or more records to be created in MV New operations, with the RecordId and the Record information.
		
		Returns:
		string
		
		The final string buffer for MV New operations.
	*/
	public function ComposeNewBuffer() {
		return $this->ComposeUpdateBuffer(false);
	}

	/*
		Function: ComposeDeleteBuffer
			Composes the final buffer string for one or more records to be deleted in MV Delete operations, with the RecordId,
			and optionally with the OriginalRecord information.
			
		Arguments:
			$includeOriginalBuffer - (boolean) Determines if the OriginalRecord must be include or not in the final buffer string.
			
		Returns:
		string
		
		The final string buffer for MV Delete operations.
	*/
	public function ComposeDeleteBuffer($includeOriginalBuffer = false) {
		$buf = "";
		for ($i = 0; $i < count($this->LkItemsArray); $i++)
		{
			if ($i > 0){
				$buf .= ASCII_Chars::RS_str;
			}
			$buf .= $this->LkItemsArray[$i]->RecordId;
		}
	
		if ($includeOriginalBuffer)
		{
			$buf .= ASCII_Chars::FS_str;
	
			for ($i = 0; $i < count($this->LkItemsArray); $i++)
			{
				if ($i > 0){
					$buf .= ASCII_Chars::RS_str;
				}
				$buf .= $this->LkItemsArray[$i]->OriginalRecord;
			}
		}
	
		return $buf;
	}
}
?>
