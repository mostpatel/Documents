<?php 
require_once("cg.php");
require_once("customer-functions.php");
require_once("member-functions.php");
require_once("customer-extra-details-functions.php");
require_once("booking-form-functions.php");
require_once("common.php");
require_once("bd.php");


	
function insertRelBookingForm($customer_id, $member_id, $customer_proof_id, $booking_form_id)
{
	try
	{
		
		
		$sql="DELETE FROM ems_rel_booking_form_member_customer_details
		      WHERE booking_form_id = $booking_form_id
			  AND ";
		if(checkForNumeric($member_id))
		$sql=$sql." member_id = $member_id";
		else if(checkForNumeric($customer_id))
		$sql=$sql." customer_id = $customer_id AND member_id is NULL";	  
		dbQuery($sql);
		if(!checkForNumeric($member_id))
		$member_id="NULL";
		if(validateForNull($customer_id, $member_id, $booking_form_id))
		{
			$sql="INSERT INTO 
				ems_rel_booking_form_member_customer_details (customer_id, member_id, customer_proof_id, booking_form_id)
				VALUES ($customer_id, $member_id, $customer_proof_id, $booking_form_id)";
		   
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





function deleteRelBookingForm($booking_form_id)
{
	
	try
	{
		if(1==1)
		{
		$sql="DELETE FROM ems_rel_booking_form_member_customer_details
		      WHERE booking_form_id = $booking_form_id";
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


function getRelBookingFormDetailsByBookingId($booking_form_id)
{
	
	try
	{
		if(checkForNumeric($booking_form_id))
		{
		$sql="SELECT customer_id, member_id, customer_proof_id
			  FROM ems_rel_booking_form_member_customer_details
			  WHERE booking_form_id=$booking_form_id";
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


function getCustomerProofIdByBookingIdAndCustomerId($booking_form_id, $customer_id)
{
	
	try
	{
		if(checkForNumeric($booking_form_id))
		{
		$sql="SELECT customer_proof_id
			  FROM ems_rel_booking_form_member_customer_details
			  WHERE booking_form_id=$booking_form_id 
			  AND customer_id = $customer_id
			  AND member_id IS NULL";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0];
		else
		return false;
		}
	}
	catch(Exception $e)
	{
	}
	
}

function getMemberProofIdByBookingIdAndMemberId($booking_form_id, $member_id)
{
	
	try
	{
		if(checkForNumeric($booking_form_id))
		{
		$sql="SELECT customer_proof_id
			  FROM ems_rel_booking_form_member_customer_details
			  WHERE booking_form_id=$booking_form_id 
			  AND member_id = $member_id";
		
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0];
		else
		return false;
		}
	}
	catch(Exception $e)
	{
	}
	
}


function getPrrofDetailsByPrimaryProofId($id)
{
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT ems_human_proof_type.proof_type, customer_proof_no
			  FROM ems_customer_proof, ems_human_proof_type
			  WHERE customer_proof_id = $id
			  AND ems_customer_proof.human_proof_type_id = ems_human_proof_type.human_proof_type_id";
		
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



function getMembersByCustomerIdForABookingId($customer_id, $booking_id)
{
	
	try
	{
		
		
		if(checkForNumeric($customer_id))
		{
		$sql="SELECT DISTINCT member_id
			  FROM ems_rel_booking_form_member_customer_details
			  WHERE customer_id = $customer_id
			  AND booking_form_id = $booking_id 
			  AND member_id is NOT NULL";
		
		
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


function getMemberDetailsByCustomerIdForABookingId($customer_id, $booking_id)
{
	
	try
	{
		if(checkForNumeric($customer_id))
		{
		$sql="SELECT ems_rel_booking_form_member_customer_details.member_id, member_name, member_email, member_dob, gender, ems_rel_booking_form_member_customer_details.customer_id, relation_id, member_nationality
			  FROM ems_rel_booking_form_member_customer_details
			  INNER JOIN  
			  ems_customer_member 
			  ON  
			  ems_customer_member.member_id = ems_rel_booking_form_member_customer_details.member_id
			  WHERE ems_rel_booking_form_member_customer_details.customer_id = $customer_id
			  AND booking_form_id = $booking_id";
		
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


?>