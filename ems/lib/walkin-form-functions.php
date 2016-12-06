<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("common.php");
require_once("bd.php");



function listWalkInForms()
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

function insertWalkInForm($name, $sub_cat_id, $mobile, $email="NA", $discussion="", $customer_type_id=-1, $reminder_date="1970-01-01")
{
	try
	{
		
	if(checkForNumeric($sub_cat_id,$mobile) &&  $sub_cat_id>0 &&  validateForNull($name))
	{
	if($customer_type_id==-1)
	{
	$customer_type_id="NULL";
	}
		
	$reminder_date=str_replace('/','-',$reminder_date);
	$reminder_date=date('Y-m-d',strtotime($reminder_date));	
		
	$name=clean_data($name);
	$email=clean_data($email);
	$discussion=clean_data($discussion);
	$reminder_date=clean_data($reminder_date);
	
    $admin_id=$_SESSION['EMSadminSession']['admin_id'];
			
	$sql="INSERT INTO 
		  ems_walkin_form (customer_name, sub_cat_id, mobile_no, email_id, discussion, customer_type_id, reminder_date, date_added, created_by)
		  VALUES ('$name', $sub_cat_id, $mobile, '$email', '$discussion', $customer_type_id,  '$reminder_date', NOW(), $admin_id)";
  
  echo $sql;
			
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





function deleteWalkInForm($id){
	
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

function checkifWalkInFormInUse($id)
{
	
	return false;
	
}		
	

function updateWalkInForm($id, $name, $sub_cat_id, $mobile, $email, $discussion, $customer_type_id, $reminder_date)
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



function getWalkInFormById($id){
	
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