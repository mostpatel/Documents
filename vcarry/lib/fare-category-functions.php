<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("common.php");
require_once("bd.php");
		
function listFareCategories(){
	
	try
	{
		$sql="SELECT fare_category_id, fare_category
		      FROM edms_fare_category
			  ORDER BY fare_category";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		return $resultArray;	  
	}
	catch(Exception $e)
	{
	}
	
}	

function getNumberOfFareCategories()
{
	$sql="SELECT count(fare_category_id)
		      FROM edms_fare_category
			  ORDER BY fare_category";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		return $resultArray[0][0];	
	
	}
function insertFareCategory($fare_category){
	
	try
	{
		$fare_category=clean_data($fare_category);
		$fare_category = ucwords(strtolower($fare_category));
		if(validateForNull($fare_category) && !checkForDuplicateFareCategory($fare_category))
		{
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$sql="INSERT INTO edms_fare_category
		      (fare_category, created_by, last_updated_by, date_added, date_modified)
			  VALUES
			  ('$fare_category', $admin_id, $admin_id, NOW(), NOW())";
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

function deleteFareCategory($id){
	
	try
	{
		if(checkForNumeric($id) && !checkIfFareCategoryInUse($id))
		{
		$sql="DELETE FROM edms_fare_category
		      WHERE fare_category_id=$id";
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

function updateFareCategory($id,$type){
	
	try
	{
		$type=clean_data($type);
		$type = ucwords(strtolower($type));
		if(checkForNumeric($id) && validateForNull($type) && !checkForDuplicateFareCategory($type,$id))
		{
			
		$sql="UPDATE edms_fare_category
		      SET fare_category='$type'
			  WHERE fare_category_id=$id";
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

function getFareCategoryById($id){
	
	try
	{
		$sql="SELECT fare_category_id, fare_category
		      FROM edms_fare_category
			  WHERE fare_category_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0];	 
	}
	catch(Exception $e)
	{
	}
	
}	
function getFareCategoryNameById($id){
	
	try
	{
		$sql="SELECT fare_category_id, fare_category
		      FROM edms_fare_category
			  WHERE fare_category_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][1];	 
	}
	catch(Exception $e)
	{
	}
	
}	

function checkForDuplicateFareCategory($fare_category,$id=false)
{
	    if(validateForNull($fare_category))
		{
		$sql="SELECT fare_category_id
		      FROM edms_fare_category
			  WHERE fare_category='$fare_category'";
		if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND fare_category_id!=$id";		  	  
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return true;
		else
		return false;
		}
	}	
function checkIfFareCategoryInUse($id)
{
	if(checkForNumeric($id))
	{
	$sql="SELECT vehicle_id FROM
			edms_vehicle
			WHERE fare_category_id=$id";
	$result=dbQuery($sql);
	if(dbNumRows($result)>0)
	return true;
	else
	return false;		
	}
	
	}	
?>