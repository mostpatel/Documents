<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("image-functions.php");
require_once("inventory-item-functions.php");
require_once("inventory-item-barcode-functions.php");
require_once("item-type-functions.php");
require_once("item-unit-functions.php");
require_once("item-manufacturer-functions.php");
require_once("tax-functions.php");
require_once("godown-functions.php");
require_once("account-ledger-functions.php");
require_once("account-sales-functions.php");
require_once("inventory-sales-functions.php");
require_once("nonstock-sales-functions.php");
require_once("tax-functions.php");
require_once("delivery-challan-functions.php");
require_once("our-company-function.php");
require_once("common.php");
require_once("bd.php");

function checkForJvItemsInArray($item_id_array,$rate_array,$quantity_array,$godown_id_array,$trans_type=NULL,$trans_id_if_update=NULL,$unit_id_array=NULL)
{
	$total_amount=false;
	$has_items=false;
	
	if(checkIfDuplicateBarcodesInArray($item_id_array))
	return "barcode_transaction_error";
	
	
	if(is_array($item_id_array) && count($item_id_array)>0)
	{
		
		for($i=0;$i<count($item_id_array);$i++)
		{
			
			$item_id=$item_id_array[$i];
			
			if(!is_numeric($item_id) && is_array($item_id) && checkForNumeric($item_id[0]))
			{
				$barcode = $item_id[1];
				$item_id=$item_id[0];
			}
			
			$rate = $rate_array[$i];
			$quantity=$quantity_array[$i];
			$godown_id=$godown_id_array[$i];
			$unit_id = $unit_id_array[$i];
			if(checkForNumeric($item_id,$rate,$quantity,$godown_id) && $item_id>0 && $rate>=0 && $quantity>0 && $godown_id>0)
			{
				if(!$has_items)
				$has_items=0;
			
				$item= getInventoryItemById($item_id);
				$rate=$rate_array[$i];
				if($item['use_barcode']==0)
				{}
				else
				$quantity=1;
				
				
				
				if(USE_BARCODE==1)
				{
					if(validateForNull($barcode))
					{
						$latest_transaction=getLatestTransactionForBarcode($barcode);
						
						if($latest_transaction && is_array($latest_transaction) && is_numeric($latest_transaction['trans_type']))	
						{
							$latest_trans_type = $latest_transaction['trans_type'];
							
							if($latest_trans_type%2==0)
							{
								if($trans_type%2==0)
								{
									
									if(!is_numeric($trans_id_if_update) || ($trans_id_if_update!=$latest_transaction['trans_id'] && $trans_type!=$latest_trans_type))
									{
										if(!is_numeric($trans_id_if_update))	
										return "barcode_transaction_error";	
										else 
										{
											$used_barcodes=getUsedBarcodeForTransaction($trans_id_if_update,$trans_type);
											if(in_array($barcode,$used_barcodes))
											{
												
											}
											else
											return "barcode_transaction_error";	
										}
									}
								}
							}
							else if($latest_trans_type%2==1)
							{
								
								
								if($trans_type%2==1)
								{
									if(!is_numeric($trans_id_if_update) || ($trans_id_if_update!=$latest_transaction['trans_id'] && $trans_type!=$latest_trans_type))
								{
										if(!is_numeric($trans_id_if_update))	
										return "barcode_transaction_error";	
										else 
										{
											$used_barcodes=getUsedBarcodeForTransaction($trans_id_if_update,$trans_type);
											if(in_array($barcode,$used_barcodes))
											{
												
											}
											else
											return "barcode_transaction_error";	
										}
									}
								}
							}
						}
					}
				}
				
				
				$amount = ($rate)*$quantity;
				
				$total_amount = $total_amount + $amount;
			
				$has_items = $has_items + $quantity;
				
			}	
			
		}
				
	}
	return $has_items;
	
	}


