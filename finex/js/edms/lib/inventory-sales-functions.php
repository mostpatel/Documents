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
require_once("account-sales-functions.php");
require_once("nonstock-sales-functions.php");
require_once("tax-functions.php");
require_once("delivery-challan-functions.php");
require_once("our-company-function.php");
require_once("common.php");
require_once("bd.php");

function checkForSalesItemsInArray($item_id_array,$rate_array,$quantity_array,$discount_array,$tax_group_id_array)
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
			$tax_group_id = $tax_group_id_array[$i];
		
			
			
			if(checkForNumeric($item_id,$rate,$quantity,$discount,$tax_group_id) && $item_id>0 && $rate>=0 && $discount>=0 && $quantity>0 && $tax_group_id>=0)
			{
				$amount = ($rate - $rate*($discount/100))*$quantity;
				
				$total_amount = $total_amount + $amount;
			
				if($tax_group_id>0)
				{
				$tax_group = getTaxGroupByID($tax_group_id);
				if($tax_group['in_out']==3) // IN SALE type tax
				{
					$total_tax_percent = getTotalTaxPercentForTaxGroup($tax_group_id);
					$base_amount = GetBaseAmountFromMRP($total_tax_percent,$amount);
					$total_tax_amount = $amount - $base_amount;
					$total_amount = $amount - $total_tax_amount;	
				}
				
				}
				
				$has_items = $total_amount;
			}	
			
		}
				
	}
	return $has_items;
	
	}

function ConvertItemNameArrayInToIdArray($item_name_array)
{
	$item_id_array = array();
	foreach($item_name_array as $item_name){
		
		$item_id=getItemIdFromFullItemName($item_name);
		if(checkForNumeric($item_id))
		$item_id_array[]=$item_id;
		else
		$item_id_array[]="";
	}
	return $item_id_array;
	
	}	
	
function insertInventoryNonStockSale($item_id_array,$rate_array,$quantity_array,$discount_array,$item_id_ns_array,$amount_ns_array,$discount_ns_array,$trans_date,$delivery_date,$to_ledger,$from_ledger,$remarks,$godown_id_array,$tax_group_array,$tax_group_ns_array,$sales_ref="NA",$sales_ref_type=0,$invoice_no=NULL,$retail_tax=0)
{
	
	$item_id_array = ConvertItemNameArrayInToIdArray($item_id_array);
	$item_id_ns_array = ConvertItemNameArrayInToIdArray($item_id_ns_array);
	$nett_amount = checkForSalesItemsInArray($item_id_array,$rate_array,$quantity_array,$discount_array,$tax_group_array);
	$nett_amount_ns = checkForSalesItemsNSInArray($item_id_ns_array,$amount_ns_array,$discount_ns_array,$tax_group_ns_array,0,NULL,NULL);
	
	if(($nett_amount && checkForNumeric($nett_amount) && $nett_amount>=0) || ($nett_amount_ns && checkForNumeric($nett_amount_ns) && $nett_amount_ns>=0))
	{
	$total_nett_amount=0;	
	if($nett_amount && checkForNumeric($nett_amount) && $nett_amount>=0)
	$total_nett_amount = $total_nett_amount + $nett_amount;
	if($nett_amount_ns && checkForNumeric($nett_amount_ns) && $nett_amount_ns>=0)
	$total_nett_amount = $total_nett_amount + $nett_amount_ns;
	
	
	$sales_id = insertSale($total_nett_amount,$trans_date,$trans_date,$to_ledger,$from_ledger,$remarks,2,0,$sales_ref,$sales_ref_type,$retial_tax,$invoice_no);
	
	if(checkForNumeric($sales_id))
	{
	if($nett_amount && checkForNumeric($nett_amount) && $nett_amount>=0)	
	insertInventoryItemsToSale($item_id_array,$rate_array,$quantity_array,$discount_array,$sales_id,$tax_group_array,$godown_id_array);
	if($nett_amount_ns && checkForNumeric($nett_amount_ns) && $nett_amount_ns>=0)
	insertNonStocksToSale($item_id_ns_array,$amount_ns_array,$discount_ns_array,$sales_id,$tax_group_ns_array,0,NULL,NULL);
	}
	else
	return "error";
	
	return $sales_id;
	}
	return "error";
}	

