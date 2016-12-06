<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("common.php");
require_once("bd.php");
		
function listVehicleGodowns(){
	
	try
	{
		$sql="SELECT godown_id, godown_name
		      FROM fin_vehicle_seize_godowns
			  ORDER BY godown_name";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		return $resultArray;	  
	}
	catch(Exception $e)
	{
	}
	
}	

function getNumberOfVehicleGodowns()
{
	$sql="SELECT count(godown_id)
		      FROM fin_vehicle_seize_godowns
			  ORDER BY godown_name";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		return $resultArray[0][0];	
	
	}
function insertVehicleGodown($godown_name){
	
	try
	{
		$godown_name=clean_data($godown_name);
		$godown_name = ucwords(strtolower($godown_name));
		if(validateForNull($godown_name) && !checkForDuplicateVehicleGodown($godown_name))
		{
		$admin_id=$_SESSION['adminSession']['admin_id'];
		$sql="INSERT INTO fin_vehicle_seize_godowns
		      (godown_name)
			  VALUES
			  ('$godown_name')";
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

function insertVehicleGodownIfNotDuplicate($godown_name){
	
	try
	{
		$godown_name=clean_data($godown_name);
		$godown_name = ucwords(strtolower($godown_name));
		$duplicate = checkForDuplicateVehicleGodown($godown_name);
		if(validateForNull($godown_name) && !$duplicate)
		{
		$admin_id=$_SESSION['adminSession']['admin_id'];
		$sql="INSERT INTO fin_vehicle_seize_godowns
		      (godown_name)
			  VALUES
			  ('$godown_name')";
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

function deleteVehicleGodown($id){
	
	try
	{
		if(checkForNumeric($id) && !checkIfVehicleGodownInUse($id))
		{
		$sql="DELETE FROM fin_vehicle_seize_godowns
		      WHERE godown_id=$id";
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

function updateVehicleGodown($id,$type){
	
	try
	{
		$type=clean_data($type);
		$type = ucwords(strtolower($type));
		if(checkForNumeric($id) && validateForNull($type) && !checkForDuplicateVehicleGodown($type,$id))
		{
			
		$sql="UPDATE fin_vehicle_seize_godowns
		      SET godown_name='$type'
			  WHERE godown_id=$id";
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

function getVehicleGodownById($id){
	
	try
	{
		$sql="SELECT godown_id, godown_name
		      FROM fin_vehicle_seize_godowns
			  WHERE godown_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0];	 
	}
	catch(Exception $e)
	{
	}
	
}	

function getOthersVehicleGodownId(){
	
	try
	{
		$sql="SELECT godown_id
		      FROM fin_vehicle_seize_godowns
			  WHERE godown_name='Others'";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0];	 
	}
	catch(Exception $e)
	{
	}
	
}	

function getVehicleGodownNameById($id){
	
	try
	{
		$sql="SELECT godown_id, godown_name
		      FROM fin_vehicle_seize_godowns
			  WHERE godown_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][1];	 
	}
	catch(Exception $e)
	{
	}
	
}	

function checkForDuplicateVehicleGodown($godown_name,$id=false)
{
	    if(validateForNull($godown_name))
		{
		$sql="SELECT godown_id
		      FROM fin_vehicle_seize_godowns
			  WHERE godown_name='$godown_name'";
		if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND godown_id!=$id";		  	  
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0];
		else
		return false;
		}
	}	
function checkIfVehicleGodownInUse($id)
{
	if(checkForNumeric($id))
	{
	$sql="SELECT vehicle_id FROM
			fin_vehicle
			WHERE godown_id=$id";
	$result=dbQuery($sql);
	if(dbNumRows($result)>0)
	return true;
	else
	return false;		
	}
	
	}	
?>