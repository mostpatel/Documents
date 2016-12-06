<?php 
require_once("cg.php");
require_once("common.php");
require_once("city-functions.php");
require_once("area-functions.php");
require_once("tax-functions.php");
require_once("item-type-functions.php");
require_once("account-head-functions.php");
require_once("account-functions.php");
require_once("customer-functions.php");
require_once("inventory-item-functions.php");
require_once("inventory-jv-functions.php");
require_once("account-period-functions.php");
require_once("account-purchase-functions.php");
require_once("account-sales-functions.php");
require_once("account-debit-note-functions.php");
require_once("account-credit-note-functions.php");
require_once("account-delivery-challan-functions.php");
require_once("account-combined-agency-functions.php");
require_once("our-company-function.php");
require_once("dictionary-functions.php");
require_once("common.php");
require_once("bd.php");

function generateBarcodeForItemId($item_id,$trans_date = false)
{
	if(checkForNumeric($item_id))
	{
	$inventory_item=getInventoryItemById($item_id);
	if($inventory_item['use_barcode']==1)
	{
	$barcode_prefix = $inventory_item['barcode_prefix'];
	$barcode_counter = $inventory_item['barcode_counter'];
	$old_trans_date = $trans_date;
	
	if(validateForNull($trans_date))
	{
	$trans_date = date('Y-m-d',strtotime($trans_date));	
	$trans_date = str_replace("-","",$trans_date);
	$barcode_prefix = $barcode_prefix.$trans_date;
	}
	
	$barcode = $barcode_prefix.$barcode_counter;
	$no_of_trans=checkIfBarcodeInUse($barcode);
	if($no_of_trans>0)
	{
		incrementBarcodeCounterForItemId($item_id);
		$barcode=generateBarcodeForItemId($item_id,$old_trans_date);
		return $barcode;
	}
	return $barcode;
	}
	else return false;
	}
	return false;
}

function incrementBarcodeCounterForItemId($item_id)
{
	if(checkForNumeric($item_id))
	{
		
	$inventory_item=getInventoryItemById($item_id);
	
	if($inventory_item['use_barcode']==1)
	{
		$barcode_counter = $inventory_item['barcode_counter'];
		$barcode_counter = $barcode_counter+1;
		$sql="UPDATE edms_inventory_item SET barcode_counter = $barcode_counter WHERE item_id = $item_id";
		$result = dbQuery($sql);
		return true;
	}
	else
	return false;
}
}


function insertInventoryBarcodeTransaction($trans_id,$trans_item_id,$trans_type,$item_id,$quantity,$barcode=NULL,$oc_id=NULL,$check_barcode_in_use_for_opening=1) // trans_type 1=purchase,2=sales,6=debit_note,4=delivery_challan,5=debit_inventory_jv,3=credit_note,7=opening,8=credit_inventory_jv
{
	if(!checkForNumeric($oc_id))
	$oc_id=$_SESSION['edmsAdminSession']['oc_id'];
	
	$or_barcode=$barcode;
	
	if(validateForNull($barcode))
	{
	$latest_transaction = getLatestTransactionForBarcode($barcode);
	if($latest_transaction && is_array($latest_transaction) && is_numeric($latest_transaction['trans_type']))	
	{
		$old_trans_type = $latest_transaction['trans_type'];
		
		if($old_trans_type%2==0)
		{
			if($trans_type%2==0)
			return "error";
		}
		else if($old_trans_type%2==1)
		{
			if($trans_type%2==1)
			return "error";
		}
	}
	}
	
	if(checkForNumeric($trans_id,$trans_type,$item_id,$trans_item_id,$quantity,$oc_id))
	{
		if(is_array($barcode))
		$barcode_array = $barcode;
		
		for($i=0;$i<$quantity;$i++)
		{
			
			$trans_date  = getTransdateFromTransIdAndTransType($trans_id,$trans_type);	
			
			$trans_date_added = getTransdateAddedFromTransIdAndTransType($trans_id,$trans_type);
			$system_generated=0;
			$barcode = $or_barcode;
			 
			if(($trans_type==1 || $trans_type==7) && !validateForNull($barcode[0]))
			{
			
			$barcode=generateBarcodeForItemId($item_id,$trans_date);
			$system_generated = 1;
			}
			else
			{
			$system_generated=0;
			
			if(isset($barcode_array))
			$barcode = $barcode_array[$i];
			}
		
		$barcode = clean_data($barcode);
		
		if($trans_type==7 && $check_barcode_in_use_for_opening==1)
		{
			$no_of_trans=checkIfBarcodeInUse($barcode);
			if($no_of_trans>0)
			return "error";
		}
		
		if($barcode && validateForNull($barcode))
		{
			
			$sql="INSERT INTO edms_barcode_transactions(barcode,item_id,trans_date,trans_id,trans_item_id,trans_type,oc_id,trans_date_added) VALUES ('$barcode',$item_id,'$trans_date',$trans_id,$trans_item_id,$trans_type,$oc_id,'$trans_date_added')";
			$result = dbQuery($sql);
			$barcode_trans_id = dbInsertId();
			
			if(($trans_type==1 || $trans_type==7) && $system_generated==1)
			incrementBarcodeCounterForItemId($item_id);
			
			
			
		}
		}
		
	}
}

