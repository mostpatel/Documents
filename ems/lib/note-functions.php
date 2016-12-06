<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("common.php");
require_once("bd.php");



	
function insertNote($note, $enquiry_form_id)
{
	try
	{
		$note=clean_data($note);
		
		if(validateForNull($note))
		{
			$sql="INSERT INTO 
				ems_note (note, enquiry_form_id, date_added)
				VALUES ('$note', $enquiry_form_id, NOW())";
		$result=dbQuery($sql);
		return "success";
		}
		else
		{
			return "error";
			}
	}
	catch(Exception $e)
	{
	}
	
}





function deleteNote($id){
	
	try
	{
		
		$sql="DELETE from ems_note 
		      WHERE note_id=$id";
		dbQuery($sql);
		return "success";
		
		
	}
	catch(Exception $e)
	{
	}
	
}	

function getNotesByEnquiryId($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT note_id, note, date_added
			  FROM ems_note
			  WHERE enquiry_form_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray;
		else
		return false;
		}
	}
	catch(Exception $e)
	{
	}
	
}

function getNoteById($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT note_id, note, date_added
			  FROM ems_note
			  WHERE note_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0];
		else
		return false;
		}
	}
	catch(Exception $e)
	{
	}
	
}

	
	

function updateNote($id, $note){
	
	try
	{
		
		
		$note=clean_data($note);
		$note = ucwords(strtolower($note));
		
		if(validateForNull($note) && checkForNumeric($id))
		{
		$sql="UPDATE ems_note
			  SET note='$note'
			  WHERE note_id=$id";
			  
		
		dbQuery($sql);
		return "success";
		}
		else
		{
			return "error";
			}
	}
	catch(Exception $e)
	{
	}
	
}	





?>