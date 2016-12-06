<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("common.php");
require_once("bd.php");
		
function listTechnicians(){
	
	try
	{
		$sql="SELECT technician_id, technician_name
		      FROM edms_technician
			  ORDER BY technician_name";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		return $resultArray;	  
	}
	catch(Exception $e)
	{
	}
	
}	

function getNumberOfTechnicians()
{
	$sql="SELECT count(technician_id)
		      FROM edms_technician
			  ORDER BY technician_name";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		return $resultArray[0][0];	
	
	}
function insertTechnician($technician_name){
	
	try
	{
		$technician_name=clean_data($technician_name);
		$technician_name = ucwords(strtolower($technician_name));
		if(validateForNull($technician_name) && !checkForDuplicateTechnician($technician_name))
		{
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$sql="INSERT INTO edms_technician
		      (technician_name)
			  VALUES
			  ('$technician_name')";
			  
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

function deleteTechnician($id){
	
	try
	{
		if(checkForNumeric($id) && !checkIfTechnicianInUse($id))
		{
		$sql="DELETE FROM edms_technician
		      WHERE technician_id=$id";
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

function updateTechnician($id,$type){
	
	try
	{
		$type=clean_data($type);
		$type = ucwords(strtolower($type));
		if(checkForNumeric($id) && validateForNull($type) && !checkForDuplicateTechnician($type,$id))
		{
			
		$sql="UPDATE edms_technician
		      SET technician_name='$type'
			  WHERE technician_id=$id";
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

function getTechnicianById($id){
	
	try
	{
		$sql="SELECT technician_id, technician_name
		      FROM edms_technician
			  WHERE technician_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0];	 
	}
	catch(Exception $e)
	{
	}
	
}	
function getTechnicianNameById($id){
	
	try
	{
		$sql="SELECT technician_id, technician_name
		      FROM edms_technician
			  WHERE technician_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][1];	 
	}
	catch(Exception $e)
	{
	}
	
}	

function checkForDuplicateTechnician($technician_name,$id=false)
{
	    if(validateForNull($technician_name))
		{
		$sql="SELECT technician_id
		      FROM edms_technician
			  WHERE technician_name='$technician_name'";
		if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND technician_id!=$id";		  	  
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return true;
		else
		return false;
		}
	}	
function checkIfTechnicianInUse($id)
{
	if(checkForNumeric($id))
	{
	$sql="SELECT technician_id FROM
			fin_job_card
			WHERE technician_id=$id";
	$result=dbQuery($sql);
	if(dbNumRows($result)>0)
	return true;
	else
	return false;		
	}
	
	}	
?>