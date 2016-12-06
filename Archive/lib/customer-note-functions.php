<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("common.php");
require_once("bd.php");



	
function insertCustomerNote($note, $customer_id)
{
	try
	{
		$note=clean_data($note);
		
		if(validateForNull($note))
		{
			$sql="INSERT INTO 
				ems_customer_note (customer_note, customer_id, date_added)
				VALUES ('$note', $customer_id, NOW())";
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





function deleteCustomerNote($id){
	
	try
	{
		
		$sql="DELETE from ems_customer_note 
		      WHERE customer_note_id=$id";
		dbQuery($sql);
		return "success";
		
		
	}
	catch(Exception $e)
	{
	}
	
}	

function getNotesByCustomerId($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT customer_note_id, customer_note, date_added
			  FROM ems_customer_note
			  WHERE customer_id=$id";
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

function getCustomerNoteById($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT customer_note_id, customer_note, date_added
			  FROM ems_customer_note
			  WHERE customer_note_id=$id";
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

	
	

function updateCustomerNote($id, $note)
{
	
	try
	{
		
		
		$note=clean_data($note);
		$note = ucwords(strtolower($note));
		
		if(validateForNull($note) && checkForNumeric($id))
		{
		$sql="UPDATE ems_customer_note
			  SET customer_note='$note'
			  WHERE customer_note_id=$id";
			  
		
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