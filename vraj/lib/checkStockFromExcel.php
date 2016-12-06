<?php
ini_set('memory_limit', '-1');
require_once('cg.php');
require_once('bd.php');
require_once('common.php');
require_once('inventory-functions.php');
require_once('godown-functions.php');
require_once('item-manufacturer-functions.php');
require_once('inventory-item-functions.php');
require_once('inventory-jv-functions.php');
require_once('account-ledger-functions.php');
require_once('account-head-functions.php');
require_once('phpExcel/PHPExcel/IOFactory.php');

function checkStockForExcel($file)
{
$inputFileName = $file;

//  Read your Excel workbook
try {
    $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
    $objPHPExcel = $objReader->load($inputFileName);
} catch(Exception $e) {
    die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
}

//  Get worksheet dimensions
$sheet = $objPHPExcel->getSheet(0); 
$highestRow = $sheet->getHighestRow(); 
$highestColumn = $sheet->getHighestColumn();
$sql_main = "START TRANSACTION;";
//  Loop through each row of the worksheet in turn
		$todays = getTodaysDate();
		$to=getNextDate($todays); // return Y-m-d			
		$error_sku_array = array();
		$instock_array = array();
		$outof_stock_array = array();
for ($row = 2; $row <= $highestRow; $row++){ 
   //  Read a row of data into an array
    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                                    NULL,
                                    TRUE,
                                    FALSE);
		
	foreach($rowData as $cell_value)
	{
		
		$sku = $cell_value[0];
		$quantity = $cell_value[1];
		$sku = clean_data($sku);
		$quantity = clean_data($quantity);
		$item_id = getItemIdFromSku($sku);
		if(validateForNull($sku))
		{
			if(checkForNumeric($item_id,$quantity) && $item_id>0 && $quantity>0)
			{	
			$closing_balance_item_array = getOpeningBalanceForItemForDate($item_id,$to);	
			$opening_balance_quantity=$closing_balance_item_array[1];
			$item = getInventoryItemById($item_id);
			if(array_key_exists($item_id,$instock_array))
			{
				$previous_quantity = 0;
				$previous_quantity = $instock_array[$item_id]['order_qty'];
				$new_required_qunaity = 0;
				$new_required_qunaity = $previous_quantity + $quantity;
				if($new_required_qunaity<=$opening_balance_quantity)
				{
				$instock_array[$item_id]['order_qty']=$new_required_qunaity;
				$instock_array[$item_id]['stock_qty']=$opening_balance_quantity;
				$instock_array[$item_id]['item']=$item;
				$instock_array[$item_id]['sku_wise'][] = $sku."=".$quantity."Qty <br>";
				}
				else
				{
					$outof_stock_array[$item_id]['sku_wise'] = $instock_array[$item_id]['sku_wise'];
					unset($instock_array[$item_id]);
					$outof_stock_array[$item_id]['order_qty']=$new_required_qunaity;
					$outof_stock_array[$item_id]['stock_qty']=$opening_balance_quantity;
					$outof_stock_array[$item_id]['item']=$item;
					$outof_stock_array[$item_id]['sku_wise'][] = $sku."=".$quantity."Qty <br>";
					
				}
			}
			else if(array_key_exists($item_id,$outof_stock_array))
			{
				$previous_quantity = 0;
				$previous_quantity = $outof_stock_array[$item_id]['order_qty'];
				$new_required_qunaity = 0;
				$new_required_qunaity = $previous_quantity + $quantity;
				$outof_stock_array[$item_id]['order_qty']=$new_required_qunaity;
				$outof_stock_array[$item_id]['stock_qty']=$opening_balance_quantity;
				$outof_stock_array[$item_id]['item']=$item;
				$outof_stock_array[$item_id]['sku_wise'][] = $sku."=".$quantity."Qty <br>";
				
			}
			else
			{
				if($quantity<=$opening_balance_quantity)
				{
				$instock_array[$item_id]['order_qty']=$quantity;
				$instock_array[$item_id]['stock_qty']=$opening_balance_quantity;
				$instock_array[$item_id]['item']=$item;
				$instock_array[$item_id]['sku_wise'][] = $sku."=".$quantity."Qty <br>";
				}
				else
				{
				$outof_stock_array[$item_id]['order_qty']=$quantity;
				$outof_stock_array[$item_id]['stock_qty']=$opening_balance_quantity;
				$outof_stock_array[$item_id]['item']=$item;
				$outof_stock_array[$item_id]['sku_wise'][] = $sku."=".$quantity."Qty <br>";
				}
				
			}
			
			}
			else
			$error_sku_array[$sku] =$quantity;
		}
	}
	

	
}
	return array($instock_array,$outof_stock_array,$error_sku_array);
}


function deductStockFromExcel($file)
{
	$return_array = checkStockForExcel($file);
	$instock_array = $return_array[0];
	$out_of_Stock_array = $return_array[1];
	$error_array = $return_array[2];
	if(count($out_of_Stock_array)>0 || count($error_array)>0)
	return $return_array;
	else
	{
		  $admin_id=$_SESSION['edmsAdminSession']['admin_id'];
          $oc_id =$_SESSION['edmsAdminSession']['oc_id'];
		  $trans_date = getTodaysDate();
		
	
	$sql="INSERT INTO edms_inventory_jv(trans_date,remarks,oc_id,created_by,last_updated_by,date_added,date_modified,jv_type_id,inventory_jv_mode,ledger_id,customer_id,purchase_order_id) VALUES ('$trans_date','',$oc_id,$admin_id,$admin_id,NOW(),NOW(),5,2,NULL,NULL,NULL)";

	$result = dbQuery($sql);
	$inventory_jv_id = dbInsertId();
	
	if(checkForNumeric($inventory_jv_id))
	{
		
		$todays = getTodaysDate();
		$to=getNextDate($todays); // return Y-m-d		
		foreach($instock_array as $item_id => $item_details_array)
		{
			
			$order_qty = $item_details_array['order_qty'];
			$item = $item_details_array['item'];
			$closing_balance_item_array = getOpeningBalanceForItemForDate($item_id,$to);	
			$opening_balance_quantity=$closing_balance_item_array[1];
			
			if($order_qty<=$opening_balance_quantity)
			{
				
				$godown_id = $item['opening_godown_id'];
				$sql="INSERT INTO edms_inventory_item_jv (item_id,rate,quantity,amount,inventory_jv_id,godown_id,created_by,last_updated_by,date_added,date_modified,type) VALUES ($item_id,0,$order_qty,0,$inventory_jv_id,$godown_id,$admin_id,$admin_id,NOW(),NOW(),1)";
				$result = dbQuery($sql);
			}
		}
		return $inventory_jv_id;
	}
	
	}
	return "error";
}


?>