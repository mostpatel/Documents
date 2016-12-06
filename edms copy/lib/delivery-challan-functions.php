<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("image-functions.php");
require_once("vehicle-functions.php");
require_once("vehicle-insurance-functions.php");
require_once("vehicle-invoice-functions.php");
require_once("vehicle-sale-cert-functions.php");
require_once("our-company-function.php");
require_once("common.php");
require_once("bd.php");
		
function listDeliveryChallansForCustomer($id){
	try
	{
		
		if(checkForNumeric($id))
		{
		$sql="SELECT delivery_challan_id,delivery_date, challan_no, salesman_ledger_id, financer_ledger_id, toolkit_inc, service_book_inc, battery_inc,spare_wheel_inc, water_bottle_inc, vehicle_id, customer_id, created_by, last_updated_by, date_added, date_modified FROM edms_vehicle_delivery_challan WHERE customer_id = $id";
		$result = dbQuery($sql);
		$resultArray =dbResultToArray($result);
		
		if(dbNumRows($result)>0)
		{
			return 	$resultArray;
		}
		else return false;
		}
	}
	catch(Exception $e)
	{
	}
	
}	

function insertDeliveryChallanForVehicle($customer_id,$delivery_date,$challan_no,$vehicle_engine_no,$vehicle_chasis_no,$insurance_company_id,$insurance_issue_date,$salesman,$financer,$tool_kit_inc,$service_book_inc,$battery_inc,$spare_wheel_inc,$water_bottle_inc,$battery_make_id=NULL,$battery_no="NA",$key_no="NA",$service_book="NA",$battery_service_no="NA",$service_no="NA"){
	try
	{
		
	    $vehicle_chasis_no = clean_data($vehicle_chasis_no);
		$vehicle_engine_no = clean_data($vehicle_engine_no);
		$vehicle_chasis_no = substr($vehicle_chasis_no,2);
		$vehicle_engine_no = substr($vehicle_engine_no,2);
		$insurance_company_id = clean_data($insurance_company_id);
		$salesman = clean_data($salesman);
		$financer = clean_data($financer);
		$battery_make_id=clean_data($battery_make_id);
		$battery_no=clean_data($battery_no);
		$key_no=clean_data($key_no);
		$service_book=clean_data($service_book);
		$customer_id=clean_data($customer_id);
	    $battery_service_no=clean_data($battery_service_no);
		$service_no=clean_data($service_no);
		
		$vehicle_id = getInStockVehicleIDFromChasisNo($vehicle_chasis_no);
		$vehicle_id = getInStockVehicleIDFromEngineNo($vehicle_engine_no);

	
		
		if(!validateForNull($battery_no))
		$battery_no="NA";
		
		if(!validateForNull($financer) || $financer==0)
		$financer="NULL";
		
		if(!validateForNull($key_no))
		$key_no="NA";
		
		if(!validateForNull($service_book))
		$service_book="NA";
		
		if(!validateForNull($battery_make_id) || $battery_make_id==-1)
		$battery_make_id="NULL";
		
		if(!validateForNull($battery_service_no))
		$battery_service_no="NA";
		
		if(!validateForNull($service_no))
		$service_no="NA";
		
		if(validateForDate($insurance_issue_date))
		$insurance_expry_date =  getInsuranceExpiryDateFromIssueDate($insurance_issue_date);
		else
		$insurance_expry_date = NULL;
		
		if(checkForNumeric($customer_id,$insurance_company_id,$salesman) && validateForNull($vehicle_engine_no,$vehicle_chasis_no,$battery_no,$key_no,$service_book,$battery_make_id,$battery_service_no,$service_no)  && !checkForDuplicateDeliveryChallan($vehicle_id))
		{
			
			if(!checkForNumeric($vehicle_reg_no[0],$vehicle_reg_no[1]) && ($vehicle_reg_no[2]=='0'))
			{
				$vehicle_reg_no=substr($vehicle_reg_no,0,2).substr($vehicle_reg_no,3);
			}
			
			$vehicle_reg_no=strtoupper($vehicle_reg_no);	
			$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
			$oc_id=$_SESSION['edmsAdminSession']['oc_id'];
			
			$oc_prefix = getPrefixFromOCId($oc_id);
			$challan_no = $oc_prefix.$challan_no;
			
			$delivery_date = str_replace('/', '-', $delivery_date);
			$delivery_date=date('Y-m-d',strtotime($delivery_date));
			
			$insurance_issue_date = str_replace('/', '-', $insurance_issue_date);
			$insurance_issue_date=date('Y-m-d',strtotime($insurance_issue_date));
			
			$sql="INSERT INTO edms_vehicle_delivery_challan
			      (delivery_date, challan_no, salesman_ledger_id, financer_ledger_id, toolkit_inc, service_book_inc, battery_inc,spare_wheel_inc, water_bottle_inc, vehicle_id, customer_id, created_by, last_updated_by, date_added, date_modified)
				  VALUES
				  ('$delivery_date','$challan_no',$salesman,$financer,$tool_kit_inc,$service_book_inc,$battery_inc,$spare_wheel_inc,$water_bottle_inc,$vehicle_id,$customer_id,$admin_id,$admin_id,NOW(),NOW())";
			
			dbQuery($sql);
			$delivery_challan_id=dbInsertId();
			
			if(checkForNumeric($insurance_company_id) && $insurance_company_id>0 && validateForNull($insurance_expry_date))
			insertInsurance($insurance_issue_date,$insurance_expry_date,0,0,$insurance_company_id,$vehicle_id,$customer_id,array(),array(),$delivery_challan_id);
			
			$sql="UPDATE edms_vehicle SET customer_id = $customer_id, is_purchased = 2, battery_make_id = $battery_make_id , battery_no ='$battery_no', key_no ='$key_no', service_book = '$service_book' , battery_service_book_no = '$battery_service_no' , service_no = '$service_no', ledger_id = $financer, last_updated_by = $admin_id , date_modified = NOW() WHERE vehicle_id = $vehicle_id";
		
dbQuery($sql);
			
			incrementChallanNoForOCID($oc_id);
			return $delivery_challan_id;
		}
		else
		{return "error";}
	}
	catch(Exception $e)
	{
	}
	
}	

