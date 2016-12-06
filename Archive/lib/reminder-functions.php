<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("enquiry-functions.php");
require_once("customer-functions.php");
require_once("adminuser-functions.php");
require_once("common.php");
require_once("bd.php");


	
function addRemainder($enquiry_form_id, $date, $remarks)
{
	
	$remarks=clean_data($remarks);
	
	$date = str_replace('/', '-', $date);
	$date=date('Y-m-d',strtotime($date));
	
	
	
		
	if(checkForNumeric($enquiry_form_id) && validateForNull($date, $remarks))
	{
	
	$sql="INSERT INTO ems_lead_remainder (enquiry_form_id, date, remarks) VALUE ($enquiry_form_id, '$date', '$remarks')";
	
	$result=dbQuery($sql);
	return "success";
	}
	
	else
	return "error";
}
	
function listRemainderForALead($enquiry_form_id)
{
	
	if(checkForNumeric($enquiry_form_id))
	{
		$sql="SELECT remainder_id, date, remarks, remainder_status 
		      FROM ems_lead_remainder 
			  WHERE enquiry_form_id = $enquiry_form_id";
			  
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		
		if(dbNumRows($result)>0)
		return $resultArray;
		else 
		return false;
	}			
}


function getRemainderById($id)
{
	if(checkForNumeric($id))
	{
		$sql="SELECT remainder_id, date, remarks, enquiry_form_id 
		FROM ems_lead_remainder 
		WHERE remainder_id=$id";
		
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		
		if(dbNumRows($result)>0)
		return $resultArray[0];
		else 
		return false;
	}		
	
	
}

function editRemainder($id, $date, $remarks)
{
	$remarks=clean_data($remarks);
	$date = str_replace('/', '-', $date);
	$date=date('Y-m-d',strtotime($date));
		
		
	if(checkForNumeric($id))
	{
		
		
		$sql="UPDATE ems_lead_remainder 
		SET date='$date', remarks='$remarks' 
		WHERE remainder_id=$id";
		
		$result=dbQuery($sql);
		return "success";
	}		
	else
	return "error";
	
}

function deleteRemainder($id)
{
	if(checkForNumeric($id))
	{
		
		$sql="DELETE FROM ems_lead_remainder 
		WHERE remainder_id=$id";
		$result=dbQuery($sql);
		return "success";
	}		
	else
	return "error";
	
}

function setDoneRemainderGeneral($id)
{
	if(checkForNumeric($id))
	{
		$sql="UPDATE ems_lead_remainder 
		SET remainder_status=1 
		WHERE remainder_id=$id";
		
		$result=dbQuery($sql);
		return "success";
		}
	else return "error";	
	
	}

function setUnDoneRemainderGeneral($id)
{
	if(checkForNumeric($id))
	{
		$sql="UPDATE ems_lead_remainder SET remainder_status=0 WHERE remainder_id=$id";
		$result=dbQuery($sql);
		return "success";
		}
	else return "error";	
	
}


function listRemainderForAnEnquiry($enquiry_id)
{
	
	if(checkForNumeric($file_id))
	{
		$sql="SELECT remainder_id,date,remarks,remainder_status FROM ems_lead_remainder WHERE enquiry_form_id=$enquiry_id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		
		if(dbNumRows($result)>0)
		return $resultArray;
		else return false;
	}			
}	
	
?>