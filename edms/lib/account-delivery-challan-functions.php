<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("area-functions.php");
require_once("account-head-functions.php");
require_once("account-period-functions.php");
require_once("account-ledger-functions.php");
require_once("invoice-counter-functions.php");
require_once("inventory-sales-functions.php");
require_once("account-sales-functions.php");
require_once("inventory-item-barcode-functions.php");
require_once("nonstock-sales-functions.php");
require_once("account-functions.php");
require_once("customer-functions.php");
require_once("customer-group-functions.php");
require_once("our-company-function.php");
require_once("common.php");
require_once("bd.php");

function getAllACDeliveryChallansForCustomerId($customer_id)
{
	$sql="SELECT *
			  FROM edms_ac_delivery_challan WHERE to_customer_id = $customer_id";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray;
	else
	return "error"; 
}

function getLastACDeliveryChallan()
{
	$sql="SELECT *
			  FROM edms_ac_delivery_challan  ORDER BY delivery_challan_id DESC LIMIT 0,1";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray[0]['delivery_challan_id'];
	else
	return "error"; 
}

function getDeliveryChallansBetweenDates($from=NULL,$to=NULL)
{
	if(!validateForNull($from))
	$from = getTodaysDateTimeBeforeDays(3);
	else
	{
		$from = str_replace('/', '-', $from);
	    $from = date('Y-m-d',strtotime($from));
	}
	if(!validateForNull($to))
	$to = getTodaysDate();
	else
	{
		$to = str_replace('/', '-', $to);
	    $to=date('Y-m-d',strtotime($to));
	}
	$sql="SELECT *
			  FROM edms_ac_delivery_challan WHERE 1=1 ";
			   if(isset($from) && validateForNull($from))
	$sql=$sql." AND trans_date >='$from' 
		  ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."AND trans_date <='$to' ";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray;
	else
	return "error"; 
}

function getAllUnInvoicedACDeliveryChallans($from=NULL,$to=NULL)
{
	if(isset($from) && validateForNull($from))
    {
	$from = str_replace('/', '-', $from);
	$from = date('Y-m-d',strtotime($from));
	}
	
	if(isset($to) && validateForNull($to))
	{
	$to = str_replace('/', '-', $to);
	$to=date('Y-m-d',strtotime($to));
	}
	$sql="SELECT * FROM edms_ac_delivery_challan WHERE sales_id IS NULL ";
			  
			  if(isset($from) && validateForNull($from))
	$sql=$sql." AND trans_date >='$from' 
		  ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."AND trans_date <='$to' ";	   	   	 	
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray;
	else
	return "error"; 
}

function getAllACDeliveryChallans($from=NULL,$to=NULL,$invoice_generated=NULL)
{
	if(isset($from) && validateForNull($from))
    {
	$from = str_replace('/', '-', $from);
	$from = date('Y-m-d',strtotime($from));
	}
	
	if(isset($to) && validateForNull($to))
	{
	$to = str_replace('/', '-', $to);
	$to=date('Y-m-d',strtotime($to));
	}
	$sql="SELECT * FROM edms_ac_delivery_challan WHERE 1=1 ";
	if(isset($invoice_generated) && $invoice_generated==1)
	$sql=$sql." AND sales_id IS NOT NULL ";
	else if(is_numeric($invoice_generated) && $invoice_generated==0)	
	$sql=$sql." AND sales_id IS NULL  ";		  
			  if(isset($from) && validateForNull($from))
	$sql=$sql." AND trans_date >='$from' 
		  ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."AND trans_date <='$to' ";
	   	   	 	
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray;
	else
	return "error"; 
}
	
function getACDeliveryChallanByACDeliveryChallanId($id)
{
	
	if(checkForNumeric($id))
	{
		$sql="SELECT *
			  FROM edms_ac_delivery_challan
			  WHERE delivery_challan_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0];
		else
		return "error"; 
		
		}
	}

