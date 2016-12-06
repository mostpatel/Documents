<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("common.php");
require_once("bd.php");


	
function listCustomerTypes(){
	
	try
	{
		$sql="SELECT customer_type_id, customer_type, reference
			  FROM ems_customer_type";
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


function listReferenceCustomerTypeIDString(){
	
	try
	{
		$sql="SELECT GROUP_CONCAT(customer_type_id)
			  FROM ems_customer_type WHERE reference=1 GROUP BY reference";
		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0]; 
		else
		return false;
		  
	}
	catch(Exception $e)
	{
	}
	
}	



function insertCustomerType($name, $refrence_status){
	try
	{
		$name=clean_data($name);
		$name = ucwords(strtolower($name));
		if(validateForNull($name) && !checkDuplicateCustomerType($name))
		{
			$sql="INSERT INTO 
				ems_customer_type (customer_type, reference)
				VALUES ('$name', $refrence_status)";
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



function checkDuplicateCustomerType($name,$id=false)
{
	if(validateForNull($name))
	{
		$sql="SELECT customer_type_id
			  FROM ems_customer_type
			  WHERE customer_type='$name'";
		if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND customer_type_id!=$id";		  
		$result=dbQuery($sql);	
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
			return $resultArray[0][0]; //duplicate found
			} 
		else
		{
			return false;
			}
	}
}		


function deleteCustomerType($id){
	
	try
	{
		if(!checkifCustomerTypeInUse($id))
		{
		$sql="DELETE FROM ems_customer_type
		      WHERE customer_type_id=$id";
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



function checkifCustomerTypeInUse($id)
{
	
	if(checkForNumeric($id))
	{
	$sql="SELECT enquiry_form_id
	      FROM ems_enquiry_form
		  Where customer_type_id=$id";
	$result=dbQuery($sql);	  
	if(dbNumRows($result)>0)
	return true;
	else 
	return false;
	}
	
}			
		
	

function updateCustomerType($id,$name){
	
	try
	{
		$name=clean_data($name);
		$name = ucwords(strtolower($name));
		if(validateForNull($name) && checkForNumeric($id) && !checkDuplicateCustomerType($name,$id))
		{
		$sql="UPDATE ems_customer_type
			  SET customer_type='$name'
			  WHERE customer_type_id=$id";
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


function getCustomerTypeById($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT customer_type_id, customer_type, reference
			  FROM ems_customer_type
			  WHERE customer_type_id=$id";
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