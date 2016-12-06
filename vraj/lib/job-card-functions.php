<?php 
require_once("cg.php");
require_once("common.php");
require_once("sms-functions.php");
require_once("sms-record-functions.php");
require_once("customer-functions.php");
require_once("vehicle-functions.php");
require_once("job-card-description-functions.php");
require_once("job-card-work-done-functions.php");
require_once("job-card-remarks-functions.php");
require_once("service-type-functions.php");
require_once("inventory-sales-functions.php");
require_once("account-sales-functions.php");
require_once("account-receipt-functions.php");
require_once("account-ledger-functions.php");
require_once("our-company-function.php");
require_once("invoice-counter-functions.php");
require_once("bd.php");

function listUnfinalizedJobCards()
{
	
	$sql="SELECT job_card_id,job_card_no, job_card_datetime,service_type_id, free_service_no, date_of_sale,kms_covered, estimated_repair_cost, bay_in, bay_out, delivery_promise, actual_delivery, next_service_date, technician_id, vehicle_id, customer_id, created_by, last_updated_by, date_added, date_modified
			 FROM edms_job_card WHERE bay_out='1970-01-01 00:00:00'";
		
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
		for($i=0;$i<count($resultArray);$i++)
		{
			$job_card_total=getTotalAmountForJobCard($resultArray[$i]['job_card_id']);
			$resultArray[$i]['total_amount'] = $job_card_total;
		}
		}
		return $resultArray;
		
		return false;
	
}

function listJobCardsBetweenDate($from=null,$to=null,$vehicle_id_array=null,$customer_id_array=null)
{
	
		$oc_id=$_SESSION['edmsAdminSession']['oc_id'];
	if(isset($vehicle_id_array) && is_numeric($vehicle_id_array))
	$vehicle_id_string = $vehicle_id_array;
	else
	$vehicle_id_string = implode(",",$vehicle_id_array);
	
	if(isset($customer_id_array) && is_numeric($customer_id_array))
	$customer_id_string = $customer_id_array;
	else
	$customer_id_string = implode(",",$customer_id_array);
	
	if(isset($from) && validateForNull($from))
    {
	$from = str_replace('/', '-', $from);
	$from = date('Y-m-d',strtotime($from));
	}
	
	if(isset($to) && validateForNull($to))
	{
	$to = str_replace('/', '-', $to);
	$to=date('Y-m-d',strtotime($to));
	$to=$to." 23:59:59";
	}
	
	$sql="SELECT job_card_id,job_card_no, job_card_datetime,service_type_id, free_service_no, date_of_sale,kms_covered, estimated_repair_cost, bay_in, bay_out, delivery_promise, actual_delivery, next_service_date, technician_id, vehicle_id, edms_job_card.customer_id, edms_job_card.created_by, edms_job_card.last_updated_by, edms_job_card.date_added, edms_job_card.date_modified
			 FROM edms_job_card,edms_customer WHERE edms_job_card.customer_id = edms_customer.customer_id  AND our_company_id = $oc_id ";
		if(isset($from) && validateForNull($from))
	$sql=$sql." AND job_card_datetime >='$from' 
		  ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql." AND job_card_datetime<='$to' ";	
	if(isset($vehicle_id_string) && $vehicle_id_string!="")
	$sql=$sql." AND vehicle_id IN ($vehicle_id_string)";  
		if(isset($customer_id_string) && $customer_id_string!="")
	$sql=$sql." AND edms_job_card.customer_id IN ($customer_id_string)";  
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
		for($i=0;$i<count($resultArray);$i++)
		{
			$job_card_total=getTotalAmountForJobCard($resultArray[$i]['job_card_id']);
			$resultArray[$i]['total_amount'] = $job_card_total;
		}
		}
		return $resultArray;
		
		return false;
	
}

