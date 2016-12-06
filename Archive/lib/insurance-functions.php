<?php 
require_once("cg.php");
require_once("vehicle-functions.php");
require_once("customer-functions.php");
require_once("insurance-company-functions.php");
require_once("common.php");
require_once("bd.php");




function insertVehicleInsurance($insurance_start_date, $insure_com_id, $third_party_rate, $idv, $discount_percentage, $cng_idv, $electric_idv, $ncb_percentage, $cng_ncb_percentage, $ele_ncb_percentage, $lPremium, $cPA, $pa_driver, $final_amount, $vehicle_id, $customer_id)
{
	try
	{
		$insurance_end_date = getInsuranceExpiryDateFromIssueDate($insurance_start_date);
		
		$insurance_start_date=str_replace('/','-',$insurance_start_date);
	    $insurance_start_date=date('Y-m-d',strtotime($insurance_start_date));
		
		$insurance_end_date=str_replace('/','-',$insurance_end_date);
	    $insurance_end_date=date('Y-m-d',strtotime($insurance_end_date));
		
		$insurance_start_date=clean_data($insurance_start_date);
		$insure_com_id=clean_data($insure_com_id);
		$third_party_rate=clean_data($third_party_rate);
		$idv=clean_data($idv);
		$discount_percentage=clean_data($discount_percentage);
		$cng_idv=clean_data($cng_idv);
		$electric_idv=clean_data($electric_idv);
		$ncb_percentage=clean_data($ncb_percentage);
		$cng_ncb_percentage=clean_data($cng_ncb_percentage);
		$ele_ncb_percentage=clean_data($ele_ncb_percentage);
		$lPremium=clean_data($lPremium);
		$cPA=clean_data($cPA);
		$pa_driver=clean_data($pa_driver);
		$final_amount=clean_data($final_amount);
		$vehicle_id=clean_data($vehicle_id);
		$customer_id=clean_data($customer_id);
		
		
		
		
		if(validateForNull($insurance_start_date, $vehicle_id, $customer_id, $idv, $third_party_rate))
		{
			$sql="INSERT INTO 
				ems_vehicle_insurance (insurance_start_date, insurance_end_date, insure_com_id, third_party_rate, idv, discount_percentage, cng_idv, electric_idv, ncb_percentage, cng_ncb_percentage, ele_ncb_percentage, lPremium, cPA, pa_driver, final_amount, vehicle_id, customer_id)
				VALUES ('$insurance_start_date', '$insurance_end_date', $insure_com_id, $third_party_rate, $idv, $discount_percentage, $cng_idv, $electric_idv, $ncb_percentage, $cng_ncb_percentage, $ele_ncb_percentage, $lPremium, $cPA, $pa_driver, $final_amount, $vehicle_id, $customer_id)";
				
			
				
		
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

function getInsuranceExpiryDateFromIssueDate($date)
{
	if(isset($date) && validateForNull($date))
			{
		    $date = str_replace('/', '-', $date);
			$date=date('Y-m-d',strtotime($date));
			}
	return date('Y-m-d', strtotime($date. ' + 364 days'));	
}






function deleteVehicleInsurance($id){
	
	try
	{
		
		$sql="DELETE FROM ems_superCategory
		      WHERE super_cat_id=$id";
		dbQuery($sql);
		return "success";
		
	}
	catch(Exception $e)
	{
	}
	
}	

	



function getInsuranceById($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT insurance_id, insurance_start_date, insurance_end_date, insure_com_id, third_party_rate, idv, discount_percentage, cng_idv, electric_idv, ncb_percentage, cng_ncb_percentage, ele_ncb_percentage, lPremium, cPA, pa_driver, final_amount, vehicle_id, customer_id
			  FROM ems_vehicle_insurance
			  WHERE insurance_id=$id";
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

function getLatestInsuranceDetailsForVehicleID($vehicle_id)
{
	$sql="SELECT max(insurance_end_date)
	      FROM ems_vehicle_insurance
		  WHERE vehicle_id=$vehicle_id
		  GROUP BY vehicle_id";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);

	if(dbNumRows($result)>0)
	{
		 $exp_date=$resultArray[0][0];
		 $sql="SELECT insurance_id
	      FROM ems_vehicle_insurance
		  WHERE vehicle_id=$vehicle_id
		  AND insurance_end_date='$exp_date'";
	$result2=dbQuery($sql);
	$resultArray2=dbResultToArray($result2);
	if(dbNumRows($result2)>0)
	{
		return getInsuranceDetailsFromInsuranceId($resultArray2[0][0]);
		}
	}
	
	}	

function getInsuranceDetailsFromInsuranceId($id)
{
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT insurance_id, insurance_start_date, insurance_end_date, insure_com_id, third_party_rate, idv, discount_percentage, cng_idv, electric_idv, ncb_percentage, cng_ncb_percentage, ele_ncb_percentage, lPremium, cPA, pa_driver, final_amount, vehicle_id, customer_id
			  FROM ems_vehicle_insurance
			  WHERE insurance_id=$id";
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


function getInsuranceByVehicleId($id)
{
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT insurance_id, insurance_start_date, insurance_end_date, insure_com_id, third_party_rate, idv, discount_percentage, cng_idv, electric_idv, ncb_percentage, cng_ncb_percentage, ele_ncb_percentage, lPremium, cPA, pa_driver, final_amount, vehicle_id, customer_id
			  FROM ems_vehicle_insurance
			  WHERE vehicle_id=$id";
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

function getInsuranceByCustomerId($id)
{
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT insurance_id, insurance_start_date, insurance_end_date, insure_com_id, third_party_rate, idv, discount_percentage, cng_idv, electric_idv, ncb_percentage, cng_ncb_percentage, ele_ncb_percentage, lPremium, cPA, pa_driver, final_amount, vehicle_id, customer_id
			  FROM ems_vehicle_insurance
			  WHERE customer_id=$id";
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