function deleteDeliveryChallanById($id){
	
	try
	{
		
		if(checkForNumeric($id))
		{
		$invoice = getVehicleInvoiceByDeliveryChallanId($id);
		$form21=getSaleCertByDeliveryChallanId($id);
		
		if(!$invoice && !$form21)
		{	
		
			$vehicle_id = getVehicleIdFromDeliveryChallan($id);
			$sql="DELETE FROM edms_vehicle_delivery_challan WHERE delivery_challan_id = $id";
			dbQuery($sql);
			
			$sql="UPDATE edms_vehicle SET is_purchased = 1 , customer_id=NULL WHERE vehicle_id = $vehicle_id";
			dbQuery($sql);
			
			deleteInsuranceForDeliveryChallan($vehicle_id);
			return "success";
		}
		return "error";
		}
		
	}
	catch(Exception $e)
	{
	}
	
}	

function getVehicleIdFromDeliveryChallan($id)
{
	if(checkForNumeric($id))
	{
		$sql="SELECT vehicle_id FROM edms_vehicle_delivery_challan WHERE delivery_challan_id = $id";
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0];
		else
		return false;
	}
	
}

function getCustomerIdFromDeliveryChallan($id)
{
	if(checkForNumeric($id))
	{
		$sql="SELECT customer_id FROM edms_vehicle_delivery_challan WHERE delivery_challan_id = $id";
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0];
		else
		return false;
	}
	
}

