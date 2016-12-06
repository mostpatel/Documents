<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("customer-functions.php");
require_once("customer-extra-details-functions.php");
require_once("enquiry-functions.php");
require_once("rel-subcat-enquiry-functions.php");
require_once("rel-enquiry-group-functions.php");
require_once("common.php");
require_once("bd.php");
require_once("sms-functions.php");
require_once("refrence-functions.php");
require_once("km-functions.php");

function listAllLeads()
{
	
	try
	{
		$sql="SELECT form_id, customer_name, sub_cat_id, mobile_no, email_id, customer_type_id, discussion, reminder_date, date_added, created_by
			  FROM ems_walkin_form";
	    
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

function insertLead($prefix, $name, $sub_cat_id, $mrp, $unit_id, $qunatity_id, $attribute_name_subCatIdArray_attrTypeIdArray_array, $mobile, $email="NA", $discussion="", $customer_type_id=-1, $refrence, $reminder_date="1970-01-01", $enquiry_date, $budget,
$customer_id=NULL, $city_id=-1, $area_id=-1, $km, $sms_status, $enquiry_group_id)
{
	 if(defined('SHOW_QUANTITY') && SHOW_QUANTITY==0) 
	 {
		
		$element_in_mrp_array = count($sub_cat_id);
		$qunatity_id=array();
		for($i=0;$i<$element_in_mrp_array;$i++)
		{
			$qunatity_id[]=1;
		}
	  }
	  
	 
	
	if(checkForProductsInArray($sub_cat_id, $mrp, $qunatity_id))
{
	
	try
	{

	$admin_id=$_SESSION['EMSadminSession']['admin_id'];
	$adminDetails = getAdminUserByID($admin_id);
	
	$admin_name = $adminDetails['admin_name'];
	$admin_email = $adminDetails['admin_email'];
	$admin_number = $adminDetails['admin_phone'];
   
	
	if(!validateForNull($customer_id))
	{
		
		$customer_id = insertCustomer($name, $email, $mobile, $prefix);
		if( checkForNumeric($area_id) && $area_id>0)
		insertCustomerExtraDetails(NULL, NULL, NULL, -1, -1, 1, $city_id, $area_id, $customer_id);
	}
	
	if($customer_type_id == 3 && validateForNull($refrence))
	$refrence_id = insertRefrence($refrence);
	else 
	$refrence_id="NULL";
	
	
	
	$enquiry_id = insertEnquiry($customer_id, $discussion, $customer_type_id, $reminder_date, $enquiry_date, $budget, $sub_cat_id, $sms_status, $refrence_id);
	
	insertFollowUp($enquiry_id, $discussion, $reminder_date, 0, -1);
	
	insertKM($km, $enquiry_id, "NULL");
	updateRelEnquiryGroup($enquiry_id, $enquiry_group_id);
	
	
$total_mrp=insertArrayElements($enquiry_id, $sub_cat_id, $mrp, $unit_id, $qunatity_id, $attribute_name_subCatIdArray_attrTypeIdArray_array);

	
	updateTotalMRPForEnquiry($enquiry_id, $total_mrp);
		
		
	return $enquiry_id;
	}


	
	catch(Exception $e)
	{
		
	}
}
else
{
	 return "error";
}
	
}

function updateTotalMRPForEnquiry($id, $total_mrp)
{
	
	try
	{
		
		if(validateForNull($id) && checkForNumeric($id))
		{
		$sql="UPDATE ems_enquiry_form
			  SET total_mrp='$total_mrp'
			  WHERE enquiry_form_id=$id";
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





function deleteLead($id){
	
	try
	{
		if(!checkifWalkInFormInUse($id))
		{
		$sql="DELETE FROM ems_walkin_form
		      WHERE form_id=$id";
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

function checkifLeadInUse($id)
{
	
	return false;
	
}		
	

function updateLead($id, $name, $sub_cat_id, $mobile, $email, $discussion, $customer_type_id, $reminder_date)
{
	
	try
	{
		$name=clean_data($name);
		$name = ucwords(strtolower($name));
		if(validateForNull($name, $mobile) && checkForNumeric($sub_cat_id))
		{
		$sql="UPDATE ems_walkin_form
			  SET customer_name='$name', sub_cat_id=$sub_cat_id, mobile_no=$mobile, email_id='$email', customer_type_id=$customer_type_id, discussion='$discussion', reminder_date='$reminder_date'
			  WHERE form_id=$id";
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



function getLeadFormById($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT form_id, customer_name, sub_cat_id, mobile_no, email_id, customer_type_id, discussion, reminder_date, date_added, created_by
			  FROM ems_walkin_form
			  WHERE form_id=$id";
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


	
?>