function insertInventoryItemSale($item_id_array,$rate_array,$quantity_array,$discount_array,$trans_date,$delivery_date,$to_ledger,$from_ledger,$remarks,$godown_id_array,$tax_group_array,$sales_ref="NA",$sales_ref_type=0)
{
	$item_id_array = ConvertItemNameArrayInToIdArray($item_id_array);

	$nett_amount = checkForSalesItemsInArray($item_id_array,$rate_array,$quantity_array,$discount_array,$tax_group_array);
	
	if($nett_amount && checkForNumeric($nett_amount) && $nett_amount>=0)
	{
	$sales_id = insertSale($nett_amount,$trans_date,$trans_date,$to_ledger,$from_ledger,$remarks,2,0,$sales_ref,$sales_ref_type);
	if(checkForNumeric($sales_id))
	insertInventoryItemsToSale($item_id_array,$rate_array,$quantity_array,$discount_array,$sales_id,$tax_group_array,$godown_id_array);
	else
	return "error";

	return $sales_id;
	}
	return "error";
}


function updateInventoryItemSale($sales_id,$item_id_array,$rate_array,$quantity_array,$discount_array,$trans_date,$delivery_date,$to_ledger,$from_ledger,$remarks,$godown_id_array,$tax_group_array,$sales_ref="NA",$sales_ref_type=0)
{
	if(checkForNumeric($sales_id))
	{
		$item_id_array = ConvertItemNameArrayInToIdArray($item_id_array);
		$nett_amount = checkForSalesItemsInArray($item_id_array,$rate_array,$quantity_array,$discount_array,$tax_group_array);
	
	if($nett_amount && checkForNumeric($nett_amount) && $nett_amount>=0)
	{
		
	
	$result=updateSale($sales_id,$nett_amount,$trans_date,$trans_date,$to_ledger,$from_ledger,$remarks,2,0,$sales_ref,$sales_ref_type);
	if($result=="success")
	{
	deleteInventoryItemsForSale($sales_id);	
	insertInventoryItemsToSale($item_id_array,$rate_array,$quantity_array,$discount_array,$sales_id,$tax_group_array,$godown_id_array);

	return "success";
	}
	}
	return "error";
		
		
	}
}

function updateInventoryNonStockItemSale($sales_id,$item_id_array,$rate_array,$quantity_array,$discount_array,$item_id_ns_array,$amount_ns_array,$discount_ns_array,$trans_date,$delivery_date,$to_ledger,$from_ledger,$remarks,$godown_id_array,$tax_group_array,$tax_group_ns_array,$sales_ref="NA",$sales_ref_type=0)
{
	if(checkForNumeric($sales_id))
	{
	
	$item_id_array = ConvertItemNameArrayInToIdArray($item_id_array);
	$item_id_ns_array = ConvertItemNameArrayInToIdArray($item_id_ns_array);
	
		$nett_amount = checkForSalesItemsInArray($item_id_array,$rate_array,$quantity_array,$discount_array,$tax_group_array);
	$nett_amount_ns = checkForSalesItemsNSInArray($item_id_ns_array,$amount_ns_array,$discount_ns_array,$tax_group_ns_array,0,NULL,NULL);
	
	
	if(($nett_amount && checkForNumeric($nett_amount) && $nett_amount>=0) || ($nett_amount_ns && checkForNumeric($nett_amount_ns) && $nett_amount_ns>=0))
	{
	
	$total_nett_amount=0;	
	if($nett_amount && checkForNumeric($nett_amount) && $nett_amount>=0)
	$total_nett_amount = $total_nett_amount + $nett_amount;
	if($nett_amount_ns && checkForNumeric($nett_amount_ns) && $nett_amount_ns>=0)
	$total_nett_amount = $total_nett_amount + $nett_amount_ns;	
		
	
	$result=updateSale($sales_id,$total_nett_amount,$trans_date,$trans_date,$to_ledger,$from_ledger,$remarks,2,0,$sales_ref,$sales_ref_type);
	
	if($result=="success")
	{
	deleteInventoryItemsForSale($sales_id);	
	deleteNonStockItemsForSale($sales_id);
		
	if($nett_amount && checkForNumeric($nett_amount) && $nett_amount>=0)	
	insertInventoryItemsToSale($item_id_array,$rate_array,$quantity_array,$discount_array,$sales_id,$tax_group_array,$godown_id_array);
	if($nett_amount_ns && checkForNumeric($nett_amount_ns) && $nett_amount_ns>=0)
	insertNonStocksToSale($item_id_ns_array,$amount_ns_array,$discount_ns_array,$sales_id,$tax_group_ns_array,0,NULL,NULL);

	return "success";
	}
	}
	return "error";
		
		
	}
}

