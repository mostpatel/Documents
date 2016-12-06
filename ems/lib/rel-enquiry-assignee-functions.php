<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("common.php");
require_once("bd.php");


	
function listRelEnquiryAssignee(){
	
	try
	{
		$sql="";
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



function insertRelEnquiryAssignee($enquiry_id, $assignee_id, $reasons_for_change=NULL)
{
	try
	{
		$reasons_for_chage=clean_data($reasons_for_chage);
		
		$admin_id=$_SESSION['EMSadminSession']['admin_id'];
		
		if(checkForNumeric($enquiry_id, $assignee_id))
		{
			$sql="INSERT INTO 
				ems_rel_enquiry_assignee_history (enquiry_form_id, admin_id, date_added, changed_by, reasons_for_change)
				VALUES ($enquiry_id, $assignee_id, NOW(), $admin_id, '$reasons_for_change')";
				
			
			
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



function updateRelEnquiryAssignee($enquiry_id, $assignee_id, $reasons_for_change=NULL)
{
	
	try
	{
		$reasons_for_change=clean_data($reasons_for_change);
		
		if(checkForNumeric($enquiry_id, $assignee_id))
		{
		$sql="UPDATE ems_enquiry_form
			  SET current_lead_holder=$assignee_id
			  WHERE enquiry_form_id=$enquiry_id";
			  
		
		dbQuery($sql);
		
		insertRelEnquiryAssignee($enquiry_id, $assignee_id, $reasons_for_change=NULL);
		
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