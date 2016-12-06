<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("image-functions.php");
require_once("inventory-item-functions.php");
require_once("item-type-functions.php");
require_once("item-manufacturer-functions.php");
require_once("tax-functions.php");
require_once("godown-functions.php");
require_once("account-ledger-functions.php");
require_once("account-debit-note-functions.php");
require_once("nonstock-debit-note-functions.php");
require_once("tax-functions.php");
require_once("delivery-challan-functions.php");
require_once("our-company-function.php");
require_once("common.php");
require_once("bd.php");

function checkForItemsInArrayDebitNote($item_id_array,$rate_array,$quantity_array,$discount_array,$godown_id_array,$tax_group_id_array)
{
	$total_amount=0;
	$has_items=false;
	if(is_array($item_id_array) && count($item_id_array)>0)
	{
		for($i=0;$i<count($item_id_array);$i++)
		{
			$item_id=$item_id_array[$i];
			$rate=$rate_array[$i];
			$quantity=$quantity_array[$i];
			$discount=$discount_array[$i];
			$godown_id = $godown_id_array[$i];
			$tax_group_id = $tax_group_id_array[$i];
			
			
			
			
			if(checkForNumeric($item_id,$rate,$quantity,$discount,$godown_id,$tax_group_id) && $godown_id>0 && $item_id>0 && $rate>=0 && $discount>=0 && $quantity>0)
			{
				
				
				$amount = ($rate - ($rate*($discount/100)))*$quantity;
				
				$total_amount = $total_amount + $amount;
				
				if($tax_group_id>0)
				{
				$tax_group = getTaxGroupByID($tax_group_id);
				if($tax_group['in_out']==2)
				{
					$total_tax_percent = getTotalTaxPercentForTaxGroup($tax_group_id);
					$total_tax_amount = ($amount*($total_tax_percent/100));
					
					$total_amount = $total_amount + $total_tax_amount;	
				}
				}
				$has_items = $total_amount;
			}	
			
		}
				
	}
	return $has_items;
	
	}

function updateInventoryNonStockItemDebitNote($debit_note_id,$item_id_array,$rate_array,$quantity_array,$discount_array,$item_id_ns_array,$amount_ns_array,$discount_ns_array,$trans_date,$delivery_date,$to_ledger,$from_ledger,$remarks,$godown_id_array,$tax_group_array,$tax_group_ns_array,$sales_ref="NA",$sales_ref_type=0)
{
	if(checkForNumeric($debit_note_id))
	{
		$nett_amount = checkForItemsInArrayDebitNote($item_id_array,$rate_array,$quantity_array,$discount_array,$godown_id_array,$tax_group_array);
	$nett_amount_ns = checkForNSItemsInArrayDebitNote($item_id_ns_array,$amount_ns_array,$discount_ns_array,$tax_group_ns_array);
	
	
	if(($nett_amount && checkForNumeric($nett_amount) && $nett_amount>=0) || ($nett_amount_ns && checkForNumeric($nett_amount_ns) && $nett_amount_ns>=0))
	{
	$total_nett_amount=0;	
	if($nett_amount && checkForNumeric($nett_amount) && $nett_amount>=0)
	$total_nett_amount = $total_nett_amount + $nett_amount;
	if($nett_amount_ns && checkForNumeric($nett_amount_ns) && $nett_amount_ns>=0)
	$total_nett_amount = $total_nett_amount + $nett_amount_ns;
	
	deleteInventoryItemsForDebitNote($debit_note_id);	
	deleteNonStockItemsForDebitNote($debit_note_id);	
	
	updateDebitNote($debit_note_id,$total_nett_amount,$trans_date,$trans_date,$to_ledger,$from_ledger,$remarks,2,0,$sales_ref,$sales_ref_type);
	insertInventoryItemsToDebitNote($item_id_array,$rate_array,$quantity_array,$discount_array,$debit_note_id,$tax_group_array,$godown_id_array);
	insertNonStocksToDebitNote($item_id_ns_array,$amount_ns_array,$discount_ns_array,$debit_note_id,$tax_group_ns_array);

	return "success";
	}
	return "error";
		
		
	}
}

function updateInventoryItemDebitNote($debit_note_id,$item_id_array,$rate_array,$quantity_array,$discount_array,$trans_date,$delivery_date,$to_ledger,$from_ledger,$remarks,$godown_id_array,$tax_group_array,$sales_ref="NA",$sales_ref_type=0)
{
	if(checkForNumeric($debit_note_id))
	{
		$nett_amount = checkForItemsInArrayDebitNote($item_id_array,$rate_array,$quantity_array,$discount_array,$godown_id_array,$tax_group_array);
	
	
	if(($nett_amount && checkForNumeric($nett_amount) && $nett_amount>=0))
	{
	
	
	deleteInventoryItemsForDebitNote($debit_note_id);	
	
	
	updateDebitNote($debit_note_id,$nett_amount,$trans_date,$trans_date,$to_ledger,$from_ledger,$remarks,2,0,$sales_ref,$sales_ref_type);
	insertInventoryItemsToDebitNote($item_id_array,$rate_array,$quantity_array,$discount_array,$debit_note_id,$tax_group_array,$godown_id_array);

	return "success";
	}
	return "error";
		
		
	}
}


