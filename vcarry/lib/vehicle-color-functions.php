<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("common.php");
require_once("bd.php");
		
function listVehicleColors(){
	
	try
	{
		$sql="SELECT vehicle_color_id, vehicle_color
		      FROM edms_vehicle_color
			  ORDER BY vehicle_color";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		return $resultArray;	  
	}
	catch(Exception $e)
	{
	}
	
}	

function getNumberOfVehicleColors()
{
	$sql="SELECT count(vehicle_color_id)
		      FROM edms_vehicle_color
			  ORDER BY vehicle_color";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		return $resultArray[0][0];	
	
	}
function insertVehicleColor($vehicle_color){
	
	try
	{
		$vehicle_color=clean_data($vehicle_color);
		$vehicle_color = ucwords(strtolower($vehicle_color));
		if(validateForNull($vehicle_color) && !checkForDuplicateVehicleColor($vehicle_color))
		{
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$sql="INSERT INTO edms_vehicle_color
		      (vehicle_color)
			  VALUES
			  ('$vehicle_color')";
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

function deleteVehicleColor($id){
	
	try
	{
		if(checkForNumeric($id) && !checkIfVehicleColorInUse($id))
		{
		$sql="DELETE FROM edms_vehicle_color
		      WHERE vehicle_color_id=$id";
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

function updateVehicleColor($id,$type){
	
	try
	{
		$type=clean_data($type);
		$type = ucwords(strtolower($type));
		if(checkForNumeric($id) && validateForNull($type) && !checkForDuplicateVehicleColor($type,$id))
		{
			
		$sql="UPDATE edms_vehicle_color
		      SET vehicle_color='$type'
			  WHERE vehicle_color_id=$id";
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

function getVehicleColorById($id){
	
	try
	{
		$sql="SELECT vehicle_color_id, vehicle_color
		      FROM edms_vehicle_color
			  WHERE vehicle_color_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0];	 
	}
	catch(Exception $e)
	{
	}
	
}	
function getVehicleColorNameById($id){
	
	try
	{
		$sql="SELECT vehicle_color_id, vehicle_color
		      FROM edms_vehicle_color
			  WHERE vehicle_color_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][1];	 
	}
	catch(Exception $e)
	{
	}
	
}	

function checkForDuplicateVehicleColor($vehicle_color,$id=false)
{
	    if(validateForNull($vehicle_color))
		{
		$sql="SELECT vehicle_color_id
		      FROM edms_vehicle_color
			  WHERE vehicle_color='$vehicle_color'";
		if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND vehicle_color_id!=$id";		  	  
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return true;
		else
		return false;
		}
}
	
function checkIfVehicleColorInUse($id)
{
	if(checkForNumeric($id))
	{
	$sql="SELECT vehicle_id FROM
			edms_vehicle
			WHERE vehicle_color_id=$id";
	$result=dbQuery($sql);
	if(dbNumRows($result)>0)
	return true;
	else
	return false;		
	}
	
	}	
?>