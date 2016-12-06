<?php 
    require_once("cg.php");
    require_once("bd.php");
    require_once("common.php");



function insertOwner($name,$con_no1,$con_no2,$dob,$email,$customer_id,$anniversary,$prefix_id)
{
	
	
	
	if(validateForNull($name) && checkForNumeric($con_no1,$customer_id,$prefix_id)  && !checkForDuplicateOwner($customer_id))
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
	
	$sql="INSERT INTO edms_customer_owner
(owner_name, owner_contact_no_1, owner_contact_no_2,owner_email,owner_dob, customer_id, owner_anniversary,prefix_id) VALUES ('$name',$con_no1,$con_no2,'$email','$dob',$customer_id,'$anniversary',$prefix_id)";
	$result=dbQuery($sql);
	return dbInsertId();
	}
	else 
	return "error";
}	
		

function checkForDuplicateOwner($customer_id,$id=false)
{
	$sql="SELECT customer_id 
			  FROM 
			  edms_customer_owner 
			  WHERE customer_id=$customer_id";
		if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND owner_id!=$id";		  
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
	

function editOwner($name,$con_no1,$con_no2,$dob,$email,$customer_id,$anniversary,$prefix_id)
{
	
	
	if(validateForNull($name) && checkForNumeric($con_no1,$customer_id,$prefix_id))
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
	
	$sql="UPDATE edms_customer_owner
SET owner_name = '$name', owner_contact_no_1 = $con_no1, owner_contact_no_2 = $con_no2 ,owner_email = '$email',owner_dob = '$dob', owner_anniversary = '$anniversary', prefix_id = $prefix_id where customer_id = $customer_id";

	$result=dbQuery($sql);
		return "success";	   
		
		}
	return "error";	
	
}	

function getOwnerDetailsByCustomerId($customer_id)
{
	if(checkForNumeric($customer_id))
	{
		$sql="SELECT * FROM edms_customer_owner WHERE customer_id = $customer_id";
		$result=dbQuery($sql);	
		$resultArray = dbResultToArray($result);
		return $resultArray[0];
	}
	return false;
	
	
}

?>