function getTransdateFromTransIdAndTransType($trans_id,$trans_type)
{
	if($trans_type==1)
	{
		$purchase = getPurchaseById($trans_id);
		
		return $purchase['trans_date'];
	}
	if($trans_type==2)
	{
		$purchase = getSaleById($trans_id);
		
		return $purchase['trans_date'];
	}
	if($trans_type==3)
	{
		$purchase = getCreditNoteById($trans_id);
		
		return $purchase['trans_date'];
	}
	if($trans_type==4)
	{
		$purchase = getACDeliveryChallanByACDeliveryChallanId($trans_id);
		
		return $purchase['trans_date'];
		
	}
	if($trans_type==5 || $trans_type==8)
	{
		$purchase = getInventoryJVById($trans_id);
		
		return $purchase['trans_date'];
		
	}
	if($trans_type==6)
	{
		$purchase = getDebitNoteById($trans_id);
		
		return $purchase['trans_date'];
	}
	if($trans_type==7)
	{
		$purchase = getInventoryItemById($trans_id);
		$our_company_id = $purchase['our_company_id'];
		return getBooksStartingDateForOC($our_company_id);
		
	}
}


function getTransdateAddedFromTransIdAndTransType($trans_id,$trans_type)
{
	if($trans_type==1)
	{
		$purchase = getPurchaseById($trans_id);
		
		return $purchase['date_added'];
	}
	if($trans_type==2)
	{
		$purchase = getSaleById($trans_id);
		
		return $purchase['date_added'];
	}
	if($trans_type==3)
	{
		$purchase = getCreditNoteById($trans_id);
		
		return $purchase['date_added'];
	}
	if($trans_type==4)
	{
		$purchase = getACDeliveryChallanByACDeliveryChallanId($trans_id);
		
		return $purchase['date_added'];
		
	}
	if($trans_type==5 || $trans_type==8)
	{
		$purchase = getInventoryJVById($trans_id);
		
		return $purchase['date_added'];
		
	}
	if($trans_type==6)
	{
		$purchase = getDebitNoteById($trans_id);
		
		return $purchase['date_added'];
	}
	if($trans_type==7)
	{
		$purchase = getInventoryItemById($trans_id);
		$our_company_id = $purchase['our_company_id'];
		return getBooksStartingDateForOC($our_company_id);
		
	}
}

function getBarcodesFromTransIdAndTransType($trans_id,$trans_type,$item_id = false)
{

if(checkForNumeric($trans_id,$trans_type))
{
	$sql="SELECT barcode FROM edms_barcode_transactions WHERE trans_id = $trans_id AND trans_type = $trans_type ";
	if($item_id && checkForNumeric($item_id))
	$sql=$sql." AND item_id = $item_id";
	
	$result = dbQuery($sql);
	if(dbNumRows($result)>0)
	{
	$resultArray = dbResultToArray($result);
	return $resultArray;
	
	}
	else return false;
}
	
}

