<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("image-functions.php");
require_once("vehicle-functions.php");
require_once("vehicle-insurance-functions.php");
require_once("account-functions.php");
require_once("account-ledger-functions.php");
require_once("invoice-counter-functions.php");
require_once("account-sales-functions.php");
require_once("our-company-function.php");
require_once("common.php");
require_once("bd.php");
		
function listVehicleInvoicesForCustomer($id){
	try
	{
		
		if(checkForNumeric($id))
		{
		$sql="SELECT vehicle_invoice_id,invoice_date, invoice_no, delivery_challan_id, vehicle_id, customer_id, sales_id, under_exchange, exchange_vehicle_id, created_by, last_updated_by, date_added, date_modified FROM edms_vehicle_invoice WHERE customer_id = $id";
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

function getExchangeVehicleIdForVehicleInvoiceId($invoice_id)
{
	if(checkForNumeric($invoice_id))
	{
		
		$sql="SELECT exchange_vehicle_id FROM edms_vehicle_invoice WHERE vehicle_invoice_id = $invoice_id";
		
		$result = dbQuery($sql);
		$resultArray  = dbResultToArray($result);
		if(dbNumRows($result))
		{
			return $resultArray[0][0];
		}
		else
		return false;
	}
	
}

function insertInvoiceForVehicle($delivery_challan_id,$invoice_date,$invoice_no,$sales_id,$exchange=0,$exchange_vehicle_id="NULL"){
	try
	{
		
			$vehicle_id = getVehicleIdFromDeliveryChallan($delivery_challan_id);
		    $customer_id = getCustomerIdFromDeliveryChallan($delivery_challan_id);
	   
		if(checkForNumeric($delivery_challan_id,$vehicle_id,$customer_id,$sales_id) && validateForNull($invoice_date)  && !checkForDuplicateVehicleInvoice($delivery_challan_id,$invoice_no) && ($invoice_no=="" || checkForNumeric($invoice_no)) && ($exchange==0 || checkForNumeric($exchange_vehicle_id)))
		{
			if($invoice_no=="")
			$invoice_no="NA";
				
			$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
			$oc_id=$_SESSION['edmsAdminSession']['oc_id'];
			
			$oc_prefix = getPrefixFromOCId($oc_id);
			$or_invoice_no = $invoice_no;
			$invoice_no = $oc_prefix.$invoice_no;
			
			$invoice_date = str_replace('/', '-', $invoice_date);
			$invoice_date=date('Y-m-d',strtotime($invoice_date));
			
		
			
			$sql="INSERT INTO edms_vehicle_invoice
			      (invoice_date, invoice_no, delivery_challan_id, vehicle_id, customer_id, sales_id, under_exchange, exchange_vehicle_id, created_by, last_updated_by, date_added, date_modified)
				  VALUES
				  ('$invoice_date','$invoice_no',$delivery_challan_id,$vehicle_id,$customer_id, $sales_id,$exchange,$exchange_vehicle_id,$admin_id,$admin_id,NOW(),NOW())";
			
			dbQuery($sql);
			$invoice_id=dbInsertId();
			
			$invoice_counter=getInvoiceCounterForOCID(VEHICLE_INVOICE_TYPE_ID,$oc_id);
			if($or_invoice_no!="NA" && $invoice_counter==$or_invoice_no)		
			incrementInvoiceNoForOCID(VEHICLE_INVOICE_TYPE_ID,$oc_id);
			return $invoice_id;
		}
		else
		{return "error";}
	}
	catch(Exception $e)
	{
	}
	
}	

function deleteVehicleInvoiceById($id){
	
	try
	{
		
		$sql="DELETE FROM edms_vehicle_invoice WHERE vehicle_invoice_id = $id";
		dbQuery($sql);
	}
	catch(Exception $e)
	{
	}
	
}

function getSalesIdFromInvoice($id)
{
	if(checkForNumeric($id))
	{
		$sql="SELECT sales_id FROM edms_vehicle_invoice WHERE vehicle_invoice_id = $id";
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0];
		else
		return false;
	}
	
}	

function getDeliveryChallanIdFromSalesId($id)
{
	if(checkForNumeric($id))
	{
		$sql="SELECT delivery_challan_id FROM edms_vehicle_invoice WHERE sales_id = $id";
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0];
		else
		return false;
	}
	
}	

function getVehicleIdFromVehicleInvoice($id)
{
	if(checkForNumeric($id))
	{
		$sql="SELECT vehicle_id FROM edms_vehicle_invoice WHERE vehicle_invoice_id = $id";
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0];
		else
		return false;
	}
	
}