function getACDeliveryChallanBySalesId($id)
{
	
	if(checkForNumeric($id))
	{
		$sql="SELECT *
			  FROM edms_ac_delivery_challan
			  WHERE sales_id=$id";
	  
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0];
		else
		return false; 
		
		}
	}	
	

function insertACDeliveryChallan($trans_date,$to_ledger,$remarks,$challan_no,$item_id_array,$quantity_array,$godown_id_array,$item_description_array,$item_id_ns_array,$item_description_ns_array,$oc_id=NULL,$delivery_note="",$terms_of_payment="",$supp_ref_no="",$other_references="",$buyer_order_no="",$order_dated="",$despatch_doc_no="",$despatch_dated="",$despatch_through="",$destination="",$terms_of_delivery="",$consignee_address="",$unit_array) // $to_ledger should start with C for customer or L for ledger to_ledger: credit, from_ledger: debit
{
	$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
	
	if(substr($to_ledger, 0, 1) == 'L')
	{
		$to_ledger=str_replace('L','',$to_ledger);
		$to_ledger=intval($to_ledger);
		$led=getLedgerById($to_ledger);
		$current_company=getCompanyForLedger($to_ledger);
				if($current_company[1]==0)
				{
					if(!is_numeric($oc_id))
					$oc_id = $current_company[0];
					$accounts_settings=getAccountsSettingsForOC($oc_id);
				}
				
		
		$to_customer="NULL";
		
		}
	else if(substr($to_ledger, 0, 1) == 'C')
	{
		$to_ledger=str_replace('C','',$to_ledger);
		$to_customer=intval($to_ledger);
		$to_ledger="NULL";
		
		$customer=getCustomerDetailsByCustomerId($to_customer);
		$customer_id=$customer['customer_id'];
		
		if(!is_numeric($oc_id))
		$oc_id=getCompanyIdFromCustomerId($customer_id);
		$accounts_settings=getAccountsSettingsForOC($oc_id);
		}	
		
		
	if( (!(checkForNumeric($to_ledger) || checkForNumeric($to_customer))) || !checkForNumeric($oc_id) )
	{
		return "ledger_error";
		}
	if(isset($trans_date) && validateForNull($trans_date))
			{
		    $trans_date = str_replace('/', '-', $trans_date);
			$trans_date=date('Y-m-d',strtotime($trans_date));
			}
		
	$ac_starting_date = $accounts_settings['ac_starting_date'];		
	
	if(strtotime($trans_date)<strtotime($ac_starting_date)) // payment date should be greater than books starting date
	{
		return "date_error";
	}	
	
	
	if(checkForNumeric($admin_id,$challan_no)  && validateForNull($trans_date))
	{
		
		
		
			$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
			
			$or_challan_no = getChallanCounterForOCID($oc_id);
			
			
			$sql="INSERT INTO edms_ac_delivery_challan (to_ledger_id,to_customer_id,oc_id,trans_date,remarks,created_by,last_updated_by,date_added,date_modified, challan_no)
			VALUES ($to_ledger,$to_customer,$oc_id,'$trans_date','$remarks',$admin_id,$admin_id,NOW(),NOW(), '$challan_no')";
		
			$result=dbQuery($sql);
			$delievry_challan_id = dbInsertId();
			
			
	$item_id_array = ConvertItemNameArrayInToIdArray($item_id_array);
	
	$item_id_ns_array = ConvertItemNameArrayInToIdArray($item_id_ns_array);
	$nett_amount = checkForACDeliveryChallanItemsInArray($item_id_array,$quantity_array,4);
	
	if($nett_amount=="barcode_transaction_error")
	return "barcode_transaction_error";
	
	$nett_amount_ns = count($item_id_ns_array);
	
	if(($nett_amount && checkForNumeric($nett_amount) && $nett_amount>=0) || ($nett_amount_ns && checkForNumeric($nett_amount_ns) && $nett_amount_ns>=0))
	{
	
	
	if(checkForNumeric($delievry_challan_id))
	{
	if($nett_amount && checkForNumeric($nett_amount) && $nett_amount>=0)	
	insertInventoryItemsToACDeliveryChallan($item_id_array,$quantity_array,$delievry_challan_id,$godown_id_array,$item_description_array,$unit_array);
	if($nett_amount_ns && checkForNumeric($nett_amount_ns) && $nett_amount_ns>=0)
	insertNonStocksToACDeliveryChallan($item_id_ns_array,$delievry_challan_id,$item_description_ns_array);
	}
	
	
	insertSalesInfo($delivery_note,$terms_of_payment,$supp_ref_no,$other_references,$buyer_order_no,$order_dated,$despatch_doc_no,$despatch_dated,$despatch_through,$destination,$terms_of_delivery,NULL,$consignee_address,$delievry_challan_id);
	
	}
			
			
			if( $challan_no==$or_challan_no)
			{		
			incrementChallanNoForOCID($oc_id);
			}
			return $delievry_challan_id;
	}
	return "error";	
}


