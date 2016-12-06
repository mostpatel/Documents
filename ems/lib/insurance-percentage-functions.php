<?php 
require_once("cg.php");
require_once("common.php");
require_once("bd.php");
		
function listInsurancePercentage()
{
	
	try
	{
		$sql="SELECT vehicle_type_id, vehicle_cc_id, period_id, insure_com_id, percentage, liablity_premium, compulsory_pa, pa_paid_driver
			  FROM ems_insurance_percentage";
			
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


function insertInsurancePercentage($vehicle_type_id, $vehicle_cc_id, $period_id, $insure_com_id, $percentage, $liablity_premium, $compulsory_pa, $pa_paid_driver)
{
	try
	{
		$percentage=clean_data($percentage);
		$liablity_premium=clean_data($liablity_premium);
		$compulsory_pa=clean_data($compulsory_pa);
		$pa_paid_driver=clean_data($pa_paid_driver);
		
		$duplicate=checkDuplicateInsurancePercentage();
		
		if(validateForNull($percentage. $liablity_premium, $compulsory_pa, $pa_paid_driver) && checkForNumeric($vehicle_type_id, $vehicle_cc_id, $period_id, $insure_com_id, $percentage) && !$duplicate)
		{
			$sql="INSERT INTO 
				ems_insurance_percentage (vehicle_type_id, vehicle_cc_id, period_id, insure_com_id, percentage, liablity_premium, compulsory_pa, pa_paid_driver)
				VALUES ($vehicle_type_id, $vehicle_cc_id, $period_id, $insure_com_id, $percentage, $liablity_premium, $compulsory_pa, $pa_paid_driver)";
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

function deleteInsurancePercentage($id){
	
	try
	{
		if(!checkifInsurancePercentageInUse($id))
		{
		$sql="DELETE FROM ems_insurance_percentage
		      WHERE per_id=$id";
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

function updateInsurancePercentage()
{
	
	
	
}	

function getInsurancePercentageById($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT vehicle_type_id, vehicle_cc_id, period_id, insure_com_id, percentage, liablity_premium, compulsory_pa,       pa_paid_driver
			  FROM ems_insurance_percentage
			  WHERE  per_id=$id";
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


function getInsurancePercentageByRelatedInfo($insurance_company_id, $vehicle_cc_id, $vehicle_type_id, $period)
{
	
	try
	{
		if(checkForNumeric($insurance_company_id,$vehicle_cc_id,$vehicle_type_id,$period))
		{
		$sql="SELECT  percentage, liablity_premium, compulsory_pa, pa_paid_driver
			  FROM  ems_insurance_period, ems_insurance_percentage 
			  WHERE   ems_insurance_period.vehicle_type_id = $vehicle_type_id AND vehicle_cc_id =$vehicle_cc_id  AND ems_insurance_period.vehicle_type_id = ems_insurance_percentage.vehicle_type_id AND ems_insurance_period.period_id = ems_insurance_percentage.period_id AND min_range <= $period  AND   max_range > $period";

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



function checkDuplicateInsurancePercentage()
{
	return false;
}	

function checkifInsurancePercentageInUse($id)
{
	
	return false;
	
}	



function getDateDifference($reg_date)
{
	
	    $sql="select DATEDIFF('$reg_date', NOW())";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		return floor(-$resultArray[0][0]/365);
		
}


?>