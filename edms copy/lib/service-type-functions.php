<?php 
require_once("cg.php");
require_once("common.php");
require_once("bd.php");
		
function listServiceTypes(){
	
	try
	{
		$sql="SELECT service_type_id, service_type
		      FROM edms_service_types
			  ORDER BY service_type";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		return $resultArray;	  
	}
	catch(Exception $e)
	{
	}
	
}	

function getNumberOfServiceTypes()
{
	$sql="SELECT count(service_type_id)
		      FROM edms_service_types
			  ORDER BY service_type";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		return $resultArray[0][0];	
	
	}
function insertServiceType($service_type){
	
	try
	{
		$service_type=clean_data($service_type);
		$service_type = ucwords(strtolower($service_type));
		if(validateForNull($service_type) && !checkForDuplicateServiceType($service_type))
		{
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$sql="INSERT INTO edms_service_types
		      (service_type, created_by, last_updated_by, date_added, date_modified)
			  VALUES
			  ('$service_type', $admin_id, $admin_id, NOW(), NOW())";
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

function deleteServiceType($id){
	
	try
	{
		if(checkForNumeric($id) && !checkIfServiceTypeInUse($id))
		{
		$sql="DELETE FROM edms_service_types
		      WHERE service_type_id=$id";
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

function updateServiceType($id,$type){
	
	try
	{
		$type=clean_data($type);
		$type = ucwords(strtolower($type));
		if(checkForNumeric($id) && validateForNull($type) && !checkForDuplicateServiceType($type,$id))
		{
			
		$sql="UPDATE edms_service_types
		      SET service_type='$type'
			  WHERE service_type_id=$id";
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

function getServiceTypeById($id){
	
	try
	{
		$sql="SELECT service_type_id, service_type
		      FROM edms_service_types
			  WHERE service_type_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0];	 
	}
	catch(Exception $e)
	{
	}
	
}	
function getServiceTypeNameById($id){
	
	try
	{
		$sql="SELECT service_type_id, service_type
		      FROM edms_service_types
			  WHERE service_type_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][1];	 
	}
	catch(Exception $e)
	{
	}
	
}	

function checkForDuplicateServiceType($service_type,$id=false)
{
	    if(validateForNull($service_type))
		{
		$sql="SELECT service_type_id
		      FROM edms_service_types
			  WHERE service_type='$service_type'";
		if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND service_type_id!=$id";		  	  
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return true;
		else
		return false;
		}
	}	
function checkIfServiceTypeInUse($id)
{
	if(checkForNumeric($id))
	{
	$sql="SELECT service_type_id FROM
			edms_job_card
			WHERE service_type_id=$id";
	$result=dbQuery($sql);
	if(dbNumRows($result)>0)
	return true;
	else
	return false;		
	}
	
	}	
?>