function listFinalizedJobCards()
{
	$today_date=getTodaysDate();
	$sql="SELECT job_card_id,job_card_no, job_card_datetime,service_type_id, free_service_no, date_of_sale,kms_covered, estimated_repair_cost, bay_in, bay_out, delivery_promise, actual_delivery, next_service_date, technician_id, vehicle_id, customer_id, created_by, last_updated_by, date_added, date_modified
			 FROM edms_job_card WHERE bay_out>='$today_date 00:00:00' AND bay_out <= '$today_date 23:59:59'";
		
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
		for($i=0;$i<count($resultArray);$i++)
		{
			$job_card_total=getTotalAmountForJobCard($resultArray[$i]['job_card_id']);
			$resultArray[$i]['total_amount'] = $job_card_total;
		}
		}
		return $resultArray;
		
		return false;
	
}


	
function MakeReceiptForJobCard($job_card_id,$trans_date,$to_id,$from_id,$amount,$remarks)
{
	if(checkForNumeric($job_card_id,$amount) && validateForNull($trans_date,$to_id,$from_id))
	{
		$result=insertReceipt($amount,$trans_date,$to_id,$from_id,$remarks,3,$job_card_id,"NA",0);
		if($result=="success")
		return true;
		else
		return false;
	}	
	return false;
	
}

function listJobCardsForCustomer($customer_id){
	
	try
	{
		if(checkForNumeric($customer_id))
		{
		$sql="SELECT job_card_id,job_card_no, job_card_datetime,service_type_id, free_service_no, date_of_sale,kms_covered, estimated_repair_cost, bay_in, bay_out, delivery_promise, actual_delivery, next_service_date, technician_id, vehicle_id, customer_id, created_by, last_updated_by, date_added, date_modified
			 FROM edms_job_card WHERE customer_id = $customer_id";
		
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
		for($i=0;$i<count($resultArray);$i++)
		{
			$job_card_total=getTotalAmountForJobCard($resultArray[$i]['job_card_id']);
			$resultArray[$i]['total_amount'] = $job_card_total;
		}
		}
		return $resultArray;
		}
		return false;
	}
	catch(Exception $e)
	{
	}
	
}	

function listJobCardsForVehicle($vehicle_id){
	
	try
	{
		if(checkForNumeric($vehicle_id))
		{
		$sql="SELECT job_card_id,job_card_no, job_card_datetime,service_type_id, free_service_no, date_of_sale,kms_covered, estimated_repair_cost, bay_in, bay_out, delivery_promise, actual_delivery, next_service_date, next_service_date, technician_id, vehicle_id, customer_id, created_by, last_updated_by, date_added, date_modified
			 FROM edms_job_card WHERE vehicle_id = $vehicle_id";
		
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
		for($i=0;$i<count($resultArray);$i++)
		{
			$job_card_total=getTotalAmountForJobCard($resultArray[$i]['job_card_id']);
			$resultArray[$i]['total_amount'] = $job_card_total;
		}
		}
		return $resultArray;
		}
		return false;
	}
	catch(Exception $e)
	{
	}
	
}	

function getNumberOfJobCards()
{
	$sql="SELECT count(job_desc_id)
		      FROM edms_job_description
			  ORDER BY job_desc";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		return $resultArray[0][0];	
	
}

function finalizeJobCard($job_card_id,$invoice_no,$bay_out,$actual_delivery,$next_service_date,$send_sms)
{
	
	if(checkForNumeric($job_card_id,$invoice_no,$send_sms) && validateForNull($bay_out,$actual_delivery))
	{
		
		$bay_out=clean_data($bay_out);
		$invoice_no=clean_data($invoice_no);
		$actual_delivery=clean_data($actual_delivery);
		$next_service_date = clean_data($next_service_date);
		if(!validateForNull($next_service_date))
		$next_service_date="1970-01-01";
		if(isset($bay_out) && validateForNull($bay_out))
			{
		    $bay_out = str_replace('/', '-', $bay_out);
			$bay_out=date('Y-m-d H:i:s',strtotime($bay_out));
			}		
			
					
		if(isset($actual_delivery) && validateForNull($actual_delivery))
			{
		    $actual_delivery = str_replace('/', '-', $actual_delivery);
			$actual_delivery=date('Y-m-d H:i:s',strtotime($actual_delivery));
			}		
			
		if(isset($next_service_date) && validateForNull($next_service_date))
			{
		    $next_service_date = str_replace('/', '-', $next_service_date);
			$next_service_date=date('Y-m-d H:i:s',strtotime($next_service_date));
			}				
		
		$sales_id = getSalesIdFromjobCardId($job_card_id);
		updateSalesToJobCardId($job_card_id,$sales_id,$invoice_no);
		$sql="UPDATE edms_job_card SET bay_out = '$bay_out' , actual_delivery = '$actual_delivery', next_service_date = '$next_service_date' WHERE job_card_id = $job_card_id";
		dbQuery($sql);
		
		if(defined('SEND_SMS') && SEND_SMS==1 && $send_sms==1)
		{
			sendJobCardFinalizeSms($job_card_id);
		}
		return $job_card_id;
		
	}
	return false;
}