function insertACDeliveryChallanForCustomerGroup($trans_date,$customer_group_id,$remarks,$challan_no,$item_id_array,$quantity_array,$godown_id_array,$item_description_array,$item_id_ns_array,$item_description_ns_array,$oc_id=NULL) // $to_ledger should start with C for customer or L for ledger to_ledger: credit, from_ledger: debit
{
	
	$customer_id_array=getCustomerIdsForCustomerGroupID($customer_group_id);
	
	$new_customer_id_array = array();
	
	foreach($customer_id_array as $customer_id)
	{
	if(!is_numeric($oc_id))
	$oc_id=getCompanyIdFromCustomerId($customer_id);	
	$new_customer_id_array[] = 'C'.$customer_id;
	}
	
	
	$challan_no = getChallanCounterForOCID($oc_id);
	insertACDeliveryChallanForCustomerIdArray($trans_date,$new_customer_id_array,$remarks,$challan_no,$item_id_array,$quantity_array,$godown_id_array,$item_description_array,$item_id_ns_array,$item_description_ns_array,$oc_id);
	
	return "success";
}

function insertACDeliveryChallanForCustomerIdArray($trans_date,$customer_id_array,$remarks,$challan_no,$item_id_array,$quantity_array,$godown_id_array,$item_description_array,$item_id_ns_array,$item_description_ns_array,$oc_id=NULL) // $to_ledger should start with C for customer or L for ledger to_ledger: credit, from_ledger: debit
{
	foreach($customer_id_array as $customer_id)
	{
		$to_ledger=str_replace('C','',$customer_id);
		$to_customer=intval($to_ledger);
		
	if(!is_numeric($oc_id))
	$oc_id=getCompanyIdFromCustomerId($to_customer);	
	
	$challan_no = getChallanCounterForOCID($oc_id);
		
		insertACDeliveryChallan($trans_date,$customer_id,$remarks,$challan_no,$item_id_array,$quantity_array,$godown_id_array,$item_description_array,$item_id_ns_array,$item_description_ns_array,$oc_id);
	}
	return "success";
	
	
}

function deleteACDeliveryChallan($id)
{
	if(checkForNumeric($id))
	{
		if(checkIfACDeliveryChallanInUse($id))
		return "use_error";
		
		if(USE_BARCODE==1)
		{
			$used_barcode_tansactions = getUsedBarcodeForTransactionItemWise($id,4);
			$used_barcode_tansactions_item_id_array = array_keys($used_barcode_tansactions);
			
			if(is_array($used_barcode_tansactions) && count($used_barcode_tansactions)>0 && checkForNumeric($used_barcode_tansactions_item_id_array[0]))
			return "barcode_in_use_error";
		}
		
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$old_payment=getACDeliveryChallanByACDeliveryChallanId($id);

		deleteInventoryBarcodeTransactionByTransId($id,4,1);
		
		$sql="DELETE FROM edms_ac_delivery_challan where delivery_challan_id=$id";
		dbQuery($sql);
		
	
		return "success";
		}
		return "error";
}

