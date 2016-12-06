<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("common.php");
require_once("bd.php");
		
function listFuelTypes(){
	
	try
	{
		$sql="SELECT fuel_type_id, fuel_type
		      FROM edms_fuel_type
			  ORDER BY fuel_type";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		return $resultArray;	  
	}
	catch(Exception $e)
	{
	}
	
}	

function getNumberOfFuelTypes()
{
	$sql="SELECT count(fuel_type_id)
		      FROM edms_fuel_type
			  ORDER BY fuel_type";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		return $resultArray[0][0];	
	
	}
function insertFuelType($fuel_type){
	
	try
	{
		$fuel_type=clean_data($fuel_type);
		$fuel_type = ucwords(strtolower($fuel_type));
		if(validateForNull($fuel_type) && !checkForDuplicateFuelType($fuel_type))
		{
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$sql="INSERT INTO edms_fuel_type
		      (fuel_type)
			  VALUES
			  ('$fuel_type')";
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

function deleteFuelType($id){
	
	try
	{
		if(checkForNumeric($id) && !checkIfFuelTypeInUse($id))
		{
		$sql="DELETE FROM edms_fuel_type
		      WHERE fuel_type_id=$id";
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

function updateFuelType($id,$type){
	
	try
	{
		$type=clean_data($type);
		$type = ucwords(strtolower($type));
		if(checkForNumeric($id) && validateForNull($type) && !checkForDuplicateFuelType($type,$id))
		{
			
		$sql="UPDATE edms_fuel_type
		      SET fuel_type='$type'
			  WHERE fuel_type_id=$id";
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

function getFuelTypeById($id){
	
	try
	{
		$sql="SELECT fuel_type_id, fuel_type
		      FROM edms_fuel_type
			  WHERE fuel_type_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0];	 
	}
	catch(Exception $e)
	{
	}
	
}	
function getFuelTypeNameById($id){
	
	try
	{
		$sql="SELECT fuel_type_id, fuel_type
		      FROM edms_fuel_type
			  WHERE fuel_type_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][1];	 
	}
	catch(Exception $e)
	{
	}
	
}	

function checkForDuplicateFuelType($fuel_type,$id=false)
{
	    if(validateForNull($fuel_type))
		{
		$sql="SELECT fuel_type_id
		      FROM edms_fuel_type
			  WHERE fuel_type='$fuel_type'";
		if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND fuel_type_id!=$id";		  	  
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return true;
		else
		return false;
		}
}
	
function checkIfFuelTypeInUse($id)
{
	if(checkForNumeric($id))
	{
	$sql="SELECT vehicle_id FROM
			edms_vehicle
			WHERE fuel_type_id=$id";
	$result=dbQuery($sql);
	if(dbNumRows($result)>0)
	return true;
	else
	return false;		
	}
	
}

function getFuelTypeIdFromFuelType($fuel_type)
{
	if(validateForNull($fuel_type))
	{
		$sql="SELECT fuel_type_id
		      FROM edms_fuel_type
			  WHERE fuel_type='$fuel_type'";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray[0][0];
	else
	return false;		
		
	}	
}

function getCNGFuelType()
{
	return (getFuelTypeIdFromFuelType('Cng'));	
	
}
?>