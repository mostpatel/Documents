<?php 
require_once("cg.php");
require_once("common.php");
require_once("bd.php");
		
function listJobCardWorkDone(){
	
	try
	{
		$sql="SELECT job_wd_id, job_wd
		      FROM edms_job_work_done
			  ORDER BY job_wd";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		return $resultArray;	  
	}
	catch(Exception $e)
	{
	}
	
}	

function getNumberOfJobCardWorkDone()
{
	$sql="SELECT count(job_wd_id)
		      FROM edms_job_work_done
			  ORDER BY job_wd";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		return $resultArray[0][0];	
	
	}
function insertJobCardWorkDone($job_wd){
	
	try
	{
		$job_wd=clean_data($job_wd);
		$job_wd = ucwords(strtolower($job_wd));
		if(validateForNull($job_wd) && !checkForDuplicateJobCardWorkDone($job_wd))
		{
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$sql="INSERT INTO edms_job_work_done
		      (job_wd, created_by, last_updated_by, date_added, date_modified)
			  VALUES
			  ('$job_wd', $admin_id, $admin_id, NOW(), NOW())";
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

function insertJobCardWorkDoneIfNotDuplicate($job_wd){
	
	try
	{
		$job_wd=clean_data($job_wd);
		$job_wd = ucwords(strtolower($job_wd));
		$duplicate = checkForDuplicateJobCardWorkDone($job_wd);
		if(validateForNull($job_wd) && !$duplicate)
		{
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$sql="INSERT INTO edms_job_work_done
		      (job_wd, created_by, last_updated_by, date_added, date_modified)
			  VALUES
			  ('$job_wd', $admin_id, $admin_id, NOW(), NOW())";
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


function deleteJobCardWorkDone($id){
	
	try
	{
		if(checkForNumeric($id) && !checkIfJobCardWorkDoneInUse($id))
		{
		$sql="DELETE FROM edms_job_work_done
		      WHERE job_wd_id=$id";
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

function updateJobCardWorkDone($id,$type){
	
	try
	{
		$type=clean_data($type);
		$type = ucwords(strtolower($type));
		if(checkForNumeric($id) && validateForNull($type) && !checkForDuplicateJobCardWorkDone($type,$id))
		{
			
		$sql="UPDATE edms_job_work_done
		      SET job_wd='$type'
			  WHERE job_wd_id=$id";
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

function getJobCardWorkDoneById($id){
	
	try
	{
		$sql="SELECT job_wd_id, job_wd
		      FROM edms_job_work_done
			  WHERE job_wd_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0];	 
	}
	catch(Exception $e)
	{
	}
	
}	

function getJobCardWorkDoneByJobCardId($job_card_id){
	
	try
	{
		if(checkForNumeric($job_card_id))
		{
		$sql="SELECT edms_job_work_done.job_wd_id, job_wd
		      FROM edms_job_work_done, edms_jb_rel_work_done
			  WHERE edms_jb_rel_work_done.job_card_id=$job_card_id AND edms_job_work_done.job_wd_id = edms_jb_rel_work_done.job_wd_id";
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
function getJobCardWorkDoneNameById($id){
	
	try
	{
		$sql="SELECT job_wd_id, job_wd
		      FROM edms_job_work_done
			  WHERE job_wd_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][1];	 
	}
	catch(Exception $e)
	{
	}
	
}	

function checkForDuplicateJobCardWorkDone($job_wd,$id=false)
{
	    if(validateForNull($job_wd))
		{
		$sql="SELECT job_wd_id
		      FROM edms_job_work_done
			  WHERE job_wd='$job_wd'";
		if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND job_wd_id!=$id";		  	  
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0];
		else
		return false;
		}
	}	
function checkIfJobCardWorkDoneInUse($id)
{
	if(checkForNumeric($id))
	{
	$sql="SELECT job_wd_id FROM
			edms_jb_rel_work_done
			WHERE job_wd_id=$id";
	$result=dbQuery($sql);
	if(dbNumRows($result)>0)
	return true;
	else
	return false;		
	}
	
}	
?>