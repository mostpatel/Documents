<?php 
require_once("cg.php");
require_once("common.php");
require_once("bd.php");
require_once("route-group-functions.php");
		
function listRoutes(){
	
	try
	{
		$sql="SELECT route_id, from_area_id, to_area_id, edms_routes.route_group_id, route_group, from_area.area_name as from_area_name, to_area.area_name as to_area_name,  edms_routes.created_by, edms_routes.last_updated_by, edms_routes.date_added, edms_routes.date_modified
		      FROM edms_routes
			  INNER JOIN edms_route_group ON edms_routes.route_group_id = edms_route_group.route_group_id
			  INNER JOIN edms_city_area as from_area ON edms_routes.from_area_id = from_area.area_id
			   INNER JOIN edms_city_area as to_area ON edms_routes.to_area_id = to_area.area_id
			  ORDER BY from_area_id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		return $resultArray;	  
	}
	catch(Exception $e)
	{
	}
	
}	

function getNumberOfRoutes()
{
	$sql="SELECT count(route_id)
		      FROM edms_routes
			  ORDER BY route";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		return $resultArray[0][0];	
	
	}
function insertRoute($from_area_id,$to_area_id,$route_group_id=NULL){
	
	try
	{
		
		if(!is_numeric($route_group_id))
		{
			$route_groups = listRouteGroups();
			$route_group_id = $route_groups[0]['route_group_id'];
		}
		if(checkForNumeric($from_area_id,$to_area_id,$route_group_id) && !checkForDuplicateRoute($from_area_id,$to_area_id))
		{
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$sql="INSERT INTO edms_routes
		      (`from_area_id`, `to_area_id`, route_group_id, created_by, last_updated_by, date_added, date_modified)
			  VALUES
			  ($from_area_id, $to_area_id, $route_group_id, $admin_id, $admin_id, NOW(), NOW())";
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

function deleteRoute($id){
	
	try
	{
		if(checkForNumeric($id) && !checkIfRouteInUse($id) && $id>100)
		{
		$sql="DELETE FROM edms_routes
		      WHERE route_id=$id";
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

function updateRoute($id,$type){
	
	try
	{
		$type=clean_data($type);
		$type = ucwords(strtolower($type));
		if(checkForNumeric($id) && validateForNull($type) && !checkForDuplicateRoute($type,$id) && $id>100)
		{
			
		$sql="UPDATE edms_routes
		      SET route='$type'
			  WHERE route_id=$id";
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

function getRouteById($id){
	
	try
	{
		$sql="SELECT route_id, from_area_id, to_area_id, edms_routes.route_group_id, route_group, from_area.area_name as from_area_name, to_area.area_name as to_area_name,  edms_routes.created_by, edms_routes.last_updated_by, edms_routes.date_added, edms_routes.date_modified
		      FROM edms_routes
			  INNER JOIN edms_route_group ON edms_routes.route_group_id = edms_route_group.route_group_id
			  INNER JOIN edms_city_area as from_area ON edms_routes.from_area_id = from_area.area_id
			   INNER JOIN edms_city_area as to_area ON edms_routes.to_area_id = to_area.area_id
			  WHERE route_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0];	 
	}
	catch(Exception $e)
	{
	}
	
}	

function getRouteByFromToAreaId($from_area_id,$to_area_id){
	
	try
	{
		$sql="SELECT route_id, from_area_id, to_area_id, edms_routes.route_group_id, route_group, from_area.area_name as from_area_name, to_area.area_name as to_area_name,  edms_routes.created_by, edms_routes.last_updated_by, edms_routes.date_added, edms_routes.date_modified
		      FROM edms_routes
			  INNER JOIN edms_route_group ON edms_routes.route_group_id = edms_route_group.route_group_id
			  INNER JOIN edms_city_area as from_area ON edms_routes.from_area_id = from_area.area_id
			   INNER JOIN edms_city_area as to_area ON edms_routes.to_area_id = to_area.area_id
			  WHERE from_area_id = $from_area_id AND to_area_id = $to_area_id";
			  
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0];	 
		else
		return false;
	}
	catch(Exception $e)
	{
	}
	
}	
function getRouteNameById($id){
	
	try
	{
		$sql="SELECT route_id, route
		      FROM edms_routes
			  WHERE route_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][1];	 
	}
	catch(Exception $e)
	{
	}
	
}	

function checkForDuplicateRoute($from_area_id,$to_area_id,$id=false)
{
	    if(validateForNull($route))
		{
		$sql="SELECT route_id
		      FROM edms_routes
			  WHERE from_area_id='$from_area_id' AND to_area_id = $to_area_id ";
		if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND route_id!=$id";		  	  
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return true;
		else
		return false;
		}
	}	
function checkIfRouteInUse($id)
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