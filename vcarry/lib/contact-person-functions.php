<?php 
    require_once("cg.php");
    require_once("bd.php");
    require_once("common.php");

function insertContactPerson($name,$con_no1,$con_no2,$dob,$email,$customer_id,$prefix=NULL,$primary_cp=0,$anniversary=NULL)
{
	if(!checkForNumeric($prefix))
		{
			$prefix="NULL";
			}
	
	if(validateForNull($name) && checkForNumeric($con_no1,$customer_id,$primary_cp)  && !checkForDuplicateContactPerson($customer_id,$name))
	{
	if(!checkForNumeric($con_no2))
	{
		$con_no2=0;
	}	
	if(!validateForNull($dob))
	$dob="1900-01-01";
	else
	{
	$dob = str_replace('/', '-', $dob);
		$dob = date('Y-m-d',strtotime($dob));	
	}
	if(!validateForNull($anniversary))
	$anniversary="1900-01-01";
	else
	{
	$anniversary = str_replace('/', '-', $anniversary);
		$anniversary = date('Y-m-d',strtotime($anniversary));	
	}
	if(!validateForNull($email))
	$email="NA";
	
	$sql="INSERT INTO edms_customer_contact_person
(cp_name, cp_contact_no_1, cp_contact_no_2,cp_email,cp_dob, customer_id, prefix_id, primary_cp, cp_anniversary) VALUES ('$name',$con_no1,$con_no2,'$email','$dob',$customer_id,$prefix,$primary_cp,'$anniversary')";
	$result=dbQuery($sql);
	return dbInsertId();
	}
	else 
	return "error";
}	
		

function checkForDuplicateContactPerson($customer_id,$cp_name,$id=false)
{
	$sql="SELECT customer_id 
			  FROM 
			  edms_customer_contact_person 
			  WHERE customer_id=$customer_id AND cp_name = '$cp_name' ";
		if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND cp_id!=$id";		
		
		$result=dbQuery($sql);	
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
			return $resultArray[0][0]; //duplicate found
			} 
		else
		{
			return false;
			}	 
	
}	
	

function editContactPerson($id,$name,$con_no1,$con_no2,$dob,$email,$customer_id,$prefix=NULL,$primary_cp=0,$anniversary=NULL)
{
	
	if(!checkForNumeric($prefix))
		{
			$prefix="NULL";
			}
	if(validateForNull($name) && checkForNumeric($con_no1,$customer_id,$primary_cp) && count($con_no1))
	{
	if(!checkForNumeric($con_no2))
	{
		$con_no2=0;
	}	
	if(!validateForNull($dob))
	$dob="1900-01-01";
	else
	{
	$dob = str_replace('/', '-', $dob);
		$dob = date('Y-m-d',strtotime($dob));	
	}
	
	if(!validateForNull($anniversary))
	$anniversary="1900-01-01";
	else
	{
	$anniversary = str_replace('/', '-', $anniversary);
		$anniversary = date('Y-m-d',strtotime($anniversary));	
	}
	if(!validateForNull($email))
	$email="NA";
	
	$sql="UPDATE edms_customer_contact_person
SET cp_name = '$name', cp_contact_no_1 = $con_no1, cp_contact_no_2 = $con_no2 ,cp_email = '$email',cp_dob = '$dob', prefix_id = $prefix, primary_cp = $primary_cp, cp_anniversary = '$anniversary' WHERE cp_id = $id ";
	$result=dbQuery($sql);
		return "success";	   
		
		}
	return "error";	
	
}

function getContactPersonDetailsForCustomerId($customer_id)	
{
	if(checkForNumeric($customer_id))
	{
		$sql="SELECT * FROM edms_customer_contact_person WHERE customer_id = $customer_id and primary_cp=1";
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
	
		return $resultArray[0];
	}
}

function getContactPersonDetailsForContactPersonId($cp_id)	
{
	if(checkForNumeric($cp_id))
	{
		$sql="SELECT * FROM edms_customer_contact_person WHERE cp_id = $cp_id";
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		return $resultArray[0];
	}
}

function getAllContactPersonDetailsForCustomerId($customer_id)	
{
	if(checkForNumeric($customer_id))
	{
		$sql="SELECT * FROM edms_customer_contact_person WHERE customer_id = $customer_id ORDER BY primary_cp DESC";
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
	
		return $resultArray;
	}
}

function deleteContactPersonDetailsForCustomerId($customer_id)	
{
	if(checkForNumeric($customer_id))
	{
		$sql="DELETE FROM edms_customer_contact_person WHERE customer_id = $customer_id AND primary_cp=1";
		echo $sql;
		$result = dbQuery($sql);
		return "success";
	}
}

?>