function checkIfACDeliveryChallanInUse($sales_id)
{
	if(checkForNumeric($sales_id))
	{
		$sale = getACDeliveryChallanByACDeliveryChallanId($sales_id);
		$sales_id = $sale['sales_id']; // 3 = job_card, 2 = item sales_auto_rasid_type
		
		if(is_numeric($sales_id))
		{
		return true;
		}
		
		return false;
	}
	return true;
}

function updateACDeliveryChallan($id,$trans_date,$to_ledger,$remarks,$challan_no,$item_id_array,$quantity_array,$godown_id_array,$item_description_array,$item_id_ns_array,$item_description_ns_array,$oc_id=NULL,$delivery_note="",$terms_of_payment="",$supp_ref_no="",$other_references="",$buyer_order_no="",$order_dated="",$despatch_doc_no="",$despatch_dated="",$despatch_through="",$destination="",$terms_of_delivery="",$consignee_address="",$unit_array=array()) // $to_ledger should start with C for customer or L for ledger
{
	$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
	
	    $old_payment=getACDeliveryChallanByACDeliveryChallanId($id);
		if(is_numeric($old_payment['sales_id']))
		return "sales_error";
		$old_amount=$old_payment['amount'];
		$old_trans_date=$old_payment['trans_date'];
		$old_to_ledger_id=$old_payment['to_ledger_id'];
		$old_to_customer_id=$old_payment['to_customer_id'];
		$old_from_ledger_id=$old_payment['from_ledger_id'];
		
		if(!is_numeric($oc_id))
	    $oc_id=$old_payment['oc_id'];
	
	if(!checkForNumeric($retail_tax))
	$retail_tax=0;
	
	 if(checkForNumeric($oc_id) && validateForNull($oc_id))
	{
		$accounts_settings=getAccountsSettingsForOC($oc_id);
		}
	
	if(substr($to_ledger, 0, 1) == 'L')
	{
		$to_ledger=str_replace('L','',$to_ledger);
		$to_ledger=intval($to_ledger);
		$to_customer="NULL";
		$led=getLedgerById($to_ledger);
		
				if($current_company[1]==0)
				{
					
					$accounts_settings=getAccountsSettingsForOC($oc_id);
				}
				
		$to_customer="NULL";
		
		}
	else if(substr($to_ledger, 0, 1) == 'C')
	{
		$to_ledger=str_replace('C','',$to_ledger);
		$to_customer=intval($to_ledger);
		$to_ledger="NULL";
		
		$customer=getCustomerDetailsByCustomerId($to_customer);
	
		$accounts_settings=getAccountsSettingsForOC($oc_id);
		}	
		
	
	if( (!(checkForNumeric($to_ledger) || checkForNumeric($to_customer))) || !checkForNumeric($oc_id) )
	{
		return "ledger_error";
		}
	
	if(isset($trans_date) && validateForNull($trans_date))
			{
		    $trans_date = str_replace('/', '-', $trans_date);
			$trans_date=date('Y-m-d',strtotime($trans_date));
			}	
			
	$ac_starting_date = $accounts_settings['ac_starting_date'];		
	
	if(strtotime($trans_date)<strtotime($ac_starting_date)) // payment date should be greater than books starting date
	{
		return "date_error";
	}	
	
	
	$item_id_array = ConvertItemNameArrayInToIdArray($item_id_array);
	$item_id_ns_array = ConvertItemNameArrayInToIdArray($item_id_ns_array);
	$nett_amount = checkForACDeliveryChallanItemsInArray($item_id_array,$quantity_array,4,$id);
	
	if($nett_amount=="barcode_transaction_error")
	return "barcode_transaction_error";
	
	$nett_amount_ns = count($item_id_ns_array);	
		
	if(checkForNumeric($admin_id,$id) && validateForNull($trans_date) && (($nett_amount && checkForNumeric($nett_amount) && $nett_amount>=0) || ($nett_amount_ns && checkForNumeric($nett_amount_ns) && $nett_amount_ns>=0)))
	{
			
			$sql="UPDATE edms_ac_delivery_challan SET  to_ledger_id=$to_ledger, to_customer_id=$to_customer, trans_date='$trans_date', remarks='$remarks',  last_updated_by=$admin_id, date_modified=NOW(), challan_no=$challan_no
			WHERE delivery_challan_id=$id";

			$result=dbQuery($sql);

	
	
	
	
	if(checkForNumeric($id))
	{
		deleteInventoryItemsForACDeliveryChallan($id);
		deleteNonStockItemsForACDeliveryChallan($id);	
		if($nett_amount && checkForNumeric($nett_amount) && $nett_amount>=0)	
		{
		insertInventoryItemsToACDeliveryChallan($item_id_array,$quantity_array,$id,$godown_id_array,$item_description_array,$unit_array);
		}
		if($nett_amount_ns && checkForNumeric($nett_amount_ns) && $nett_amount_ns>=0)
		insertNonStocksToACDeliveryChallan($item_id_ns_array,$id,$item_description_ns_array);
	}
	
	insertSalesInfo($delivery_note,$terms_of_payment,$supp_ref_no,$other_references,$buyer_order_no,$order_dated,$despatch_doc_no,$despatch_dated,$despatch_through,$destination,$terms_of_delivery,NULL,$consignee_address,$id);
	
		
			
			return "success";
	}
	return "error";	
	
	}	
	