function getCustomerIdFromVehicleInvoice($id)
{
	if(checkForNumeric($id))
	{
		$sql="SELECT customer_id FROM edms_vehicle_invoice WHERE vehicle_invoice_id = $id";
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0];
		else
		return false;
	}
	
}

function updateVehicleInvoice($delivery_challan_id,$invoice_date,$invoice_no,$exchange=0,$exchange_vehicle_id="NULL"){
	try
	{
		
	   	$vehicle_id = getVehicleIdFromDeliveryChallan($delivery_challan_id);
		$customer_id =getCustomerIdFromDeliveryChallan($delivery_challan_id);
	   

		if(checkForNumeric($delivery_challan_id) && validateForNull($invoice_date) && ($invoice_no=="" || checkForNumeric($invoice_no)) && ($exchange==0 || checkForNumeric($exchange_vehicle_id)))
		{
			if($invoice_no=="")
			$invoice_no="NA";
			
			if(!is_numeric($exchange))
			$exchange=0;
			
			if($exchange==0)
			$exchange_vehicle_id="NULL";	
			$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
			$oc_id=$_SESSION['edmsAdminSession']['oc_id'];
			
			$oc_prefix = getPrefixFromOCId($oc_id);
			$invoice_no = $oc_prefix.$invoice_no;
			
			$invoice_date = str_replace('/', '-', $invoice_date);
			$invoice_date=date('Y-m-d',strtotime($invoice_date));
			
			
			
			$sql="UPDATE edms_vehicle_invoice
			      SET invoice_date = '$invoice_date', invoice_no = '$invoice_no', under_exchange = $exchange, exchange_vehicle_id = $exchange_vehicle_id, last_updated_by = $admin_id, date_modified = NOW()
				 WHERE delivery_challan_id=$delivery_challan_id";
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

function getVehicleInvoiceByVehicleId($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT vehicle_invoice_id,invoice_date, invoice_no, delivery_challan_id, vehicle_id, customer_id, sales_id, under_exchange, exchange_vehicle_id, created_by, last_updated_by, date_added, date_modified FROM edms_vehicle_invoice WHERE vehicle_id = $id";
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

function getVehicleInvoiceByDeliveryChallanId($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT vehicle_invoice_id,invoice_date, invoice_no, delivery_challan_id, vehicle_id, customer_id, sales_id, under_exchange, exchange_vehicle_id, created_by, last_updated_by, date_added, date_modified FROM edms_vehicle_invoice WHERE delivery_challan_id = $id";
		$result = dbQuery($sql);
		$resultArray =dbResultToArray($result);
		
		if(dbNumRows($result)>0)
		{
			return 	$resultArray[0];
		}
		else return false;
		}
		return false;
	}
	catch(Exception $e)
	{
	}
	
}	

function getVehicleInvoiceById($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT vehicle_invoice_id,invoice_date, invoice_no, delivery_challan_id, vehicle_id, customer_id, sales_id, under_exchange, exchange_vehicle_id, created_by, last_updated_by, date_added, date_modified FROM edms_vehicle_invoice WHERE vehicle_invoice_id = $id";
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

function checkForDuplicateVehicleInvoice($delivery_challan_id,$invoice_no)
{
	$sql="SELECT delivery_challan_id FROM edms_vehicle_invoice
	      WHERE delivery_challan_id=$delivery_challan_id";
	if(validateForNull($invoice_no) && checkForNumeric($invoice_no))	  
	$sql=$sql." OR invoice_no='$invoice_no'";
	 		  
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