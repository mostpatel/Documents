<?php 
require_once("cg.php");
require_once("bd.php");
require_once("common.php");

function listEnquiryGroups()
{
	
	try
	{
		$sql="SELECT enquiry_group_id, enquiry_group_name
		      FROM ems_enquiry_group";
		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		return $resultArray; 
	}
	catch(Exception $e)
	{
	}
}




function insertEnquiryGroup($name){
	
	try
	{
		$name=clean_data($name);
		$name = ucfirst(strtolower($name));
		
		$duplicate=checkForDuplicateEnquiryGroup($name);
		
		if(validateForNull($name) && !$duplicate)
		{
		
		$admin_id=$_SESSION['EMSadminSession']['admin_id'];
		
		$sql="INSERT INTO
		      ems_enquiry_group (enquiry_group_name)
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

function deleteEnquiryGroup($id){
	
	try
	{
		if(checkForNumeric($id) && !checkIfEnquiryGroupInUse($id) && $id!=1)
		{
		$admin_id=$_SESSION['EMSadminSession']['admin_id'];
		
		$sql="DELETE FROM
			  ems_enquiry_group
			  WHERE enquiry_group_id = $id";
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

function updateEnquiryGroup($id,$name)
{
	
	try
	{
		$name=clean_data($name);
		$name = ucfirst(strtolower($name));
		$duplicate=checkForDuplicateEnquiryGroup($name,$id);
		if(validateForNull($name) && checkForNumeric($id) && !$duplicate && $id!=1)
		{
			
		$admin_id=$_SESSION['EMSadminSession']['admin_id'];
		
		$sql="UPDATE ems_enquiry_group
			  SET enquiry_group_name = '$name'
			  WHERE enquiry_group_id = $id";	  
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

function checkForDuplicateEnquiryGroup($name, $id=false)
{
	try{
		$sql="SELECT enquiry_group_id 
			  FROM 
			  ems_enquiry_group 
			  WHERE enquiry_group_name='$name'";
		if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND enquiry_group_id!=$id";		  
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

function getEnquiryGroupByID($id)
{
	$sql="SELECT enquiry_group_id, enquiry_group_name
			  FROM 
			  ems_enquiry_group 
			  WHERE enquiry_group_id=$id";
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
	
function checkIfEnquiryGroupInUse($id)
{
	$sql="SELECT enquiry_group_id
	      FROM ems_rel_enquiry_group
		  WHERE enquiry_group_id=$id LIMIT 0, 1";
	$result=dbQuery($sql);
	if(dbNumRows($result)>0)
	{
		return true;
	}
	
	return false;
			
}	
?>