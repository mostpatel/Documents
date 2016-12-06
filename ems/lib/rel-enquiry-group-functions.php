<?php 
require_once("cg.php");
require_once("bd.php");
require_once("common.php");




function insertRelEnquiryGroup($enquiry_id, $enquiry_group_id_array)
{
	
	try
	{
		
		if(checkForNumeric($enquiry_id))
		{
			
		foreach($enquiry_group_id_array as $enquiry_group_id)
		{
		if(checkForNumeric($enquiry_group_id) && $enquiry_group_id>0)
		{
		$sql="INSERT INTO
		      ems_rel_enquiry_group (enquiry_form_id, enquiry_group_id)
			  VALUES
			  ($enquiry_id, $enquiry_group_id)";
		
		
		$result=dbQuery($sql);
			
		}
		}
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

function deleteRelEnquiryGroup($enquiry_id){
	
	try
	{
		if(checkForNumeric($enquiry_id))
		{
	
		$sql="DELETE FROM
			  ems_rel_enquiry_group
			  WHERE enquiry_form_id = $enquiry_id";
		dbQuery($sql);	
		return  "success";
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

function updateRelEnquiryGroup($enquiry_id, $enquiry_group_id_array)
{
	deleteRelEnquiryGroup($enquiry_id);
	insertRelEnquiryGroup($enquiry_id, $enquiry_group_id_array);
	
	return "success";
}

function getEnquiryGroupNamesByEnquiryId($enquiry_id)
{
	
	try
	{
		
		$sql="SELECT enquiry_group_name
		
			  FROM ems_enquiry_group
			  
			  JOIN ems_rel_enquiry_group
			  ON ems_enquiry_group.enquiry_group_id = ems_rel_enquiry_group.enquiry_group_id
			  
			  WHERE ems_rel_enquiry_group.enquiry_form_id = $enquiry_id";
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

?>