<?php 
require_once("cg.php");
require_once("common.php");
require_once("city-functions.php");
require_once("area-functions.php");
require_once("customer-functions.php");
require_once("common.php");
require_once("bd.php");



		

function insertCustomerExtraDetails($dob, $address, $secondary_address="", $profession_id=-1, $data_from_id=-1, $customer_nationality, $city_id=-1,$area_id=-1, $customer_id)
{	
	try
	{
				
		$dob=clean_data($dob);
		$address=clean_data($address);
		$secondary_address = clean_data($secondary_address);
		$admin_id=$_SESSION['EMSadminSession']['admin_id'];
		
		$dob=str_replace('/','-',$dob);
	    $dob=date('Y-m-d',strtotime($dob));
		
		
		if(!validateForNull($dob))
		$dob="1970-01-01 00:00:00";
		
		if($city_id==-1)
		$city_id="NULL";
		if($area_id==-1)
		$area_id="NULL";
		else
		{
			if($city_id==-1);
			$city=DEF_CITY_ID;
		    $area_id = insertArea($area_id,$city);	
		}
		
		
		
		if($profession_id==-1)
		$profession_id="NULL";
		
		if($data_from_id==-1 || !validateForNull($data_from_id))
		$data_from_id="NULL";
			
			if(checkForNumeric($customer_id))
			{
				
				
				$sql="INSERT INTO ems_customer_extra_details(customer_dob, customer_address, secondary_address, profession_id, city_id, area_id,customer_nationality, customer_id, data_from_id)				
				      VALUES ('$dob', '$address', '$secondary_address', $profession_id, $city_id, $area_id, $customer_nationality, $customer_id, $data_from_id)";
				
				
				$result=dbQuery($sql);
				
				
				
				return "success";
			}
			else
			{
				return false;
			}
		
		
	}
	catch(Exception $e)
	{
	}
	
}	



function insertCustomerCityAndArea($city_id, $area_id, $customer_id)
{	
	try
	{
		
		if($city_id==-1)
		$city_id="NULL";
		if($area_id==-1)
		$area_id="NULL";
		else
		{
			if($city_id==-1);
			$city=DEF_CITY_ID;
		 $area_id = insertArea($area_id,$city);	
		}
		
		
			
			if(checkForNumeric($customer_id))
			{
				
				
				$sql="INSERT INTO ems_customer_extra_details( city_id, area_id, customer_id)				
				      VALUES ($city_id, $area_id, $customer_id)";
				 
				
				$result=dbQuery($sql);
				
				return "success";
			}
			else
			{
				return false;
			}
		
		
	}
	catch(Exception $e)
	{
	}
	
}	





function getExtraCustomerDetailsById($id)
{
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT extra_details_id, customer_dob, customer_address, secondary_address, profession_id, data_from_id, customer_nationality, city_id, area_id, customer_id
			  FROM ems_customer_extra_details
			  WHERE customer_id=$id";
	    
		
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0];
		else
		return false;
		}
	}
	catch(Exception $e)
	{
	}
	
}	



function updateExtraCustomerDetails($id,$dob,$address, $secondary_address, $profession_id, $data_from_id=-1, $customer_nationality, $city_id, $area_id=-1)
{
	
	
	try
	{
	   
		
		$dob=clean_data($dob);
		$dob=str_replace('/','-',$dob);
	    $dob=date('Y-m-d',strtotime($dob));
	
		$address=clean_data($address);
		$secondary_address=clean_data($secondary_address);
		
		$admin_id=$_SESSION['EMSadminSession']['admin_id'];
		
			if(!validateForNull($dob))
		$dob="1970-01-01 00:00:00";
		
		if($city_id==-1)
		$city_id="NULL";
		if($area_id==-1)
		$area_id="NULL";
		else
		{
			if($city_id==-1);
			$city=DEF_CITY_ID;
		    $area_id = insertArea($area_id,$city);	
		}
		if($profession_id==-1)
		$profession_id="NULL";
		
		if($data_from_id==-1 || !validateForNull($data_from_id))
		$data_from_id="NULL";
			
			if(validateForNull($address))
			{
				
				$sql="UPDATE ems_customer_extra_details
				     SET customer_address = '$address', secondary_address='$secondary_address', profession_id=$profession_id, data_from_id=$data_from_id, customer_nationality = $customer_nationality, customer_dob = '$dob', city_id = $city_id, area_id =$area_id
					 WHERE customer_id=$id";
			
			   
			    
				$result=dbQuery($sql);
				
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


function deletetExtraCustomerDetails($id){
	
	try
	{
		if(1==1)
		{
		$sql="DELETE FROM ems_customer_extra_details 
		      WHERE customer_id=$id";
		
		
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
?>