function insertInventoryJV($item_id_array,$rate_array,$quantity_array,$item_id_cd_array,$rate_cd_array,$quantity_cd_array,$trans_date,$remarks,$godown_id_array,$godown_id_cd_array,$jv_type_id=NULL,$inventory_jv_mode=0,$ledger_customer_id=NULL,$unit_id_array=NULL,$unit_id_cd_array=NULL)
{
	$item_id_array = ConvertItemNameArrayInToIdArray($item_id_array);
	$item_id_cd_array = ConvertItemNameArrayInToIdArray($item_id_cd_array);
	
	if($inventory_jv_mode==1)
	$trans_type=5;
	else if($inventory_jv_mode==2)
	$trans_type=8;
	else
	$trans_type=0;
	$nett_amount_credit = checkForJvItemsInArray($item_id_array,$rate_array,$quantity_array,$godown_id_array,$trans_type,NULL,$unit_id_array);
	$nett_amount_debit = checkForJvItemsInArray($item_id_cd_array,$rate_cd_array,$quantity_cd_array,$godown_id_cd_array,$trans_type,NULL,$unit_id_array);
	
	if($nett_amount_credit=="barcode_transaction_error" || $nett_amount_debit=="barcode_transaction_error")
	return "barcode_transaction_error";
	
	if(!checkForNumeric($jv_type_id))
	$jv_type_id="NULL";
	if(substr($ledger_customer_id, 0, 1) == 'L')
	{
		$ledger_customer_id=str_replace('L','',$ledger_customer_id);
		$ledger_customer_id=intval($ledger_customer_id);
		 
		$led=getLedgerById($ledger_customer_id);
		$customer_id="NULL";
		$ledger_id = $led['ledger_id'];
		}
	else if(substr($ledger_customer_id, 0, 1) == 'C')
	{
		
		$ledger_customer_id=str_replace('C','',$ledger_customer_id);
		$to_customer=intval($ledger_customer_id);
		$ledger_id="NULL";
		
		$customer=getCustomerDetailsByCustomerId($to_customer);
		$customer_id=$customer['customer_id'];
	
		}	
		
	
	if(!checkForNumeric($customer_id))
	$customer_id="NULL";
	
	if(!checkForNumeric($ledger_id))
	$ledger_id="NULL";
	
	
	if(!$nett_amount_credit && !$nett_amount_debit)
	return "error";
		
	if(($nett_amount_debit && checkForNumeric($nett_amount_debit) && $nett_amount_debit>=0) || ($nett_amount_credit && checkForNumeric($nett_amount_credit) && $nett_amount_credit>=0) && validateForNull($trans_date))
	{
	
	  $admin_id=$_SESSION['edmsAdminSession']['admin_id'];
	
	  $oc_id =$_SESSION['edmsAdminSession']['oc_id'];
		if(isset($trans_date) && validateForNull($trans_date))
			{
		    $trans_date = str_replace('/', '-', $trans_date);
			$trans_date=date('Y-m-d',strtotime($trans_date));
			}
	
	$sql="INSERT INTO edms_inventory_jv(trans_date,remarks,oc_id,created_by,last_updated_by,date_added,date_modified,jv_type_id,inventory_jv_mode,ledger_id,customer_id) VALUES ('$trans_date','$remarks',$oc_id,$admin_id,$admin_id,NOW(),NOW(),$jv_type_id,$inventory_jv_mode,$ledger_id,$customer_id)";

	$result = dbQuery($sql);
	$inventory_jv_id = dbInsertId();
	if(checkForNumeric($inventory_jv_id))
	{
	if($nett_amount_credit && checkForNumeric($nett_amount_credit) && $nett_amount_credit>=0)	
	insertInventoryItemsToJV($item_id_array,$rate_array,$quantity_array,$inventory_jv_id,$godown_id_array,1,$unit_id_array); // credit
	
	if($nett_amount_debit && checkForNumeric($nett_amount_debit) && $nett_amount_debit>=0)
	insertInventoryItemsToJV($item_id_cd_array,$rate_cd_array,$quantity_cd_array,$inventory_jv_id,$godown_id_cd_array,0,$unit_id_cd_array); // debit
	}
	else
	return "error";
	
	return $inventory_jv_id;
	}
	return "error";
}	

