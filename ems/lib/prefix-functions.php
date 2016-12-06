<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("common.php");
require_once("bd.php");


	
function listPrefix()
{
	
	try
	{
		$sql="SELECT prefix_id, prefix
			  FROM ems_prefix";
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



function insertPrefix($prefix){
	try
	{
		$prefix=clean_data($prefix);
		$prefix = ucwords(strtolower($prefix));
		if(validateForNull($prefix) && !checkDuplicatePrefix($prefix))
		{
			$sql="INSERT INTO 
				ems_prefix (prefix)
				VALUES ('$prefix')";
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



function checkDuplicatePrefix($prefix,$id=false)
{
	if(validateForNull($prefix))
	{
		$sql="SELECT prefix_id
			  FROM ems_prefix
			  WHERE prefix ='$prefix'";
		if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND prefix_id!=$id";		  
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


function deletePrefix($id){
	
	try
	{
		if(1==1) //!checkifPrefixInUse($id)
		{
		$sql="DELETE FROM ems_prefix
		      WHERE prefix_id=$id";
		
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



function checkifPrefixInUse($id)
{
	
	if(checkForNumeric($id))
	{
	$sql="SELECT 
	      FROM 
		  Where";
	$result=dbQuery($sql);	  
	if(dbNumRows($result)>0)
	return true;
	else 
	return false;
	}
	
}			
		
	

function updatePrefix($id, $prefix)
{
	
	try
	{
		
		$prefix=clean_data($prefix);
		$prefix = ucwords(strtolower($prefix));
		if(validateForNull($prefix) && checkForNumeric($id))
		{
		
		
		
		$sql="UPDATE ems_prefix
			  SET prefix ='$prefix'
			  WHERE prefix_id=$id";
			  
	   
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


function getPrefixById($id)
{
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT prefix_id, prefix
			  FROM ems_prefix
			  WHERE prefix_id=$id";
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