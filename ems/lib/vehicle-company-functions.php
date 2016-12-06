<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("vehicle-model-functions.php");
require_once("common.php");
require_once("bd.php");
		
function listVehicleCompanies(){
	
	try
	{
		$sql="SELECT vehicle_company_id, vehicle_company_name
			  FROM ems_vehicle_company ORDER BY vehicle_company_name";
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


function insertVehicleCompany($name){
	try
	{
		$name=clean_data($name);
		$name = ucwords(strtolower($name));
		if(validateForNull($name) && !checkDuplicateVehicleCompany($name))
		{
			$sql="INSERT INTO 
				ems_vehicle_company(vehicle_company_name)
				VALUES ('$name')";
		$result=dbQuery($sql);
		//$company_id=dbInsertId();
       //insertVehicleModel("others",$company_id);
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

function deleteVehicleCompany($id){
	
	try
	{
		if(!checkifVehicleCompanyInUse($id))
		{
		$sql="DELETE FROM ems_vehicle_company
		      WHERE vehicle_company_id=$id";
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

function updateVehicleCompany($id,$name){
	
	try
	{
		$name=clean_data($name);
		$name = ucwords(strtolower($name));
		if(validateForNull($name) && checkForNumeric($id) && !checkDuplicateVehicleCompany($name,$id))
		{
		$sql="UPDATE ems_vehicle_company
			  SET vehicle_company_name='$name'
			  WHERE vehicle_company_id=$id";
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

function getVehicleCompanyById($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT vehicle_company_id, vehicle_company_name
			  FROM ems_vehicle_company
			  WHERE vehicle_company_id=$id";
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

function getVehicleCompanyNameById($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT vehicle_company_name
			  FROM ems_vehicle_company
			  WHERE vehicle_company_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0];
		else
		return false;
		}
	}
	catch(Exception $e)
	{
	}
	
}

function checkDuplicateVehicleCompany($name,$id=false)
{
	if(validateForNull($name))
	{
		$sql="SELECT vehicle_company_id
			  FROM ems_vehicle_company
			  WHERE vehicle_company_name='$name'";
		if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND vehicle_company_id!=$id";		  
		$result=dbQuery($sql);	
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
			return $resultArray[0][0]; //duplicate found
			} 
		else
		{
			return false;
			}
	}
}	

function checkifVehicleCompanyInUse($id)
{
	if(checkForNumeric($id))
	{
	$sql="SELECT vehicle_company_id
	      FROM ems_vehicle
		  Where vehicle_company_id=$id";
	$result=dbQuery($sql);	  
	if(dbNumRows($result)>0)
	return true;
	else 
	return false;
	}
}		
?>