<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("common.php");
require_once("bd.php");

function listRtoWork(){
	
	try
	{
		$sql="SELECT rto_work_id, rto_work_name
		  FROM fin_rto_work ORDER BY rto_work_name";
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

function insertRtoWork($rto_work_name){
	
	try
	{
		$rto_work_name=clean_data($rto_work_name);
		$rto_work_name = ucwords(strtolower($rto_work_name));
		
		if(validateForNull($rto_work_name) && !checkForDuplicateRtoWork($rto_work_name))
			{
			$admin_id=$_SESSION['adminSession']['admin_id'];
			$sql="INSERT INTO fin_rto_work
					(rto_work_name)
					VALUES
					('$rto_work_name')";
					
			dbQuery($sql);
			$dealer_id=dbInsertId();
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

function insertRtoWorkIfNotDuplicate($rto_work_name){
	
	try
	{
		$rto_work_name=clean_data($rto_work_name);
		$rto_work_name = ucwords(strtolower($rto_work_name));
		
		$duplicate = checkForDuplicateRtoWork($rto_work_name);
		if(checkForNumeric($duplicate))
		return $duplicate;	
		if(validateForNull($rto_work_name))
			{
			$admin_id=$_SESSION['adminSession']['admin_id'];
			$sql="INSERT INTO fin_rto_work
					(rto_work_name)
					VALUES
					('$rto_work_name')";
					
			dbQuery($sql);
			$dealer_id=dbInsertId();
			return $dealer_id;
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



function checkIfRtoWorkIsInUse($rto_work_id)
{
	
	$sql="SELECT rto_work_id FROM fin_rto_work_rate
 WHERE rto_work_id=$rto_work_id";
	$result=dbQuery($sql);
	
	if(dbNumRows($result)>0)
	{	
	return true;
	}
	else
	return false;
	
	}	

function deleteRtoWork($id){
	
	try
	{
		if(checkForNumeric($id) && !checkIfRtoWorkIsInUse($id))
		{
		$sql="DELETE FROM fin_rto_work WHERE rto_work_id=$id";
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

function updateRtoWork($id,$rto_work_name){
	
	try
	{
		$rto_work_name=clean_data($rto_work_name);
		$rto_work_name = ucwords(strtolower($rto_work_name));
		
		if(validateForNull($rto_work_name)  && checkForNumeric($id))
			{
			
			$admin_id=$_SESSION['adminSession']['admin_id'];
			$sql="UPDATE fin_rto_work
					SET rto_work_name = '$rto_work_name'
					WHERE rto_work_id=$id";
			
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

function getRtoWorkById($id){
	
	try
	{
		$sql="SELECT rto_work_id, rto_work_name
		  FROM fin_rto_work
		  WHERE rto_work_id=$id";
		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0]; 
		else
		return false;
	}
	catch(Exception $e)
	{
	}
	
}

function getRtoWorkNameFromRtoWorkId($id)
{
try
	{
		$sql="SELECT  rto_work_name
		  FROM fin_rto_work
		  WHERE rto_work_id=$id";
		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0]; 
		else
		return false;
	}
	catch(Exception $e)
	{
	}	
}

function checkForDuplicateRtoWork($rto_work_name)
{
	if(validateForNull($rto_work_name))
	{
	$sql="SELECT  rto_work_id
		  FROM fin_rto_work
		  WHERE rto_work_name='$rto_work_name'";
		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0]; 
		else
		return false;
	}
	return true;
}


function insertRtoWorkRate($rto_agent_id,$rto_work_id,$model_id,$rate)
{
	if(checkForNumeric($rto_work_id,$rto_agent_id,$model_id,$rate))
	{
		$sql="INSERT INTO fin_rto_work_rate (rto_work_id,rto_agent_id,model_id,rate) VALUES ($rto_work_id,$rto_agent_id,$model_id,$rate)";
		dbQuery($sql);
		$rto_work_rate_id = dbInsertId();
		return $rto_work_rate_id;
	}
}

function deleteRtoWorkRate($rto_agent_id,$rto_work_id,$model_id)
{
	if(checkForNumeric($rto_work_id,$rto_agent_id,$model_id))
	{
		$sql="DELETE FROM fin_rto_work_rate WHERE rto_work_id = $rto_work_id AND rto_agent_id = $rto_agent_id AND model_id = $model_id";
		dbQuery($sql);
		return "success";
	}
}

function insertRtoWorkRateForModelArray($rto_agent_id,$rto_work_id,$model_id_array,$rate)
{
	foreach($model_id_array as $model_id)
	insertRtoWorkRate($rto_agent_id,$rto_work_id,$model_id,$rate);

	return "success";
}

function updateRtoAgentWorkRate($rto_agent_id,$rto_work_id,$model_id,$rate)
{
	if(checkForNumeric($rto_agent_id,$rto_work_id,$model_id,$rate))
	{
		$sql="UPDATE fin_rto_work_rate SET rate = $rate WHERE fin_rto_work_rate.rto_agent_id=$rto_agent_id AND fin_rto_work_rate.rto_work_id = $rto_work_id AND fin_rto_work_rate.model_id = $model_id";
		dbQuery($sql);
		return "success";
	}
}

function listRtoWorkRateAgentWise()
{
	$sql="SELECT * FROM fin_rto_work_rate, fin_rto_work, fin_rto_agent, fin_vehicle_model WHERE fin_rto_work_rate.rto_work_id = fin_rto_work.rto_work_id AND fin_rto_work_rate.rto_agent_id = fin_rto_agent.rto_agent_id AND fin_rto_work_rate.model_id = fin_vehicle_model.model_id ORDER BY fin_rto_work_rate.rto_agent_id";
	$result = dbQuery($sql);
	$resultArray = dbResultToArray($result);
	return $resultArray;
}

function getRtoAgentWorkRate($rto_agent_id,$rto_work_id,$model_id)
{
	if(checkForNumeric($rto_agent_id,$rto_work_id,$model_id))
	{
	$sql="SELECT * FROM fin_rto_work_rate, fin_rto_work, fin_rto_agent, fin_vehicle_model WHERE fin_rto_work_rate.rto_work_id = fin_rto_work.rto_work_id AND fin_rto_work_rate.rto_agent_id = fin_rto_agent.rto_agent_id AND fin_rto_work_rate.model_id = fin_vehicle_model.model_id AND fin_rto_work_rate.rto_agent_id=$rto_agent_id AND fin_rto_work_rate.rto_work_id = $rto_work_id AND fin_rto_work_rate.model_id = $model_id";
	$result = dbQuery($sql);
	$resultArray = dbResultToArray($result);
	return $resultArray[0];
	}
}

function  getRtoAgentWork($rto_agent_id,$model_id)
{
	if(checkForNumeric($rto_agent_id,$model_id))
	{
	$sql="SELECT * FROM fin_rto_work_rate, fin_rto_work, fin_rto_agent, fin_vehicle_model WHERE fin_rto_work_rate.rto_work_id = fin_rto_work.rto_work_id AND fin_rto_work_rate.rto_agent_id = fin_rto_agent.rto_agent_id AND fin_rto_work_rate.model_id = fin_vehicle_model.model_id AND fin_rto_work_rate.rto_agent_id=$rto_agent_id  AND fin_rto_work_rate.model_id = $model_id";
	$result = dbQuery($sql);
	$resultArray = dbResultToArray($result);
	return $resultArray;	
	}
}

function insertRtoWorkArrayForVehicle($vehicle_id,$rto_work_array)
{
	if(checkForNumeric($vehicle_id))
	{
		foreach($rto_work_array as $rto_work_id)
		insertRtoWorkForVehicle($vehicle_id,$rto_work_id);
	}
}

function insertRtoWorkForVehicle($vehicle_id,$rto_work_id)
{
	if(checkForNumeric($vehicle_id,$rto_work_id) && !checkForDuplicateRtoWorkForVehicle($vehicle_id,$rto_work_id))
	{
		$sql="INSERT INTO fin_vehicle_rto_work(vehicle_id,rto_work_id) VALUES ($vehicle_id,$rto_work_id)";
		$result = dbQuery($sql);
	}
	
}

function checkForDuplicateRtoWorkForVehicle($vehicle_id,$rto_work_id)
{
 	if(checkForNumeric($vehicle_id,$rto_work_id))
	{
		
		$sql="SELECT * FROM fin_vehicle_rto_work WHERE vehicle_id = $vehicle_id AND rto_work_id = $rto_work_id";
		$result = dbQuery($sql);
		if(dbNumRows($result)>0)
		return true;
		else
		return false;
	}
	return false;
}
function deleteRtoWorkForVehicle($vehicle_id)
{
	if(checkForNumeric($vehicle_id))
	{
		$sql="DELETE FROM fin_vehicle_rto_work WHERE vehicle_id = $vehicle_id";
		dbQuery($sql);
		
		return "success";
	}
	return "error";
	
}

function getRtoWorkForVehicleId($vehicle_id)
{
	if(checkForNumeric($vehicle_id))
	{
		$sql="SELECT * FROM fin_vehicle,fin_vehicle_rto_work,fin_vehicle_docs,fin_rto_work, fin_rto_work_rate WHERE  fin_vehicle.vehicle_id = fin_vehicle_docs.vehicle_id AND fin_vehicle_docs.vehicle_id = $vehicle_id AND fin_vehicle_docs.vehicle_id = fin_vehicle_rto_work.vehicle_id AND fin_vehicle_rto_work.rto_work_id = fin_rto_work.rto_work_id AND fin_rto_work_rate.rto_work_id = fin_vehicle_rto_work.rto_work_id AND fin_rto_work_rate.rto_agent_id = fin_vehicle_docs.rto_agent_id AND fin_rto_work_rate.model_id = fin_vehicle.model_id";
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		return $resultArray;
	}
	
}

function getRtoWorkIdArrayForVehicleId($vehicle_id)
{
	if(checkForNumeric($vehicle_id))
	{
		$sql="SELECT * FROM fin_vehicle_rto_work,fin_vehicle,fin_rto_work, fin_rto_work_rate, fin_vehicle_docs WHERE fin_vehicle.vehicle_id = fin_vehicle_docs.vehicle_id AND fin_vehicle_docs.vehicle_id = $vehicle_id AND fin_vehicle.vehicle_id = fin_vehicle_rto_work.vehicle_id AND fin_vehicle_rto_work.rto_work_id = fin_rto_work.rto_work_id AND fin_rto_work_rate.rto_work_id = fin_vehicle_rto_work.rto_work_id AND fin_rto_work_rate.rto_agent_id = fin_vehicle_docs.rto_agent_id AND fin_rto_work_rate.model_id = fin_vehicle.model_id";
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		$returnArray=array();
		
		foreach($resultArray as $re)
		$returnArray[] = $re['rto_work_id'];
		return $returnArray;
	}
	
}
/*

function getRtoWorkForVehicleId($vehicle_id)
{
	if(checkForNumeric($vehicle_id))
	{
		$sql="SELECT * FROM fin_vehicle,fin_vehicle_rto_work,fin_rto_work, fin_rto_work_rate WHERE   fin_vehicle.vehicle_id = $vehicle_id AND fin_vehicle.vehicle_id = fin_vehicle_rto_work.vehicle_id AND fin_vehicle_rto_work.rto_work_id = fin_rto_work.rto_work_id AND fin_rto_work_rate.rto_work_id = fin_vehicle_rto_work.rto_work_id AND fin_rto_work_rate.rto_agent_id = fin_vehicle.rto_agent_id AND fin_rto_work_rate.model_id = fin_vehicle.model_id";
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		return $resultArray;
	}
	
}

function getRtoWorkIdArrayForVehicleId($vehicle_id)
{
	if(checkForNumeric($vehicle_id))
	{
		$sql="SELECT * FROM fin_vehicle_rto_work,fin_vehicle,fin_rto_work, fin_rto_work_rate WHERE  fin_vehicle.vehicle_id = $vehicle_id AND fin_vehicle.vehicle_id = fin_vehicle_rto_work.vehicle_id AND fin_vehicle_rto_work.rto_work_id = fin_rto_work.rto_work_id AND fin_rto_work_rate.rto_work_id = fin_vehicle_rto_work.rto_work_id AND fin_rto_work_rate.rto_agent_id = fin_vehicle.rto_agent_id AND fin_rto_work_rate.model_id = fin_vehicle.model_id";
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		$returnArray=array();
		
		foreach($resultArray as $re)
		$returnArray[] = $re['rto_work_id'];
		return $returnArray;
	}
	
}
*/
?>