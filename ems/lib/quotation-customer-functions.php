<?php 
require_once("cg.php");
require_once("common.php");
require_once("city-functions.php");
require_once("area-functions.php");
require_once("image-functions.php");
require_once("common.php");
require_once("bd.php");



		
function insertQuotationCustomer($name,$email="NA",$contact_no, $address="NA", $city_id, $customer_id, $quotation_date)
{	
	try
	{
		$name=clean_data($name);
		$address=clean_data($address);
		$email=clean_data($email);
		$admin_id=$_SESSION['EMSadminSession']['admin_id'];
		
			
		
			$name = ucwords(strtolower($name));
			if(validateForNull($name,$email) && $contact_no!=null && !empty($contact_no))
			{
				
				$sql="INSERT INTO ems_quotation_customer (quo_customer_name, quo_customer_email, quo_customer_address, city_id, customer_id, quotation_date)				
				      VALUES ('$name', '$email', '$address', $city_id, $customer_id, '$quotation_date')";
				
				$result=dbQuery($sql);
				$quotation_customer_id=dbInsertId();		
				addQuotationCutomerContactNo($quotation_customer_id, $contact_no);
				
				
				return $quotation_customer_id;
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

	




	


function addQuotationCutomerContactNo($quo_customer_id, $contact_no)
{
	try
	{
		if(is_array($contact_no))
		{
			foreach($contact_no as $no)
			{
				if(checkForNumeric($no))
				{
				insertQuotationContactNoCustomer($quo_customer_id,$no); 
				}
			}
		}
		else
		{
			
			if(checkForNumeric($contact_no))
				{
				insertQuotationContactNoCustomer($quo_customer_id,$contact_no); 
				}
			
		}
	}
	catch(Exception $e)
	{
	}
}

function insertQuotationContactNoCustomer($id,$contact_no)
{
	try
	{
		
		if(checkForNumeric($id)==true && checkForNumeric($contact_no))
		{
			
		$sql="INSERT INTO ems_quotation_customer_contact_no
				      (quo_customer_contact_no, quo_customer_id)
					  VALUES
					  ('$contact_no', $id)";
				dbQuery($sql);	  
		}
	}
	catch(Exception $e)
	{}
	
	
}
function deleteQuotationContactNoCustomer($id)
{
	try
	{
		$sql="DELETE FROM ems_quotation_customer_contact_no
			  WHERE quo_customer_contact_no_id=$id";
		dbQuery($sql);	  
	}
	catch(Exception $e)
	{}
	
	
	
	}
function deleteAllQuotationContactNoCustomer($id)
{
	try
	{
		$sql="DELETE FROM ems_quotation_customer_contact_no
			  WHERE quo_customer_id=$id";
		dbQuery($sql);
	}
	catch(Exception $e)
	{}
	
	
	
	}	





function getQuotationCustomerById($id)
{
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT quo_customer_id, quo_customer_name, quo_customer_email, quo_customer_address, city_id, customer_id,     quotation_date
			  FROM ems_quotation_customer
			  WHERE quo_customer_id=$id";
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


function getQuotationCustomerByCustomerId($customer_id)
{
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT quo_customer_id, quo_customer_name, quo_customer_email, quo_customer_address, city_id, customer_id, quotation_date
			  FROM ems_quotation_customer
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

function getQuotationCustomerContactNo($id)
{
	if(checkForNumeric($id))
	{
		$sql="SELECT quo_customer_contact_no 
		      FROM ems_quotation_customer_contact_no
			  WHERE quo_customer_id=$id";
				$result=dbQuery($sql);	  
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray;
		else
		return false;
		}
	}


			
?>