function updateInventoryNonStockItemJV($inventory_jv_id,$item_id_array,$rate_array,$quantity_array,$item_id_cd_array,$rate_cd_array,$quantity_cd_array,$trans_date,$remarks,$godown_id_array,$godown_id_cd_array,$jv_type_id=NULL,$ledger_customer_id=NULL,$unit_id_array=NULL,$unit_id_cd_array=NULL)
{
	
	if(checkForNumeric($inventory_jv_id))
	{
	
	$item_id_array = ConvertItemNameArrayInToIdArray($item_id_array);
	$item_id_cd_array = ConvertItemNameArrayInToIdArray($item_id_cd_array);
	
	if(USE_BARCODE==1)
	{
		$old_inventory_jv = getInventoryJVById($inventory_jv_id);
		
		if($old_inventory_jv['inventory_jv_mode']==1)
		$trans_type = 5;
		else if($old_inventory_jv['inventory_jv_mode']==2)
		$trans_type = 8;
		else $trans_type=0;
		if($trans_type>0)
		{
		if($trans_type==5)	
		$old_new_items_clash=checkOldUsedItemsAndNewItemsForEditTransaction($item_id_cd_array,$inventory_jv_id,$trans_date,$trans_type,$rate_cd_array,$quantity_cd_array,NULL,NULL);
		else if($trans_type==8)
		$old_new_items_clash=checkOldUsedItemsAndNewItemsForEditTransaction($item_id_array,$inventory_jv_id,$trans_date,$trans_type,$rate_array,$quantity_array,NULL,NULL);
		
		if($old_new_items_clash!="success")
		return $old_new_items_clash;
		}
	}
	
	$nett_amount_credit = checkForJvItemsInArray($item_id_array,$rate_array,$quantity_array,$godown_id_array,$trans_type,$inventory_jv_id,$unit_id_array);
	$nett_amount_debit = checkForJvItemsInArray($item_id_cd_array,$rate_cd_array,$quantity_cd_array,$godown_id_cd_array,$trans_type,$inventory_jv_id,$unit_id_cd_array);
	
	if($nett_amount_credit=="barcode_transaction_error" || $nett_amount_debit=="barcode_transaction_error")
	return "barcode_transaction_error";
	
	if(!$nett_amount_credit && !$nett_amount_debit)
	return "error";
	
	if(!$nett_amount_credit)
	$nett_amount_credit=0;
	else if(!$nett_amount_debit)
	$nett_amount_debit=0;
	
	
	
	if(!checkForNumeric($jv_type_id))
	$jv_type_id="NULL";
	
	if(substr($ledger_customer_id, 0, 1) == 'L')
	{
		$ledger_customer_id=str_replace('L','',$ledger_customer_id);
		$ledger_customer_id=intval($ledger_customer_id);
		 
		$led=getLedgerById($ledger_customer_id);
		$customer_id="NULL";
		$ledger_id = $led['ledger_id'];
		}
	else if(substr($ledger_customer_id, 0, 1) == 'C')
	{
		
		$ledger_customer_id=str_replace('C','',$ledger_customer_id);
		$to_customer=intval($ledger_customer_id);
		$ledger_id="NULL";
		
		$customer=getCustomerDetailsByCustomerId($to_customer);
		$customer_id=$customer['customer_id'];
	
		}	
	
	if(!checkForNumeric($customer_id))
	$customer_id="NULL";
	
	if(!checkForNumeric($ledger_id))
	$ledger_id="NULL";
	
	
	if(($nett_amount_debit && checkForNumeric($nett_amount_debit) && $nett_amount_debit>=0) || ($nett_amount_credit && checkForNumeric($nett_amount_credit) && $nett_amount_credit>=0) && validateForNull($trans_date) && checkForNumeric($inventory_jv_id))
	{
		
	$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
	
	$oc_id =$_SESSION['edmsAdminSession']['oc_id'];
		if(isset($trans_date) && validateForNull($trans_date))
			{
		    $trans_date = str_replace('/', '-', $trans_date);
			$trans_date=date('Y-m-d',strtotime($trans_date));
			}	
	
	$sql="UPDATE edms_inventory_jv SET trans_date = '$trans_date' , remarks = '$remarks', last_updated_by = $admin_id, date_modified = NOW(), jv_type_id = $jv_type_id, ledger_id = $ledger_id,  customer_id = $customer_id WHERE inventory_jv_id = $inventory_jv_id";	
	
	$result = dbQuery($sql);
	
	deleteInventoryItemsForJv($inventory_jv_id,0);	
	
    if($nett_amount_credit && checkForNumeric($nett_amount_credit) && $nett_amount_credit>=0)	
	insertInventoryItemsToJV($item_id_array,$rate_array,$quantity_array,$inventory_jv_id,$godown_id_array,1,$unit_id_array);

	if($nett_amount_debit && checkForNumeric($nett_amount_debit) && $nett_amount_debit>=0)
	insertInventoryItemsToJV($item_id_cd_array,$rate_cd_array,$quantity_cd_array,$inventory_jv_id,$godown_id_cd_array,0,$unit_id_array);

	return "success";
	
	}
	return "error";
		
		
	}
}

