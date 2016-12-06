<?php 
require_once("cg.php");
require_once("common.php");
require_once("bd.php");
		
function listJobCardRemarks(){
	
	try
	{
		$sql="SELECT jb_remark_id, jb_remarks
		      FROM edms_jb_remarks
			  ORDER BY jb_remarks";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		return $resultArray;	  
	}
	catch(Exception $e)
	{
	}
	
}	

function getNumberOfJobCardRemarks()
{
	$sql="SELECT count(jb_remark_id)
		      FROM edms_jb_remarks
			  ORDER BY jb_remarks";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		return $resultArray[0][0];	
	
	}
function insertJobCardRemarks($jb_remarks,$job_card_id){
	
	try
	{
		$jb_remarks=clean_data($jb_remarks);
		$jb_remarks = ucwords(strtolower($jb_remarks));
		if(validateForNull($jb_remarks) && checkForNumeric($job_card_id) && !checkForDuplicateJobCardRemarks($jb_remarks,$job_card_id))
		{
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$sql="INSERT INTO edms_jb_remarks
		      (jb_remarks, job_card_id, created_by, last_updated_by, date_added, date_modified)
			  VALUES
			  ('$jb_remarks', $job_card_id, $admin_id, $admin_id, NOW(), NOW())";
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

function deleteJobCardRemarks($id){
	
	try
	{
		if(checkForNumeric($id) && !checkIfJobCardRemarksInUse($id))
		{
		$sql="DELETE FROM edms_jb_remarks
		      WHERE jb_remark_id=$id";
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

function deleteJobCardRemarksForJobCardId($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="DELETE FROM edms_jb_remarks
		      WHERE job_card_id=$id";
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

function updateJobCardRemarks($id,$type){
	
	try
	{
		$type=clean_data($type);
		$type = ucwords(strtolower($type));
		if(checkForNumeric($id) && validateForNull($type) && !checkForDuplicateJobCardRemarks($type,$job_card_id,$id))
		{
			
		$sql="UPDATE edms_jb_remarks
		      SET jb_remarks='$type'
			  WHERE jb_remark_id=$id";
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

function getJobCardRemarksById($id){
	
	try
	{
		$sql="SELECT jb_remark_id, jb_remarks
		      FROM edms_jb_remarks
			  WHERE jb_remark_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0];	 
	}
	catch(Exception $e)
	{
	}
	
}	

function getJobCardRemarksByJobCardId($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT jb_remark_id, jb_remarks
		      FROM edms_jb_remarks
			  WHERE job_card_id=$id";
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

function checkForDuplicateJobCardRemarks($jb_remarks,$job_card_id,$id=false)
{
	    if(validateForNull($jb_remarks))
		{
		$sql="SELECT jb_remark_id
		      FROM edms_jb_remarks
			  WHERE jb_remarks='$jb_remarks' AND job_card_id = $job_card_id ";
		if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND jb_remark_id!=$id";		  	  
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0];
		else
		return false;
		}
}	
?>