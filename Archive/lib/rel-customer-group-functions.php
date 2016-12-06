<?php 
require_once("cg.php");
require_once("bd.php");
require_once("common.php");




function insertRelCustomerGroup($customer_id, $customer_group_id_array)
{
	
	try
	{
		
		
		if(checkForNumeric($customer_id))
		{
			
		foreach($customer_group_id_array as $customer_group_id)
		{
		$sql="INSERT INTO
		      ems_rel_customer_group (customer_id,customer_group_id)
			  VALUES
			  ($customer_id, $customer_group_id)";
		$result=dbQuery($sql);	
		}
		
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

function deleteRelCustomerGroup($customer_id){
	
	try
	{
		if(checkForNumeric($id))
		{
	
		$sql="DELETE FROM
			  ems_rel_customer_group
			  WHERE customer_id = $customer_id";
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

function updateRelCustomerGroup($customer_id, $customer_group_id_array)
{
	deleteRelCustomerGroup($customer_id);
	insertRelCustomerGroup($customer_id, $customer_group_id_array);
}

function getCustomerGroupNamesByCustomerId($customer_id)
{
	
	try
	{
		
		$sql="SELECT customer_group_name
		
			  FROM ems_customer_group
			  
			  JOIN ems_rel_customer_group
			  ON ems_customer_group.customer_group_id = ems_rel_customer_group.customer_group_id
			  
			  WHERE ems_rel_customer_group.customer_id = $customer_id";
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

?>