function getInventoryItemForACDeliveryChallanId($id)
{
	if(checkForNumeric($id))
	{
		$return_array = array();
		$sql="SELECT edms_ac_sales_item.sales_item_id,edms_ac_sales_item.item_id,edms_ac_sales_item.rate,edms_ac_sales_item.quantity,edms_ac_trans_item_unit.rate as or_rate, edms_ac_trans_item_unit.quantity as or_quantity,discount,amount,net_amount,edms_ac_sales_item.sales_id,godown_id,created_by,last_updated_by,date_added,date_modified , item_desc FROM edms_ac_sales_item LEFT JOIN edms_ac_trans_item_unit ON edms_ac_sales_item.sales_item_id = edms_ac_trans_item_unit.sales_item_id  WHERE edms_ac_sales_item.delivery_challan_id = $id";	
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
			$i=0;
			foreach($resultArray as $re)
			{
				$sales_item_id = $re['sales_item_id'];
				$return_array[$i]['sales_item_details'] = $re;
				$i++;
			}
			return $return_array;
		}
	}
	
}	

function getNonStockItemForACDeliveryChallanId($id)
{
	if(checkForNumeric($id))
	{
		$return_array = array();
		$sql="SELECT edms_ac_sales_nonstock.sales_non_stock_id,item_id,discount,amount,net_amount,edms_ac_sales_nonstock.sales_id,created_by,last_updated_by,date_added,date_modified ,item_desc FROM edms_ac_sales_nonstock
		WHERE edms_ac_sales_nonstock.delivery_challan_id = $id ";	
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
			$i=0;
			foreach($resultArray as $re)
			{
				$sales_item_id = $re['sales_non_stock_id'];
				$return_array[$i]['sales_item_details'] = $re;
				$i++;
			}
			return $return_array;
		}
	}
	
}	

function updateSaleToDeliveryChallan($sales_id,$delivery_challan_id)
{
	if(checkForNumeric($sales_id,$delivery_challan_id))
	{
		$sql="UPDATE edms_ac_delivery_challan SET sales_id = $sales_id WHERE delivery_challan_id = $delivery_challan_id";
		dbQuery($sql);
		return "success";
	}
	return "error";
}

	
?>