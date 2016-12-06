<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("common.php");
require_once("bd.php");

function listItemManufacturers(){
	
	try
	{
		$sql="SELECT manufacturer_id, manufacturer_name, manufacturer_address, manufacturer_contact_no
		  FROM edms_item_manufacturer
		  ORDER BY manufacturer_name";
		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray; 
		else
		return false;
	}
	catch(Exception $e)
	{
	}
	
}	

function insertItemManufacturer($manufacturer_name,$manufacturer_address,$contact_no){
	
	try
	{
		$manufacturer_name=clean_data($manufacturer_name);
		$manufacturer_name = ucwords(strtolower($manufacturer_name));
		$manufacturer_address=clean_data($manufacturer_address);
		$duplcate=checkForDuplicateItemManufacturer($manufacturer_name,$manufacturer_address);
		
		if(!checkForNumeric($contact_no))
		$contact_no=0;
		
		if(validateForNull($manufacturer_name) && !$duplcate)
			{
			$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
			
			$sql="INSERT INTO edms_item_manufacturer
					(manufacturer_name, manufacturer_address, manufacturer_contact_no, created_by, last_updated_by, date_added, date_modified)
					VALUES
					('$manufacturer_name','$manufacturer_address',$contact_no
					,$admin_id,$admin_id,NOW(),NOW())";
			
			dbQuery($sql);
			$manufacturer_id=dbInsertId();
			return "success";
			}
		{
			return "error";
			}	
	}
	catch(Exception $e)
	{
	}
	
}


function insertItemManufacturerIFNotDuplicate($manufacturer_name,$manufacturer_address,$contact_no){
	
	try
	{
		$manufacturer_name=clean_data($manufacturer_name);
		$manufacturer_name = ucwords(strtolower($manufacturer_name));
		$manufacturer_address=clean_data($manufacturer_address);
		$duplcate=checkForDuplicateItemManufacturer($manufacturer_name,$manufacturer_address);
		
		if(!checkForNumeric($contact_no))
		$contact_no=0;
		
		if(validateForNull($manufacturer_name) && !$duplcate)
			{
			$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
			
			$sql="INSERT INTO edms_item_manufacturer
					(manufacturer_name, manufacturer_address, manufacturer_contact_no, created_by, last_updated_by, date_added, date_modified)
					VALUES
					('$manufacturer_name','$manufacturer_address',$contact_no
					,$admin_id,$admin_id,NOW(),NOW())";
			
			dbQuery($sql);
			$manufacturer_id=dbInsertId();
			return $manufacturer_id;
			}
			else if($duplcate)
			return $duplcate;
				
	}
	catch(Exception $e)
	{
	}
	
}


function deleteItemManufacturer($id){
	
	try
	{
		if(checkForNumeric($id) && !checkIfItemManufacturerInUse($id))
		{
		$sql="DELETE FROM edms_item_manufacturer WHERE manufacturer_id=$id";
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

function updateItemManufacturer($id,$manufacturer_name,$manufacturer_address,$contact_no){
	
	try
	{
		$manufacturer_name=clean_data($manufacturer_name);
		$manufacturer_name = ucwords(strtolower($manufacturer_name));
		$manufacturer_address=clean_data($manufacturer_address);
		
		if(!checkForNumeric($contact_no))
		$contact_no=0;
		
		if(validateForNull($manufacturer_name) && !checkForDuplicateItemManufacturer($manufacturer_name,$city_id,$id))
			{
			
			$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
			$sql="UPDATE edms_item_manufacturer
					SET manufacturer_name = '$manufacturer_name', manufacturer_address ='$manufacturer_address', manufacturer_contact_no = $contact_no, last_updated_by=$admin_id, date_modified=NOW()
					WHERE manufacturer_id=$id";
					
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

function getItemManufacturerById($id){
	
	try
	{
		$sql="SELECT manufacturer_id, manufacturer_name, manufacturer_address, manufacturer_contact_no
		  FROM edms_item_manufacturer
		  WHERE  manufacturer_id=$id";
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

function getNotAvailableItemManufacturer(){
	
	try
	{
		$sql="SELECT manufacturer_id, manufacturer_name, manufacturer_address, manufacturer_contact_no
		  FROM edms_item_manufacturer
		  WHERE  manufacturer_name='Not Available'";
		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0]; 
		else
		return insertItemManufacturerIFNotDuplicate('Not Available','',NULL);
	}
	catch(Exception $e)
	{
	}
	
}

function getItemManufacturerNameFromItemManufacturerId($id)
{
try
	{
		$sql="SELECT  manufacturer_name
		  FROM edms_item_manufacturer
		  WHERE manufacturer_id=$id";
		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0]; 
		else
		return false;
	}
	catch(Exception $e)
	{
	}	
}


	
function checkForDuplicateItemManufacturer($name,$manufacturer_address,$id=false)
{
	
	$sql="SELECT manufacturer_id
		  FROM edms_item_manufacturer
		  WHERE manufacturer_name='$name'
		  AND manufacturer_address= '$manufacturer_address'";
		if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND manufacturer_id!=$id";		  
		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0]; 
		else
		return false;
	
}


function checkIfItemManufacturerInUse($id)
{
	$sql="SELECT item_id FROM edms_inventory_item WHERE manufacturer_id=$id";
	$result=dbQuery($sql);
	if(dbNumRows($result)>0)
	return true;
	else
	return false;
	
	}

?>