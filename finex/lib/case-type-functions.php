<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("common.php");
require_once("bd.php");
		
function listCaseTypes(){
	
	try
	{
		$sql="SELECT case_type_id, case_type
		      FROM fin_case_type
			  ORDER BY case_type";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		return $resultArray;	  
	}
	catch(Exception $e)
	{
	}
	
}	

function getNumberOfCaseTypes()
{
	$sql="SELECT count(case_type_id)
		      FROM fin_case_type
			  ORDER BY case_type";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		return $resultArray[0][0];	
	
	}
function insertCaseType($case_type){
	
	try
	{
		$case_type=clean_data($case_type);
		$case_type = ucwords(strtolower($case_type));
		if(validateForNull($case_type) && !checkForDuplicateCaseType($case_type))
		{
		$admin_id=$_SESSION['adminSession']['admin_id'];
		$sql="INSERT INTO fin_case_type
		      (case_type, created_by, last_updated_by, date_added, date_modified)
			  VALUES
			  ('$case_type', $admin_id, $admin_id, NOW(), NOW())";
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

function insertCaseTypeIfNotDuplicate($case_type){
	
	try
	{
		$case_type=clean_data($case_type);
		$case_type = ucwords(strtolower($case_type));
		$duplicate = checkForDuplicateCaseType($case_type);
		if(validateForNull($case_type) && !$duplicate)
		{
		$admin_id=$_SESSION['adminSession']['admin_id'];
		$sql="INSERT INTO fin_case_type
		      (case_type, created_by, last_updated_by, date_added, date_modified)
			  VALUES
			  ('$case_type', $admin_id, $admin_id, NOW(), NOW())";
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

function deleteCaseType($id){
	
	try
	{
		if(checkForNumeric($id) && !checkIfCaseTypeInUse($id))
		{
		$sql="DELETE FROM fin_case_type
		      WHERE case_type_id=$id";
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

function updateCaseType($id,$type){
	
	try
	{
		$type=clean_data($type);
		$type = ucwords(strtolower($type));
		if(checkForNumeric($id) && validateForNull($type) && !checkForDuplicateCaseType($type,$id))
		{
			
		$sql="UPDATE fin_case_type
		      SET case_type='$type'
			  WHERE case_type_id=$id";
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

function getCaseTypeById($id){
	
	try
	{
		$sql="SELECT case_type_id, case_type
		      FROM fin_case_type
			  WHERE case_type_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0];	 
	}
	catch(Exception $e)
	{
	}
	
}	

function getOthersCaseTypeId(){
	
	try
	{
		$sql="SELECT case_type_id
		      FROM fin_case_type
			  WHERE case_type='Others'";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0];	 
	}
	catch(Exception $e)
	{
	}
	
}	

function getCaseTypeNameById($id){
	
	try
	{
		$sql="SELECT case_type_id, case_type
		      FROM fin_case_type
			  WHERE case_type_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][1];	 
	}
	catch(Exception $e)
	{
	}
	
}	

function checkForDuplicateCaseType($case_type,$id=false)
{
	    if(validateForNull($case_type))
		{
		$sql="SELECT case_type_id
		      FROM fin_case_type
			  WHERE case_type='$case_type'";
		if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND case_type_id!=$id";		  	  
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0];
		else
		return false;
		}
	}	
function checkIfCaseTypeInUse($id)
{
	if(checkForNumeric($id))
	{
	$sql="SELECT case_type_id FROM
			fin_legal_notice
			WHERE case_type_id=$id";
	$result=dbQuery($sql);
	if(dbNumRows($result)>0)
	return true;
	else
	return false;		
	}
	
	}	
?>