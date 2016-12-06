<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("common.php");
require_once("bd.php");


	
function listVehicleTypes(){
	
	try
	{
		$sql="SELECT vehicle_type_id, vehicle_type
			  FROM ems_vehicle_type";
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



function insertVehicleType($type){
	try
	{
		$type=clean_data($type);
		$type = ucwords(strtolower($type));
		if(validateForNull($type) && !checkDuplicateVehicleType($type))
		{
			$sql="INSERT INTO 
				ems_vehicle_type (vehicle_type)
				VALUES ('$type')";
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



function checkDuplicateVehicleType($type,$id=false)
{
	if(validateForNull($name))
	{
		$sql="SELECT vehicle_type_id
			  FROM ems_vehicle_type
			  WHERE vehicle_type='$type'";
		if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND vehicle_type_id!=$id";		  
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


function deleteVehicleType($id){
	
	try
	{
		if(!checkifVehicleTypeInUse($id))
		{
		$sql="DELETE FROM ems_vehicle_type
		      WHERE vehicle_type_id=$id";
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

function checkifVehicleTypeInUse($id)
{
	
	if(checkForNumeric($id))
	{
	$sql="SELECT vehicle_type_id
	      FROM ems_vehicle_type
		  Where vehicle_type_id=$id";
	$result=dbQuery($sql);	  
	if(dbNumRows($result)>0)
	return true;
	else 
	return false;
	}
	
}		
	

function updateVehicleType($id,$type){
	
	try
	{
		$type=clean_data($type);
		$type = ucwords(strtolower($type));
		if(validateForNull($type) && checkForNumeric($id) && !checkDuplicateVehicleType($type,$id))
		{
		$sql="UPDATE ems_vehicle_type
			  SET vehicle_type='$type'
			  WHERE vehicle_type_id=$id";
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


function getVehicleTypeById($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT vehicle_type_id, vehicle_type
			  FROM ems_vehicle_type
			  WHERE vehicle_type_id=$id";
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


?>