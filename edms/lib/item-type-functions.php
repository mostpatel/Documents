<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("common.php");
require_once("bd.php");
		
function listItemTypes(){
	
	try
	{
		$sql="SELECT item_type_id, item_type, inc_inventory, IF(inc_inventory=0,'No','Yes') as inc_inventory_yes_no
		      FROM edms_item_type
			  ORDER BY item_type";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		return $resultArray;	  
	}
	catch(Exception $e)
	{
	}
	
}	

function listItemTypesInventory(){
	
	try
	{
		$sql="SELECT item_type_id, item_type, inc_inventory, IF(inc_inventory=0,'No','Yes') as inc_inventory_yes_no
		      FROM edms_item_type WHERE inc_inventory=1
			  ORDER BY item_type";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		return $resultArray;	  
	}
	catch(Exception $e)
	{
	}
	
}	

function listItemTypesNonStock(){
	
	try
	{
		$sql="SELECT item_type_id, item_type, inc_inventory, IF(inc_inventory=0,'No','Yes') as inc_inventory_yes_no
		      FROM edms_item_type WHERE inc_inventory=0
			  ORDER BY item_type";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		return $resultArray;	  
	}
	catch(Exception $e)
	{
	}
	
}	

function getNumberOfItemTypes()
{
	$sql="SELECT count(item_type_id)
		      FROM edms_item_type
			  ORDER BY item_type";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		return $resultArray[0][0];	
	
	}
function insertItemType($item_type,$inc_inventory){
	
	try
	{
		$item_type=clean_data($item_type);
		$item_type = ucwords(strtolower($item_type));
		if(validateForNull($item_type) && checkForNumeric($inc_inventory) && !checkForDuplicateItemType($item_type))
		{
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$sql="INSERT INTO edms_item_type
		      (item_type, inc_inventory, created_by, last_updated_by, date_added, date_modified)
			  VALUES
			  ('$item_type', $inc_inventory, $admin_id, $admin_id, NOW(), NOW())";
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

function deleteItemType($id){
	
	try
	{
		if(checkForNumeric($id) && !checkIfItemTypeInUse($id))
		{
		$sql="DELETE FROM edms_item_type
		      WHERE item_type_id=$id";
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

function updateItemType($id,$type){
	
	try
	{
		$type=clean_data($type);
		$type = ucwords(strtolower($type));
		if(checkForNumeric($id) && validateForNull($type) && !checkForDuplicateItemType($type,$id))
		{
			
		$sql="UPDATE edms_item_type
		      SET item_type='$type'
			  WHERE item_type_id=$id";
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

function getItemTypeById($id){
	
	try
	{
		$sql="SELECT item_type_id, item_type, inc_inventory , IF(inc_inventory=0,'No','Yes') as inc_inventory_yes_no
		      FROM edms_item_type
			  WHERE item_type_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0];	 
	}
	catch(Exception $e)
	{
	}
	
}	
function getItemTypeNameById($id){
	
	try
	{
		$sql="SELECT item_type_id, item_type
		      FROM edms_item_type
			  WHERE item_type_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][1];	 
	}
	catch(Exception $e)
	{
	}
	
}	

function checkForDuplicateItemType($item_type,$id=false)
{
	    if(validateForNull($item_type))
		{
		$sql="SELECT item_type_id
		      FROM edms_item_type
			  WHERE item_type='$item_type'";
		if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND item_type_id!=$id";		  	  
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return true;
		else
		return false;
		}
	}	
function checkIfItemTypeInUse($id)
{
	if(checkForNumeric($id))
	{
	$sql="SELECT item_type_id FROM
			edms_inventory_item
			WHERE item_type_id=$id";
	$result=dbQuery($sql);
	if(dbNumRows($result)>0)
	return true;
	else
	return false;		
	}
	
	}	
?>