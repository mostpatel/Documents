<?php 
require_once("cg.php");
require_once("common.php");
require_once("bd.php");


	
function listVehicleCC(){
	
	try
	{
		$sql="SELECT vehicle_cc_id, vehicle_cc, vehicle_type_id
			  FROM ems_vehicle_cc";
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



function insertVehicleCC($cc, $vehicle_type_id){
	try
	{
		$cc=clean_data($cc);
		$cc = ucwords(strtolower($cc));
		if(validateForNull($cc) && !checkDuplicateVehicleCC($cc))
		{
			$sql="INSERT INTO 
				ems_vehicle_cc (vehicle_cc, vehicle_type_id)
				VALUES ('$cc', $vehicle_type_id)";
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



function checkDuplicateVehicleCC($cc,$id=false)
{
	if(validateForNull($name))
	{
		$sql="SELECT vehicle_cc_id
			  FROM ems_vehicle_cc
			  WHERE vehicle_cc='$cc'";
		if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND vehicle_cc_id!=$id";		  
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


function deleteVehicleCC($id){
	
	try
	{
		if(!checkifVehicleCCInUse($id))
		{
		$sql="DELETE FROM ems_vehicle_cc
		      WHERE vehicle_cc_id=$id";
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

function checkifVehicleCCInUse($id)
{
	
	
	return true;
	
	
	
}		
	

function updateVehicleCC($id,$cc, $vehicle_type_id){
	
	try
	{
		$cc=clean_data($cc);
		$cc = ucwords(strtolower($cc));
		if(validateForNull($cc) && checkForNumeric($id) && !checkDuplicateVehicleCC($name,$id))
		{
		$sql="UPDATE ems_vehicle_cc
			  SET vehicle_cc ='$cc', vehicle_type_id=$vehicle_type_id
			  WHERE vehicle_cc_id=$id";
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


function getVehicleCCById($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT vehicle_cc_id, vehicle_cc, vehicle_type_id
			  FROM ems_vehicle_cc
			  WHERE vehicle_cc_id=$id";
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


function getAllVehicleCCForAVehicleTypeId($id)
{
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT vehicle_cc_id, vehicle_cc, vehicle_type_id
			  FROM ems_vehicle_cc
			  WHERE vehicle_type_id=$id";
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

?>