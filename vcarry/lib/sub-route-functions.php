<?php 
require_once("cg.php");
require_once("common.php");
require_once("bd.php");
		
function listSubRoutes(){
	
	try
	{
		$sql="SELECT sub_route_id, route_name, distance, route_id
		      FROM edms_sub_routes
			  ORDER BY sub_route_id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		return $resultArray;	  
	}
	catch(Exception $e)
	{
	}
	
}	

function getNumberOfSubRoutes()
{
	$sql="SELECT count(sub_route_id)
		      FROM edms_sub_routes
			  ORDER BY route_group";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		return $resultArray[0][0];	
	
	}
function insertSubRoute($name,$distance,$route_id){
	
	try
	{
		$name=clean_data($name);
		$name = ucwords(strtolower($name));
		
		if(validateForNull($name) && checkForNumeric($distance,$route_id) && !checkForDuplicateSubRoute($route_id,$name))
		{
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$sql="INSERT INTO edms_sub_routes
		      (`route_name`, `distance`, `route_id`)
			  VALUES
			  ('$name', $distance, $route_id)";
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

function deleteSubRoute($id){
	
	try
	{
		if(checkForNumeric($id) && !checkIfSubRouteInUse($id))
		{
		$sql="DELETE FROM edms_sub_routes
		      WHERE sub_route_id=$id";
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

function updateSubRoute($id,$name,$distance,$route_group_id){
	
	try
	{
		$name=clean_data($name);
		$name = ucwords(strtolower($name));
		if(validateForNull($name) && checkForNumeric($id,$distance,$route_group_id) && !checkForDuplicateSubRoute($route_group_id,$name,$id))
		{
			
		$sql="UPDATE edms_sub_routes
		      SET `route_name`='$name',`distance`=$distance,`route_group_id`=$route_group_id
			  WHERE sub_route_id=$id";
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

function getSubRouteById($id){
	
	try
	{
		$sql="SELECT sub_route_id, route_name, distance, route_group_id
		      FROM edms_sub_routes
			  WHERE sub_route_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0];	 
	}
	catch(Exception $e)
	{
	}
	
}	
function getSubRouteNameById($id){
	
	try
	{
		$sql="SELECT sub_route_id, route_name
		      FROM edms_sub_routes
			  WHERE sub_route_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][1];	 
	}
	catch(Exception $e)
	{
	}
	
}	

function checkForDuplicateSubRoute($route_group_id,$name,$id=false)
{
	    if(validateForNull($name) && checkForNumeric($route_group_id))
		{
		$sql="SELECT sub_route_id
		      FROM edms_sub_routes
			  WHERE route_name='$name' AND route_id = $route_group_id ";
		if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND sub_route_id!=$id";		  	  
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return true;
		else
		return false;
		}
	}	
function checkIfSubRouteInUse($id)
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