<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("common.php");
require_once("bd.php");


	
function listReasons(){
	
	try
	{
		$sql="SELECT decline_id, decline_reason
			  FROM ems_decline_reasons";
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



function insertReason($reason){
	try
	{
		$reason=clean_data($reason);
		
		if(validateForNull($reason) && !checkDuplicateReason($reason))
		{
			$sql="INSERT INTO 
				ems_decline_reasons (decline_reason)
				VALUES ('$reason')";
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






function deleteReason($id){
	
	try
	{
		if(!checkifReasonInUse($id))
		{
		
		$sql="DELETE FROM ems_decline_reasons
		      WHERE decline_id=$id";
		dbQuery($sql);
		return "success";
		}else
		{
			return "error";
		}
		
	}
	catch(Exception $e)
	{
	}
	
}	

function checkDuplicateReason($id)
{
	
	return false;
	
}

function checkifReasonInUse($id)
{
	
	if(checkForNumeric($id))
	{
	$sql="SELECT not_bought_id
	      FROM ems_not_bought
		  Where decline_id=$id";
	$result=dbQuery($sql);	  
	if(dbNumRows($result)>0)
	return true;
	else 
	return false;
	}
	
}			
			
	

function updateReason($id,$reason){
	
	try
	{
		$reason=clean_data($reason);
		if(validateForNull($reason) && checkForNumeric($id))
		{
		$sql="UPDATE ems_decline_reasons
			  SET decline_reason='$reason'
			  WHERE decline_id=$id";
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



function getReasonById($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT decline_id, decline_reason
			  FROM ems_decline_reasons
			  WHERE decline_id=$id";
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



function declineReasonAnalysis($from=null,$to=null)
{
	
if(isset($from) && validateForNull($from))
	{
	$from = str_replace('/', '-', $from);
		$from=date('Y-m-d',strtotime($from));
	}
if(isset($to) && validateForNull($to))
{
	$to = str_replace('/', '-', $to);
		$to=date('Y-m-d',strtotime($to));
}	
	
	$today=getTodaysDate();
	
	$sql="SELECT ems_not_bought.decline_id, decline_reason, 
	
	      (SELECT COUNT(not_bought_id) FROM ems_not_bought) as total_not_bought_enquiries,
	
	      COUNT(enquiry_form_id) as total_enquiries_for_a_declined_reason
	
          FROM ems_not_bought
		  
		  JOIN ems_decline_reasons 
		  ON ems_not_bought.decline_id = ems_decline_reasons.decline_id
		  
		  GROUP BY ems_not_bought.decline_id";
		   
	if(isset($from) && validateForNull($from))
	$sql=$sql." AND enquiry_date>='$from'";
	if(isset($to) && validateForNull($to))  
	$sql=$sql." AND enquiry_date<='$to'";
	
	
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	return $resultArray;		
}



function getAllEnquiryDetailsForADeclineReason($decline_reason_id, $from=null,$to=null)
{
	
if(isset($from) && validateForNull($from))
	{
	$from = str_replace('/', '-', $from);
		$from=date('Y-m-d',strtotime($from));
	}
if(isset($to) && validateForNull($to))
{
	$to = str_replace('/', '-', $to);
		$to=date('Y-m-d',strtotime($to));
}	
	
	$today=getTodaysDate();
	
	$sql="SELECT ems_enquiry_form.enquiry_form_id, customer_name, customer_email, is_bought, enquiry_closed_by, enquiry_close_date, admin_name,
	
	 (SELECT GROUP_CONCAT(DISTINCT customer_contact_no SEPARATOR '<br>') FROM ems_customer_contact_no WHERE ems_customer_contact_no.customer_id = ems_customer.customer_id GROUP BY ems_customer.customer_id ) as contact_no,
	 
	  GROUP_CONCAT(sub_cat.sub_cat_id), 
	  
	  GROUP_CONCAT(sub_cat.sub_cat_name SEPARATOR ' <br> ') as sub_cat_name, 
	  
	  ems_not_bought.discussion
		
	 	  FROM
		   
	      ems_subCategory as sub_cat, ems_customer, ems_admin, ems_enquiry_form, ems_rel_subCategory_enquiry_form, ems_not_bought
		  
		  WHERE
		  
		  ems_not_bought.decline_id = $decline_reason_id
		  AND
		  ems_enquiry_form.enquiry_form_id=ems_rel_subCategory_enquiry_form.enquiry_form_id 
		  AND 
		  ems_rel_subCategory_enquiry_form.sub_cat_id = sub_cat.sub_cat_id
		  AND
		  ems_customer.customer_id = ems_enquiry_form.customer_id
		  AND
		  ems_enquiry_form.enquiry_closed_by = ems_admin.admin_id
		  AND
		  ems_not_bought.enquiry_form_id = ems_enquiry_form.enquiry_form_id"; 
	   
	if(isset($from) && validateForNull($from))
	$sql=$sql." AND ems_enquiry_form.enquiry_date>='$from' 
		   ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql." AND ems_enquiry_form.enquiry_date<='$to'";
	
	$sql=$sql." GROUP BY ems_enquiry_form.enquiry_form_id";
	
	
	
	
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	return $resultArray;	
}


?>