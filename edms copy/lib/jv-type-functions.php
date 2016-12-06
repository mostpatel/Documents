<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("common.php");
require_once("bd.php");
		
function listJVTypes(){
	
	try
	{
		$sql="SELECT jv_type_id, jv_type
		      FROM edms_ac_jv_types
			  ORDER BY jv_type";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		return $resultArray;	  
	}
	catch(Exception $e)
	{
	}
	
}	

function listAccountJVTypes(){
	
	try
	{
		$sql="SELECT jv_type_id, jv_type
		      FROM edms_ac_jv_types
			  WHERE jv_type_mode = 0
			  ORDER BY jv_type";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		return $resultArray;	  
	}
	catch(Exception $e)
	{
	}
	
}	

function listInventoryJVTypes(){
	
	try
	{
		$sql="SELECT jv_type_id, jv_type
		      FROM edms_ac_jv_types
			  WHERE jv_type_mode = 1
			  ORDER BY jv_type";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		return $resultArray;	  
	}
	catch(Exception $e)
	{
	}
	
}	


function getNumberOfJVTypes()
{
	$sql="SELECT count(jv_type_id)
		      FROM edms_ac_jv_types
			  ORDER BY jv_type";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		return $resultArray[0][0];	
	
	}
function insertJVType($jv_type,$jv_mode){
	
	try
	{
		$jv_type=clean_data($jv_type);
		$jv_type = ucwords(strtolower($jv_type));
		if(validateForNull($jv_type) && checkForNumeric($jv_mode) && !checkForDuplicateJVType($jv_type,$jv_mode))
		{
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$sql="INSERT INTO edms_ac_jv_types
		      (jv_type, jv_type_mode)
			  VALUES
			  ('$jv_type', $jv_mode)";
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

function deleteJVType($id){
	
	try
	{
		if(checkForNumeric($id) && !checkIfJVTypeInUse($id))
		{
		$sql="DELETE FROM edms_ac_jv_types
		      WHERE jv_type_id=$id";
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

function updateJVType($id,$type,$mode){
	
	try
	{
		$type=clean_data($type);
		$type = ucwords(strtolower($type));
		if(checkForNumeric($id,$mode) && validateForNull($type) && !checkForDuplicateJVType($type,$mode,$id))
		{
			
		$sql="UPDATE edms_ac_jv_types
		      SET jv_type='$type'
			  WHERE jv_type_id=$id";
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

function getJVTypeById($id){
	
	try
	{
		$sql="SELECT jv_type_id, jv_type, jv_type_mode
		      FROM edms_ac_jv_types
			  WHERE jv_type_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0];	 
	}
	catch(Exception $e)
	{
	}
	
}	
function getJVTypeNameById($id){
	
	try
	{
		
		if(checkForNumeric($id))
		{
		$sql="SELECT jv_type_id, jv_type
		      FROM edms_ac_jv_types
			  WHERE jv_type_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][1];	 
		}
		else 
		return "Not Added";
	}
	catch(Exception $e)
	{
	}
	
}	

function checkForDuplicateJVType($jv_type,$mode,$id=false)
{
	    if(validateForNull($jv_type) && checkForNumeric($mode))
		{
		$sql="SELECT jv_type_id
		      FROM edms_ac_jv_types
			  WHERE jv_type='$jv_type' AND jv_type_mode = $mode ";
		if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND jv_type_id!=$id";		  	  
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return true;
		else
		return false;
		}
	}	
function checkIfJVTypeInUse($id)
{
	if(checkForNumeric($id))
	{
	$sql="SELECT jv_type_id FROM
			edms_ac_jv
			WHERE jv_type_id=$id
			UNION ALL
			SELECT jv_type_id FROM edms_inventory_jv
			WHERE jv_type_id=$id";
	$result=dbQuery($sql);
	if(dbNumRows($result)>0)
	return true;
	else
	return false;		
	}
	
	}	
?>