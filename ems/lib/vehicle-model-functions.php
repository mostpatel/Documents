<?php 
require_once("cg.php");
require_once("common.php");
require_once("bd.php");
		
function listVehicleModels()
{
	
	try
	{
		$sql="SELECT vehicle_model_id, vehicle_model_name, vehicle_company_id, vehicle_type_id, vehicle_cc_id 
			  FROM ems_vehicle_model
			  ORDER BY vehicle_model_name";
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


function insertVehicleModel($name,$vehicle_company_id, $vehicle_type_id, $vehicle_cc_id){
	try
	{
		$name=clean_data($name);
		$name = ucwords(strtolower($name));
		$duplicate=checkDuplicateVehicleModel($name,$vehicle_company_id);
		if(validateForNull($name) && checkForNumeric($vehicle_company_id, $vehicle_type_id, $vehicle_cc_id) && !$duplicate)
		{
			$sql="INSERT INTO 
				ems_vehicle_model(vehicle_model_name, vehicle_company_id, vehicle_type_id, vehicle_cc_id)
				VALUES ('$name',$vehicle_company_id, $vehicle_type_id, $vehicle_cc_id)";
		$result=dbQuery($sql);
		return dbInsertId();
		}
		else if($duplicate!==false)
		{
			return $duplicate;
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

function deleteVehicleModel($id){
	
	try
	{
		if(!checkifVehicleModelInUse($id))
		{
		$sql="DELETE FROM ems_vehicle_model
		      WHERE vehicle_model_id=$id";
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

function updateVehicleModel($id,$name,$vehicle_company_id, $vehicle_type_id, $vehicle_cc_id){
	
	try
	{
		
		$name=clean_data($name);
		$name = ucwords(strtolower($name));
		
		
		
		if(validateForNull($name) && checkForNumeric($id, $vehicle_company_id, $vehicle_type_id, $vehicle_cc_id) && 
		!checkDuplicateVehicleModel($name,$vehicle_company_id,$id))
		{
			
		$sql="UPDATE ems_vehicle_model
			  SET vehicle_model_name='$name', vehicle_company_id=$vehicle_company_id, vehicle_type_id=$vehicle_type_id,                vehicle_cc_id=$vehicle_cc_id 
			  WHERE vehicle_model_id=$id";
	    
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

function getVehicleModelById($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT vehicle_model_id, vehicle_model_name, vehicle_company_id, vehicle_type_id, vehicle_cc_id
			  FROM ems_vehicle_model
			  WHERE  vehicle_model_id=$id";
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

function getModelNameById($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT  vehicle_model_name
			  FROM ems_vehicle_model
			  WHERE vehicle_model_id=$id";
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

function checkDuplicateVehicleModel($name,$vehicle_company_id,$id=false)
{
	
	if(validateForNull($name))
	{
		$sql="SELECT vehicle_model_id
			  FROM ems_vehicle_model
			  WHERE vehicle_model_name='$name'
			  AND vehicle_company_id=$vehicle_company_id";
		if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND vehicle_model_id!=$id";		  
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

function checkifVehicleModelInUse($id)
{
	if(checkForNumeric($id))
	{
	$sql="SELECT vehicle_model_id
	      FROM ems_vehicle
		  Where vehicle_model_id=$id";
	$result=dbQuery($sql);	  
	if(dbNumRows($result)>0)
	return true;
	else 
	return false;
	}
}	

function getModelsFromCompanyID($id)
{
	
	if(checkForNumeric($id))
		{
			
		$sql="SELECT vehicle_model_id, vehicle_model_name
			  FROM ems_vehicle_model
			  WHERE vehicle_company_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray;
		else
		return false;
		}
	}	
?>