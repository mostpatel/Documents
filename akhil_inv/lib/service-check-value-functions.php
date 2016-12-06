<?php 
require_once("cg.php");
require_once("common.php");
require_once("bd.php");
require_once("service-check-functions.php");
		
function listServiceCheckValuesForServiceCheck($service_check_id){ // 0= radio, 1= cb, 2= yes or no
	try
	{
		if(checkForNumeric($service_check_id))
		{
		$sql="SELECT service_check_value_id, service_check_value, service_check_id
		      FROM edms_service_check_values WHERE service_check_id = $service_check_id
			  ORDER BY service_check_value_id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		return $resultArray;	  
		}
		return false;
	}
	catch(Exception $e)
	{
	}
	
}	

function listServiceCheckValuesForServiceCheckForJobCardId($service_check_id,$job_card_id){ // 0= radio, 1= cb, 2= yes or no
	try
	{
		if(checkForNumeric($service_check_id))
		{
		$sql="SELECT service_check_value_id, service_check_id
		      FROM edms_jb_rel_service_check WHERE service_check_id = $service_check_id AND job_card_id = $job_card_id
			  ORDER BY service_check_value_id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		$returnArray = array();
		if(dbNumRows($result)>0)
		{
			foreach($resultArray as $re)
			$returnArray[] = $re['service_check_value_id'];
		}
		return $returnArray;	  
		}
		return array();
	}
	catch(Exception $e)
	{
	}
	
}	

function getNumberOfServiceCheckValues()
{
	$sql="SELECT count(service_check_value_id)
		      FROM edms_service_check_values
			  ORDER BY service_check_value";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		return $resultArray[0][0];	
	
	}
function insertServiceCheckValue($service_check_value,$service_check_id){
	
	try
	{
		$service_check_value=clean_data($service_check_value);
		$service_check_value = ucwords(strtolower($service_check_value));
		if(validateForNull($service_check_value) && checkForNumeric($service_check_id) && !checkForDuplicateServiceCheckValue($service_check_value,$service_check_id))
		{
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$sql="INSERT INTO edms_service_check_values
		      (service_check_value, service_check_id, created_by, last_updated_by, date_added, date_modified)
			  VALUES
			  ('$service_check_value', $service_check_id, $admin_id, $admin_id, NOW(), NOW())";
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

function deleteServiceCheckValue($id){
	
	try
	{
		if(checkForNumeric($id) && !checkIfServiceCheckValueInUse($id))
		{
		$sql="DELETE FROM edms_service_check_values
		      WHERE service_check_value_id=$id";
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


function deleteAllServiceCheckValueForServiceCheckId($service_check_id){
	
	try
	{
		if(checkForNumeric($service_check_id) && !checkIfServiceCheckInUse($service_check_id))
		{
		$sql="DELETE FROM edms_service_check_values
		      WHERE service_check_id=$service_check_id";
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


function getServiceCheckForServiceCheckValue($id)
{
	if(checkForNumeric($id))
	{
	$service_check_value=getServiceCheckValueById($id);
	return $service_check_value['service_check_id'];
	}
}

function updateServiceCheckValue($id,$type){
	
	try
	{
		
		$type=clean_data($type);
		$type = ucwords(strtolower($type));
		$service_check_id = getServiceCheckForServiceCheckValue($id);
		if(checkForNumeric($id,$service_check_id) && validateForNull($type) && !checkForDuplicateServiceCheckValue($type,$service_check_id,$id))
		{
			
		$sql="UPDATE edms_service_check_values
		      SET service_check_value='$type', service_check_id = $service_check_id
			  WHERE service_check_value_id=$id";
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

function getServiceCheckValueById($id){
	
	try
	{
		$sql="SELECT service_check_value_id, service_check_value, service_check_id
		      FROM edms_service_check_values
			  WHERE service_check_value_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0];	 
	}
	catch(Exception $e)
	{
	}
	
}	
function getServiceCheckValueNameById($id){
	
	try
	{
		$sql="SELECT service_check_value_id, service_check_value
		      FROM edms_service_check_values
			  WHERE service_check_value_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][1];	 
	}
	catch(Exception $e)
	{
	}
	
}	

function checkForDuplicateServiceCheckValue($service_check_value,$service_check_id,$id=false)
{
	    if(validateForNull($service_check_value) && checkForNumeric($service_check_id))
		{
		$sql="SELECT service_check_value_id
		      FROM edms_service_check_values
			  WHERE service_check_value='$service_check_value' AND service_check_id = $service_check_id ";
		if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND service_check_value_id!=$id";		  	  
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return true;
		else
		return false;
		}
	}	
function checkIfServiceCheckValueInUse($id)
{
	if(checkForNumeric($id))
	{
	$sql="SELECT service_check_value_id FROM
			edms_jb_rel_service_check
			WHERE service_check_value_id=$id";
	$result=dbQuery($sql);
	if(dbNumRows($result)>0)
	return true;
	else
	return false;		
	}
	
}
?>