function insertInventoryItemToSale($item_id,$rate,$quantity,$discount,$sales_id,$godown_id,$tax_group_id,$warranty=0)
{
	if(!validateForNull($warranty))
	$warranty=0;
	if(checkForNumeric($item_id,$rate,$quantity,$discount,$sales_id,$godown_id,$tax_group_id,$warranty) && $tax_group_id>=0 && $godown_id>0 && $item_id>0 && $rate>=0 && $discount>=0 && $quantity>0)
			{
				
				$amount = $quantity * $rate;
				$nett_amount = $amount - $amount*($discount/100);
				
				if($tax_group_id>0)
				{
				$tax_group = getTaxGroupByID($tax_group_id);
				if($tax_group['in_out']==3)
				{
					$total_tax_percent = getTotalTaxPercentForTaxGroup($tax_group_id);
					$base_amount = GetBaseAmountFromMRP($total_tax_percent,$nett_amount); 
					$nett_amount = $base_amount;
					
				}
				}
				
				$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
				
				
				$sql="INSERT INTO edms_ac_sales_item (item_id,rate,quantity,discount,amount,net_amount,sales_id,godown_id,warranty,created_by,last_updated_by,date_added,date_modified) VALUES ($item_id,$rate,$quantity,$discount,$amount,$nett_amount,$sales_id,$godown_id,$warranty,$admin_id,$admin_id,NOW(),NOW())";
				dbQuery($sql);
				return dbInsertId();
			}	
		return false;	
	
}

function deleteInventoryItemsForSale($sales_id)
{
	if(checkForNumeric($sales_id))
	{
		deleteTaxForSale($sales_id);
		$sql="DELETE FROM edms_ac_sales_item WHERE sales_id = $sales_id";
		dbQuery($sql);
		return "success";
	}	
	return "error";
}

function insertInventoryItemsToSale($item_id_array,$rate_array,$quantity_array,$discount_array,$sales_id,$tax_group_array,$godown_id_array,$warranty=0)
{
	if(!validateForNull($warranty))
	$warranty=0;
	
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
			
			
			if(checkForNumeric($item_id,$rate,$quantity,$discount,$sales_id,$godown_id,$warranty) && $item_id>0 && $rate>=0 && $discount>=0 && $quantity>0 && $tax_group_id>=0)
			{
				$net_amount = (($rate - $rate*($discount/100))*$quantity);
				
				if($tax_group_id>0)
				{
				$tax_group = getTaxGroupByID($tax_group_id);
				if($tax_group['in_out']==3)
				{
					$total_tax_percent = getTotalTaxPercentForTaxGroup($tax_group_id);
					$base_amount = GetBaseAmountFromMRP($total_tax_percent,$net_amount); 
					$net_amount = $base_amount;
					
				}
				}
				
				
				$sales_item_id=insertInventoryItemToSale($item_id,$rate,$quantity,$discount,$sales_id,$godown_id,$tax_group_id,$warranty);
				
				if($tax_group_id>0)
				insertTaxToSale($sales_id,$sales_item_id,$tax_group_id,$net_amount);
			}	
			
		}
		return "success";
				
	}
	
	return "error";
	
}
	