function updateDeliveryChallan($delivery_challan_id,$vehicle_id,$insurance_id,$delivery_date,$challan_no,$vehicle_engine_no,$vehicle_chasis_no,$insurance_company_id,$insurance_issue_date,$salesman,$financer,$tool_kit_inc,$service_book_inc,$battery_inc,$spare_wheel_inc,$water_bottle_inc,$battery_make_id=NULL,$battery_no="NA",$key_no="NA",$service_book="NA",$battery_service_no="NA",$service_no="NA"){
	try
	{
		
	    $vehicle_chasis_no = clean_data($vehicle_chasis_no);
		$vehicle_engine_no = clean_data($vehicle_engine_no);
		$insurance_company_id = clean_data($insurance_company_id);
		$salesman = clean_data($salesman);
		$financer = clean_data($financer);
		$battery_make_id=clean_data($battery_make_id);
		$battery_no=clean_data($battery_no);
		$key_no=clean_data($key_no);
		$service_book=clean_data($service_book);
		 $battery_service_no=clean_data($battery_service_no);
		$service_no=clean_data($service_no);
		
		
		if(!validateForNull($battery_no))
		$battery_no="NA";
		
		if(!validateForNull($financer) || $financer==0)
		$financer="NULL";
		
		if(!validateForNull($key_no))
		$key_no="NA";
		
		if(!validateForNull($service_book))
		$service_book="NA";
		
		if(!validateForNull($battery_make_id) || $battery_make_id==-1)
		$battery_make_id="NULL";
		
		if(!validateForNull($battery_service_no))
		$battery_service_no="NA";
		
		if(!validateForNull($service_no))
		$service_no="NA";
		
		if(validateForDate($insurance_issue_date))
		$insurance_expry_date =  getInsuranceExpiryDateFromIssueDate($insurance_issue_date);
		else
		$insurance_expry_date = NULL;
		
		if(checkForNumeric($insurance_company_id,$salesman,$delivery_challan_id) && validateForNull($vehicle_engine_no,$vehicle_chasis_no,$battery_no,$key_no,$service_book,$battery_make_id,$battery_service_no,$service_no)  && !checkForDuplicateDeliveryChallan($vehicle_id,$vehicle_id))
		{
			
			if(!checkForNumeric($vehicle_reg_no[0],$vehicle_reg_no[1]) && ($vehicle_reg_no[2]=='0'))
			{
				$vehicle_reg_no=substr($vehicle_reg_no,0,2).substr($vehicle_reg_no,3);
			}
			
			$vehicle_reg_no=strtoupper($vehicle_reg_no);	
			$customer_id = getCustomerIdFromDeliveryChallan($delivery_challan_id);
			$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
			$oc_id=$_SESSION['edmsAdminSession']['oc_id'];
			
			$oc_prefix = getPrefixFromOCId($oc_id);
			$challan_no = $oc_prefix.$challan_no;
			
			$delivery_date = str_replace('/', '-', $delivery_date);
			$delivery_date=date('Y-m-d',strtotime($delivery_date));
			
			$insurance_issue_date = str_replace('/', '-', $insurance_issue_date);
			$insurance_issue_date=date('Y-m-d',strtotime($insurance_issue_date));
			
			$sql="UPDATE edms_vehicle_delivery_challan
			      SET delivery_date = '$delivery_date', challan_no = '$challan_no', salesman_ledger_id = $salesman, financer_ledger_id = $financer, toolkit_inc = $tool_kit_inc, service_book_inc = $service_book_inc, battery_inc = $battery_inc,spare_wheel_inc = $spare_wheel_inc, water_bottle_inc = $water_bottle_inc, last_updated_by = $admin_id, date_modified = NOW()
				 WHERE delivery_challan_id=$delivery_challan_id";
			dbQuery($sql);
			
			if(is_numeric($insurance_company_id) && $insurance_company_id>0 && validateForNull($insurance_expry_date))
			{
			if(checkForNumeric($insurance_id))	
			updateInsurance($insurance_id,$vehicle_id,$insurance_issue_date,$insurance_expry_date,0,0,$insurance_company_id,array(),array());
			else
			insertInsurance($insurance_issue_date,$insurance_expry_date,0,0,$insurance_company_id,$vehicle_id,$customer_id,array(),array(),$delivery_challan_id);
			}
			else
			deleteInsuranceForDeliveryChallan($delivery_challan_id);
			
			$sql="UPDATE edms_vehicle SET  battery_make_id = $battery_make_id , battery_no ='$battery_no', key_no ='$key_no', service_book = '$service_book', battery_service_book_no = '$battery_service_no' , service_no = '$service_no', ledger_id = $financer,  last_updated_by = $admin_id , date_modified = NOW() WHERE vehicle_id = $vehicle_id";
			dbQuery($sql);
			return "success";
		}
		else
		{return "error";}
	}
	catch(Exception $e)
	{
	}
	
}	

function getDeliveryChallanByVehicleId($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT delivery_date, challan_no, salesman_ledger_id, financer_ledger_id, toolkit_inc, service_book_inc, battery_inc,spare_wheel_inc, water_bottle_inc, vehicle_id, customer_id, created_by, last_updated_by, date_added, date_modified FROM edms_vehicle_delivery_challan WHERE vehicle_id = $id";
		$result = dbQuery($sql);
		$resultArray =dbResultToArray($result);
		
		if(dbNumRows($result)>0)
		{
			return 	$resultArray[0];
		}
		else return false;
		}
		
	}
	catch(Exception $e)
	{
	}
	
}	

function getDeliveryChallanById($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT delivery_date, challan_no, salesman_ledger_id, financer_ledger_id, toolkit_inc, service_book_inc, battery_inc,spare_wheel_inc, water_bottle_inc, vehicle_id, customer_id, created_by, last_updated_by, date_added, date_modified FROM edms_vehicle_delivery_challan WHERE delivery_challan_id = $id";
		$result = dbQuery($sql);
		$resultArray =dbResultToArray($result);
		
		if(dbNumRows($result)>0)
		{
			return 	$resultArray[0];
		}
		else return false;
		}
		
	}
	catch(Exception $e)
	{
	}
	
}	

function checkForDuplicateDeliveryChallan($vehicle_id,$id=false)
{
	$sql="SELECT delivery_challan_id FROM edms_vehicle_delivery_challan
	      WHERE vehicle_id=$vehicle_id";
	if($id)
	$sql=$sql." AND vehicle_id != $id";			  
		$result=dbQuery($sql);	
		if(dbNumRows($result)>0)
		{
			return true; //duplicate found
			} 
		else
		{
			return false;
			}	 	  
	}

?>