<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("common.php");
require_once("bd.php");
		
function listCasePetetionars(){
	
	try
	{
		$sql="SELECT case_petetionar_id, case_petetionar
		      FROM fin_case_petetionar
			  ORDER BY case_petetionar";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		return $resultArray;	  
	}
	catch(Exception $e)
	{
	}
	
}	

function getNumberOfCasePetetionars()
{
	$sql="SELECT count(case_petetionar_id)
		      FROM fin_case_petetionar
			  ORDER BY case_petetionar";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		return $resultArray[0][0];	
	
	}
function insertCasePetetionar($case_petetionar){
	
	try
	{
		$case_petetionar=clean_data($case_petetionar);
		$case_petetionar = ucwords(strtolower($case_petetionar));
		if(validateForNull($case_petetionar) && !checkForDuplicateCasePetetionar($case_petetionar))
		{
		$admin_id=$_SESSION['adminSession']['admin_id'];
		$sql="INSERT INTO fin_case_petetionar
		      (case_petetionar, created_by, last_updated_by, date_added, date_modified)
			  VALUES
			  ('$case_petetionar', $admin_id, $admin_id, NOW(), NOW())";
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

function insertCasePetetionarIfNotDuplicate($case_petetionar){
	
	try
	{
		$case_petetionar=clean_data($case_petetionar);
		$case_petetionar = ucwords(strtolower($case_petetionar));
		$duplicate = checkForDuplicateCasePetetionar($case_petetionar);
		if(validateForNull($case_petetionar) && !$duplicate)
		{
		$admin_id=$_SESSION['adminSession']['admin_id'];
		$sql="INSERT INTO fin_case_petetionar
		      (case_petetionar, created_by, last_updated_by, date_added, date_modified)
			  VALUES
			  ('$case_petetionar', $admin_id, $admin_id, NOW(), NOW())";
		dbQuery($sql);	  
		return dbInsertId();
		}
		else if($duplicate)
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

function deleteCasePetetionar($id){
	
	try
	{
		if(checkForNumeric($id) && !checkIfCasePetetionarInUse($id))
		{
		$sql="DELETE FROM fin_case_petetionar
		      WHERE case_petetionar_id=$id";
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

function updateCasePetetionar($id,$type){
	
	try
	{
		$type=clean_data($type);
		$type = ucwords(strtolower($type));
		if(checkForNumeric($id) && validateForNull($type) && !checkForDuplicateCasePetetionar($type,$id))
		{
			
		$sql="UPDATE fin_case_petetionar
		      SET case_petetionar='$type'
			  WHERE case_petetionar_id=$id";
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

function getCasePetetionarById($id){
	
	try
	{
		$sql="SELECT case_petetionar_id, case_petetionar
		      FROM fin_case_petetionar
			  WHERE case_petetionar_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0];	 
	}
	catch(Exception $e)
	{
	}
	
}	

function getOthersCasePetetionarId(){
	
	try
	{
		$sql="SELECT case_petetionar_id
		      FROM fin_case_petetionar
			  WHERE case_petetionar='Others'";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0];	 
	}
	catch(Exception $e)
	{
	}
	
}	

function getCasePetetionarNameById($id){
	
	try
	{
		$sql="SELECT case_petetionar_id, case_petetionar
		      FROM fin_case_petetionar
			  WHERE case_petetionar_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][1];	 
	}
	catch(Exception $e)
	{
	}
	
}	

function checkForDuplicateCasePetetionar($case_petetionar,$id=false)
{
	    if(validateForNull($case_petetionar))
		{
		$sql="SELECT case_petetionar_id
		      FROM fin_case_petetionar
			  WHERE case_petetionar='$case_petetionar'";
		if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND case_petetionar_id!=$id";		  	  
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0];
		else
		return false;
		}
	}	
function checkIfCasePetetionarInUse($id)
{
	if(checkForNumeric($id))
	{
	$sql="SELECT vehicle_id FROM
			fin_vehicle
			WHERE case_petetionar_id=$id";
	$result=dbQuery($sql);
	if(dbNumRows($result)>0)
	return true;
	else
	return false;		
	}
	
	}	
?>