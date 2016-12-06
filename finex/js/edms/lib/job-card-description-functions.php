<?php 
require_once("cg.php");
require_once("common.php");
require_once("bd.php");
		
function listJobCardDescription(){
	
	try
	{
		$sql="SELECT job_desc_id, job_desc
		      FROM edms_job_description
			  ORDER BY job_desc";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		return $resultArray;	  
	}
	catch(Exception $e)
	{
	}
	
}	

function getNumberOfJobCardDescription()
{
	$sql="SELECT count(job_desc_id)
		      FROM edms_job_description
			  ORDER BY job_desc";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		return $resultArray[0][0];	
	
	}
function insertJobCardDescription($job_desc){
	
	try
	{
		$job_desc=clean_data($job_desc);
		$job_desc = ucwords(strtolower($job_desc));
		if(validateForNull($job_desc) && !checkForDuplicateJobCardDescription($job_desc))
		{
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$sql="INSERT INTO edms_job_description
		      (job_desc, created_by, last_updated_by, date_added, date_modified)
			  VALUES
			  ('$job_desc', $admin_id, $admin_id, NOW(), NOW())";
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

function insertJobCardDescriptionIfNotDuplicate($job_desc){
	
	try
	{
		$job_desc=clean_data($job_desc);
		$job_desc = ucwords(strtolower($job_desc));
		$duplicate = checkForDuplicateJobCardDescription($job_desc);
		if(validateForNull($job_desc) && !$duplicate)
		{
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$sql="INSERT INTO edms_job_description
		      (job_desc, created_by, last_updated_by, date_added, date_modified)
			  VALUES
			  ('$job_desc', $admin_id, $admin_id, NOW(), NOW())";
		dbQuery($sql);	  
		return dbInsertId();
		}
		else if($duplicate && checkForNumeric($duplicate))
		return $duplicate;
		else
		{
			return "error";
			}
	}
	catch(Exception $e)
	{
	}
	
}	


function deleteJobCardDescription($id){
	
	try
	{
		if(checkForNumeric($id) && !checkIfJobCardDescriptionInUse($id))
		{
		$sql="DELETE FROM edms_job_description
		      WHERE job_desc_id=$id";
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

function updateJobCardDescription($id,$type){
	
	try
	{
		$type=clean_data($type);
		$type = ucwords(strtolower($type));
		if(checkForNumeric($id) && validateForNull($type) && !checkForDuplicateJobCardDescription($type,$id))
		{
			
		$sql="UPDATE edms_job_description
		      SET job_desc='$type'
			  WHERE job_desc_id=$id";
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

function getJobCardDescriptionById($id){
	
	try
	{
		$sql="SELECT job_desc_id, job_desc
		      FROM edms_job_description
			  WHERE job_desc_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0];	 
	}
	catch(Exception $e)
	{
	}
	
}	

function getJobCardDescriptionByJobCardId($job_card_id){
	
	try
	{
		if(checkForNumeric($job_card_id))
		{
		$sql="SELECT edms_job_description.job_desc_id, job_desc
		      FROM edms_job_description, edms_jb_rel_description
			  WHERE edms_jb_rel_description.job_card_id=$job_card_id AND edms_job_description.job_desc_id = edms_jb_rel_description.job_desc_id";
			 
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray;	 
		}
		return false;
	}
	catch(Exception $e)
	{
	}
	
}
function getJobCardDescriptionNameById($id){
	
	try
	{
		$sql="SELECT job_desc_id, job_desc
		      FROM edms_job_description
			  WHERE job_desc_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][1];	 
	}
	catch(Exception $e)
	{
	}
	
}	

function checkForDuplicateJobCardDescription($job_desc,$id=false)
{
	    if(validateForNull($job_desc))
		{
		$sql="SELECT job_desc_id
		      FROM edms_job_description
			  WHERE job_desc='$job_desc'";
		if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND job_desc_id!=$id";		  	  
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0];
		else
		return false;
		}
	}	
function checkIfJobCardDescriptionInUse($id)
{
	if(checkForNumeric($id))
	{
	$sql="SELECT job_desc_id FROM
			edms_jb_rel_description
			WHERE job_desc_id=$id";
	$result=dbQuery($sql);
	if(dbNumRows($result)>0)
	return true;
	else
	return false;		
	}
	
}	
?>