function getInventoryItemForSaleId($sales_id)
{
	if(checkForNumeric($sales_id))
	{
		$return_array = array();
		$sql="SELECT edms_ac_sales_item.sales_item_id,item_id,rate,quantity,discount,amount,net_amount,edms_ac_sales_item.sales_id,godown_id,created_by,last_updated_by,date_added,date_modified , edms_tax_grp.tax_group_id, tax_group_name, in_out, SUM(tax_amount) as tax_amount FROM edms_ac_sales_item LEFT JOIN edms_ac_sales_tax ON edms_ac_sales_item.sales_item_id = edms_ac_sales_tax.sales_item_id
		LEFT JOIN edms_tax_grp ON edms_tax_grp.tax_group_id = edms_ac_sales_tax.tax_group_id WHERE edms_ac_sales_item.sales_id = $sales_id GROUP BY edms_ac_sales_item.sales_item_id";	
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
			$i=0;
			foreach($resultArray as $re)
			{
				$sales_item_id = $re['sales_item_id'];
				$tax = getTaxForSaleItemId($sales_item_id);
				$return_array[$i]['sales_item_details'] = $re;
				$return_array[$i]['tax_details'] = $tax;
				$i++;
			}
			return $return_array;
		}
	}
	
}	

function getInventoryItemRegularForSaleId($sales_id)
{
	if(checkForNumeric($sales_id))
	{
		$return_array = array();
		$sql="SELECT edms_ac_sales_item.sales_item_id,item_id,rate,quantity,discount,amount,net_amount,edms_ac_sales_item.sales_id,godown_id,created_by,last_updated_by,date_added,date_modified , edms_tax_grp.tax_group_id, tax_group_name, in_out, SUM(tax_amount) as tax_amount FROM edms_ac_sales_item 
		LEFT JOIN edms_ac_sales_tax ON edms_ac_sales_item.sales_item_id = edms_ac_sales_tax.sales_item_id
		LEFT JOIN edms_tax_grp ON edms_tax_grp.tax_group_id = edms_ac_sales_tax.tax_group_id WHERE edms_ac_sales_item.sales_id = $sales_id AND warranty=0  GROUP BY edms_ac_sales_item.sales_item_id";	
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
			$i=0;
			foreach($resultArray as $re)
			{
				$sales_item_id = $re['sales_item_id'];
				$tax = getTaxForSaleItemId($sales_item_id);
				$return_array[$i]['sales_item_details'] = $re;
				$return_array[$i]['tax_details'] = $tax;
				$i++;
			}
			return $return_array;
		}
	}
	
}	

function getInventoryItemGeneralRegularForSaleId($sales_id)
{
	if(checkForNumeric($sales_id))
	{
		$return_array = array();
		$sql="SELECT edms_ac_sales_item.sales_item_id,edms_ac_sales_item.item_id,rate,quantity,discount,amount,net_amount,edms_ac_sales_item.sales_id,godown_id,edms_ac_sales_item.created_by,edms_ac_sales_item.last_updated_by,edms_ac_sales_item.date_added,edms_ac_sales_item.date_modified , edms_tax_grp.tax_group_id, tax_group_name, in_out, SUM(tax_amount) as tax_amount FROM edms_ac_sales_item 
		LEFT JOIN edms_ac_sales_tax ON edms_ac_sales_item.sales_item_id = edms_ac_sales_tax.sales_item_id
		LEFT JOIN edms_tax_grp ON edms_tax_grp.tax_group_id = edms_ac_sales_tax.tax_group_id
		LEFT JOIN edms_inventory_item ON edms_ac_sales_item.item_id = edms_inventory_item.item_id WHERE edms_ac_sales_item.sales_id = $sales_id AND warranty=0 AND (item_type_id IS NULL OR item_type_id = 2) GROUP BY edms_ac_sales_item.sales_item_id";	
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
			$i=0;
			foreach($resultArray as $re)
			{
				$sales_item_id = $re['sales_item_id'];
				$tax = getTaxForSaleItemId($sales_item_id);
				$return_array[$i]['sales_item_details'] = $re;
				$return_array[$i]['tax_details'] = $tax;
				$i++;
			}
			return $return_array;
		}
	}
	
}	

