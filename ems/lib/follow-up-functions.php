<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("enquiry-functions.php");
require_once("customer-functions.php");
require_once("adminuser-functions.php");
require_once("rel-subcat-enquiry-functions.php");
require_once("prefix-functions.php");
require_once("report-functions.php");
require_once("common.php");
require_once("bd.php");


function listFollowUp(){
	
	try
	{
		$sql="SELECT follow_up_id, discussion, enquiry_form_id, follow_up_type_id, created_by, date_added
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



function insertFollowUp($enquiry_form_id, $discussion, $follow_up_date, $sms_status, $follow_up_type_id)
{
	try
	{
		$discussion=clean_data($discussion);
		$admin_id=$_SESSION['EMSadminSession']['admin_id'];
		
	 	$follow_up_date=str_replace('/','-',$follow_up_date);
		$follow_up_date=date('Y-m-d H:i:s',strtotime($follow_up_date));
		
		if($follow_up_type_id == -1)
		$follow_up_type_id = "NULL";
		if(!validateForNull($discussion))
		$discussion = "";
		
		$sql="INSERT INTO 
				ems_follow_up (next_follow_up_date, discussion, enquiry_form_id, follow_up_type_id, created_by, date_added)
				VALUES ('$follow_up_date','$discussion', $enquiry_form_id, $follow_up_type_id, $admin_id, NOW())";
		$result=dbQuery($sql);
		setReminderDateForEnquiry($enquiry_form_id);
		$enquiry_details = getEnquiryById($enquiry_form_id);
		$is_bought = $enquiry_details['is_bought'];
		if($is_bought==0 || $is_bought==3)
		{
		$sql="UPDATE ems_enquiry_form
			  SET is_bought=3
			  WHERE enquiry_form_id=$enquiry_form_id";
		dbQuery($sql);
		}
		
		if($sms_status==1)
		{
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
				
				foreach($contact_nos as $contact_no)
				{
					
				if(checkForNumeric($contact_no[0]) && strlen($contact_no[0])==10)
					{
				 sendNewLeadSMS($customer['customer_name'], $contact_no[0], $admin_name, $admin_number, $admin_email, $type=2, $sub_cat_id);
				}
				}
		}
		
		return "success";
		
	}
	catch(Exception $e)
	{
	}
	
}






function deleteFollowUp($id){
	
	try
	{
		
		$followUpDetails = getFollowUpById($id);
		$enquiry_form_id = $followUpDetails['enquiry_form_id'];
	
		$sql="DELETE FROM ems_follow_up
		      WHERE follow_up_id=$id";
		dbQuery($sql);
		setReminderDateForEnquiry($enquiry_form_id);	
		
		
		$noOfFollowUps = countFollowUpsForEnquiryId($id);
		
		
		$enquiryDetails = getEnquiryById($enquiry_form_id);
		
		$isBoughtVariable = $enquiryDetails['is_bought'];
		
		
		if($noOfFollowUps==0 && $isBoughtVariable==3)
		{
		
		$sql="UPDATE ems_enquiry_form
			  SET is_bought=0
			  WHERE enquiry_form_id=$enquiry_form_id";
	   
	    
        dbQuery($sql);
		
	    }
		
		return "success";
		
	}
	catch(Exception $e)
	{
	}
	
}



function countFollowUpsForEnquiryId($id){
	
	try
	{
		$sql="SELECT follow_up_id, enquiry_form_id
			  FROM ems_follow_up
			  WHERE enquiry_form_id = $id";
		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		$no = dbNumRows($result);
		return($no);
		
		  
	}
	catch(Exception $e)
	{
	}
	
}	

	
	

function updateFollowUp($id, $discussion, $next_follow_up_date)
{
	
	try
	{
		$id=clean_data($id);
		$discussion=clean_data($discussion);
		$next_follow_up_date=clean_data($next_follow_up_date);
		
		$next_follow_up_date=str_replace('/','-',$next_follow_up_date);
	    $next_follow_up_date=date('Y-m-d',strtotime($next_follow_up_date));
		
		
		
		if(checkForNumeric($id))
		{
		$sql="UPDATE ems_follow_up
			  SET discussion='$discussion', next_follow_up_date='$next_follow_up_date'
			  WHERE follow_up_id=$id";
	
		
		dbQuery($sql);
		$follow_up = getFollowUpById($id);	  
		setReminderDateForEnquiry($follow_up['enquiry_form_id']);
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



function getFollowUpById($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT follow_up_id, discussion, next_follow_up_date, enquiry_form_id, follow_up_type_id, created_by, date_added
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


function getFollowUpDetailsByEnquiryId($id)
{
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT follow_up_id, next_follow_up_date, discussion, enquiry_form_id, follow_up_type_id, created_by, date_added
			  FROM ems_follow_up
			  WHERE enquiry_form_id=$id";
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

function getReminderDateForEnquiry($id)
{
	if(checkForNumeric($id))
	{
	$sql="SELECT  follow_up_date
			  FROM ems_enquiry_form
			  WHERE enquiry_form_id=$id";
			  
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0];
		else
		return false;
	}
	
}
	
function setReminderDateForEnquiry($id)
{
	
	if(checkForNumeric($id))
	{
		
	$reminder_date = getMAXFollowUpDate($id);
	
	if(validateForNull($reminder_date))
	{	
	$sql="UPDATE
			   ems_enquiry_form SET follow_up_date = '$reminder_date'
			  WHERE enquiry_form_id=$id";
			  
		$result=dbQuery($sql);
	}
		return "success";
		
	
	}
return "error";
}




function setPreviousFollowUpsForNewCode()
{
	
	$sql="SELECT  enquiry_form_id, enquiry_discussion, follow_up_date, date_added, created_by
			  FROM ems_enquiry_form";
	    
	$result=dbQuery($sql);	 
	$resultArray=dbResultToArray($result);
	
	foreach($resultArray as $r)
	{
	
	$enquiry_form_id = $r['enquiry_form_id'];
	$discussion = clean_data($r['enquiry_discussion']);
	$follow_up_date = $r['follow_up_date'];
	$date_added = $r['date_added'];
	$created_by = $r['created_by'];
	
	$sql="INSERT INTO 
		 ems_follow_up (next_follow_up_date, discussion, enquiry_form_id, follow_up_type_id, created_by, date_added)
		 VALUES ('$follow_up_date','$discussion', $enquiry_form_id, NULL, $created_by, '$date_added')";
	$result=dbQuery($sql);
	
	setReminderDateForEnquiry($enquiry_form_id);
	
	}
		
}

?>