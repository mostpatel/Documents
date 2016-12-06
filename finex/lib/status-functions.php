<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("common.php");
require_once("bd.php");


	
function listStatus(){
	
	try
	{
		$sql="SELECT status_id, status 
			  FROM fin_enquiry_status";
		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray; 
		else
		return false;
		  
	}
	catch(Exception $e)
	{
	}
	
}	



function insertStatus($name){
	try
	{
		$name=clean_data($name);
		$name = ucwords(strtolower($name));
		if(validateForNull($name) && !checkDuplicateStatus($name))
		{
			$sql="INSERT INTO 
				fin_enquiry_status (status)
				VALUES ('$name')";
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



function checkDuplicateStatus($name,$id=false)
{
	if(validateForNull($name))
	{
		$sql="SELECT status_id
			  FROM fin_enquiry_status
			  WHERE status='$name'";
		if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND status_id!=$id";		  
		$result=dbQuery($sql);	
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
			return $resultArray[0][0]; //duplicate found
			} 
		else
		{
			return false;
			}
	}
}		


function deleteStatus($id){
	
	try
	{
		if(!checkifStatusInUse($id))
		{
		$sql="DELETE FROM fin_enquiry_status
		      WHERE status_id=$id";
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



function checkifStatusInUse($id)
{
	
	if(checkForNumeric($id))
	{
	$sql="SELECT enquiry_id_id
	      FROM fin_enquiry
		  Where status_id=$id";
	$result=dbQuery($sql);	  
	if(dbNumRows($result)>0)
	return true;
	else 
	return false;
	}
	
}			
		
	

function updateStatus($id,$name){
	
	try
	{
		$name=clean_data($name);
		$name = ucwords(strtolower($name));
		if(validateForNull($name) && checkForNumeric($id) && !checkDuplicateStatus($name,$id))
		{
		$sql="UPDATE fin_enquiry_status
			  SET status='$name'
			  WHERE status_id=$id";
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


function getStatusById($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT status_id, status
			  FROM fin_enquiry_status
			  WHERE status_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][1];
		else
		return false;
		}
	}
	catch(Exception $e)
	{
	}
	
}


?>