function getInventoryItemLubRegularForSaleId($sales_id)
{
	if(checkForNumeric($sales_id))
	{
		$return_array = array();
		$sql="SELECT edms_ac_sales_item.sales_item_id,edms_ac_sales_item.item_id,rate,quantity,discount,amount,net_amount,edms_ac_sales_item.sales_id,godown_id,edms_ac_sales_item.created_by,edms_ac_sales_item.last_updated_by,edms_ac_sales_item.date_added,edms_ac_sales_item.date_modified , edms_tax_grp.tax_group_id, tax_group_name, in_out, SUM(tax_amount) as tax_amount FROM edms_ac_sales_item 
		LEFT JOIN edms_ac_sales_tax ON edms_ac_sales_item.sales_item_id = edms_ac_sales_tax.sales_item_id
		LEFT JOIN edms_tax_grp ON edms_tax_grp.tax_group_id = edms_ac_sales_tax.tax_group_id
		LEFT JOIN edms_inventory_item ON edms_ac_sales_item.item_id = edms_inventory_item.item_id WHERE edms_ac_sales_item.sales_id = $sales_id AND warranty=0 AND (item_type_id = 1) GROUP BY edms_ac_sales_item.sales_item_id";	
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
			$i=0;
			foreach($resultArray as $re)
			{
				$sales_item_id = $re['sales_item_id'];
				$tax = getTaxForSaleItemId($sales_item_id);
				$return_array[$i]['sales_item_details'] = $re;
				$return_array[$i]['tax_details'] = $tax;
				$i++;
			}
			return $return_array;
		}
	}
	
}	


function getInventoryItemWarrantyForSaleId($sales_id)
{
	if(checkForNumeric($sales_id))
	{
		$return_array = array();
		$sql="SELECT edms_ac_sales_item.sales_item_id,item_id,rate,quantity,discount,amount,net_amount,edms_ac_sales_item.sales_id,godown_id,created_by,last_updated_by,date_added,date_modified , edms_tax_grp.tax_group_id, tax_group_name, in_out, SUM(tax_amount) as tax_amount FROM edms_ac_sales_item
		LEFT JOIN edms_ac_sales_tax ON edms_ac_sales_item.sales_item_id = edms_ac_sales_tax.sales_item_id
		LEFT JOIN edms_tax_grp ON edms_tax_grp.tax_group_id = edms_ac_sales_tax.tax_group_id WHERE edms_ac_sales_item.sales_id = $sales_id AND warranty=1  GROUP BY edms_ac_sales_item.sales_item_id ";	
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
			$i=0;
			foreach($resultArray as $re)
			{
				$sales_item_id = $re['sales_item_id'];
				$tax = getTaxForSaleItemId($sales_item_id);
				$return_array[$i]['sales_item_details'] = $re;
				$return_array[$i]['tax_details'] = $tax;
				$i++;
			}
			return $return_array;
		}
	}
	
}




function getNonStockItemForSaleId($sales_id)
{
	if(checkForNumeric($sales_id))
	{
		$return_array = array();
		$sql="SELECT edms_ac_sales_nonstock.sales_non_stock_id,item_id,discount,amount,net_amount,edms_ac_sales_nonstock.sales_id,created_by,last_updated_by,date_added,date_modified , edms_tax_grp.tax_group_id, tax_group_name, in_out, SUM(tax_amount) as tax_amount FROM edms_ac_sales_nonstock 
		LEFT JOIN edms_ac_sales_tax ON edms_ac_sales_nonstock.sales_non_stock_id = edms_ac_sales_tax.sales_non_stock_id
		LEFT JOIN edms_tax_grp ON edms_tax_grp.tax_group_id = edms_ac_sales_tax.tax_group_id  
		WHERE edms_ac_sales_nonstock.sales_id = $sales_id GROUP BY edms_ac_sales_nonstock.sales_non_stock_id";	
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
			$i=0;
			foreach($resultArray as $re)
			{
				$sales_item_id = $re['sales_non_stock_id'];
				$tax = getTaxForSaleItemId($sales_item_id,true);
				$return_array[$i]['sales_item_details'] = $re;
				$return_array[$i]['tax_details'] = $tax;
				$i++;
			}
			return $return_array;
		}
	}
	
}	


