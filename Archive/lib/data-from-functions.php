<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("common.php");
require_once("bd.php");


	
function listDataFrom()
{
	
	try
	{
		$sql="SELECT data_from_id, data_from
			  FROM ems_data_from";
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



function insertDataFrom($data_from){
	try
	{
		$data_from=clean_data($data_from);
		$data_from = ucwords(strtolower($data_from));
		if(validateForNull($data_from) && !checkDuplicateDataFrom($data_from))
		{
			$sql="INSERT INTO 
				ems_data_from (data_from)
				VALUES ('$data_from')";
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



function checkDuplicateDataFrom($data_from,$id=false)
{
	if(validateForNull($data_from))
	{
		$sql="SELECT data_from_id
			  FROM ems_data_from
			  WHERE data_from='$data_from'";
		if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND data_from_id!=$id";		  
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


function deleteDataFrom($id){
	
	try
	{
		if(1==1) //!checkifDataFromInUse($id)
		{
		$sql="DELETE FROM ems_data_from
		      WHERE data_from_id=$id";
		
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



function checkifDataFromInUse($id)
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
		
	

function updateDataFrom($id,$data_from)
{
	
	try
	{
		$data_from=clean_data($data_from);
		$data_from = ucwords(strtolower($data_from));
		if(validateForNull($data_from) && checkForNumeric($id) && !checkDuplicateDataFrom($data_from,$id))
		{
		$sql="UPDATE ems_data_from
			  SET data_from ='$data_from'
			  WHERE data_from_id=$id";
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


function getDataFromById($id)
{
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT data_from_id, data_from
			  FROM ems_data_from
			  WHERE data_from_id=$id";
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

function getCustomersByDataFromId($id)
{
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT 
			  FROM 
			  WHERE data_from_id=$id";
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