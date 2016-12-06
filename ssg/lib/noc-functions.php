<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("file-functions.php");
require_once("loan-functions.php");
require_once("common.php");
require_once("bd.php");


function listNOCForFileID($file_id){
	
	try
	{
		if(checkForNumeric($file_id))
		{
			$sql="SELECT noc_id, noc_date,  file_id, remarks, created_by, last_modified_by, date_added, date_modified FROM fin_file_noc WHERE file_id=$file_id ORDER BY noc_date DESC";
			$result=dbQuery($sql);
			$resultArray=dbResultToArray($result);
			if(dbNumRows($result)>0)
			return $resultArray;
			else
			return false;
			}
	}
	catch(Exception $e)
	{
	}
	
}	

function getNumberOfNOCForFileID($file_id)
{
	if(checkForNumeric($file_id))
	{
		
		$sql="SELECT count(noc_id) FROM fin_file_noc WHERE file_id=$file_id ";
			$result=dbQuery($sql);
			$resultArray=dbResultToArray($result);
			if(dbNumRows($result)>0)
			return $resultArray[0][0];
			else
			return 0;
		
		}
	
}

function getLatestNOCDateForFile($file_id)
{
	if(checkForNumeric($file_id))
	{
		
		$sql="SELECT noc_date FROM fin_file_noc WHERE file_id=$file_id ORDER BY noc_date DESC";
			$result=dbQuery($sql);
			$resultArray=dbResultToArray($result);
			if(dbNumRows($result)>0)
			return $resultArray[0][0];
			else
			return 0;
		
		}
	}

function insertNOC($file_id,$noc_date,$remarks=null){
	
	try
	{
		
		 $admin_id=$_SESSION['adminSession']['admin_id'];
		
		 $noc_date=clean_data($noc_date);
		 
		 
		 $file=getFileDetailsByFileId($file_id);
		 $file_status = $file['file_status'];
		 if(!validateForNull($remarks))
		 $remarks="";
		
		
		 
		 if(checkForNumeric($file_id) && validateForNull($noc_date) && !checkForDuplicateNOC($file_id) && ($file_status==2 || $file_status==4))
		 {
		
		$noc_date = str_replace('/', '-', $noc_date);// converts dd/mm/yyyy to dd-mm-yyyy
		$noc_date = date('Y-m-d',strtotime($noc_date)); // converts date to Y-m-d format
				 
		$sql="INSERT INTO fin_file_noc(noc_date,  file_id, remarks, created_by, last_modified_by, date_added, date_modified) VALUES ('$noc_date', $file_id, '$remarks', $admin_id, $admin_id, NOW(), NOW())";
	
		$result=dbQuery($sql);
		return dbInsertId();
		 }
		 return "error";
	}
	catch(Exception $e)
	{
	}
	
}	

function deleteNOC($id){
	
	try
	{
		if(checkForNumeric($id))
		{
			$sql="DELETE FROM fin_file_noc WHERE noc_id=$id";
			dbQuery($sql);
			return "success";
			}
		return "error";	
	}
	catch(Exception $e)
	{
	}
	
}	

function updateNOC($file_id,$noc_date,$remarks){
	
	try
	{
		
		 $admin_id=$_SESSION['adminSession']['admin_id'];
		
		 $noc_date=clean_data($noc_date);
		 
		 
		 
		 if(!validateForNull($remarks))
		 $remarks="";
		
		 
		 if(checkForNumeric($file_id) && validateForNull($noc_date))
		 {
		
		$noc_date = str_replace('/', '-', $noc_date);// converts dd/mm/yyyy to dd-mm-yyyy
		$noc_date = date('Y-m-d',strtotime($noc_date)); // converts date to Y-m-d format
			
		$sql= "UPDATE fin_file_noc SET noc_date = '$noc_date',$remarks='$remarks' WHERE file_id = $file_id";
		dbQuery($sql);
		return "success";
		 }
		 return "error";
	}
	catch(Exception $e)
	{
	}
	
}	

function getNOCById($id){
	
	try
	{
		
		if(checkForNumeric($id))
		{
			$sql="SELECT noc_id, noc_date, file_id, remarks, created_by, last_modified_by, date_added, date_modified FROM fin_file_noc WHERE noc_id=$id";
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

function getNOCByFileId($id){
	
	try
	{
		
		if(checkForNumeric($id))
		{
			$sql="SELECT noc_id, noc_date, file_id, remarks, created_by, last_modified_by, date_added, date_modified FROM fin_file_noc WHERE file_id=$id";
			$result=dbQuery($sql);
			$resultArray=dbResultToArray($result);
			if(dbNumRows($result)>0)
			return $resultArray[0];
			else
			return false;
			}
			return false;
	}
	catch(Exception $e)
	{
	}
	
}		

function checkForDuplicateNOC($file_id){
	
	try
	{
		
		if(checkForNumeric($id))
		{
			$sql="SELECT noc_id, noc_date, file_id, remarks, created_by, last_modified_by, date_added, date_modified FROM fin_file_noc WHERE file_id=$file_id";
			$result=dbQuery($sql);
			$resultArray=dbResultToArray($result);
			if(dbNumRows($result)>0)
			return true;
			else
			return false;
			}
	}
	catch(Exception $e)
	{
	}
	
}		
?>