function sendJobCardFinalizeSms($job_card_id)
{
	
	$returnn = false;
	if(checkForNumeric($job_card_id))
	{
	        $customer_id = getCustomerIdByJobCardId($job_card_id);
			
			$vehicle_id = getVehicleIdByJobCardId($job_card_id);
			$customer = getCustomerDetailsByCustomerId($customer_id);
			$vehicle = getVehicleById($vehicle_id);
			$bill_amount = getTotalAmountForJobCard($job_card_id);
			if($vehicle['vehicle_reg_no']=="NA")
			{
				$vehicle=VEHICLE_DEFAULT;
			}
			else
			$vehicle = $vehicle['vehicle_reg_no'];
			foreach($customer['contact_no'] as $contact_no)
			{
		    $result_array=sendFinalizeJobCardSMS($customer['customer_name'],$contact_no[0],$vehicle,round($bill_amount),1);
			
			
			if($result_array && is_array($result_array))
			{
			insertSMSRecord($result_array[1],1,$contact_no[0],$result_array[0],'1970-01-01',$job_card_id);
			$returnn=true;
			}
			}
	return $returnn;		
	}
	
}

function getFinalizeDetailsForJobCard($job_card_id)
{
	if(checkForNumeric($job_card_id))
	{
		$sql="SELECT jb_invoice_id, invoice_no FROM edms_jb_rel_sales WHERE invoice_no NOT LIKE '%NA' AND job_card_id = $job_card_id";	
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][1];
		else
		return false;
	}	
	return false;
}

function getTotalAmountForJobCard($job_card_id)
{
	if(checkForNumeric($job_card_id))
	{
	$sales_id=getSalesIdFromjobCardId($job_card_id);
	$total = 0;
		$non_stock_items = getNonStockItemForSaleId($sales_id);
		
		if($non_stock_items && is_array($non_stock_items) && count($non_stock_items)>0)
		{
			foreach($non_stock_items as $non_stock_item)
			{
				
			$net_amount = $non_stock_item['sales_item_details']['net_amount'];
			if(isset($non_stock_item['sales_item_details']['tax_amount']) && checkForNumeric($non_stock_item['sales_item_details']['tax_amount']) && $non_stock_item['sales_item_details']['tax_amount'])
			$net_amount = $net_amount + $non_stock_item['sales_item_details']['tax_amount'];
			
			$total = $total + $net_amount;
			}
		}
		$inventory_items = getInventoryItemForSaleId($sales_id);
		if($inventory_items && is_array($inventory_items) && count($inventory_items)>0)
		{
			foreach($inventory_items as $inventory_item)
			{
			$net_amount = $inventory_item['sales_item_details']['net_amount'];
			if(isset($inventory_item['sales_item_details']['tax_amount']) && checkForNumeric($inventory_item['sales_item_details']['tax_amount']) && $inventory_item['sales_item_details']['tax_amount'])
			$net_amount = $net_amount + $inventory_item['sales_item_details']['tax_amount'];
			$total = $total + $net_amount;
			}
		}
	return $total;
	}
	return 0;
}
	
