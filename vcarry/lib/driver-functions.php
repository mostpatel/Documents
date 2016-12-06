<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("account-ledger-functions.php");
require_once("account-head-functions.php");
require_once("common.php");
require_once("bd.php");

function listDrivers(){
	
	try
	{
		$sql="SELECT `driver_id`, `driver_name`, `contact_no_1`, `contact_no_2`, `vehicle_type_id`, edms_driver.`area_id`, `type`, `fixed_amount`, `share_expense`, edms_driver.`created_by`, edms_driver.`last_updated_by`, edms_driver.`date_added`, edms_driver.`date_modified`, edms_driver.`ledger_id`, email, multi_trip
		  FROM edms_driver INNER JOIN edms_ac_ledgers ON edms_ac_ledgers.ledger_id = edms_driver.ledger_id ORDER BY driver_name";
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

function insertDriver($driver_name,$contact_no_1,$contact_no_2,$vehicle_type_id,$area_id,$type,$fixed_amount,$share_expense,$email,$multi_trip){
	
	try
	{
		$driver_name=clean_data($driver_name);
		$driver_name = ucwords(strtolower($driver_name));
		$contact_no_1=clean_data($contact_no_1);
		$contact_no_2=clean_data($contact_no_2);
		$vehicle_type_id=clean_data($vehicle_type_id);
		$area_id=clean_data($area_id);
		$fixed_amount=clean_data($fixed_amount);
	    $share_expense=clean_data($share_expense);
		$email=clean_data($email);
		if(!checkForNumeric($multi_trip))
		$multi_trip=0;
		if($contact_no_2==NULL || $contact_no_2=="")
		{
			$contact_no_2=0;
			}	
		if(validateForNull($driver_name,$email) && !checkForDuplicateDriver($driver_name,$email) && checkForNumeric($contact_no_1,$contact_no_2,$vehicle_type_id,$area_id,$type,$fixed_amount,$share_expense,$multi_trip))
			{
				
			$ledger_id=insertLedger($driver_name,"","",-1,"NA",NULL,getSundryCreditorsId(),NULL,NULL,NULL,"",0,0,NULL,7);	
			if(checkForNumeric($ledger_id))
			{
			$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
			$sql="INSERT INTO edms_driver
					(`driver_name`, `contact_no_1`, `contact_no_2`, `vehicle_type_id`, `area_id`, `type`, `fixed_amount`, `share_expense`, `created_by`, `last_updated_by`, `date_added`, `date_modified`, edms_driver.`ledger_id`, email, multi_trip)
					VALUES
					('$driver_name','$contact_no_1','$contact_no_2',$vehicle_type_id,$area_id,$type,$fixed_amount,$share_expense,$admin_id,$admin_id,NOW(),NOW(),$ledger_id,'$email',$multi_trip)";
			
			dbQuery($sql);
			$driver_id=dbInsertId();
			return $driver_id;
			}
			else
			return "error";
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

function checkIfDriverIsInUse($driver_id)
{
	
	$sql="SELECT driver_id FROM fin_file WHERE driver_id=$driver_id";
	$result=dbQuery($sql);
	
	if(dbNumRows($result)>0)
	{	
	return true;
	}
	else
	return false;
	
	}	

function deleteDriver($id){
	
	try
	{
		if(checkForNumeric($id) && !checkIfDriverIsInUse($id))
		{
		$sql="DELETE FROM edms_driver WHERE driver_id=$id";
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

function updateDriver($id,$driver_name,$contact_no_1,$contact_no_2,$vehicle_type_id,$area_id,$type,$fixed_amount,$share_expense,$email,$multi_trip){
	
	try
	{
		$driver_name=clean_data($driver_name);
		$driver_name = ucwords(strtolower($driver_name));
		$contact_no_1=clean_data($contact_no_1);
		$contact_no_2=clean_data($contact_no_2);
		$vehicle_type_id=clean_data($vehicle_type_id);
		$area_id=clean_data($area_id);
		$fixed_amount=clean_data($fixed_amount);
		$email=clean_data($email);
	    $share_expense=clean_data($share_expense);
		
		if($contact_no_2==NULL || $contact_no_2=="")
		{
			$contact_no_2=0;
			}	
			if(!checkForNumeric($multi_trip))
		$multi_trip=0;
		if(validateForNull($driver_name,$email) && !checkForDuplicateDriver($driver_name,$email,$id) && checkForNumeric($contact_no_1,$contact_no_2,$vehicle_type_id,$area_id,$type,$fixed_amount,$share_expense))
		{
			
			$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
			$sql="UPDATE edms_driver
					SET `driver_name`='$driver_name',`contact_no_1`='$contact_no_1',`contact_no_2`=$contact_no_2,`vehicle_type_id`=$vehicle_type_id,`area_id`=$area_id,`type`=$type,`fixed_amount`=$fixed_amount,`share_expense`=$share_expense,`last_updated_by`=$admin_id,`date_modified`=NOW(), email = '$email', multi_trip = 0
					WHERE driver_id=$id";
			
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

function getDriverById($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT *
		  FROM edms_driver
		  WHERE driver_id=$id";
		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0]; 
		else
		return false;
		}
		return false;
	}
	catch(Exception $e)
	{
	}
	
}

function getDriverSByVehicleTypeId($id){
	
	try
	{
		$sql="SELECT *
		  FROM edms_driver
		  WHERE driver_id=$id";
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

function getDriverNameFromDriverId($id)
{
try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT  driver_name
		  FROM edms_driver
		  WHERE driver_id=$id";
		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0]; 
		else
		return false;
		}
		return false;
	}
	catch(Exception $e)
	{
	}	
}

function checkForDuplicateDriver($driver_name,$email,$id=false)
{
	if(validateForNull($driver_name))
	{
	$sql="SELECT  driver_id
		  FROM edms_driver
		  WHERE (driver_name='$driver_name' OR email = '$email') ";
	if(checkForNumeric($id))
	$sql=$sql." AND driver_id!= $id";	  
		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0]; 
		else
		return false;
	}
	return true;
}

function getDriverIdFromDriverEmail($driver_email)
{
	if(validateForNull($driver_email))
	{
		$sql="SELECT driver_id FROM edms_driver WHERE email='$driver_email'";
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0];
		else
		return false;
	}
	return false;
}


?>