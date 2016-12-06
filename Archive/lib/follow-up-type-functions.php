<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("common.php");
require_once("bd.php");


	
function listFollowUpTypes(){
	
	try
	{
		$sql="SELECT follow_up_type_id, follow_up_type
			  FROM ems_follow_up_type";
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



function insertFollowUpType($follow_up_type)
{
	try
	{
		$follow_up_type=clean_data($follow_up_type);
		$follow_up_type = ucwords(strtolower($follow_up_type));
		if(validateForNull($follow_up_type) && !checkDuplicateFollowUpType($follow_up_type))
		{
			$sql="INSERT INTO 
				ems_follow_up_type (follow_up_type)
				VALUES ('$follow_up_type')";
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



function checkDuplicateFollowUpType($follow_up_type,$id=false)
{
	if(validateForNull($follow_up_type))
	{
		$sql="SELECT follow_up_type_id
			  FROM ems_follow_up_type
			  WHERE follow_up_type='$follow_up_type'";
		if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND follow_up_type_id!=$id";		  
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


function deleteFollowUpType($id){
	
	try
	{
		if(!checkifFollowUpTypeInUse($id))
		{
		$sql="DELETE FROM ems_follow_up_type
		      WHERE follow_up_type_id=$id";
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



function checkifFollowUpTypeInUse($id)
{
	
	if(checkForNumeric($id))
	{
	$sql="SELECT follow_up_type_id
	      FROM ems_follow_up
		  Where follow_up_type_id=$id";
	$result=dbQuery($sql);	  
	if(dbNumRows($result)>0)
	return true;
	else 
	return false;
	}
	
}			
		
	

function updateFollowUpType($id,$follow_up_type)
{
	
	try
	{
		$follow_up_type=clean_data($follow_up_type);
		$follow_up_type = ucwords(strtolower($follow_up_type));
		if(validateForNull($follow_up_type) && checkForNumeric($id) && !checkDuplicateFollowUpType($follow_up_type,$id))
		{
		$sql="UPDATE ems_follow_up_type
			  SET follow_up_type ='$follow_up_type'
			  WHERE follow_up_type_id=$id";
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


function getFollowUpTypeById($id)
{
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT follow_up_type_id, follow_up_type
			  FROM ems_follow_up_type
			  WHERE follow_up_type_id=$id";
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


?>