<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("common.php");
require_once("bd.php");
		
function listItemUnits(){
	
	try
	{
		$sql="SELECT item_unit_id, unit_name
		      FROM edms_item_unit
			  ORDER BY unit_name";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		return $resultArray;	  
	}
	catch(Exception $e)
	{
	}
	
}	

function getNumberOfItemUnits()
{
	$sql="SELECT count(item_unit_id)
		      FROM edms_item_unit
			  ORDER BY unit_name";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		return $resultArray[0][0];	
	
	}
function insertItemUnit($unit_name){
	
	try
	{
		$unit_name=clean_data($unit_name);
		$unit_name = ucwords(strtolower($unit_name));
		if(validateForNull($unit_name) && !checkForDuplicateItemUnit($unit_name))
		{
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$sql="INSERT INTO edms_item_unit
		      (unit_name, created_by, last_updated_by, date_added, date_modified)
			  VALUES
			  ('$unit_name', $admin_id, $admin_id, NOW(), NOW())";
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

function deleteItemUnit($id){
	
	try
	{
		if(checkForNumeric($id) && !checkIfItemUnitInUse($id))
		{
		$sql="DELETE FROM edms_item_unit
		      WHERE item_unit_id=$id";
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

function updateItemUnit($id,$type){
	
	try
	{
		$type=clean_data($type);
		$type = ucwords(strtolower($type));
		if(checkForNumeric($id) && validateForNull($type) && !checkForDuplicateItemUnit($type,$id))
		{
			
		$sql="UPDATE edms_item_unit
		      SET unit_name='$type'
			  WHERE item_unit_id=$id";
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

function getItemUnitById($id){
	
	try
	{
		$sql="SELECT item_unit_id, unit_name
		      FROM edms_item_unit
			  WHERE item_unit_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0];	 
	}
	catch(Exception $e)
	{
	}
	
}	
function getItemUnitNameById($id){
	
	try
	{
		$sql="SELECT item_unit_id, unit_name
		      FROM edms_item_unit
			  WHERE item_unit_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][1];	 
	}
	catch(Exception $e)
	{
	}
	
}	

function checkForDuplicateItemUnit($unit_name,$id=false)
{
	    if(validateForNull($unit_name))
		{
		$sql="SELECT item_unit_id
		      FROM edms_item_unit
			  WHERE unit_name='$unit_name'";
		if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND item_unit_id!=$id";		  	  
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return true;
		else
		return false;
		}
	}	
function checkIfItemUnitInUse($id)
{
	if(checkForNumeric($id))
	{
	$sql="SELECT item_unit_id FROM
			edms_inventory_item
			WHERE item_unit_id=$id";
	$result=dbQuery($sql);
	if(dbNumRows($result)>0)
	return true;
	else
	return false;		
	}
	
	}	
?>