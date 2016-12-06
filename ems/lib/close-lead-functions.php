<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("follow-up-functions.php");
require_once("sms-functions.php");
require_once("rel-subcat-enquiry-functions.php");
require_once("customer-functions.php");
require_once("adminuser-functions.php");
require_once("prefix-functions.php");
require_once("common.php");
require_once("bd.php");


	
function listCloseLead(){
	
	try
	{
		$sql="SELECT follow_up_id, discussion, enquiry_form_id, created_by, date_added
			  FROM ems_follow_up";
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



function insertCloseLead($is_bought, $decline_reason, $note, $enquiry_form_id, $purchase_date, $tour_ending_date, $sms_status)
{
	try
	{
		$note=clean_data($note);
		
		$admin_id=$_SESSION['EMSadminSession']['admin_id'];
		
		    $purchase_date=str_replace('/','-',$purchase_date);
	        $purchase_date=date('Y-m-d',strtotime($purchase_date));
		    if(!validateForNull($purchase_date))
			$purchase_date="1970-01-01 00:00:00";
			
			$tour_ending_date=str_replace('/','-',$tour_ending_date);
	        $tour_ending_date=date('Y-m-d',strtotime($tour_ending_date));
		    if(!validateForNull($tour_ending_date))
			$tour_ending_date="1970-01-01 00:00:00";
	 	
		
			$sql="UPDATE ems_enquiry_form
			  SET is_bought=$is_bought, enquiry_close_date=NOW(), enquiry_closed_by=$admin_id, purchase_date='$purchase_date', tour_ending_date = '$tour_ending_date'
			  WHERE ems_enquiry_form.enquiry_form_id=$enquiry_form_id";
			  
			
			
		  
		$result=dbQuery($sql);
		
		
		$adminDetails = getAdminUserByID($admin_id);
		
		
		
	
		$admin_name = $adminDetails['admin_name'];
		$admin_email = $adminDetails['admin_email'];
		$admin_number = $adminDetails['admin_phone'];
		
		
		
		$customerDetails = getCustomerByEnquiryId($enquiry_form_id);
		$customer_id = $customerDetails['customer_id'];
		
		$customer = getCustomerById($customer_id);
		$prefix_id = $customer['prefix_id'];
		$prefixDetails = getPrefixById($prefix_id);
		$customer_prefix = $prefixDetails['prefix'];
		$contact_nos = getCustomerContactNo($customer_id);
		
		$relSubCatEnquiryData = getRelSubCatEnquiryFromEnquiryId($enquiry_form_id);
		$sub_cat_id = $relSubCatEnquiryData[0]['sub_cat_id'];
		
		if($sms_status==1)
		{
		
		if($is_bought==1)
		{
			
			
			
			foreach($contact_nos as $contact_no)
				{
					
				if(checkForNumeric($contact_no[0]) && strlen($contact_no[0])==10)
				
				
				{	
				  sendNewLeadSMS($customer['customer_name'], $contact_no[0], $admin_name, $admin_number, $admin_email, $type=3, $sub_cat_id);
				
				}
				}
		}
		
		else if($is_bought==2)
		{
			foreach($contact_nos as $contact_no)
				{
					
				if(checkForNumeric($contact_no[0]) && strlen($contact_no[0])==10)
					
					{
				sendNewLeadSMS($customer['customer_name'], $contact_no[0], $admin_name, $admin_number, $admin_email, $type=4, $sub_cat_id);
				
					}
				}
		
		}
		
		}
		
		if($decline_reason==-1)
		$decline_reason='NULL';
		
		if(!validateForNull($note))
		{
			$note="NA";
			
		}
		if($is_bought==2)
		{
			
		$sql2="INSERT INTO 
				ems_not_bought (enquiry_form_id, discussion, decline_id)
				VALUES ($enquiry_form_id, '$note', $decline_reason)";
				
		
	
		$result=dbQuery($sql2);
		}
		return "success";
		
		
		
	}
	catch(Exception $e)
	{
	}
	
}






function deleteCloseLead($id)
{
	
	$sql="DELETE FROM ems_ab_booking_form
		  WHERE enquiry_id=$id";
	dbQuery($sql);
	
	try
	{
		$followUpCount = countFollowUpsForEnquiryId($id);
		
		if($followUpCount==0)
		{
		
		
		$sql="UPDATE ems_enquiry_form
			  SET is_bought=0, enquiry_close_date='1970-01-01'
			  WHERE enquiry_form_id=$id";
		
	  
		dbQuery($sql);
		}
		else if($followUpCount>0)
		
		{
			
			$sql="UPDATE ems_enquiry_form
			  SET is_bought=3, enquiry_close_date='1970-01-01'
			  WHERE enquiry_form_id=$id";
			  
		dbQuery($sql);
	
			
		}
		
		$sql="DELETE from ems_not_bought
			  WHERE enquiry_form_id=$id";
			  
		dbQuery($sql);
		
		return "success";
		
	}
	catch(Exception $e)
	{
	}
	
}	

	
	

function updateCloseLead($is_bought, $purchase_date, $decline_reason, $note, $enquiry_form_id)
{
	
	try
	{
		
		if(validateForNull($is_bought, $enquiry_form_id))
		{
		deleteCloseLead($enquiry_form_id);
		insertCloseLead($is_bought, $decline_reason, $note, $enquiry_form_id, $purchase_date);
		
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


function getCloseLeadById($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT follow_up_id, discussion, enquiry_form_id, created_by, date_added
			  FROM ems_follow_up
			  WHERE follow_up_id=$id";
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

function getCloseLeadByEnquiryId($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT ems_enquiry_form.enquiry_form_id, ems_enquiry_form.is_bought, ems_enquiry_form.enquiry_close_date, ems_enquiry_form.enquiry_closed_by, ems_not_bought.not_bought_id, ems_not_bought.discussion, ems_not_bought.decline_id 
			  FROM ems_enquiry_form, ems_not_bought
			  WHERE ems_enquiry_form.enquiry_form_id=$id AND ems_not_bought.enquiry_form_id=$id";
		
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

function getIsBoughtVarEnquiryId($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT enquiry_form_id, is_bought
			  FROM ems_enquiry_form
			  WHERE enquiry_form_id=$id";
			 
		
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