<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("common.php");
require_once("bd.php");
		
function listCourts(){
	
	try
	{
		$sql="SELECT court_id, court
		      FROM fin_court
			  ORDER BY court";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		return $resultArray;	  
	}
	catch(Exception $e)
	{
	}
	
}	

function getNumberOfCourts()
{
	$sql="SELECT count(court_id)
		      FROM fin_court
			  ORDER BY court";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		return $resultArray[0][0];	
	
	}
function insertCourt($court){
	
	try
	{
		$court=clean_data($court);
		$court = ucwords(strtolower($court));
		if(validateForNull($court) && !checkForDuplicateCourt($court))
		{
		$admin_id=$_SESSION['adminSession']['admin_id'];
		$sql="INSERT INTO fin_court
		      (court, created_by, last_updated_by, date_added, date_modified)
			  VALUES
			  ('$court', $admin_id, $admin_id, NOW(), NOW())";
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

function insertCourtIfNotDuplicate($court){
	
	try
	{
		$court=clean_data($court);
		$court = ucwords(strtolower($court));
		$duplicate = checkForDuplicateCourt($court);
		if(validateForNull($court) && !$duplicate)
		{
		$admin_id=$_SESSION['adminSession']['admin_id'];
		$sql="INSERT INTO fin_court
		      (court, created_by, last_updated_by, date_added, date_modified)
			  VALUES
			  ('$court', $admin_id, $admin_id, NOW(), NOW())";
		dbQuery($sql);	  
		return dbInsertId();
		}
		else if($duplicate)
		return $duplicate;
		else
		{
			return "error";
			}
	}
	catch(Exception $e)
	{
	}
	
}	

function deleteCourt($id){
	
	try
	{
		if(checkForNumeric($id) && !checkIfCourtInUse($id))
		{
		$sql="DELETE FROM fin_court
		      WHERE court_id=$id";
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

function updateCourt($id,$type){
	
	try
	{
		$type=clean_data($type);
		$type = ucwords(strtolower($type));
		if(checkForNumeric($id) && validateForNull($type) && !checkForDuplicateCourt($type,$id))
		{
			
		$sql="UPDATE fin_court
		      SET court='$type'
			  WHERE court_id=$id";
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

function getCourtById($id){
	
	try
	{
		$sql="SELECT court_id, court
		      FROM fin_court
			  WHERE court_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0];	 
	}
	catch(Exception $e)
	{
	}
	
}	

function getOthersCourtId(){
	
	try
	{
		$sql="SELECT court_id
		      FROM fin_court
			  WHERE court='Others'";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0];	 
	}
	catch(Exception $e)
	{
	}
	
}	

function getCourtNameById($id){
	
	try
	{
		$sql="SELECT court_id, court
		      FROM fin_court
			  WHERE court_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][1];	 
	}
	catch(Exception $e)
	{
	}
	
}	

function checkForDuplicateCourt($court,$id=false)
{
	    if(validateForNull($court))
		{
		$sql="SELECT court_id
		      FROM fin_court
			  WHERE court='$court'";
		if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND court_id!=$id";		  	  
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0];
		else
		return false;
		}
	}	
function checkIfCourtInUse($id)
{
	if(checkForNumeric($id))
	{
	$sql="SELECT court_id FROM
			fin_court
			WHERE court_id=$id";
	$result=dbQuery($sql);
	if(dbNumRows($result)>0)
	return true;
	else
	return false;		
	}
	
	}	
?>