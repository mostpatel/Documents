<?php 
require_once("cg.php");
require_once("common.php");
require_once("bd.php");
		
function listPackingUnits(){
	
	try
	{
		$sql="SELECT packing_unit_id, packing_unit
		      FROM edms_packing_unit
			  ORDER BY packing_unit_id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		return $resultArray;	  
	}
	catch(Exception $e)
	{
	}
	
}	

function getNumberOfPackingUnits()
{
	$sql="SELECT count(packing_unit_id)
		      FROM edms_packing_unit
			  ORDER BY packing_unit";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		return $resultArray[0][0];	
	
	}
function insertPackingUnit($packing_unit){
	
	try
	{
		$packing_unit=clean_data($packing_unit);
		$packing_unit = ucwords(strtolower($packing_unit));
		if(validateForNull($packing_unit) && !checkForDuplicatePackingUnit($packing_unit))
		{
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$sql="INSERT INTO edms_packing_unit
		      (packing_unit, created_by, last_updated_by, date_added, date_modified)
			  VALUES
			  ('$packing_unit', $admin_id, $admin_id, NOW(), NOW())";
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


function insertPackingUnitIfNotDuplicate($packing_unit,$bd2=false){
	
	try
	{
		$packing_unit=clean_data($packing_unit);
		$packing_unit = ucwords(strtolower($packing_unit));
		$duplicate = checkForDuplicatePackingUnit($packing_unit,false,$bd2);
		if(validateForNull($packing_unit) && !$duplicate)
		{
		if($bd2)
		$admin_id = DEFAULT_ADMIN_ID;
		else	
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$sql="INSERT INTO edms_packing_unit
		      (packing_unit, created_by, last_updated_by, date_added, date_modified)
			  VALUES
			  ('$packing_unit', $admin_id, $admin_id, NOW(), NOW())";
		dbQuery($sql,$bd2);	  
		return dbInsertId($bd2);
		}
		else if(checkForNumeric($duplicate))
		return $duplicate;
		else
		{
			return "error";
			}
	}
	catch(Exception $e)
	{
	}
	
}	

function deletePackingUnit($id){
	
	try
	{
		if(checkForNumeric($id) && !checkIfPackingUnitInUse($id))
		{
		$sql="DELETE FROM edms_packing_unit
		      WHERE packing_unit_id=$id";
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

function updatePackingUnit($id,$type){
	
	try
	{
		$type=clean_data($type);
		$type = ucwords(strtolower($type));
		
		if(checkForNumeric($id) && validateForNull($type) && !checkForDuplicatePackingUnit($type,$id))
		{
			
		$sql="UPDATE edms_packing_unit
		      SET packing_unit='$type'
			  WHERE packing_unit_id=$id";
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

function getPackingUnitById($id){
	
	try
	{
		$sql="SELECT packing_unit_id, packing_unit
		      FROM edms_packing_unit
			  WHERE packing_unit_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0];	 
	}
	catch(Exception $e)
	{
	}
	
}	
function getPackingUnitNameById($id){
	
	try
	{
		$sql="SELECT packing_unit_id, packing_unit
		      FROM edms_packing_unit
			  WHERE packing_unit_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][1];	 
	}
	catch(Exception $e)
	{
	}
	
}	

function checkForDuplicatePackingUnit($packing_unit,$id=false,$bd2=false)
{
	    if(validateForNull($packing_unit))
		{
		$sql="SELECT packing_unit_id
		      FROM edms_packing_unit
			  WHERE packing_unit='$packing_unit'";
		if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND packing_unit_id!=$id";		  	  
		$result=dbQuery($sql,$bd2);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0];
		else
		return false;
		}
	}	
function checkIfPackingUnitInUse($id)
{
	if(checkForNumeric($id))
	{
	$sql="SELECT packing_unit_id FROM
			edms_lr_product
			WHERE packing_unit_id=$id";
	$result=dbQuery($sql);
	if(dbNumRows($result)>0)
	return true;
	else
	return false;		
	}
	
	}	
?>