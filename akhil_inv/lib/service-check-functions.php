<?php 
require_once("cg.php");
require_once("common.php");
require_once("service-check-value-functions.php");
require_once("bd.php");
		
function listServiceChecks(){ // 0= radio, 1= cb, 2= yes or no
	
	try
	{
		$sql="SELECT service_check_id, service_check, check_type, IF(check_type=0,'Single Value Selection',IF(check_type=1,'Multiple Value  Selection','Yes or No')) as type
		      FROM edms_service_check
			  ORDER BY service_check";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		return $resultArray;	  
	}
	catch(Exception $e)
	{
	}
	
}	

function listServiceChecksOrderByType(){ // 0= radio, 1= cb, 2= yes or no
	
	try
	{
		$sql="SELECT service_check_id, service_check, check_type, IF(check_type=0,'Single Value Selection',IF(check_type=1,'Multiple Value  Selection','Yes or No')) as type
		      FROM edms_service_check
			  ORDER BY check_type";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		return $resultArray;	  
	}
	catch(Exception $e)
	{
	}
	
}	

function getNumberOfServiceChecks()
{
	$sql="SELECT count(service_check_id)
		      FROM edms_service_check
			  ORDER BY service_check";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		return $resultArray[0][0];	
	
	}
function insertServiceCheck($service_check,$check_type){
	
	try
	{
		$service_check=clean_data($service_check);
		$service_check = ucwords(strtolower($service_check));
		if(validateForNull($service_check) && checkForNumeric($check_type) && !checkForDuplicateServiceCheck($service_check,$check_type))
		{
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$sql="INSERT INTO edms_service_check
		      (service_check, check_type, created_by, last_updated_by, date_added, date_modified)
			  VALUES
			  ('$service_check', $check_type, $admin_id, $admin_id, NOW(), NOW())";
		dbQuery($sql);	
		$service_check_id = dbInsertId();
		if($check_type==2)
		{
			insertServiceCheckValue('No',$service_check_id);
			insertServiceCheckValue('Yes',$service_check_id);	
		}  
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

function deleteServiceCheck($id){
	
	try
	{
		if(checkForNumeric($id) && !checkIfServiceCheckInUse($id))
		{
		$sql="DELETE FROM edms_service_check
		      WHERE service_check_id=$id";
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

function updateServiceCheck($id,$type,$check_type){
	
	try
	{
		$type=clean_data($type);
		$type = ucwords(strtolower($type));
		$in_use = checkIfServiceCheckInUse($id);
		$old_check = getServiceCheckById($id);
		$old_check_type = $old_check['check_type'];
		if(checkForNumeric($id,$check_type) && validateForNull($type) && !checkForDuplicateServiceCheck($type,$check_type,$id))
		{
			
		$sql="UPDATE edms_service_check
		      SET service_check='$type' ";
		if(!$in_use)
		$sql=$sql.", check_type = $check_type ";
		
		$sql=$sql." WHERE service_check_id=$id";
		
		if(!$in_use && $old_check_type!=$check_type)
		{
		if($check_type==2)	
		{
			deleteAllServiceCheckValueForServiceCheckId($id);
			insertServiceCheckValue('No',$id);
			insertServiceCheckValue('Yes',$id);
		}	
		
		}
		
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

function getServiceCheckById($id){
	
	try
	{
		$sql="SELECT service_check_id, service_check, check_type  , IF(check_type=0,'Single Value Selection',IF(check_type=1,'Multiple Value  Selection','Yes or No')) as type
		      FROM edms_service_check
			  WHERE service_check_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0];	 
	}
	catch(Exception $e)
	{
	}
	
}	
function getServiceCheckNameById($id){
	
	try
	{
		$sql="SELECT service_check_id, service_check
		      FROM edms_service_check
			  WHERE service_check_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][1];	 
	}
	catch(Exception $e)
	{
	}
	
}	

function checkForDuplicateServiceCheck($service_check,$check_type,$id=false)
{
	    if(validateForNull($service_check) && checkForNumeric($check_type))
		{
		$sql="SELECT service_check_id
		      FROM edms_service_check
			  WHERE service_check='$service_check' AND check_type = $check_type ";
		if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND service_check_id!=$id";		  	  
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return true;
		else
		return false;
		}
	}	
function checkIfServiceCheckInUse($id)
{
	if(checkForNumeric($id))
	{
	$sql="SELECT service_check_id FROM
			edms_jb_rel_service_check
			WHERE service_check_id=$id";
	$result=dbQuery($sql);
	if(dbNumRows($result)>0)
	return true;
	else
	return false;		
	}
	
	}	
?>