<?php 
    require_once("cg.php");
    require_once("bd.php");
    require_once("common.php");

function checkForDuplicateCustomerToGroup($customer_id,$group_id)
{
	
	$sql="SELECT group_id FROM edms_rel_groups_customer WHERE customer_id = $customer_id AND group_id = $group_id";
	$result=dbQuery($sql);	
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	{
		return true;
		}
	else
	{
		return false;
		}
}



function insertCustomerGroup($name,$group_array)
{
	
	$group_id=insertCustomerGroupName($name);
	
	if(checkForNumeric($group_id))
	{
	insertCustomersToGroup($group_array,$group_id);
	return "success";
	}
	else 
	return "error";
}	
		
function insertCustomerGroupName($name)
{
	$duplicate = checkForDuplicateCustomerGroup($name);
	if(validateForNull($name) && strlen($name)<255 && !$duplicate)
	{
		$name=trim($name);
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$sql="INSERT INTO edms_groups (group_name,created_by,last_updated_by,date_added,date_modified)
		       VALUES('$name',$admin_id,$admin_id,NOW(),NOW())";
		dbQuery($sql);
		return dbInsertId();	   
		
		}
	else if(checkForNumeric($duplicate))
	return $duplicate;	
	return "error";	
}

function AddCustomerToGroups($customer_id,$group_array)
{
 	DeleteGroupsForCustomer($customer_id);	
 if(checkForNumeric($customer_id,$group_array[0]))
 {
	foreach($group_array as $group_id)
	{
		if(checkForNumeric($group_id) && !checkForDuplicateCustomerToGroup($customer_id,$group_id))
		{
		
			$sql="INSERT INTO edms_rel_groups_customer(group_id,customer_id) VALUES ($group_id,$customer_id)";
			dbQuery($sql);
		}
	} 
	return "success";
 }	
}		

function DeleteGroupsForCustomer($customer_id)
{
	if(checkForNumeric($customer_id))
	{
		$sql="DELETE FROM edms_rel_groups_customer WHERE customer_id = $customer_id";
		dbQuery($sql);
	}
}

