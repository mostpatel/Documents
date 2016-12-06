<?php 
require_once("cg.php");
require_once("common.php");
require_once("bd.php");


	
function listInsurancePeriod(){
	
	try
	{
		$sql="SELECT period_id, period, min_range, max_range, vehicle_type_id
			  FROM ems_insurance_period";
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



function insertInsurancePeriod($period, $min, $max, $vehicle_type_id)
{
	try
	{
		$period=clean_data($period);
		$min=clean_data($min);
		$max=clean_data($max);
		
		$period=ucwords($period);
		if(validateForNull($period, $min, $max) && !checkDuplicateInsurancePeriod($period))
		{
			$sql="INSERT INTO 
				ems_insurance_period (period, min_range, max_range, vehicle_type_id)
				VALUES ('$period', $min, $max, $vehicle_type_id)";
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



function checkDuplicateInsurancePeriod($period, $id=false)
{
	if(validateForNull($period))
	{
		$sql="SELECT period_id
			  FROM ems_insurance_period
			  WHERE period='$period'";
		if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND period_id!=$id";		  
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


function deleteInsurancePeriod($id){
	
	try
	{
		if(!checkifInsurancePeriodInUse($id))
		{
		$sql="DELETE FROM ems_insurance_period
		      WHERE period_id=$id";
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

function checkifInsurancePeriodInUse($id)
{
	
	
	return true;
	
	
	
}		
	

function updateInsurancePeriod($id,$period, $min, $max, $vehicle_type_id)
{
	
	try
	{
		$period=clean_data($period);
		$min=clean_data($min);
		$max=clean_data($max);
		
		$period = ucwords(strtolower($period));
		
		if(validateForNull($period, $min, $max) && checkForNumeric($id) && !checkDuplicateInsurancePeriod($period,$id))
		{
		$sql="UPDATE ems_insurance_period
			  SET period ='$period', min_range = '$min', max_range = '$max', vehicle_type_id=$vehicle_type_id
			  WHERE period_id=$id";
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


function getInsurancePeriodById($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT period_id, period, min_range, max_range, vehicle_type_id
			  FROM ems_insurance_period
			  WHERE period_id=$id";
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