function insertJobCard($job_card_no,$jb_date_time,$service_type_id,$free_service_no,$date_of_sale,$kms_covered,$estimate_cost,$bay_in,$delivery_promise,$technician_id,$vehicle_id,$customer_id,$job_description_array,$actual_work_done_array,$remarks_array,$service_check_array){
	
	try
	{
		
		
		$job_card_no=clean_data($job_card_no);
		$jb_date_time=clean_data($jb_date_time);
		$service_type_id=clean_data($service_type_id);
		$free_service_no=clean_data($free_service_no);
		$date_of_sale=clean_data($date_of_sale);
		$kms_covered=clean_data($kms_covered);
		$estimate_cost=clean_data($estimate_cost);
		$bay_in=clean_data($bay_in);
		$delivery_promise=clean_data($delivery_promise);
		$technician_id=clean_data($technician_id);
		$vehicle_id=clean_data($vehicle_id);
		$customer_id=clean_data($customer_id);
		
		if(!validateForNull($date_of_sale))
		$date_of_sale="01/01/1970";
		
		$actual_delivery="01/01/1970";	
		$bay_out="01/01/1970 00:00:00";
		
		if(!checkForNumeric($kms_covered))
		$kms_covered=0;
		
		if(!checkForNumeric($estimate_cost))
		$estimate_cost=0;
		
		if(isset($date_of_sale) && validateForNull($date_of_sale))
			{
		    $date_of_sale = str_replace('/', '-', $date_of_sale);
			$date_of_sale=date('Y-m-d',strtotime($date_of_sale));
			}	
			
		
		if(isset($jb_date_time) && validateForNull($jb_date_time))
			{
		    $jb_date_time = str_replace('/', '-', $jb_date_time);
			$jb_date_time=date('Y-m-d H:i:s',strtotime($jb_date_time));
			}			
		
		if(isset($bay_in) && validateForNull($bay_in))
			{
		    $bay_in = str_replace('/', '-', $bay_in);
			$bay_in=date('Y-m-d H:i:s',strtotime($bay_in));
			}	
		
		if(isset($bay_out) && validateForNull($bay_out))
			{
		    $bay_out = str_replace('/', '-', $bay_out);
			$bay_out=date('Y-m-d H:i:s',strtotime($bay_out));
			}		
			
		if(isset($delivery_promise) && validateForNull($delivery_promise))
			{
		    $delivery_promise = str_replace('/', '-', $delivery_promise);
			$delivery_promise=date('Y-m-d H:i:s',strtotime($delivery_promise));
			}	
		
		if(isset($actual_delivery) && validateForNull($actual_delivery))
			{
		    $actual_delivery = str_replace('/', '-', $actual_delivery);
			$actual_delivery=date('Y-m-d H:i:s',strtotime($actual_delivery));
			}				
		
		if(validateForNull($job_card_no,$jb_date_time,$bay_in,$bay_out,$delivery_promise) && checkForNumeric($service_type_id,$free_service_no,$kms_covered,$estimate_cost,$technician_id,$vehicle_id,$customer_id) && !checkForDuplicateJobCard($job_card_no))
		{
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$oc_id=$_SESSION['edmsAdminSession']['oc_id'];
		$sql="INSERT INTO edms_job_card
		      (job_card_no, job_card_datetime,service_type_id, free_service_no, date_of_sale,kms_covered, estimated_repair_cost, bay_in, bay_out, delivery_promise, actual_delivery, technician_id, vehicle_id, customer_id, created_by, last_updated_by, date_added, date_modified)
			  VALUES
			  ('$job_card_no', '$jb_date_time', $service_type_id, $free_service_no, '$date_of_sale', $kms_covered, $estimate_cost , '$bay_in', '$bay_out', '$delivery_promise', '$actual_delivery', $technician_id, $vehicle_id, $customer_id, $admin_id, $admin_id, NOW(), NOW())";
		dbQuery($sql);
		$job_card_id = dbInsertId();
		insertServiceCheckArraysToJobCard($service_check_array,$job_card_id);  
		insertJobDescArrayToJobCard($job_description_array,$job_card_id);
		insertJobWorkDoneArrayToJobCard($actual_work_done_array,$job_card_id);
		insertJobRemarksArrayToJobCard($remarks_array,$job_card_id);	
		
		$job_card_counter=getJobCounterForOCID($oc_id);
		if($job_card_counter==$job_card_no)		
		incrementJobCounterForOCID($oc_id);
		
		return $job_card_id;
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



function deleteJobCard($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$result = deleteSaleForJobCardId($id);
		if($result)
		{
		$sql="DELETE FROM edms_job_card
		      WHERE job_card_id=$id";
		dbQuery($sql);
		return "success";	 
		}
		return "error";
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

function updateJobCard($id,$job_card_no,$jb_date_time,$service_type_id,$free_service_no,$date_of_sale,$kms_covered,$estimate_cost,$bay_in,$delivery_promise,$technician_id,$job_description_array,$actual_work_done_array,$remarks_array,$service_check_array){
	
	try
	{
		$id = clean_data($id);
		$job_card_no=clean_data($job_card_no);
		$jb_date_time=clean_data($jb_date_time);
		$service_type_id=clean_data($service_type_id);
		$free_service_no=clean_data($free_service_no);
		$date_of_sale=clean_data($date_of_sale);
		$kms_covered=clean_data($kms_covered);
		$estimate_cost=clean_data($estimate_cost);
		$bay_in=clean_data($bay_in);
		$delivery_promise=clean_data($delivery_promise);
		$technician_id=clean_data($technician_id);
		$vehicle_id=clean_data($vehicle_id);
		$customer_id=clean_data($customer_id);
		
		if(!validateForNull($date_of_sale))
		$date_of_sale="01/01/1970";
		
		$actual_delivery="01/01/1970";	
		$bay_out="01/01/1970 00:00:00";
		
		if(!checkForNumeric($kms_covered))
		$kms_covered=0;
		
		if(!checkForNumeric($estimate_cost))
		$estimate_cost=0;
		
		if(isset($date_of_sale) && validateForNull($date_of_sale))
			{
		    $date_of_sale = str_replace('/', '-', $date_of_sale);
			$date_of_sale=date('Y-m-d',strtotime($date_of_sale));
			}	
			
		
		if(isset($jb_date_time) && validateForNull($jb_date_time))
			{
		    $jb_date_time = str_replace('/', '-', $jb_date_time);
			$jb_date_time=date('Y-m-d H:i:s',strtotime($jb_date_time));
			}			
		
		if(isset($bay_in) && validateForNull($bay_in))
			{
		    $bay_in = str_replace('/', '-', $bay_in);
			$bay_in=date('Y-m-d H:i:s',strtotime($bay_in));
			}	
		
		if(isset($bay_out) && validateForNull($bay_out))
			{
		    $bay_out = str_replace('/', '-', $bay_out);
			$bay_out=date('Y-m-d H:i:s',strtotime($bay_out));
			}		
			
		if(isset($delivery_promise) && validateForNull($delivery_promise))
			{
		    $delivery_promise = str_replace('/', '-', $delivery_promise);
			$delivery_promise=date('Y-m-d H:i:s',strtotime($delivery_promise));
			}	
		
		if(isset($actual_delivery) && validateForNull($actual_delivery))
			{
		    $actual_delivery = str_replace('/', '-', $actual_delivery);
			$actual_delivery=date('Y-m-d H:i:s',strtotime($actual_delivery));
			}				
		
		if(validateForNull($job_card_no,$jb_date_time,$bay_in,$bay_out,$delivery_promise) && checkForNumeric($id,$service_type_id,$free_service_no,$kms_covered,$estimate_cost,$technician_id) && !checkForDuplicateJobCard($job_card_no,$id))
		{
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$oc_id=$_SESSION['edmsAdminSession']['oc_id'];
		$sql="UPDATE edms_job_card
		      SET job_card_no = '$job_card_no', job_card_datetime = '$jb_date_time',service_type_id = $service_type_id, free_service_no = $free_service_no, date_of_sale = '$date_of_sale',kms_covered = $kms_covered, estimated_repair_cost = $estimate_cost, bay_in = '$bay_in', delivery_promise = '$delivery_promise',  technician_id = $technician_id, last_updated_by = $admin_id, date_modified = NOW()
			 WHERE job_card_id = $id";
			 
		dbQuery($sql);
		
		
		deleteServiceChecksForJobCardId($id);
		deleteJobDescForJobCard($id);
		deleteJobWorkDoneForJobCard($id);
		deleteJobCardRemarksForJobCardId($id);
		
		insertServiceCheckArraysToJobCard($service_check_array,$id);  
		insertJobDescArrayToJobCard($job_description_array,$id);
		insertJobWorkDoneArrayToJobCard($actual_work_done_array,$id);
		insertJobRemarksArrayToJobCard($remarks_array,$id);	
		

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

function getJobCardById($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$returnArray = array();
		$sql="SELECT job_card_no, job_card_datetime,service_type_id, free_service_no, date_of_sale,kms_covered, estimated_repair_cost, bay_in, bay_out, delivery_promise, actual_delivery, next_service_date, technician_id, vehicle_id, customer_id, created_by, last_updated_by, date_added, date_modified
		      FROM edms_job_card
			  WHERE job_card_id=$id";
	
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
		$sales_id = getSalesIdFromjobCardId($id);	
		$remarks=getJobCardRemarksByJobCardId($id);
		$sale = getSaleById($sales_id);
		$work_done=getJobCardWorkDoneByJobCardId($id);
		$description=getJobCardDescriptionByJobCardId($id);	
		$service_checks = getServiceCheckValuesForJobCardId($id);

		$regular_items = getInventoryItemRegularForSaleId($sales_id);
		$regular_general_items = getInventoryItemGeneralRegularForSaleId($sales_id);
		$regular_lubricant_items = getInventoryItemLubRegularForSaleId($sales_id);
		$warranty_items = getInventoryItemWarrantyForSaleId($sales_id);
		$regular_ns_items = getNonStockItemOurForSaleId($sales_id);
		$outside_job_items = getNonStockItemOutSideJobForSaleId($sales_id);
		$returnArray['job_card_details'] = $resultArray[0];
		$returnArray['job_card_description'] = $description;
		$returnArray['job_card_work_done'] = $work_done;
		$returnArray['job_card_remarks'] = $remarks;
		$returnArray['job_card_regular_items'] = $regular_items;
		$returnArray['job_card_regular_general_items'] = $regular_general_items;
		$returnArray['job_card_regular_lub_items'] = $regular_lubricant_items;
		$returnArray['job_card_warranty_items'] = $warranty_items;
		$returnArray['job_card_ns_items'] = $regular_ns_items;
		$returnArray['job_card_outside_job'] = $outside_job_items;
		$returnArray['job_card_checks'] = $service_checks;
		$returnArray['job_card_sales'] = $sale;
		return $returnArray;	
		}
		}
	}
	catch(Exception $e)
	{
	}
	
}	

function getCustomerIdByJobCardId($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$returnArray = array();
		$sql="SELECT  customer_id
		      FROM edms_job_card
			  WHERE job_card_id=$id";
	
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
		return $resultArray[0][0];	
		}
		}
	}
	catch(Exception $e)
	{
	}
	
}	


function getVehicleIdByJobCardId($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$returnArray = array();
		$sql="SELECT  vehicle_id
		      FROM edms_job_card
			  WHERE job_card_id=$id";
	
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
		return $resultArray[0][0];	
		}
		}
	}
	catch(Exception $e)
	{
	}
	
}	