function getBarcodeTransactionsFromTransIdAndTransType($trans_id,$trans_type,$item_id = false)
{

if(checkForNumeric($trans_id,$trans_type))
{
	$sql="SELECT * FROM edms_barcode_transactions WHERE trans_id = $trans_id AND trans_type = $trans_type ORDER BY barcode_transaction_id ";
	if($item_id && checkForNumeric($item_id))
	$sql=$sql." AND item_id = $item_id";
	
	$result = dbQuery($sql);
	if(dbNumRows($result)>0)
	{
	$resultArray = dbResultToArray($result);
	return $resultArray;
	
	}
	else return false;
}
	
}

function getBarcodeTransactionFromBarcode($barcode,$item_id)
{
if(checkForNumeric($item_id) && validateForNull($barcode))
{
	$sql="SELECT barcode_transaction_id FROM edms_barcode_transactions WHERE item_id = $item_id AND barcode = '$barcode'";
	$result = dbQuery($sql);
	if(dbNumRows($result)>0)
	{
	$resultArray = dbResultToArray($result);
	return $resultArray[0][0];
	}
	else return false;
}
	
}

function deleteInventoryBarcodeTransactionByTransId($trans_id,$trans_type,$check_for_in_use=1)
{
	if(checkForNumeric($trans_id,$trans_type))
	{
		if($check_for_in_use==1)
		{
			$all_barcode_transactions=getBarcodesFromTransIdAndTransType($trans_id,$trans_type);
			foreach($all_barcode_transactions as $barcode_transaction)
			{
			     $latest_transaction=  getLatestTransactionForBarcode($barcode_transaction['barcode']);
				 
				 if($latest_transaction['trans_id'] == $trans_id && $trans_type ==$latest_transaction['trans_type'])
				 {
					 
				 }
				 else
				 return false;
			}
		}
		$sql="DELETE FROM edms_barcode_transactions WHERE trans_id = $trans_id AND trans_type = $trans_type";
		dbQuery($sql);
		return true;
	}
	return false;
	
}

function deleteInventoryBarcodeTransactionByTransItemId($trans_id,$trans_item_id,$trans_type)
{
	if(checkForNumeric($trans_id,$trans_item_id,$trans_type))
	{
		$sql="DELETE FROM edms_barcode_transactions WHERE trans_item_id = $trans_item_id AND trans_id = $trans_id AND trans_type = $trans_type";
		dbQuery($sql);
		return true;
	}
	return false;
	
}	

function deleteInventoryBarcodeTransactionByBarcode($barcode,$trans_id,$trans_type)
{
	if(checkForNumeric($trans_id,$trans_type))
	{
		$sql="DELETE FROM edms_barcode_transactions WHERE barcode = '$barcode' AND trans_id = $trans_id AND trans_type = $trans_type";
		dbQuery($sql);
		return true;
	}
	return false;
	
}		

