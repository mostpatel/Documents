<?php 
require_once("cg.php");
require_once("bd.php");
require_once("common.php");

function listCustomerGroups()
{
	
	try
	{
		$sql="SELECT customer_group_id, customer_group_name
		      FROM ems_customer_group";
		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		return $resultArray; 
	}
	catch(Exception $e)
	{
	}
}




function insertCustomerGroup($name){
	
	try
	{
		$name=clean_data($name);
		$name = ucfirst(strtolower($name));
		
		$duplicate=checkForDuplicateCustomerGroup($name);
		
		if(validateForNull($name) && !$duplicate)
		{
		
		$admin_id=$_SESSION['EMSadminSession']['admin_id'];
		
		$sql="INSERT INTO
		      ems_customer_group (customer_group_name)
			  VALUES
			  ('$name')";
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

function deleteCustomerGroup($id){
	
	try
	{
		if(checkForNumeric($id) && !checkIfCustomerGroupInUse($id))
		{
		$admin_id=$_SESSION['EMSadminSession']['admin_id'];
		$sql="DELETE FROM
			  ems_customer_group
			  WHERE customer_group_id = $id";
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

function updateCustomerGroup($id,$name)
{
	
	try
	{
		$name=clean_data($name);
		$name = ucfirst(strtolower($name));
		$duplicate=checkForDuplicateCustomerGroup($name,$id);
		if(validateForNull($name) && checkForNumeric($id) && !$duplicate)
		{
			
		$admin_id=$_SESSION['EMSadminSession']['admin_id'];
		
		$sql="UPDATE ems_customer_group
			  SET customer_group_name = '$name'
			  WHERE customer_group_id = $id";	  
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

function checkForDuplicateCustomerGroup($name,$id=false)
{
	try{
		$sql="SELECT customer_group_id 
			  FROM 
			  ems_customer_group 
			  WHERE customer_group_name='$name'";
		if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND customer_group_id!=$id";		  
		$result=dbQuery($sql);	
		
		if(dbNumRows($result)>0)
		{
			return true; //duplicate found
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

function getCustomerGroupByID($id)
{
	$sql="SELECT customer_group_id, customer_group_name
			  FROM 
			  ems_customer_group 
			  WHERE customer_group_id=$id";
		$result=dbQuery($sql);	
		$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	{
		return $resultArray[0];
		}
	else
	{
		return false;
		}
	}
	
function checkIfCustomerGroupInUse($id)
{
	$sql="SELECT customer_group_id
	      FROM ems_rel_customer_group
		  WHERE customer_group_id=$id LIMIT 0, 1";
	$result=dbQuery($sql);
	if(dbNumRows($result)>0)
	{
		return true;
	}
	
	return false;
			
}	
?>