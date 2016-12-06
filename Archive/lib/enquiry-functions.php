<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("customer-functions.php");
require_once("sms-functions.php");
require_once("rel-enquiry-assignee-functions.php");
require_once("common.php");
require_once("bd.php");


function listEnquiry()
{
	
	try
	{
		$sql="SELECT enquiry_form_id, enquiry_discussion, follow_up_date, customer_id, customer_type_id, date_added, created_by
			  FROM ems_enquiry_form";
	    
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

function insertEnquiry($customer_id, $discussion="", $customer_type_id=-1, $reminder_date="1970-01-01", $enquiry_date, $budget=0, $sub_cat_id_array, $sms_status, $refrence_id)
{
	try
	{
		
	$discussion=clean_data($discussion);
	$reminder_date=clean_data($reminder_date);	
	$todays_date=getTodaysDate();
	if(!validateForNull($budget) || !checkForNumeric($budget))
	$budget=0;
		
	if( $customer_id>0 )
	{
	if($customer_type_id==-1)
	{
	$customer_type_id="NULL";
	}
		
	$reminder_date=str_replace('/','-',$reminder_date);
	$reminder_date=date('Y-m-d H:i:s',strtotime($reminder_date));	
		
	
	$enquiry_date=str_replace('/','-',$enquiry_date);
	$enquiry_date=date('Y-m-d H:i:s',strtotime($enquiry_date));
		

	
    $admin_id=$_SESSION['EMSadminSession']['admin_id'];
	$oc_id=$_SESSION['EMSadminSession']['oc_id'];
	
	
	$current_enquiry_id_counter = getEnquiryIdCounterForOCID(5);
	
	$unique_enquiry_id =  date('dmY', strtotime(getTodaysDate()))."$current_enquiry_id_counter"; 
	
	if(!checkForNumeric($refrence_id))
	$refrence_id="NULL";
	
	$sql="INSERT INTO 
		  ems_enquiry_form (enquiry_discussion, customer_type_id, customer_id, follow_up_date, enquiry_date, date_added, created_by, current_lead_holder, enquiry_closed_by, budget, unique_enquiry_id, refrence_id)
		  VALUES ('$discussion', $customer_type_id, $customer_id, '$reminder_date', '$enquiry_date', NOW(), $admin_id, $admin_id, $admin_id, $budget, '$unique_enquiry_id', $refrence_id)";
		
		$result=dbQuery($sql);
		
		$enquiry_id=dbInsertId();
		
		incrementEnquiryIdForOCID(5);
		
		insertRelEnquiryAssignee($enquiry_id, $admin_id, $reasons_for_change=NULL);	
	
			if(strtotime($todays_date)==strtotime($enquiry_date) && $sms_status==1)
			{
					
				$adminDetails = getAdminUserByID($admin_id);
			
				$admin_name = $adminDetails['admin_name'];
				$admin_email = $adminDetails['admin_email'];
				$admin_number = $adminDetails['admin_phone'];
				
				$customer = getCustomerById($customer_id);
				$prefix_id = $customer['prefix_id'];
				$prefixDetails = getPrefixById($prefix_id);
				$customer_prefix = $prefixDetails['prefix'];
				$contact_nos = getCustomerContactNo($customer_id);
				
				
				
				foreach($contact_nos as $contact_no)
				{
					
				if(checkForNumeric($contact_no[0]) && strlen($contact_no[0])==10)
					{
						
									
				$data = sendNewLeadSMS($customer['customer_name'], $contact_no[0], $admin_name, $admin_number, $admin_email, 1, $sub_cat_id_array[0]);
	                       
				
				/* 
				$message = $data[0];
				$messageId = $data[1];
				$message_type = $data[2];
				
				 // $statusDetailsArray = getSMSStatusDetails($messageId);
				 
				 print_r($statusDetailsArray);
				 exit;
				 $delivery_date_time = $statusDetailsArray[0];
				 $status = $statusDetailsArray[1];
				 
				// insertSMSStatusDetails($enquiry_id, $message, $status, $message_type, $messageId, $delivery_date_time);
				*/
					}
				}
			}
		return $enquiry_id;
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





function deleteEnquiry($id)
{
	
	try
	{
		if(1==1)
		{
		$sql="DELETE FROM ems_enquiry_form
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

function checkifEnquiryInUse($id)
{
	
	return false;
	
}		
	

function updateEnquiry($id, $discussion, $customer_type_id=-1, $follow_up_date, $budget=0)
{
	
	try
	{
		$id=clean_data($id);
		$discussion=clean_data($discussion);
		$follow_up_date=clean_data($follow_up_date);
		
		$follow_up_date=str_replace('/','-',$follow_up_date);
	    $follow_up_date=date('Y-m-d',strtotime($follow_up_date));
		
		if($customer_type_id==-1)
			{
			$customer_type_id="NULL";
			}
		
		
		if(checkForNumeric($id))
		{
		$sql="UPDATE ems_enquiry_form
			  SET customer_type_id=$customer_type_id, enquiry_discussion='$discussion', follow_up_date='$follow_up_date',           budget=$budget
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



function getEnquiryById($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT enquiry_form_id, unique_enquiry_id, enquiry_discussion, follow_up_date, customer_id, customer_type_id, created_by, current_lead_holder, date_added, is_bought, enquiry_close_date, enquiry_closed_by, total_mrp, budget, purchase_date, tour_ending_date, enquiry_date
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


function getAdminIdsForAnEnquiryId($id)
{
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT created_by
			  FROM ems_enquiry_form
			  WHERE enquiry_form_id=$id
			  UNION ALL
			  SELECT created_by FROM ems_follow_up
			  WHERE enquiry_form_id=$id";
			  
		$result=dbQuery($sql);
		
		
		$resultArray=dbResultToArray($result);
		
		if(dbNumRows($result)>0)
		{
		$user_id_array=array();	
		foreach($resultArray as $user)
		{
			$user_id_array[]=$user[0];
		}
	
		$user_id_array = array_unique($user_id_array);
		return $user_id_array;
		}
		else
		return false;
		}
	}
	catch(Exception $e)
	{
	}
	
}


function getEnquiryByCustomerId($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT enquiry_form_id, customer_id, enquiry_date, date_added, is_bought, enquiry_close_date, current_lead_holder
			  FROM ems_enquiry_form
			  WHERE customer_id=$id";
			  
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

function getNoOfEnquiriesForCustomerId($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT enquiry_form_id
			  FROM ems_enquiry_form
			  WHERE customer_id=$id";
			  
		$result=dbQuery($sql);
		
		
		
		$number = dbNumRows($result);
		return $number;
		
		}
	}
	catch(Exception $e)
	{
	}
	
}


function getNoOfSuccessfullEnquiriesForCustomerId($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT enquiry_form_id
			  FROM ems_enquiry_form
			  WHERE customer_id=$id AND is_bought=1";
			  
		$result=dbQuery($sql);
		
		
		
		$number = dbNumRows($result);
		return $number;
		
		}
	}
	catch(Exception $e)
	{
	}
	
}


function updateEnquiryDateFromDateAdded()
{
	
	try
	{
		
		$sql="UPDATE ems_enquiry_form
			  SET enquiry_date=date_added";
		dbQuery($sql);
		return "success";
		
	}
	catch(Exception $e)
	{
	}
	
}	


function updateCurrentLeadHolder()
{
	
	try
	{
		
		$sql="UPDATE ems_enquiry_form
			  SET current_lead_holder=created_by";
		dbQuery($sql);
		return "success";
		
	}
	catch(Exception $e)
	{
	}
	
}	
?>