function getBarcodeStockInventoryItems($trans_date,$type=0,$oc_id=NULL) // type = 0 means in stock, 1 means not in stock
{
	if($type==1)
	$stock_string = "1,3,5";
	else
	$stock_string  = "2,4,6";
	
	if(!checkForNumeric($oc_id))
	{
		$our_company_id=$_SESSION['edmsAdminSession']['oc_id'];
	}
	else
	$our_company_id = $oc_id;
	if(isset($trans_date) && validateForNull($trans_date))
			{
		    $trans_date = str_replace('/', '-', $trans_date);
			$trans_date=date('Y-m-d',strtotime($trans_date));
			}	
	
	$sql="SELECT * FROM (SELECT  barcode, MAX(barcode_transaction_id), MAX(trans_date), ( SELECT trans_type FROM edms_barcode_transactions as inner_barcode_table WHERE edms_barcode_transactions.item_id = inner_barcode_table.item_id ORDER BY trans_date DESC,trans_date_added DESC LIMIT 0,1) as trans_type, edms_inventory_item.item_id , CONCAT(item_name, ' | ' , IF(alias!='',CONCAT(alias,' | '),'') , IF(mrp!='',CONCAT(mrp,' | '),'') , IF(item_code!='',CONCAT(item_code,' | '),''), IF(mfg_item_code!='NA',CONCAT(mfg_item_code,' | '),''), IF(edms_inventory_item.manufacturer_id IS NULL,'',CONCAT(manufacturer_name,' | ')), barcode) as full_item_name FROM edms_barcode_transactions  INNER JOIN edms_inventory_item ON edms_inventory_item.item_id = edms_barcode_transactions.item_id LEFT OUTER JOIN edms_item_manufacturer
		  ON edms_inventory_item.manufacturer_id=edms_item_manufacturer.manufacturer_id  WHERE trans_date <= '$trans_date' AND oc_id = $our_company_id GROUP BY barcode,item_id  ORDER BY trans_date DESC,trans_date_added DESC) trans_table WHERE trans_type IN ($stock_string)";
	// 1= purchase, 3= credit note, 5 = inward inventory
	$result = dbQuery($sql);
	if(dbNumRows($result)>0)
	{
	$resultArray = dbResultToArray($result);
	return $resultArray;
	}
}		

function checkIfBarcodeInUse($barcode,$oc_id=NULL)
{
	if(!checkForNumeric($oc_id))
	{
		$oc_id=$_SESSION['edmsAdminSession']['oc_id'];
	}
	if(validateForNull($barcode) && checkForNumeric($oc_id))
	{
	$sql="SELECT COUNT(barcode) FROM edms_barcode_transactions WHERE barcode = '$barcode' AND oc_id = $oc_id GROUP BY barcode ";
	$result = dbQuery($sql);
	if(dbNumRows($result)>0)
	{
	$resultArray = dbResultToArray($result);
	return $resultArray[0][0];
	}
	else
	return 0;
	}
	return 0;
}

function getNoOfUsedBarcodeForOpeningItem($item_id,$oc_id=NULL)
{
	$i=0;
		$j=0;
	if(!checkForNumeric($oc_id))
	{
		$oc_id=$_SESSION['edmsAdminSession']['oc_id'];
	}
	if(checkForNumeric($oc_id,$item_id))
	{
		
		$barcodes=getBarcodesFromTransIdAndTransType($item_id,7);
		foreach($barcodes as $barcode)
		{
			$barcode = $barcode[0];
			if(checkIfBarcodeInUse($barcode)>1)
			$i++;
			else
			$j++;
		}
	}
	return array($i,$j); // first is used 
}

function getUsedBarcodeForTransaction($trans_id,$trans_type,$oc_id=NULL)
{
	$return_array = array();
	if(!checkForNumeric($oc_id))
	{
		$oc_id=$_SESSION['edmsAdminSession']['oc_id'];
	}
	
	if(checkForNumeric($oc_id,$trans_id))
	{
		
		$barcodes=getBarcodeTransactionsFromTransIdAndTransType($trans_id,$trans_type);
		
		foreach($barcodes as $barcod)
		{
			$barcode = $barcod['barcode'];
			$latest_transaction = getLatestTransactionForBarcode($barcode);
			if($latest_transaction['trans_id']==$trans_id && $latest_transaction['trans_type']==$trans_type)
			{}
			else
			{
				$return_array[] = $barcod;
			}
		}
	}
	return $return_array;  
}


