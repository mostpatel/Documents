<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("enquiry-functions.php");
require_once("customer-functions.php");
require_once("adminuser-functions.php");
require_once("rel-subcat-enquiry-functions.php");
require_once("prefix-functions.php");
require_once("common.php");
require_once("bd.php");


	
function listVisits(){
	
	try
	{
		$sql="SELECT visit_id, visit_discussion, enquiry_form_id, created_by, date_added
			  FROM ems_visit";
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



function insertVisit($enquiry_form_id, $visit_discussion, $visit_date, $sms_status)
{
	try
	{
		$visit_discussion=clean_data($visit_discussion);
		$admin_id=$_SESSION['EMSadminSession']['admin_id'];
		
	 	$visit_date=str_replace('/','-',$visit_date);
		$visit_date=date('Y-m-d H:i:s',strtotime($visit_date));
		
		$sql="INSERT INTO 
				ems_visit (visit_date, visit_discussion, enquiry_form_id, created_by, date_added)
				VALUES ('$visit_date','$visit_discussion', $enquiry_form_id, $admin_id, NOW())";
		$result=dbQuery($sql);
		
		
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
				 sendNewLeadSMS($customer_prefix." ".$customer['customer_name'], $contact_no[0], $admin_name, $admin_number, $admin_email, $type=10, $sub_cat_id);
				}
				}
		}
		
		return "success";
		
	}
	catch(Exception $e)
	{
	}
	
}

function deleteVisit($id){
	
	try
	{
		
		$visitDetails = getVisitId($id);
		$enquiry_form_id = $visitDetails['enquiry_form_id'];
		
		$sql="DELETE FROM ems_visit
		      WHERE visit_id=$id";
		dbQuery($sql);
		
		return "success";
		
	}
	catch(Exception $e)
	{
	}
	
}



function countVisitsForEnquiryId($id){
	
	try
	{
		$sql="SELECT visit_id, enquiry_form_id
			  FROM ems_visit
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

	
	

function updateVisit($id, $visit_discussion, $visit_date)
{
	
	try
	{
		$id=clean_data($id);
		$visit_discussion=clean_data($visit_discussion);
		$visit_date=clean_data($visit_date);
		
		$visit_date=str_replace('/','-',$visit_date);
	    $visit_date=date('Y-m-d',strtotime($visit_date));
		
		
		
		if(checkForNumeric($id))
		{
		$sql="UPDATE ems_visit
			  SET visit_discussion='$visit_discussion', visit_date='$visit_date'
			  WHERE visit_id=$id";
		
		
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



function getVisitById($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT visit_id, visit_discussion, visit_date, enquiry_form_id, created_by, date_added
			  FROM ems_visit
			  WHERE visit_id=$id";
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


function getVisitDetailsByEnquiryId($id)
{
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT visit_id, visit_date, visit_discussion, enquiry_form_id, created_by, date_added
			  FROM ems_visit
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


function getVisitedEnquiries()
{
	
	try
	{
		if(1==1)
		{
		$sql="SELECT enquiry_form_id
			  FROM ems_rel_enquiry_group
			  WHERE enquiry_group_id=1";
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

function addPreviousDefaultVisit()
{
	$enquiryIds = getVisitedEnquiries();
	foreach($enquiryIds as $enquiryId)
	{
	$enquiry_form_id = $enquiryId['enquiry_form_id'];
	insertVisit($enquiry_form_id, $visit_discussion=" ", $visit_date="01/09/2015", $sms_status=0);
	}
}

	
?>