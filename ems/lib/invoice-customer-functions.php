<?php 
require_once("cg.php");
require_once("common.php");
require_once("city-functions.php");
require_once("area-functions.php");
require_once("image-functions.php");
require_once("common.php");
require_once("bd.php");





		
function insertInvoiceCustomer($name,$email="NA",$contact_no, $address="NA", $city_id, $customer_id, $invoice_date){	
	try
	{
		$name=clean_data($name);
		$address=clean_data($address);
		$email=clean_data($email);
		$admin_id=$_SESSION['EMSadminSession']['admin_id'];
		
			
		
			$name = ucwords(strtolower($name));
			if(validateForNull($name,$email) && $contact_no!=null && !empty($contact_no))
			{
				
				$sql="INSERT INTO ems_invoice_customer (in_customer_name, in_customer_email, in_customer_address, city_id, customer_id, invoice_date)				
				      VALUES ('$name', '$email', '$address', $city_id, $customer_id, '$invoice_date')";
					  
				
				
				$result=dbQuery($sql);
				$invoice_customer_id=dbInsertId();		
				addInvoiceCutomerContactNo($invoice_customer_id,$contact_no);
				
				
				return $invoice_customer_id;
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

	




	


function addInvoiceCutomerContactNo($in_customer_id, $contact_no)
{
	try
	{
		if(is_array($contact_no))
		{
			foreach($contact_no as $no)
			{
				if(checkForNumeric($no))
				{
				insertInvoiceContactNoCustomer($in_customer_id,$no); 
				}
			}
		}
		else
		{
			
			if(checkForNumeric($contact_no))
				{
				insertInvoiceContactNoCustomer($in_customer_id,$contact_no); 
				}
			
		}
	}
	catch(Exception $e)
	{
	}
}

function insertInvoiceContactNoCustomer($id,$contact_no)
{
	try
	{
		
		if(checkForNumeric($id)==true && checkForNumeric($contact_no))
		{
			
		$sql="INSERT INTO ems_invoice_customer_contact_no
				      (in_customer_contact_no, in_customer_id)
					  VALUES
					  ('$contact_no', $id)";
				dbQuery($sql);	  
		}
	}
	catch(Exception $e)
	{}
	
	
}
function deleteInvoiceContactNoCustomer($id)
{
	try
	{
		$sql="DELETE FROM ems_invoice_customer_contact_no
			  WHERE in_customer_contact_no_id=$id";
		dbQuery($sql);	  
	}
	catch(Exception $e)
	{}
	
	
	
	}
function deleteAllInvoiceContactNoCustomer($id)
{
	try
	{
		$sql="DELETE FROM ems_invoice_customer_contact_no
			  WHERE in_customer_id=$id";
		dbQuery($sql);
	}
	catch(Exception $e)
	{}
	
	
	
	}	





function getInvoiceCustomerById($id)
{
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT in_customer_id, in_customer_name, in_customer_email, in_customer_address, city_id, customer_id, invoice_date
			  FROM ems_invoice_customer
			  WHERE in_customer_id=$id";
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


function getInvoiceCustomerByCustomerId($customer_id)
{
	
	try
	{
		if(checkForNumeric($customer_id))
		{
		$sql="SELECT in_customer_id, in_customer_name, in_customer_email, in_customer_address, city_id, customer_id, invoice_date
			  FROM ems_invoice_customer
			  WHERE customer_id=$customer_id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray;
		else
		return false;
		}
	}
	catch(Exception $e)
	{
	}
	
}	

function getInvoiceCustomerContactNo($id)
{
	if(checkForNumeric($id))
	{
		$sql="SELECT in_customer_contact_no 
		      FROM ems_invoice_customer_contact_no
			  WHERE in_customer_id=$id";
				$result=dbQuery($sql);	  
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray;
		else
		return false;
		}
	}


			
?>