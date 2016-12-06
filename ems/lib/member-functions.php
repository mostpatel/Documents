<?php 
require_once("cg.php");
require_once("common.php");
require_once("city-functions.php");
require_once("area-functions.php");
require_once("image-functions.php");
require_once("common.php");
require_once("bd.php");



		
function insertMember($name,$email="NA",$contact_no="NA", $relation_id=-1, $dob, $member_nationality, $gender, $customer_id)
{	
	try
	{
		
		
		
		$name=clean_data($name);
		$email=clean_data($email);
		
		if(!validateForNull($email))
		$email="NA";
		
		$admin_id=$_SESSION['EMSadminSession']['admin_id'];
		
		
		
			$name = ucwords(strtolower($name));
			
			$dob=str_replace('/','-',$dob);
	        $dob=date('Y-m-d',strtotime($dob));
		    if(!validateForNull($dob))
			$dob="1970-01-01 00:00:00";
			
			if($relation_id==-1)
		    $relation_id="NULL";
			
			if(validateForNull($name,$email) && $contact_no!=null && !empty($contact_no))
			{
				
				$sql="INSERT INTO ems_customer_member (member_name, member_email, member_dob, member_nationality, gender, relation_id, customer_id)				
				      VALUES ('$name', '$email', '$dob', $member_nationality, $gender, $relation_id, $customer_id)";
				
				
				$result=dbQuery($sql);
				$member_id=dbInsertId();		
				addMemberContactNo($member_id, $contact_no);
				
				
				return $member_id;
			}
			else
			{
				return false;
			}
		
		
	}
	catch(Exception $e)
	{
	}
	
}	

function deleteMember($id){
	
	try
	{
		$sql="DELETE FROM ems_customer_member 
		      WHERE member_id=$id";
		dbQuery($sql);
		return "success";	  
	}
	catch(Exception $e)
	{
	}
	
}	

function updateMember($id, $name,$email,$contact_no, $relation_id, $dob, $member_nationality, $gender, $member_id, $customer_id)
{
	
	try
	{
		
		
		$name=clean_data($name);
		$email=clean_data($email);
		
		
		$dob=clean_data($dob);
		$dob=str_replace('/','-',$dob);
	    $dob=date('Y-m-d',strtotime($dob));
		
		if(!validateForNull($dob))
		{
		$dob="1970-01-01 00:00:00";
		}
		
	    $name = ucwords(strtolower($name));
		
		
			
			if(validateForNull($name,$email) && checkForNumeric($member_id, $customer_id) && $contact_no!=null && !empty($contact_no))
			{
				
				$sql="UPDATE ems_customer_member
				     SET member_name='$name', member_email='$email', member_dob='$dob', member_nationality = $member_nationality, gender='$gender', relation_id='$relation_id', customer_id='$customer_id'
					 WHERE member_id=$id";
					 
			   
				$result=dbQuery($sql);
				
				deleteContactNoMember($id);
				addMemberContactNo($id,$contact_no);
				
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

function getMemberById($id)
{
	
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT member_id, member_name, member_email, member_dob, relation_id, customer_id, member_nationality, gender
			  FROM ems_customer_member
			  WHERE member_id=$id";
		
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0];
		else
		return false;
		}
        return false;
	}
	catch(Exception $e)
	{
	}
	
}	



function getMembersByCustomerId($id)
{
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT member_id, member_name, member_email, member_dob, gender, customer_id, relation_id, member_nationality
			  FROM ems_customer_member
			  WHERE customer_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		
		if(dbNumRows($result)>0)
		{
		
		return $resultArray;
		}
		else
		return false;
		}
	}
	catch(Exception $e)
	{
	}
	
}	



function addMemberContactNo($member_id,$contact_no)
{
	try
	{
		if(is_array($contact_no))
		{
			foreach($contact_no as $no)
			{
				if(checkForNumeric($no))
				{
				insertContactNoMember($member_id,$no); 
				}
			}
		}
		else
		{
			
			if(checkForNumeric($contact_no))
				{
				insertContactNoMember($member_id,$contact_no); 
				}
			
		}
	}
	catch(Exception $e)
	{
	}
}

function insertContactNoMember($id,$contact_no)
{
	try
	{
		
		if(checkForNumeric($id)==true && checkForNumeric($contact_no))
		{
			
		$sql="INSERT INTO ems_customer_member_contact_no
				      (member_contact_no, member_id)
					  VALUES
					  ('$contact_no', $id)";
		
		
				dbQuery($sql);
			  
		}
	}
	catch(Exception $e)
	{}
	
	
}
function deleteContactNoMember($id)
{
	try
	{
		$sql="DELETE FROM ems_customer_member_contact_no
			  WHERE customer_member_contact_no_id=$id";
		dbQuery($sql);	  
	}
	catch(Exception $e)
	{}
	
	
	
	}
function deleteAllContactNoMember($id)
{
	try
	{
		$sql="DELETE FROM ems_customer_member_contact_no
			  WHERE member_id=$id";
		dbQuery($sql);
	}
	catch(Exception $e)
	{}
	
	
	
	}	
function updateContactNoMember($id,$contact_no)
{
	try
	{
		deleteAllContactNoMember($id);
		addMemberContactNo($id,$contact_no);
	}
	catch(Exception $e)
	{}
	
	
	
	}

function getMemberContactNo($id)
{
	if(checkForNumeric($id))
	{
		$sql="SELECT member_contact_no FROM ems_customer_member_contact_no
				WHERE member_id=$id";
				$result=dbQuery($sql);	  
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray;
		else
		return false;
	}
    return false;
}


function getMemberIdFromContactNo($no)
{
	if(checkForNumeric($no))
	{
		$sql="SELECT member_id FROM ems_customer_member_contact_no
				WHERE member_contact_no=$no";
				$result=dbQuery($sql);	  
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0];
		else
		return false;
	}
}

function getMemberIdFromEmail($email)
{
	if(validateForNull($email))
	{
		$sql="SELECT member_id FROM ems_customer_member
				WHERE member_email='$email'";
				$result=dbQuery($sql);	  
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0];
		else
		return false;
	}
}

function getMemberIdFromMemberName($name)
{
	if(validateForNull($name))
	{
		$sql="SELECT member_id FROM ems_customer_member
				WHERE member_name='$name'";
				$result=dbQuery($sql);	  
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0];
		else
		return false;
	}
}





function listAllMobileNosOfMembers(){
	
	try
	{
		
		$sql="SELECT customer_member_contact_no_id, member_contact_no, member_id
		      FROM ems_customer_member_contact_no";
		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		return $resultArray; 
	}
	catch(Exception $e)
	{
	}
}
			
?>