function insertInventoryNonStockItemDebitNote($item_id_array,$rate_array,$quantity_array,$discount_array,$item_id_ns_array,$amount_ns_array,$discount_ns_array,$trans_date,$delivery_date,$to_ledger,$from_ledger,$remarks,$godown_id_array,$tax_group_array,$tax_group_ns_array,$debit_note_ref="NA",$debit_note_ref_type=0)
{
	
	
	$nett_amount = checkForItemsInArrayDebitNote($item_id_array,$rate_array,$quantity_array,$discount_array,$godown_id_array,$tax_group_array);
	$nett_amount_ns = checkForNSItemsInArrayDebitNote($item_id_ns_array,$amount_ns_array,$discount_ns_array,$tax_group_ns_array);
	
	if(($nett_amount && checkForNumeric($nett_amount) && $nett_amount>=0) || ($nett_amount_ns && checkForNumeric($nett_amount_ns) && $nett_amount_ns>=0))
	{
	$total_nett_amount=0;	
	if($nett_amount && checkForNumeric($nett_amount) && $nett_amount>=0)
	$total_nett_amount = $total_nett_amount + $nett_amount;
	if($nett_amount_ns && checkForNumeric($nett_amount_ns) && $nett_amount_ns>=0)
	$total_nett_amount = $total_nett_amount + $nett_amount_ns;
	
	$debit_note_id = addDebitNote($total_nett_amount,$trans_date,$trans_date,$to_ledger,$from_ledger,$remarks,2,0,$debit_note_ref,$debit_note_ref_type);
	if(checkForNumeric($debit_note_id))
	{
	insertNonStocksToDebitNote($item_id_ns_array,$amount_ns_array,$discount_ns_array,$debit_note_id,$tax_group_ns_array);
		
	insertInventoryItemsToDebitNote($item_id_array,$rate_array,$quantity_array,$discount_array,$debit_note_id,$tax_group_array,$godown_id_array);
	
	}
	else
	return "error";
	
	return $debit_note_id;
	}
	return "error";
}

function insertInventoryItemDebitNote($item_id_array,$rate_array,$quantity_array,$discount_array,$trans_date,$delivery_date,$to_ledger,$from_ledger,$remarks,$godown_id_array,$tax_group_array,$debit_note_ref="NA",$debit_note_ref_type=0)
{
	
	
	$nett_amount = checkForItemsInArrayDebitNote($item_id_array,$rate_array,$quantity_array,$discount_array,$godown_id_array,$tax_group_array);
	
	if($nett_amount && checkForNumeric($nett_amount) && $nett_amount>0)
	{
	$debit_note_id = addDebitNote($nett_amount,$trans_date,$trans_date,$to_ledger,$from_ledger,$remarks,2,0,$debit_note_ref,$debit_note_ref_type);
	if(checkForNumeric($debit_note_id))
	{
	insertInventoryItemsToDebitNote($item_id_array,$rate_array,$quantity_array,$discount_array,$debit_note_id,$tax_group_array,$godown_id_array);
	}
	else
	return "error";
	
	return $debit_note_id;
	}
	return "error";
}

function insertInventoryItemToDebitNote($item_id,$rate,$quantity,$discount,$debit_note_id,$godown_id,$tax_group_id)
{
	if(checkForNumeric($item_id,$rate,$quantity,$discount,$godown_id,$tax_group_id) && $godown_id>0 && $item_id>0 && $rate>=0 && $discount>=0 && $quantity>0 && $tax_group_id>=0)
			{
				
				$amount = $quantity * $rate;
				$nett_amount = $amount - $amount*($discount/100);
				
				if($tax_group_id>0)
				{
				$tax_group = getTaxGroupByID($tax_group_id);
				if($tax_group['in_out']==2)
				{
					$total_tax_percent = getTotalTaxPercentForTaxGroup($tax_group_id);
					$total_tax_amount = ($nett_amount*($total_tax_percent/100));
					$nett_amount = $nett_amount + $total_tax_amount;	
					
				}
				}
				
				$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
				
				
				$sql="INSERT INTO edms_ac_debit_note_item (item_id,rate,quantity,discount,amount,net_amount,debit_note_id,godown_id,created_by,last_updated_by,date_added,date_modified) VALUES ($item_id,$rate,$quantity,$discount,$amount,$nett_amount,$debit_note_id,$godown_id,$admin_id,$admin_id,NOW(),NOW())";
				dbQuery($sql);
				return dbInsertId();
			}	
		return false;	
	
}

function deleteInventoryItemsForDebitNote($debit_note_id)
{
	if(checkForNumeric($debit_note_id))
	{
		deleteTaxForDebitNote($debit_note_id);
		$sql="DELETE FROM edms_ac_debit_note_item WHERE debit_note_id = $debit_note_id";
		dbQuery($sql);
		return "success";
	}	
	return "error";
}

