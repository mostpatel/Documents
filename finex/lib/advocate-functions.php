<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("common.php");
require_once("bd.php");

function listAdvocates(){
	
	try
	{
		$sql="SELECT `advocate_id`, `advocate_name`, `advocate_address`, `contact_no`, `contact_no2`, `created_by`, `last_updated_by`, `date_added`, `date_modified`
		  FROM fin_advocate
		  ORDER BY advocate_name";
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

function insertAdvocate($dealer_name,$secondary_name,$dealer_address,$contact_no,$contact_no2){
	
	try
	{
		$dealer_name=clean_data($dealer_name);
		$secondary_name=clean_data($secondary_name);
		$dealer_name = ucwords(strtolower($dealer_name));
		$dealer_address=clean_data($dealer_address);
		$duplcate=checkForDuplicateAdvocate($dealer_name);
		if($dealer_name!=null && $dealer_name!=''  && !checkForDuplicateAdvocate($dealer_name))
			{
			$admin_id=$_SESSION['adminSession']['admin_id'];
			$sql="INSERT INTO fin_advocate
					(`advocate_name`, secondary_advocate_name, `advocate_address`, `contact_no`, `contact_no2`, `created_by`, `last_updated_by`, `date_added`, `date_modified`)
					VALUES
					('$dealer_name','$secondary_name','$dealer_address','$contact_no','$contact_no2',$admin_id,$admin_id,NOW(),NOW())";
			dbQuery($sql);
			$dealer_id=dbInsertId();
			
			return $dealer_id;
			}
		else if($duplcate)
		{
			
			return "error";
			}	
	}
	catch(Exception $e)
	{
	}
	
}



function checkForDuplicateAdvocate($advocate_name,$id)
{
	if(validateForNull($advocate_name))
	{
	$sql="SELECT  advocate_id
	      FROM fin_advocate
	     WHERE  advocate_name='$advocate_name'";
	if(is_numeric($id))
	$sql=$sql." AND advocate_id!=$id";
	$result=dbQuery($sql);	
	$resultArray=dbResultToArray($result);
	
	if(dbNumRows($result)>0)
	return true;
	else
	return false;
	}
	return false;
	}
function checkIfAdvocateInUse($advocate_id)
{
	if(checkForNumeric($advocate_id))
	{
	$sql="SELECT  advocate_id
	      FROM fin_legal_notice
	     WHERE  advocate_id=$advocate_id";
	
	$result=dbQuery($sql);	
	$resultArray=dbResultToArray($result);
	
	if(dbNumRows($result)>0)
	return true;
	else
	return false;
	}
	return false;
	}	
function deleteAdvocate($id){
	
	try
	{
		if(checkForNumeric($id) && !checkIfAdvocateInUse($id))
		{
		$sql="DELETE FROM fin_advocate WHERE advocate_id=$id";
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

function updateAdvocate($id,$dealer_name,$secondary_advocate_name,$dealer_address,$contact_no,$contact_no2){
	
	try
	{
		$dealer_name=clean_data($dealer_name);
		$secondary_name=clean_data($secondary_name);
		$dealer_name = ucwords(strtolower($dealer_name));
		$dealer_address=clean_data($dealer_address);
		if($dealer_name!=null && $dealer_name!=''  && checkForNumeric($id) && !checkForDuplicateAdvocate($dealer_name,$id))
			{
			
			$admin_id=$_SESSION['adminSession']['admin_id'];
			$sql="UPDATE fin_advocate
					SET advocate_name = '$dealer_name', secondary_advocate_name = '$secondary_advocate_name', advocate_address ='$dealer_address', contact_no = '$contact_no', contact_no2 = '$contact_no2', last_updated_by=$admin_id, date_modified=NOW()
					WHERE advocate_id=$id";
					
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

function getAdvocateById($id){
	
	try
	{
		$sql="SELECT advocate_id, advocate_name, secondary_advocate_name, advocate_address, contact_no, contact_no2
		  FROM fin_advocate
		  WHERE  advocate_id=$id";
		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0]; 
		else
		return false;
	}
	catch(Exception $e)
	{
	}
	
}



?>