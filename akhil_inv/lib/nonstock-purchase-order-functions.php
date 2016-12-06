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
require_once("account-purchase-order-functions.php");
require_once("tax-functions.php");
require_once("delivery-challan-functions.php");
require_once("our-company-function.php");
require_once("common.php");
require_once("bd.php");

function updateNonStockItemPurchaseOrder($purchase_order_id,$item_id_array,$amount_array,$discount_array,$trans_date,$delivery_date,$to_ledger,$from_ledger,$remarks,$tax_group_array,$purchase_order_ref="NA",$purchase_order_ref_type=0)
{
	if(checkForNumeric($purchase_order_id))
	{
		$nett_amount = checkForNSItemsInArray($item_id_array,$amount_array,$discount_array,$tax_group_array);
	
	if($nett_amount && checkForNumeric($nett_amount) && $nett_amount>=0)
	{
	deleteNonStockItemsForPurchaseOrder($purchase_order_id);	
	updatePurchaseOrder($purchase_order_id,$nett_amount,$trans_date,$trans_date,$to_ledger,$from_ledger,$remarks,$purchase_order_ref,$purchase_order_ref_type);
	insertNonStocksToPurchaseOrder($item_id_array,$amount_array,$discount_array,$purchase_order_id,$tax_group_array);
	return "success";
	}
	return "error";
		
		
	}
}



function insertNonStockPurchaseOrder($item_id_array,$amount_array,$discount_array,$trans_date,$delivery_date,$to_ledger,$from_ledger,$remarks,$tax_group_array,$purchase_order_ref="NA",$purchase_order_ref_type=0)
{
	
	
	$nett_amount = checkForNSItemsInArray($item_id_array,$amount_array,$discount_array,$tax_group_array);
	
	if($nett_amount && checkForNumeric($nett_amount) && $nett_amount>0)
	{
	$purchase_order_id = addPurchaseOrder($nett_amount,$trans_date,$trans_date,$to_ledger,$from_ledger,$remarks,2,0,$purchase_order_ref,$purchase_order_ref_type);
	if(checkForNumeric($purchase_order_id))
	{
	insertNonStocksToPurchaseOrder($item_id_array,$amount_array,$discount_array,$purchase_order_id,$tax_group_array);
	}
	else
	return "error";
	
	return $purchase_order_id;
	}
	return "error";
}

function insertNonStockToPurchaseOrder($item_id,$amount,$discount,$purchase_order_id,$tax_group_id)
{
	
	if(checkForNumeric($item_id,$amount,$discount,$tax_group_id) &&  $item_id>0  && $discount>=0  && $tax_group_id>=0)
			{
				
			
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
				
				
				$sql="INSERT INTO edms_ac_purchase_order_nonstock (item_id,amount,discount,net_amount,purchase_order_id,created_by,last_updated_by,date_added,date_modified) VALUES ($item_id,$amount,$discount,$nett_amount,$purchase_order_id,$admin_id,$admin_id,NOW(),NOW())";
				dbQuery($sql);
				return dbInsertId();
			}	
		return false;		
}

function deleteNonStockItemsForPurchaseOrder($purchase_order_id)
{
	if(checkForNumeric($purchase_order_id))
	{
		deleteTaxForPurchaseOrder($purchase_order_id);
		$sql="DELETE FROM edms_ac_purchase_order_nonstock WHERE purchase_order_id = $purchase_order_id";
		dbQuery($sql);
		return "success";
	}	
	return "error";
}

function insertNonStocksToPurchaseOrder($item_id_array,$amount_array,$discount_array,$purchase_order_id,$tax_group_array)
{
	
	if(is_array($item_id_array) && count($item_id_array)>0)
	{
		for($i=0;$i<count($item_id_array);$i++)
		{
			$item_id=$item_id_array[$i];
			$amount=$amount_array[$i];
			$discount=$discount_array[$i];
			$tax_group_id = $tax_group_array[$i];

			
						
			if(checkForNumeric($item_id,$amount,$discount,$purchase_order_id,$tax_group_id) && $item_id>0 && $amount>=0 && $discount>=0 &&  $tax_group_id>=0)
			{
				$net_amount = $amount - $amount*($discount/100);
				
				$purchase_order_item_id=insertNonStockToPurchaseOrder($item_id,$amount,$discount,$purchase_order_id,$tax_group_id);
				
				if($tax_group_id>0)
				insertTaxToPurchaseOrder($purchase_order_id,$purchase_order_item_id,$tax_group_id,$net_amount,true);
			}	
			
		}
		
		return "success";
				
	}
	
	return "error";
	
}
	
?>