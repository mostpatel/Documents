<?php 
require_once("cg.php");
require_once("common.php");
require_once("bd.php");
require_once("shipping-location-functions.php");
require_once("route-functions.php");
		
function listRouteFares(){
	
	try
	{
		$sql="SELECT `route_fare_id`, `route_id`, `vehicle_type_id`, `fare`, `fare_category_id`, driver_fare
		      FROM edms_route_fare
			  ORDER BY route_fare_id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		return $resultArray;	  
	}
	catch(Exception $e)
	{
	}
	
}	

function getNumberOfRouteFares()
{
	$sql="SELECT count(route_fare_id)
		      FROM edms_route_fare
			  ORDER BY route_group";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		return $resultArray[0][0];	
	
	}
function insertRouteFare($route_id,$vehicle_type_id,$fare,$fare_category_id,$driver_fare=0){
	
	try
	{
		
		if(!checkForNumeric($driver_fare))
		$driver_fare=0;
		if(checkForNumeric($vehicle_type_id,$route_id,$fare,$fare_category_id,$driver_fare) && !checkForDuplicateRouteFare($route_id,$vehicle_type_id,$fare_category_id))
		{
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$sql="INSERT INTO edms_route_fare
		      ( `route_id`, `vehicle_type_id`, `fare`, `fare_category_id`, driver_fare)
			  VALUES
			  ('$route_id', $vehicle_type_id, $fare,$fare_category_id, $driver_fare)";
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

function deleteRouteFare($id){
	
	try
	{
		if(checkForNumeric($id) && !checkIfRouteFareInUse($id))
		{
		$sql="DELETE FROM edms_route_fare
		      WHERE route_fare_id=$id";
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

function updateRouteFare($id,$fare,$driver_fare){
	
	try
	{
		$name=clean_data($name);
		$name = ucwords(strtolower($name));
		if(!checkForNumeric($driver_fare))
		$driver_fare=0;
		if(checkForNumeric($fare,$driver_fare,$id))
		{
			
		$sql="UPDATE edms_route_fare
		      SET fare=$fare, driver_fare = $driver_fare
			  WHERE route_fare_id=$id";
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

function getRouteFareById($id){
	
	try
	{
		$sql="SELECT route_fare_id, `route_id`, `vehicle_type_id`, `fare`, `fare_category_id`, driver_fare
		      FROM edms_route_fare
			  WHERE route_fare_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0];	 
	}
	catch(Exception $e)
	{
	}
	
}	
function getRouteFareNameById($id){
	
	try
	{
		$sql="SELECT route_fare_id, route_name
		      FROM edms_route_fare
			  WHERE route_fare_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][1];	 
	}
	catch(Exception $e)
	{
	}
	
}	

function checkForDuplicateRouteFare($route_id,$vehicle_type_id,$fare_category_id,$id=false)
{
	    if(checkForNumeric($route_id,$vehicle_type_id,$fare_category_id))
		{
		$sql="SELECT route_fare_id
		      FROM edms_route_fare
			  WHERE route_id='$route_id' AND vehicle_type_id = $vehicle_type_id AND fare_category_id = $fare_category_id ";
		if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND route_fare_id!=$id";		  	  
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return true;
		else
		return false;
		}
	}	
function checkIfRouteFareInUse($id)
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
	
function getFareForRouteAndVehicleTypeID($route_id,$vehicle_type_id)	
{
	if(checkForNumeric($route_id,$vehicle_type_id))
	{
		$sql="SELECT fare
		      FROM edms_route_fare
			  WHERE route_id='$route_id' AND vehicle_type_id = $vehicle_type_id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0];
		else
		return false;	  
		
	}
	
}
	
function getRouteFareForTrip($from_shipping_id,$to_shipping_id,$vehicle_type_id)	
{
	
	if(checkForNumeric($from_shipping_id,$to_shipping_id,$vehicle_type_id))
	{
		$from_shipping_loc = getShippingLocationForshippingLocationId($from_shipping_id);
		$to_shipping_loc = getShippingLocationForshippingLocationId($to_shipping_id);
		
		$from_area_id = $from_shipping_loc['area_id'];
		$to_area_id = $to_shipping_loc['area_id'];
		$route = getRouteByFromToAreaId($from_area_id,$to_area_id);
		$route_id = $route[0];
		return getFareForRouteAndVehicleTypeID($route_id,$vehicle_type_id);
		
	}
	
}	
?>