function getNonStockItemOurForSaleId($sales_id)
{
	if(checkForNumeric($sales_id))
	{
		$return_array = array();
		$sql="SELECT edms_ac_sales_nonstock.sales_non_stock_id,item_id,discount,amount,net_amount,edms_ac_sales_nonstock.sales_id,created_by,last_updated_by,date_added,date_modified , edms_tax_grp.tax_group_id, tax_group_name, in_out, SUM(tax_amount) as tax_amount FROM edms_ac_sales_nonstock
		LEFT JOIN edms_ac_sales_tax ON edms_ac_sales_nonstock.sales_non_stock_id = edms_ac_sales_tax.sales_non_stock_id
		LEFT JOIN edms_tax_grp ON edms_tax_grp.tax_group_id = edms_ac_sales_tax.tax_group_id  WHERE edms_ac_sales_nonstock.sales_id = $sales_id  AND ns_type=0 GROUP BY edms_ac_sales_nonstock.sales_non_stock_id";	
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
			$i=0;
			foreach($resultArray as $re)
			{
				$sales_item_id = $re['sales_non_stock_id'];
				$tax = getTaxForSaleItemId($sales_item_id,true);
				$return_array[$i]['sales_item_details'] = $re;
				$return_array[$i]['tax_details'] = $tax;
				$i++;
			}
			return $return_array;
		}
	}
	
}	

function getNonStockItemOutSideJobForSaleId($sales_id)
{
	if(checkForNumeric($sales_id))
	{
		$return_array = array();
		$sql="SELECT edms_ac_sales_nonstock.sales_non_stock_id,item_id,discount,amount,net_amount, edms_ac_sales_nonstock.sales_id,created_by,last_updated_by,date_added,date_modified , edms_tax_grp.tax_group_id, tax_group_name, in_out, SUM(tax_amount) as tax_amount FROM edms_ac_sales_nonstock
		LEFT JOIN edms_ac_sales_tax ON edms_ac_sales_nonstock.sales_non_stock_id = edms_ac_sales_tax.sales_non_stock_id
		LEFT JOIN edms_tax_grp ON edms_tax_grp.tax_group_id = edms_ac_sales_tax.tax_group_id  WHERE edms_ac_sales_nonstock.sales_id = $sales_id  AND ns_type=1 GROUP BY edms_ac_sales_nonstock.sales_non_stock_id";	
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
			$i=0;
			foreach($resultArray as $re)
			{
				$sales_item_id = $re['sales_non_stock_id'];
				$tax = getTaxForSaleItemId($sales_item_id,true);
				$return_array[$i]['sales_item_details'] = $re;
				$return_array[$i]['tax_details'] = $tax;
				$i++;
			}
			return $return_array;
		}
	}
	
}	

function getTaxForSaleItemId($sales_item_id,$non_stock=false)
{
	if(checkForNumeric($sales_item_id))
	{
		$sql="SELECT sales_id, edms_tax_grp.tax_group_id, tax_group_name, SUM(tax_amount) as tax_amount, edms_tax.tax_id, CONCAT(IF(edms_tax.in_out>0,'OUT','IN'), ' ', tax_name) as tax_name_in_out, tax_name, edms_tax.in_out, tax_percent FROM edms_ac_sales_tax, edms_tax_grp, edms_tax WHERE edms_tax_grp.tax_group_id = edms_ac_sales_tax.tax_group_id AND edms_tax.tax_id = edms_ac_sales_tax.tax_id AND
		";
		if(!$non_stock)
		$sql=$sql." edms_ac_sales_tax.sales_item_id = $sales_item_id GROUP BY tax_id";
		else if($non_stock)
		$sql=$sql." edms_ac_sales_tax.sales_non_stock_id = $sales_item_id GROUP BY tax_id";
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