function checkForDuplicateCustomerGroup($name,$id=false)
{
	$sql="SELECT group_id 
			  FROM 
			  edms_groups 
			  WHERE group_name='$name'";
		if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND group_id!=$id";		  
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
	

function editCustomerGroupName($id,$name)
{
	
	if(validateForNull($name) && strlen($name)<255 && !checkForDuplicateCustomerGroup($name,$id) && checkForNumeric($id))
	{
		$name=trim($name);
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$sql="UPDATE  edms_groups
		      SET group_name='$name',last_updated_by=$admin_id,date_modified=NOW()
		      WHERE group_id=$id";
		dbQuery($sql);
		return "success";	   
		
		}
	return "error";	
	
	}	

function deleteCustomerGroup($id)
{
	if(checkForNumeric($id))
	{
		$sql="DELETE FROM edms_groups WHERE group_id=$id";
		dbQuery($sql);
		return "success";
		}
	return "error";	
}	

function insertCustomersToGroup($files_array,$group_id)
{
	if(count($files_array)>0 && is_numeric($group_id))
	{
	foreach($files_array as $customer_id)
	{	
    
	$sql="INSERT INTO edms_rel_groups_customer (group_id,customer_id)
	      VALUES($group_id,$customer_id)";
	dbQuery($sql);
	  
	}
	return "success";	
	
	}
	return "error";
	}

function getGroupsForCustomerId($customer_id)
{
	$sql="SELECT edms_rel_groups_customer.group_id,group_name FROM edms_rel_groups_customer,edms_groups WHERE edms_groups.group_id = edms_rel_groups_customer.group_id AND customer_id = $customer_id";
	$result=dbQuery($sql);	
		$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	{
		return $resultArray;
		}
	else
	{
		return false;
		}
}


function getGroupsStringForCustomerId($customer_id)
{
	$sql="SELECT GROUP_CONCAT(group_id) FROM edms_rel_groups_customer WHERE customer_id = $customer_id GROUP BY customer_id";
	$result=dbQuery($sql);	
		$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	{
		return $resultArray;
		}
	else
	{
		return false;
		}
}
 

function deleteRelCustomerGroupByGroupID($group_id)
{
	if(checkForNumeric($group_id))
	{
		$sql="DELETE FROM edms_rel_groups_customer WHERE group_id=$group_id";
		dbQuery($sql);
		return "success";
		}
	return "error";	
	
	}

function deleteRelCustomerGroupByCustomerID($group_id)
{
	if(checkForNumeric($group_id))
	{
		$sql="DELETE FROM edms_rel_groups_customer WHERE customer_id=$group_id";
		dbQuery($sql);
		return "success";
		}
	return "error";	
	
	}
	
function editCustomerGroup($id,$name,$group_array)
{
	editCustomerGroupName($id,$name);
	$result1=deleteRelCustomerGroupByGroupID($id);
	$result2=insertCustomersToGroup($group_array,$id);
	if($result1=="success" && $result2=="success")
	return "success";
	else
	return "error";
	}		
	

function listCustomerGroups()
{
	$sql="SELECT edms_groups.group_id,group_name,GROUP_CONCAT(customer_id) as files_id
	      FROM edms_groups
		  LEFT JOIN edms_rel_groups_customer
		  ON edms_groups.group_id=edms_rel_groups_customer.group_id
		  GROUP BY edms_groups.group_id";
	$result=dbQuery($sql);	 
	if(dbNumRows($result)>0)
	return dbResultToArray($result);
	else 
	return "error"; 
	}		
function listCustomerGroupsWithRest()
{
	$sql="SELECT edms_groups.group_id,group_name,GROUP_CONCAT(customer_id) as files_id
	      FROM edms_groups
		  LEFT JOIN edms_rel_groups_customer
		  ON edms_groups.group_id=edms_rel_groups_customer.group_id
		  GROUP BY edms_groups.group_id";
	$result=dbQuery($sql);	
	$resultArray=dbResultToArray($result); 
	if(dbNumRows($result)>0)
	{
		$j=count($resultArray);
		$restGroups=getRestGroupsFromGroup();
		if($restGroups!="error")
		{
		$resultArray[$j]['group_id']=0;
		$resultArray[$j]['group_name']='Rest Groups';
		$resultArray[$j]['groups_id']=$restGroups;
		}
		return $resultArray;
		}
	else 
	return "error"; 
	}		

function getCustomerGroupByID($id)
{
	$sql="SELECT edms_groups.group_id,group_name,GROUP_CONCAT(customer_id) as customer_ids
	      FROM edms_groups
		  LEFT JOIN edms_rel_groups_customer
		  ON edms_groups.group_id=edms_rel_groups_customer.group_id
		  WHERE edms_groups.group_id=$id
		  GROUP BY edms_groups.group_id";
	$result=dbQuery($sql);	
	$resultArray=dbResultToArray($result); 
	if(dbNumRows($result)>0)
	return $resultArray[0];
	else 
	return "error"; 
}

function getCustomerGroupNameByID($id)
{
	$sql="SELECT group_name
	      FROM edms_groups
		  WHERE edms_groups.group_id=$id
		 ";
	$result=dbQuery($sql);	
	$resultArray=dbResultToArray($result); 
	if(dbNumRows($result)>0)
	return $resultArray[0][0];
	else 
	return "error"; 
}

function getCustomerGroupIdNameByName($name)
{
	if(validateForNull($name))
	{
	$sql="SELECT group_id
	      FROM edms_groups
		  WHERE group_name='$name'
		 ";
	$result=dbQuery($sql);	
	$resultArray=dbResultToArray($result); 
	if(dbNumRows($result)>0)
	return $resultArray[0][0];
	else 
	return "error";
	}
}

function getCustomerIdsForCustomerGroupID($id)
{
	if(checkForNumeric($id))
	{
	$sql="SELECT edms_groups.group_id,group_name,GROUP_CONCAT(customer_id) as customer_ids
	      FROM edms_groups
		  LEFT JOIN edms_rel_groups_customer
		  ON edms_groups.group_id=edms_rel_groups_customer.group_id
		  WHERE edms_groups.group_id=$id
		  GROUP BY edms_groups.group_id";
		  
	$result=dbQuery($sql);	
	$resultArray=dbResultToArray($result); 
	
	if(dbNumRows($result)>0)
	{
	
	return explode(',',$resultArray[0]['customer_ids']);
	}
	else 
	return "error"; 
	
	}
	return "error"; 
	
}

function updateCustomersForCustomerGroup($group_id,$customer_array)
{
	if(checkForNumeric($group_id))
	{
		$sql="DELETE FROM edms_rel_groups_customer WHERE group_id = $group_id";
		dbQuery($sql);
		
		foreach($customer_array as $customer_id)
		{
			if(checkForNumeric($customer_id))
			{
			$sql="INSERT INTO edms_rel_groups_customer (group_id,customer_id) VALUES ($group_id,$customer_id)";
			dbQuery($sql);
			}
		}
	}
	
}
	

?>