function insertInventoryItemToJV($item_id,$rate,$quantity,$inventory_jv_id,$godown_id,$type=0,$barcode=NULL,$unit_id=NULL) // type 0=debit , 1=credit
{
	if(!validateForNull($type))
	$type=0;
	
	
	
	if(!checkForNumeric($unit_id) && $unit_id<0)
	$unit_id=getNotAvailableItemUnit();
	
	if(checkForNumeric($item_id,$rate,$quantity,$inventory_jv_id,$godown_id,$type,$unit_id) && $unit_id>=0 && $type>=0 && $godown_id>0 && $item_id>0 && $rate>=0 && $quantity>0)
			{
				$item = getInventoryItemById($item_id);
				if($item['use_barcode']==1 && !validateForNull($barcode))
				return false;
				
				if($item['use_barcode']==1 && !checkForNumeric(getBarcodeTransactionFromBarcode($barcode,$item_id))) 
				return false;
				
				if($item['use_barcode']==1)
				$quantity=1;
				
				$base_rate_quantity_array=ConvertRateAndQuantityForItemAndItemUnitId($rate,$quantity,$item_id,$unit_id);
				
				$base_rate = $base_rate_quantity_array[0];
				$base_quantity = $base_rate_quantity_array[1];
				
				$amount = $quantity * $rate;
	
				$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
				
				
				$sql="INSERT INTO edms_inventory_item_jv (item_id,rate,quantity,amount,inventory_jv_id,godown_id,created_by,last_updated_by,date_added,date_modified,type) VALUES ($item_id,$base_rate,$base_quantity,$amount,$inventory_jv_id,$godown_id,$admin_id,$admin_id,NOW(),NOW(),$type)";
				dbQuery($sql);
				$inventory_jv_item_id = dbInsertId();
				
				insertTransItemUnit(NULL,NULL,NULL,NULL,$inventory_jv_item_id,$unit_id,$item_id,$rate,$quantity);
				if(USE_BARCODE==1)
				{
				if($type==0)
				$barcode_trans_type = 5;
				else if($type==1) 
				$barcode_trans_type = 8;
				
				
				insertInventoryBarcodeTransaction($inventory_jv_id,$inventory_jv_item_id,$barcode_trans_type,$item_id,$quantity,$barcode);
				}
				
				return $inventory_jv_item_id;
			}	
		return false;	
	
}

function deleteInventoryItemsForJv($inventory_jv_id,$check_barcode_in_use = 1)
{
	if(checkForNumeric($inventory_jv_id))
	{
		if(USE_BARCODE==1)
		{
		$inventory_jv = getInventoryJVById($inventory_jv_id);
		
		if($inventory_jv['inventory_jv_mode']==1)
		$trans_type = 5;
		else if($inventory_jv['inventory_jv_mode']==2)
		$trans_type = 8;
		else $trans_type=0;
		
		if($trans_type>0)
		deleteInventoryBarcodeTransactionByTransId($inventory_jv_id,$trans_type,$check_barcode_in_use);
		}
		$sql="DELETE FROM edms_inventory_item_jv WHERE inventory_jv_id = $inventory_jv_id";
		dbQuery($sql);
		return "success";
	}	
	return "error";
}

