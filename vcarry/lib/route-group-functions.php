<?php 
require_once("cg.php");
require_once("common.php");
require_once("bd.php");
		
function listRouteGroups(){
	
	try
	{
		$sql="SELECT route_group_id, route_group
		      FROM edms_route_group
			  ORDER BY route_group_id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		return $resultArray;	  
	}
	catch(Exception $e)
	{
	}
	
}	

function getNumberOfRouteGroups()
{
	$sql="SELECT count(route_group_id)
		      FROM edms_route_group
			  ORDER BY route_group";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		return $resultArray[0][0];	
	
	}
function insertRouteGroup($route_group){
	
	try
	{
		$route_group=clean_data($route_group);
		$route_group = ucwords(strtolower($route_group));
		if(validateForNull($route_group) && !checkForDuplicateRouteGroup($route_group))
		{
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$sql="INSERT INTO edms_route_group
		      (route_group, created_by, last_updated_by, date_added, date_modified)
			  VALUES
			  ('$route_group', $admin_id, $admin_id, NOW(), NOW())";
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

function deleteRouteGroup($id){
	
	try
	{
		if(checkForNumeric($id) && !checkIfRouteGroupInUse($id) && $id>100)
		{
		$sql="DELETE FROM edms_route_group
		      WHERE route_group_id=$id";
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

function updateRouteGroup($id,$type){
	
	try
	{
		$type=clean_data($type);
		$type = ucwords(strtolower($type));
		if(checkForNumeric($id) && validateForNull($type) && !checkForDuplicateRouteGroup($type,$id))
		{
			
		$sql="UPDATE edms_route_group
		      SET route_group='$type'
			  WHERE route_group_id=$id";
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

function getRouteGroupById($id){
	
	try
	{
		$sql="SELECT route_group_id, route_group
		      FROM edms_route_group
			  WHERE route_group_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0];	 
	}
	catch(Exception $e)
	{
	}
	
}	
function getRouteGroupNameById($id){
	
	try
	{
		$sql="SELECT route_group_id, route_group
		      FROM edms_route_group
			  WHERE route_group_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][1];	 
	}
	catch(Exception $e)
	{
	}
	
}	

function checkForDuplicateRouteGroup($route_group,$id=false)
{
	    if(validateForNull($route_group))
		{
		$sql="SELECT route_group_id
		      FROM edms_route_group
			  WHERE route_group='$route_group'";
		if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND route_group_id!=$id";		  	  
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return true;
		else
		return false;
		}
	}	
function checkIfRouteGroupInUse($id)
{
	if(checkForNumeric($id))
	{
	$sql="SELECT auto_rasid_type FROM
			edms_ac_payment
			WHERE auto_rasid_type=$id
		UNION ALL 
		SELECT auto_rasid_type FROM
			edms_ac_receipt
			WHERE auto_rasid_type=$id";
	$result=dbQuery($sql);
	if(dbNumRows($result)>0)
	return true;
	else
	return false;		
	}
	
	}	
?>