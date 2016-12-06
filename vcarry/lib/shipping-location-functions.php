<?php 
require_once("cg.php");
require_once("bd.php");
require_once("common.php");

function listShippingLocationForCustomerId($customer_id,$dont_include_id=false)
{
	if(checkForNumeric($customer_id))
	{
		
		$sql="SELECT shipping_location_id, `shipping_name`, `shipping_address`, shipping_address2, edms_shipping_locations.`city_id`, city_name, area_name, edms_shipping_locations.`area_id`, `cp_name`, `cp_contact_no`, `recess_timings_from`, `recess_timings_to`, `goods_type`, `goods_weight_range`, `customer_id`, primary_location FROM `edms_shipping_locations`, edms_city, edms_city_area WHERE edms_shipping_locations.city_id = edms_city.city_id AND edms_shipping_locations.area_id = edms_city_area.area_id AND edms_shipping_locations.customer_id = $customer_id";
		if(checkForNumeric($dont_include_id))
		$sql=$sql." AND edms_shipping_locations.shipping_location_id NOT IN ($dont_include_id)";
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		return $resultArray;
	}
}

function getShippingLocationForshippingLocationId($customer_id)
{
	if(checkForNumeric($customer_id))
	{
		
		$sql="SELECT shipping_location_id, `shipping_name`, `shipping_address`, shipping_address2,edms_shipping_locations.`city_id`, city_name, area_name, edms_shipping_locations.`area_id`, `cp_name`, `cp_contact_no`, `recess_timings_from`, `recess_timings_to`, `goods_type`, `goods_weight_range`, `customer_id`, primary_location FROM `edms_shipping_locations`, edms_city, edms_city_area WHERE edms_shipping_locations.city_id = edms_city.city_id AND edms_shipping_locations.area_id = edms_city_area.area_id AND edms_shipping_locations.shipping_location_id = $customer_id";
		
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
	
		return $resultArray[0];
	}
}


function insertShippingLocation($shipping_name,$shipping_address,$city_id,$area,$cp_name,$cp_contact_no,$recess_from,$recess_to,$goods_type,$goods_weight_range,$customer_id,$primary_location=0,$shipping_address2)
{
	$shipping_name=clean_data($shipping_name);
	$shipping_address=clean_data($shipping_address);
	$cp_name=clean_data($cp_name);
	$cp_contact_no=clean_data($cp_contact_no);
	$goods_type=clean_data($goods_type);
	$goods_weight_range=clean_data($goods_weight_range);
	$recess_from=clean_data($recess_from);
	$recess_to=clean_data($recess_to);
	$shipping_address2 = clean_data($shipping_address2);
	//$area_id=insertArea($area,$city_id);
	$area_id = $area;
		if(!checkForNumeric($primary_location))
		$primary_location=0;
	
	
	if(validateForNull($shipping_name,$shipping_address) && checkForNumeric($city_id,$area_id,$customer_id,$cp_contact_no) )
	{
	if(!validateForNull($cp_name))
	{
		$cp_name="NA";
	}	
	if(!validateForNull($recess_from))
	$recess_from="00:00:00";
	
	if(!validateForNull($recess_to))
	$recess_to="00:00:00";
	
	if(!checkForNumeric($goods_weight_range))
	$goods_weight_range=0;
	
	if(!validateForNull($goods_type))
	$goods_type="NA";
	
	$sql="INSERT INTO edms_shipping_locations
(shipping_name, shipping_address, city_id,area_id,cp_name, cp_contact_no,recess_timings_from,recess_timings_to,goods_type, goods_weight_range, customer_id, primary_location, shipping_address2) VALUES ('$shipping_name','$shipping_address',$city_id, $area_id, '$cp_name','$cp_contact_no', '$recess_from', '$recess_to', '$goods_type', $goods_weight_range, $customer_id, $primary_location,'$shipping_address2')";
	$result=dbQuery($sql);
	return dbInsertId();
	}
	else 
	return "error";
}	
		


	

function editShippingLocation($id,$shipping_name,$shipping_address,$city_id,$area,$cp_name,$cp_contact_no,$recess_from,$recess_to,$goods_type,$goods_weight_range,$primary_location=0,$shipping_address2="")
{
	
	
	$shipping_name=clean_data($shipping_name);
	$shipping_address=clean_data($shipping_address);
	$cp_name=clean_data($cp_name);
	$cp_contact_no=clean_data($cp_contact_no);
	$goods_type=clean_data($goods_type);
	$goods_weight_range=clean_data($goods_weight_range);
	$recess_from=clean_data($recess_from);
	$recess_to=clean_data($recess_to);
	//$area_id=insertArea($area,$city_id);
	$area_id = $area;
	$shipping_address2 = clean_data($shipping_address2);
	if(!checkForNumeric($primary_location))
$primary_location=0;
	
	if(validateForNull($shipping_name,$shipping_address) && checkForNumeric($city_id,$area_id,$id,$cp_contact_no) )
	{
	if(!checkForNumeric($cp_name))
	{
		$cp_name="NA";
	}	
	if(!validateForNull($recess_from))
	$recess_from="00:00:00";
	
	if(!validateForNull($recess_to))
	$recess_to="00:00:00";
	
	if(!checkForNumeric($goods_weight_range))
	$goods_weight_range=0;
	
	if(!validateForNull($goods_type))
	$goods_type="NA";
	
	$sql="UPDATE edms_shipping_locations
SET shipping_name = '$shipping_name', shipping_address = '$shipping_address', city_id = $city_id, area_id = $area_id, cp_name = '$cp_name', cp_contact_no = $cp_contact_no ,recess_timings_from = '$recess_from',recess_timings_to = '$recess_to', goods_type = '$goods_type', goods_weight_range = '$goods_weight_range', shipping_address2 = '$shipping_address2' WHERE shipping_location_id = $id ";

	$result=dbQuery($sql);
		return "success";	   
		
		}
	return "error";	
	
}

function deleteShippingLocation($id)
{
	if(checkForNumeric($id))
	{
		$sql="DELETE FROM edms_shipping_locations WHERE shipping_location_id = $id";
		dbQuery($sql);
		return "success";
	}
}	

function deleteShippingLocationForcustomer_id($id)
{
	if(checkForNumeric($id))
	{
		$sql="DELETE FROM edms_shipping_locations WHERE customer_id = $id AND primary_location=1";
		dbQuery($sql);
		return "success";
	}
}	

?>