function insertInventoryItemsToJV($item_id_array,$rate_array,$quantity_array,$inventory_jv_id,$godown_id_array,$type=0,$unit_id_array=NULL)
{
	
	if(!validateForNull($type))
	$type=0;
	
	if(is_array($item_id_array) && count($item_id_array)>0)
	{
		for($i=0;$i<count($item_id_array);$i++)
		{
			
				
			
			$item_id=$item_id_array[$i];
			
			if(is_array($item_id))
			{
		    $barcode = $item_id[1];	
			$item_id = intval($item_id[0]);
			}
			
			$rate=$rate_array[$i];
			$quantity=$quantity_array[$i];
			$godown_id = $godown_id_array[$i];
			$unit_id = $unit_id_array[$i];
			
			if(checkForNumeric($item_id,$rate,$quantity,$inventory_jv_id,$godown_id,$type) && $item_id>0 && $rate>=0 && $quantity>0)
			{
				
				$sales_item_id=insertInventoryItemToJV($item_id,$rate,$quantity,$inventory_jv_id,$godown_id,$type,$barcode,$unit_id);
			}	
			
		}
		return "success";
				
	}
	
	return "error";
	
}

function deleteInventoryJv($inventory_jv_id)
{
	if(checkForNumeric($inventory_jv_id))
	{
		
		if(USE_BARCODE==1)
		{
			$old_inventory_jv = getInventoryItemById($inventory_jv_id);
			if($old_inventory_jv['inventory_jv_mode']>0)
			{
			if($old_inventory_jv['inventory_jv_mode']==1)
			$trans_type = 5;
			else if($old_inventory_jv['inventory_jv_mode']==2)
			$trans_type = 8;
			else $trans_type=0;	
			$used_barcode_tansactions = getUsedBarcodeForTransactionItemWise($inventory_jv_id,$trans_type);
			$used_barcode_tansactions_item_id_array = array_keys($used_barcode_tansactions);
			
			if(is_array($used_barcode_tansactions) && count($used_barcode_tansactions)>0 && checkForNumeric($used_barcode_tansactions_item_id_array[0]))
			return "barcode_in_use_error";
			}
		}
		
		$sql="DELETE FROM edms_inventory_jv WHERE inventory_jv_id = $inventory_jv_id";
		dbQuery($sql);
		return "success";
	}
	return "error";
}
function getInventoryItemForJvId($inventory_jv_id)
{
	if(checkForNumeric($inventory_jv_id))
	{
		$return_array = array();
		$sql="SELECT edms_inventory_item_jv.inventory_item_jv_id,item_id,rate,quantity,amount,godown_id,edms_inventory_item_jv.inventory_jv_id,godown_id,edms_inventory_jv.created_by,edms_inventory_jv.last_updated_by,edms_inventory_jv.date_added,edms_inventory_jv.date_modified,type, inventory_jv_mode, jv_type_id, ledger_id, customer_id FROM edms_inventory_jv , edms_inventory_item_jv WHERE edms_inventory_item_jv.inventory_jv_id = edms_inventory_jv.inventory_jv_id AND  edms_inventory_jv.inventory_jv_id = $inventory_jv_id";	
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
			
			return $resultArray;
		}
	}
	
}	

function getDebitInventoryItemForJvId($inventory_jv_id)
{
	if(checkForNumeric($inventory_jv_id))
	{
		$return_array = array();
		$sql="SELECT edms_inventory_item_jv.inventory_item_jv_id,edms_inventory_item_jv.item_id,rate,quantity,amount,godown_id,edms_inventory_item_jv.inventory_jv_id,godown_id,edms_inventory_jv.created_by,edms_inventory_jv.last_updated_by,edms_inventory_jv.date_added,edms_inventory_jv.date_modified,type, inventory_jv_mode, jv_type_id, ledger_id, customer_id , barcode_transaction_id, barcode
		FROM edms_inventory_jv 
		INNER JOIN edms_inventory_item_jv ON edms_inventory_item_jv.inventory_jv_id = edms_inventory_jv.inventory_jv_id 
		LEFT JOIN edms_barcode_transactions ON  edms_inventory_item_jv.inventory_item_jv_id = edms_barcode_transactions.trans_item_id AND trans_type = 5 
		WHERE  edms_inventory_jv.inventory_jv_id = $inventory_jv_id AND type=0";	
	
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
			
			return $resultArray;
		}
	}
	
}	


