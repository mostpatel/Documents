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
require_once('account-purchase-order-functions.php');
require_once('inventory-purchase-order-functions.php');
require_once('account-head-functions.php');
require_once('godown-functions.php');
require_once('phpExcel/PHPExcel/IOFactory.php');

function insertPurchaseOrderFromExcel($file)
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
		$item_array = array();
		$qty_array = array();
		$rate_array = array();
		$disc_array = array();
		$tax_group_array = array();
		$godown_array = array();
		$godowns = listGodowns();
		$godown_id = $godowns[0]['godown_id'];
for ($row = 1; $row <= $highestRow; $row++){ 
   //  Read a row of data into an array
    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                                    NULL,
                                    TRUE,
                                    FALSE);
		if($row==1)
		{
		$po_number = $rowData[0][1];
		
		}
		else if($row==2)
		{
			$supplier = $rowData[0][1];
			$supplier_id = getLedgerIdFromLedgerName($supplier);
			if(!checkForNumeric($supplier_id))
			return "error";
			else
			$supplier_id = "L".$supplier_id;
			
		}
		else if($row>3)
		{
			$upc = $rowData[0][0];

			$qty = $rowData[0][1];
			$rate = $rowData[0][2];
			
			if(validateForNull($upc))
			{
				
				$item_id=getItemIdFromUPC($upc);
				
				if(!checkForNumeric($item_id,$qty,$rate) && $qty<=0 && $rate<=0)
				return "error";
				else
				{
					$item_id_array[] =$item_id;
					$qty_array[] = $qty;
					$rate_array[] =$rate;
					$disc_array[] = 0;
					$tax_group_array[] = 0;
					$godown_array[] = $godown_id;
				}
			}	
		}
	
}

$oc_id =$_SESSION['edmsAdminSession']['oc_id'];
		$purchase_order_id=insertInventoryNonStockItemPurchaseOrder($item_id_array,$rate_array,$qty_array,$disc_array,array(),array(),array(),date('d/m/Y',strtotime(getTodaysDate())),date('d/m/Y',strtotime(getTodaysDate())),-1,$supplier_id,"",$godown_array,$tax_group_array,array(),$po_number,0,$oc_id,0);
		return $purchase_order_id;	
	
}




?>