function checkForDuplicateJobCard($job_card_no,$id=false)
{
		$job_card_no = clean_data($job_card_no);
	    if(validateForNull($job_card_no))
		{
		$sql="SELECT job_card_id
		      FROM edms_job_card
			  WHERE job_card_no='$job_card_no'";
		if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND job_card_id!=$id";		  	  
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return true;
		else
		return false;
		}
	}	
function checkIfJobCardInUse($id)
{
	if(checkForNumeric($id))
	{
	$sql="SELECT job_desc_id FROM
			edms_jb_rel_description
			WHERE job_desc_id=$id";
	$result=dbQuery($sql);
	if(dbNumRows($result)>0)
	return true;
	else
	return false;		
	}
	
}

function insertJobDescArrayToJobCard($jobDesc_array,$job_card_id)
{
	if(checkForNumeric($job_card_id) && is_array($jobDesc_array) && count($jobDesc_array)>0)
	{
		foreach($jobDesc_array as $job_desc)
		{
			
			if(validateForNull($job_desc))	
			{
				$job_desc_id=insertJobCardDescriptionIfNotDuplicate($job_desc);
				insertJobDescToJobCard($job_desc_id,$job_card_id);
			}
			
		}
		
	}
	
}	
function insertJobDescToJobCard($job_desc_id,$job_card_id)
{
	if(checkForNumeric($job_card_id,$job_desc_id))
	{
		
		$sql="INSERT INTO edms_jb_rel_description (job_card_id,job_desc_id) VALUES ($job_card_id,$job_desc_id)";
		$result = dbQuery($sql);
		return dbInsertId();
	}
	return false;
	
}	