function getUsedBarcodeForTransactionItemWise($trans_id,$trans_type,$oc_id=NULL)
{
	
	$return_array = array();
	if(!checkForNumeric($oc_id))
	{
		$oc_id=$_SESSION['edmsAdminSession']['oc_id'];
	}
	
	if(checkForNumeric($oc_id,$trans_id,$trans_type))
	{
		
		$barcodes=getBarcodeTransactionsFromTransIdAndTransType($trans_id,$trans_type);
		
		foreach($barcodes as $barcod)
		{
			$barcode = $barcod['barcode'];
			$item_id = $barcod['item_id'];
			$latest_transaction = getLatestTransactionForBarcode($barcode);
			if($latest_transaction['trans_id']==$trans_id && $latest_transaction['trans_type']==$trans_type)
			{}
			else
			{
				$return_array[$item_id][] = $barcod;
			}
		}
	}
	return $return_array;  
}

function getUnUsedBarcodeForTransactionItemWise($trans_id,$trans_type,$oc_id=NULL)
{
	
	$return_array = array();
	if(!checkForNumeric($oc_id))
	{
		$oc_id=$_SESSION['edmsAdminSession']['oc_id'];
	}
	
	if(checkForNumeric($oc_id,$trans_id,$trans_type))
	{
		
		$barcodes=getBarcodeTransactionsFromTransIdAndTransType($trans_id,$trans_type);
		
		foreach($barcodes as $barcod)
		{
			$barcode = $barcod['barcode'];
			$item_id = $barcod['item_id'];
			$latest_transaction = getLatestTransactionForBarcode($barcode);
			if($latest_transaction['trans_id']==$trans_id && $latest_transaction['trans_type']==$trans_type)
			{
				$return_array[$item_id][] = $barcod;	
			}
			else
			{
			
			}
		}
	}
	return $return_array;  
}

function CheckIfLatestTransactionForBarcode($barcode,$trans_id,$trans_type,$oc_id=NULL)
{
	if(USE_BARCODE==0)
	return true;
	if(!checkForNumeric($oc_id))
	{
		$oc_id=$_SESSION['edmsAdminSession']['oc_id'];
	}
	if(validateForNull($barcode) && checkForNumeric($oc_id,$trans_id,$trans_type))
	{
	$sql="SELECT barcode_transaction_id,trans_type,trans_id,trans_item_id FROM edms_barcode_transactions WHERE barcode = '$barcode' AND          oc_id = $oc_id ORDER BY trans_date DESC, trans_date_added DESC ";
	$result = dbQuery($sql);
	
	if(dbNumRows($result)>0)
	{
	$resultArray = dbResultToArray($result);
	if($resultArray[0]['trans_id']==$trans_id && $trans_type == $resultArray[0]['trans_type'])
	{
		return true;
	}
	return false;
	}
	else
	return false;
	}
	return false;	
	
}

function getLatestTransactionForBarcode($barcode,$oc_id=NULL)
{
	if(!checkForNumeric($oc_id))
	{
		$oc_id=$_SESSION['edmsAdminSession']['oc_id'];
	}
	if(validateForNull($barcode) && checkForNumeric($oc_id))
	{
	$sql="SELECT barcode_transaction_id,trans_type,trans_id,trans_item_id FROM edms_barcode_transactions WHERE barcode = '$barcode' AND oc_id = $oc_id ORDER BY trans_date DESC,trans_date_added DESC LIMIT 0,1";
	$result = dbQuery($sql);
	
	if(dbNumRows($result)>0)
	{
	$resultArray = dbResultToArray($result);
	
	return $resultArray[0];
	}
	else
	return false;
	}
	return false;	
	
}