function insertInventoryItemsToDebitNote($item_id_array,$rate_array,$quantity_array,$discount_array,$debit_note_id,$tax_group_array,$godown_id_array)
{
	
	if(is_array($item_id_array) && count($item_id_array)>0)
	{
		for($i=0;$i<count($item_id_array);$i++)
		{
			$item_id=$item_id_array[$i];
			$rate=$rate_array[$i];
			$quantity=$quantity_array[$i];
			$discount=$discount_array[$i];
			$tax_group_id = $tax_group_array[$i];
			$godown_id = $godown_id_array[$i];
			
						
			if(checkForNumeric($item_id,$rate,$quantity,$discount,$debit_note_id,$godown_id,$tax_group_id) && $godown_id>0 && $item_id>0 && $rate>=0 && $discount>=0 && $quantity>0 && $tax_group_id>=0)
			{
				$net_amount = ( ($rate - $rate*($discount/100))*$quantity);
				
				
				$debit_note_item_id=insertInventoryItemToDebitNote($item_id,$rate,$quantity,$discount,$debit_note_id,$godown_id,$tax_group_id);
				
				if($tax_group_id>0)
				insertTaxToDebitNote($debit_note_id,$debit_note_item_id,$tax_group_id,$net_amount);
			}	
			
		}
		
		return "success";
				
	}
	
	return "error";
	
}

function getInventoryItemForDebitNoteId($sales_id)
{
	if(checkForNumeric($sales_id))
	{
		$return_array = array();
		$sql="SELECT edms_ac_debit_note_item.debit_note_item_id,item_id,rate,quantity,discount,amount,net_amount,edms_ac_debit_note_item.debit_note_id,godown_id,created_by,last_updated_by,date_added,date_modified, edms_tax_grp.tax_group_id, tax_group_name, in_out, SUM(tax_amount) as tax_amount FROM edms_ac_debit_note_item 
		LEFT JOIN edms_ac_debit_note_tax ON edms_ac_debit_note_item.debit_note_item_id = edms_ac_debit_note_tax.debit_note_item_id 
		LEFT JOIN edms_tax_grp ON edms_tax_grp.tax_group_id = edms_ac_debit_note_tax.tax_group_id  WHERE edms_ac_debit_note_item.debit_note_id = $sales_id GROUP BY edms_ac_debit_note_item.debit_note_item_id";	
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
			$i=0;
			foreach($resultArray as $re)
			{
				
				$sales_item_id = $re['debit_note_item_id'];
				$tax = getTaxForDebitNoteItemId($sales_item_id);
				$return_array[$i]['debit_note_item_details'] = $re;
				$return_array[$i]['tax_details'] = $tax;
				$i++;
			}
			return $return_array;
		}
	}
	
}	

function getNonStockItemForDebitNoteId($sales_id)
{
	if(checkForNumeric($sales_id))
	{
		$return_array = array();
		$sql="SELECT  edms_ac_debit_note_nonstock.debit_note_non_stock_id,item_id,discount,amount,net_amount,edms_ac_debit_note_nonstock.debit_note_id,created_by,last_updated_by,date_added,date_modified, edms_tax_grp.tax_group_id, tax_group_name, in_out, SUM(tax_amount) as tax_amount  FROM edms_ac_debit_note_nonstock 
		LEFT JOIN edms_ac_debit_note_tax ON edms_ac_debit_note_nonstock.debit_note_non_stock_id = edms_ac_debit_note_tax.debit_note_non_stock_id 
		LEFT JOIN edms_tax_grp ON edms_tax_grp.tax_group_id = edms_ac_debit_note_tax.tax_group_id  WHERE edms_ac_debit_note_nonstock.debit_note_id = $sales_id GROUP BY edms_ac_debit_note_nonstock.debit_note_non_stock_id";	
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
			$i=0;
			foreach($resultArray as $re)
			{
				$sales_item_id = $re['debit_note_non_stock_id'];
				$tax = getTaxForDebitNoteItemId($sales_item_id,true);
				$return_array[$i]['debit_note_item_details'] = $re;
				$return_array[$i]['tax_details'] = $tax;
				$i++;
			}
			return $return_array;
		}
	}
	
}	

function getTaxForDebitNoteItemId($sales_item_id,$non_stock=false)
{
	if(checkForNumeric($sales_item_id))
	{
		$sql="SELECT debit_note_id, edms_tax_grp.tax_group_id, tax_group_name, SUM(tax_amount) as tax_amount, edms_tax.tax_id, CONCAT(IF(edms_tax.in_out>0,'OUT','IN'), ' ', tax_name) as tax_name_in_out, tax_name, edms_tax.in_out, tax_percent FROM edms_ac_debit_note_tax, edms_tax_grp, edms_tax WHERE edms_tax_grp.tax_group_id = edms_ac_debit_note_tax.tax_group_id AND edms_tax.tax_id = edms_ac_debit_note_tax.tax_id AND
		";
		if(!$non_stock)
		$sql=$sql." edms_ac_debit_note_tax.debit_note_item_id = $sales_item_id GROUP BY tax_id";
		else if($non_stock)
		$sql=$sql." edms_ac_debit_note_tax.debit_note_non_stock_id = $sales_item_id GROUP BY tax_id";
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray;
		else
		return false;
		
		}
	return false;
}

	
?>