function deleteJobDescForJobCard($job_card_id)
{
	if(checkForNumeric($job_card_id))
	{
		
		$sql="DELETE FROM edms_jb_rel_description WHERE job_card_id = $job_card_id";
		$result = dbQuery($sql);
		return true;
	}
	return false;
	
	
}

function insertJobWorkDoneArrayToJobCard($jobWorkDone_array,$job_card_id)
{
	if(checkForNumeric($job_card_id) && is_array($jobWorkDone_array) && count($jobWorkDone_array)>0)
	{
		foreach($jobWorkDone_array as $job_wd)
		{
			
			if(validateForNull($job_wd))	
			{
				$job_wd_id=insertJobCardWorkDoneIfNotDuplicate($job_wd);
				insertJobWorkDoneToJobCard($job_wd_id,$job_card_id);
			}
			
		}
		
	}
	
}	
function insertJobWorkDoneToJobCard($job_wd_id,$job_card_id)
{
	if(checkForNumeric($job_card_id,$job_wd_id))
	{
		
		$sql="INSERT INTO edms_jb_rel_work_done (job_card_id,job_wd_id) VALUES ($job_card_id,$job_wd_id)";
		$result = dbQuery($sql);
		return dbInsertId();
	}
	return false;
	
}	

function deleteJobWorkDoneForJobCard($job_card_id)
{
	if(checkForNumeric($job_card_id))
	{
		
		$sql="DELETE FROM edms_jb_rel_work_done WHERE job_card_id = $job_card_id";
		$result = dbQuery($sql);
		return true;
	}
	return false;
	
	
}

