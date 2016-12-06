<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("common.php");
require_once("bd.php");
		
function listBatteryMakes(){
	
	try
	{
		$sql="SELECT battery_make_id, battery_make
		      FROM edms_battery_make
			  ORDER BY battery_make";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		return $resultArray;	  
	}
	catch(Exception $e)
	{
	}
	
}	

function getNumberOfBatteryMakes()
{
	$sql="SELECT count(battery_make_id)
		      FROM edms_battery_make
			  ORDER BY battery_make";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		return $resultArray[0][0];	
	
	}
function insertBatteryMake($battery_make){
	
	try
	{
		$battery_make=clean_data($battery_make);
		$battery_make = ucwords(strtolower($battery_make));
		if(validateForNull($battery_make) && !checkForDuplicateBatteryMake($battery_make))
		{
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$sql="INSERT INTO edms_battery_make
		      (battery_make)
			  VALUES
			  ('$battery_make')";
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

function deleteBatteryMake($id){
	
	try
	{
		if(checkForNumeric($id) && !checkIfBatteryMakeInUse($id))
		{
		$sql="DELETE FROM edms_battery_make
		      WHERE battery_make_id=$id";
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

function updateBatteryMake($id,$type){
	
	try
	{
		$type=clean_data($type);
		$type = ucwords(strtolower($type));
		if(checkForNumeric($id) && validateForNull($type) && !checkForDuplicateBatteryMake($type,$id))
		{
			
		$sql="UPDATE edms_battery_make
		      SET battery_make='$type'
			  WHERE battery_make_id=$id";
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

function getBatteryMakeById($id){
	
	try
	{
		$sql="SELECT battery_make_id, battery_make
		      FROM edms_battery_make
			  WHERE battery_make_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0];	 
	}
	catch(Exception $e)
	{
	}
	
}	
function getBatteryMakeNameById($id){
	
	try
	{
		$sql="SELECT battery_make_id, battery_make
		      FROM edms_battery_make
			  WHERE battery_make_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][1];	 
	}
	catch(Exception $e)
	{
	}
	
}	

function checkForDuplicateBatteryMake($battery_make,$id=false)
{
	    if(validateForNull($battery_make))
		{
		$sql="SELECT battery_make_id
		      FROM edms_battery_make
			  WHERE battery_make='$battery_make'";
		if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND battery_make_id!=$id";		  	  
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return true;
		else
		return false;
		}
	}	
function checkIfBatteryMakeInUse($id)
{
	if(checkForNumeric($id))
	{
	$sql="SELECT vehicle_id FROM
			edms_vehicle
			WHERE battery_make_id=$id";
	$result=dbQuery($sql);
	if(dbNumRows($result)>0)
	return true;
	else
	return false;		
	}
	
	}	
?>