function getCreditInventoryItemForJvId($inventory_jv_id)
{
	if(checkForNumeric($inventory_jv_id))
	{
		$return_array = array();
		$sql="SELECT edms_inventory_item_jv.inventory_item_jv_id,edms_inventory_item_jv.item_id,rate,quantity,amount,godown_id,edms_inventory_item_jv.inventory_jv_id,edms_inventory_jv.created_by,edms_inventory_jv.last_updated_by,edms_inventory_jv.date_added,edms_inventory_jv.date_modified,type, inventory_jv_mode, jv_type_id, ledger_id, customer_id , barcode_transaction_id, barcode
		FROM edms_inventory_jv 
		INNER JOIN edms_inventory_item_jv ON edms_inventory_item_jv.inventory_jv_id = edms_inventory_jv.inventory_jv_id 
		LEFT JOIN edms_barcode_transactions ON  edms_inventory_item_jv.inventory_item_jv_id = edms_barcode_transactions.trans_item_id AND trans_type = 8 
		 WHERE  edms_inventory_jv.inventory_jv_id = $inventory_jv_id AND type=1";	
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
			
			return $resultArray;
		}
	}
	
}	

function getInventoryJVById($inventory_jv_id)
{
	if(checkForNumeric($inventory_jv_id))
	{
		$sql="SELECT inventory_jv_id, trans_date, remarks, inventory_jv_mode, jv_type_id, ledger_id, customer_id, date_added FROM edms_inventory_jv WHERE inventory_jv_id = $inventory_jv_id";
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
			return $resultArray[0];
		}
	}
	
}

function getTotalDebitJvForItemIdUptoDate($item_id,$to=NULL,$model=false,$godown_id=NULL) // ledgers without cash and banks, ledger_id should start with l for ledger and c for customer
{
	
if(!$model)
{	

if(isset($to) && validateForNull($to))
{
	$to = str_replace('/', '-', $to);
		$to=date('Y-m-d',strtotime($to));
	}
	$current_company=getCurrentCompanyForUser($_SESSION['edmsAdminSession']['admin_id']);
	$oc_id = $current_company[0];
	if(checkForNumeric($item_id))
	{
	$sql="SELECT edms_inventory_item_jv.item_id,SUM(amount) as total_amount, SUM(quantity) as quantity, SUM(amount)/SUM(quantity) as avg_rate
			  FROM edms_inventory_jv, edms_inventory_item_jv WHERE type = 0 AND  edms_inventory_item_jv.inventory_jv_id = edms_inventory_jv.inventory_jv_id AND  edms_inventory_jv.oc_id = $oc_id AND ";
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(validateForNull($godown_id) && checkForNumeric($godown_id))
	$sql=$sql." godown_id = $godown_id AND ";	  
	if(!$model)	  
	$sql=$sql." item_id=$item_id GROUP BY item_id";
	else if($model)
	$sql=$sql." model_id=$item_id GROUP BY model_id";

	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray[0];
	else
	return 0; 	
	}
	return 0;
	}
	
	return 0;	
}
function getTotalCreditJvForItemIdUptoDate($item_id,$to=NULL,$model=false,$godown_id=NULL) // ledgers without cash and banks, ledger_id should start with l for ledger and c for customer
{
if(!$model)
{	
if(isset($to) && validateForNull($to))
{
	$to = str_replace('/', '-', $to);
		$to=date('Y-m-d',strtotime($to));
	}
	$current_company=getCurrentCompanyForUser($_SESSION['edmsAdminSession']['admin_id']);
	$oc_id = $current_company[0];
	if(checkForNumeric($item_id))
	{
	$sql="SELECT edms_inventory_item_jv.item_id,SUM(amount) as total_amount, SUM(quantity) as quantity, SUM(amount)/SUM(quantity) as avg_rate
			  FROM edms_inventory_jv, edms_inventory_item_jv WHERE type = 1 AND  edms_inventory_item_jv.inventory_jv_id = edms_inventory_jv.inventory_jv_id AND  edms_inventory_jv.oc_id = $oc_id AND ";
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(validateForNull($godown_id) && checkForNumeric($godown_id))
	$sql=$sql." godown_id = $godown_id AND ";	  
	if(!$model)	  
	$sql=$sql." item_id=$item_id GROUP BY item_id";
	else if($model)
	$sql=$sql." model_id=$item_id GROUP BY model_id";
	$result=dbQuery($sql);
	
	$resultArray=dbResultToArray($result);
	
	if(dbNumRows($result)>0)
	return $resultArray[0];
	else
	return 0; 	
	}
	return 0;
	}	
}
?>