function insertJobRemarksArrayToJobCard($jobRemarks_array,$job_card_id)
{
	if(checkForNumeric($job_card_id) && is_array($jobRemarks_array) && count($jobRemarks_array)>0)
	{
		foreach($jobRemarks_array as $job_remarks)
		{
			
			if(validateForNull($job_remarks))	
			{
				$job_wd_id=insertJobCardRemarks($job_remarks,$job_card_id);
			}
			
		}
		
	}
	
}	



function insertSalesToJobCardId($job_card_id,$sales_id,$invoice_no)
{
	if(checkForNumeric($job_card_id,$sales_id) && (!validateForNull($invoice_no) || checkForNumeric($invoice_no)))
	{
		if(!validateForNull($invoice_no))
		$invoice_no = "NA";
		$oc_id=$_SESSION['edmsAdminSession']['oc_id'];
			
			$oc_prefix = getPrefixFromOCId($oc_id);
			$invoice_no = $oc_prefix.$invoice_no;
			
			$sql="INSERT INTO edms_jb_rel_sales (sales_id,job_card_id,invoice_no) VALUES ($sales_id,$job_card_id,'$invoice_no')";
			$result = dbQuery($sql);
			return "success";
	}
	return "error";
}

function deleteSaleForJobCardId($id)
{
	if(checkForNumeric($id))
	{
		$sales_id = getSalesIdFromjobCardId($id);
		if(!checkIfSaleInUse($sales_id))
		{
		$sql="DELETE FROM edms_jb_rel_sales WHERE job_card_id = $id";
		dbQuery($sql);
		deleteSale($sales_id);
		return true;
		}
		return false;
	}
	return false;
}

