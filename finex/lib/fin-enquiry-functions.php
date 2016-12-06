<?php 
require_once("cg.php");
require_once("city-functions.php");

require_once("customer-functions.php");
require_once("adminuser-functions.php");

require_once("report-functions.php");
require_once("common.php");
require_once("bd.php");


function listFinEnquiries(){
	
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



function insertFinEnquiry($enquiry_date, $name, $primary_number, $secondary_number, $address, $status_id, $note)
{
	try
	{
		$address=clean_data($address);
		$note=clean_data($note);
		
		$admin_id=$_SESSION['EMSadminSession']['admin_id'];
		
	 	$enquiry_date=str_replace('/','-',$enquiry_date);
		$enquiry_date=date('Y-m-d H:i:s',strtotime($enquiry_date));
		
		
		if(!validateForNull($address))
		$address = "";
		
		if(!validateForNull($secondary_number))
		$secondary_number = 9999999999;
		
		if(!validateForNull($note))
		$note = "";
		
		$sql="INSERT INTO 
				fin_enquiry (name, phone_primary, phone_secondary, enquiry_date, status_id, address, note, date_added, added_by)
				VALUES ('$name', $primary_number, $secondary_number, '$enquiry_date', $status_id, '$address', '$note', NOW(), $admin_id)";
		$result=dbQuery($sql);
		
		return "success";
		
	}
	catch(Exception $e)
	{
	}
	
}








function updateFinEnquiry($id, $discussion, $next_follow_up_date)
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



function getFinEnquiryById($id){
	
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



function viewFinEnquiries($from=null,$to=null)
{
	
	if(isset($from) && validateForNull($from))
	{
	$from = str_replace('/', '-', $from);
		$from=date('Y-m-d',strtotime($from));
		$from=$from." 00:00:00";
	}
if(isset($to) && validateForNull($to))
{
	$to = str_replace('/', '-', $to);
		$to=date('Y-m-d',strtotime($to));
		$to=$to." 23:59:59";
}	

    $today=getTodaysDate();
	
	$sql="SELECT enquiry_id, name, phone_primary, phone_secondary, enquiry_date, status_id, address, note, date_added, added_by
	     FROM
	     fin_enquiry
		  
		  WHERE
		  1=1"; 
	
	if(isset($status_id_array) && validateForNull($status_id_array))
	{
	$status_id_string = implode(",",$status_id_array);	
	$sql=$sql." AND status_id IN $status_id_string";
	}
	
	
	if(isset($from) && validateForNull($from))
	$sql=$sql." AND enquiry_date>='$from' 
		   ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql." AND enquiry_date<='$to'";
	
    
	
	
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	
	return $resultArray;		
}



?>