function getSucceddingTransactionForBarcode($barcode,$trans_id,$trans_type,$oc_id=NULL)
{
if(!checkForNumeric($oc_id))
	{
		$oc_id=$_SESSION['edmsAdminSession']['oc_id'];
	}
	if(validateForNull($barcode) && checkForNumeric($oc_id,$trans_id,$trans_type))
	{	
	$sql="SELECT barcode_transaction_id,trans_type,trans_id,trans_item_id,trans_date FROM edms_barcode_transactions WHERE barcode = '$barcode' AND oc_id = $oc_id AND trans_date >= (SELECT trans_date FROM edms_barcode_transactions WHERE barcode='$barcode' AND trans_id = $trans_id AND trans_type = $trans_type) AND trans_date_added > (SELECT trans_date_added FROM edms_barcode_transactions WHERE barcode='$barcode' AND trans_id = $trans_id AND trans_type = $trans_type)  ORDER BY trans_date,trans_date_added";
	$result = dbQuery($sql);
	if(dbNumRows($result)>0)
	{
	$resultArray = dbResultToArray($result);
	return $resultArray[0];
	}
	else
	return false;
	}
	return 0;	
	
}
function checkCurrentStatusForBarcode($barcode,$oc_id=NULL)
{
	if(!checkForNumeric($oc_id))
	{
		$oc_id=$_SESSION['edmsAdminSession']['oc_id'];
	}
	if(validateForNull($barcode) && checkForNumeric($oc_id))
	{
	$sql="SELECT trans_type FROM edms_barcode_transactions WHERE barcode = '$barcode' AND oc_id = $oc_id ORDER BY trans_date DESC,trans_date_added DESC ";
	$result = dbQuery($sql);
	if(dbNumRows($result)>0)
	{
	$resultArray = dbResultToArray($result);
	return $resultArray[0][0];
	}
	else
	return 0;
	}
	return 0;
}

function checkOldUsedItemsAndNewItemsForEditTransaction($item_id_array,$sales_id,$trans_date,$trans_type,$rate_array,$quantity_array,$discount_array,$tax_group_array)
{ 
	
	if(validateForNull($trans_date) && checkForNumeric($trans_type,$sales_id))
	{
	    $new_item_id_array = array_filter($item_id_array);
		$used_barcode_transactions = getUsedBarcodeForTransaction($sales_id,$trans_type);
		$used_barcode_transactions_to_match_array = array();
		$i=0;
		$transaction_date = $trans_date;
		if(isset($transaction_date) && validateForNull($transaction_date))
			{
		    $transaction_date = str_replace('/', '-', $transaction_date);
			$transaction_date=date('Y-m-d',strtotime($transaction_date));
			}	
		foreach($used_barcode_transactions as $used_barcode_transaction)
		{
			$used_barcode_transactions_to_match_array[$i][]=$used_barcode_transaction['item_id'];
			$used_barcode_transactions_to_match_array[$i][]=$used_barcode_transaction['barcode'];
			$i++;
			$corresponding_key = -1;
			
			$corresponding_key= array_search(array($used_barcode_transaction['item_id'],$used_barcode_transaction['barcode']),$item_id_array);
			
			
			
			$succedding_transaction = getSucceddingTransactionForBarcode($used_barcode_transaction['barcode'],$used_barcode_transaction['trans_id'],$used_barcode_transaction['trans_type']);
			
			$succedding_transaction_date = date('Y-m-d',strtotime($succedding_transaction['trans_date']));
			
			if(strtotime($transaction_date)>strtotime($succedding_transaction_date))
			{
			return "succedding_transaction_error";
			}
			
			
			if(!$corresponding_key || $corresponding_key<0)
			{
				
				return "barcode_item_used_error";
			}
			else if($corresponding_key>0)
			{	
				$rate=$rate_array[$corresponding_key];
				
				$quantity=$quantity_array[$corresponding_key];
				
				if(!in_array($trans_type,array(5,8)))
				$discount=$discount_array[$corresponding_key];
				else 
				$discount=0;
				
				if(!in_array($trans_type,array(5,8)))
				$tax_group_id = $tax_group_array[$corresponding_key];
				else 
				$tax_group_id = 0;
				
				if(checkForNumeric($rate,$quantity,$discount,$tax_group_id) && $rate>=0 && $discount>=0 && $quantity>0 && $tax_group_id>=0)
				{
					
				}
				else
				{
					
				return "barcode_item_used_error";
				}
			} // else if
		} // foreach
	return "success";
	} // if
	else
	return "error";
}
?>