function updateSalesToJobCardId($job_card_id,$sales_id,$invoice_no)
{
	if(checkForNumeric($job_card_id,$sales_id) && (!validateForNull($invoice_no) || checkForNumeric($invoice_no)))
	{
		
		if(!validateForNull($invoice_no))
		$invoice_no = "NA";
		
		$sales = getSaleById($sales_id);
		$oc_id = $sales['oc_id'];
			
			$invoice_type = getRetailInvoiceTypeForOcId($oc_id);
			$oc_prefix  = $invoice_type['invoice_prefix'];
			$or_invoice_no= $invoice_no;
			$invoice_no = $oc_prefix.$invoice_no;
		
			$sql="UPDATE edms_jb_rel_sales SET invoice_no = '$invoice_no' WHERE job_card_id = $job_card_id";
			
			$result = dbQuery($sql);
			
			$job_card_counter=$invoice_type['invoice_counter'];
			if($job_card_counter==$or_invoice_no)	
			{	
			incrementInvoiceNoForOCID($invoice_type['invoice_type_id'],$oc_id);
			}
			return "success";
	}
	return "error";
}


function getSalesIdFromjobCardId($id)
{
	if(checkForNumeric($id))
	{
		$sql="SELECT sales_id FROM edms_jb_rel_sales WHERE job_card_id = $id";	
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0];
		else
		return false;
	}	
}

function insertServiceCheckArraysToJobCard($service_check_array,$job_card_id)
{
 if(checkForNumeric($job_card_id) && is_array($service_check_array) && count($service_check_array)>0)
 {
	 foreach($service_check_array as $check_id => $check_values_array)
	 {
		 insertServiceChecksToJobCard($check_values_array,$check_id,$job_card_id);
		 
		}
	 
	}	
	
}

function insertServiceChecksToJobCard($service_check_value_array,$service_check_id,$job_card_id)
{
 if(checkForNumeric($job_card_id,$service_check_id) && is_array($service_check_value_array) && count($service_check_value_array)>0)
 {
	 foreach($service_check_value_array as $check_value_id)
	 {
		 insertServiceCheckToJobCard($service_check_id,$check_value_id,$job_card_id);
		}
	 
	}	
	
} 

function insertServiceCheckToJobCard($service_check_id,$service_check_value_id,$job_card_id)
{
 if(checkForNumeric($job_card_id,$service_check_id,$service_check_value_id) && $service_check_id>0 && $service_check_value_id>0 && $job_card_id>0)
 {
	 $sql="INSERT INTO edms_jb_rel_service_check (service_check_id, service_check_value_id, job_card_id) VALUES ($service_check_id,$service_check_value_id,$job_card_id)";
	 dbQuery($sql);
	 return true;
	}	
	return false;
	
}

function deleteServiceChecksForJobCardId($job_card_id)
{
	if(checkForNumeric($job_card_id))
	{
	 $sql="DELETE FROM edms_jb_rel_service_check WHERE job_card_id = $job_card_id";
	 dbQuery($sql);
	 return true;
	}
	return false;
}

function getServiceCheckValuesForJobCardId($job_card_id)
{
 	if(checkForNumeric($job_card_id))
	{
		$sql="SELECT edms_jb_rel_service_check.service_check_id, edms_jb_rel_service_check.service_check_value_id, service_check, GROUP_CONCAT(service_check_value  ORDER BY edms_service_check_values.service_check_value_id) as service_check_values, check_type FROM edms_jb_rel_service_check, edms_service_check, edms_service_check_values WHERE job_card_id = $job_card_id AND edms_jb_rel_service_check.service_check_id = edms_service_check.service_check_id AND edms_service_check_values.service_check_value_id = edms_jb_rel_service_check.service_check_value_id GROUP BY edms_jb_rel_service_check.service_check_id ORDER BY check_type";	
		$result=dbQuery($sql);
		$resultArray = dbResultToArray($result);
	if(dbNumRows($result)>0)
		return $resultArray;
	}
}

function getNextServiceDateForVehicleId($vehicle_id)
{
	if(checkForNumeric($vehicle_id))
	{
		$sql="SELECT MAX(next_service_date) FROM edms_job_card WHERE vehicle_id = $vehicle_id";
		$result=dbQuery($sql);
		$resultArray = dbResultToArray($result);
	if(dbNumRows($result)>0)
		return $resultArray[0][0];
	}
	else return false;
}

?>