<?php
/** PHPExcel */
require_once('phpExcel/PHPExcel.php');

require_once('cg.php');
require_once('bd.php');
/** PHPExcel_Writer_Excel2007 */
require_once('phpExcel/PHPExcel/Writer/Excel2007.php');
ini_set("memory_limit", "-1");
function convertStringToUtf($str)
{
	return utf8_encode($str);
}

// Create new PHPExcel object

$sql = "SELECT item_name, item_code as sku, mfg_item_code as upc, opening_quantity + IFNULL((SELECT SUM(IF(type=0,quantity,-quantity))  FROM edms_inventory_item_jv WHERE edms_inventory_item_jv.item_id = edms_inventory_item.item_id GROUP BY edms_inventory_item_jv.item_id ),0) as qty_in_stock, godown_name as shelf, manufacturer_name as manufacturer, ledger_name as supplier FROM edms_inventory_item LEFT JOIN edms_godown ON edms_godown.godown_id = edms_inventory_item.opening_godown_id  LEFT JOIN edms_item_manufacturer ON edms_item_manufacturer.manufacturer_id = edms_inventory_item.manufacturer_id LEFT JOIN edms_ac_ledgers ON edms_ac_ledgers.ledger_id = edms_inventory_item.supplier_id WHERE 1=1 ORDER BY item_name
 ";
$result = dbQuery($sql);
$resultArray = dbResultToArray($result,MYSQL_ASSOC);
$new_array =array();

foreach($resultArray as $re)
{
	$re['item_name']=utf8_encode($re['item_name']);
	$new_array[]= $re;
}

//array_walk_recursive($resultArray, 'convertStringToUtf');
$objPHPExcel = new PHPExcel();

// Set properties

$objPHPExcel->getProperties()->setCreator("Tap And Type");
$objPHPExcel->getProperties()->setLastModifiedBy("EDMS Software");
$objPHPExcel->getProperties()->setTitle("EDMS Stock REPORT");
$objPHPExcel->getProperties()->setSubject("EDMS RASID REPORT");
$objPHPExcel->getProperties()->setDescription("EDMS RASID REPORT");

$emi_payment_array = 

// Add some data

$objPHPExcel->setActiveSheetIndex(0);

$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Item Name');
$objPHPExcel->getActiveSheet()->setCellValueExplicit('B1', 'SKU', PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet()->SetCellValueExplicit('C1', 'UPC', PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Qty In Stock');
$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Shelf');
$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Manufacturer');
$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Supplier');

//$objPHPExcel->getActiveSheet()->fromArray($new_array, null, 'A2');
$i=2;
foreach($new_array as $n)
{
	echo $i;
	$objPHPExcel->getActiveSheet()->SetCellValue('A'.$i, $n['item_name']);
$objPHPExcel->getActiveSheet()->setCellValueExplicit('B'.$i, $n['sku'], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet()->SetCellValueExplicit('C'.$i, $n['upc'], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet()->SetCellValue('D'.$i, $n['qty_in_stock']);
$objPHPExcel->getActiveSheet()->SetCellValue('E'.$i, $n['shelf']);
$objPHPExcel->getActiveSheet()->SetCellValue('F'.$i, $n['manufacturer']);
$objPHPExcel->getActiveSheet()->SetCellValue('G'.$i, $n['supplier']);
	
	
	$i++;
}

$objPHPExcel->getActiveSheet()->setTitle("Stock");

	$file_title = "stock_as_on_".date('d_m_Y_H_i_s');	
// Save Excel 2007 file
$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$objWriter->save(str_replace('.php', '.xlsx',$file_title.".php"));

header("Location: ".WEB_ROOT."admin/settings/inventory_settings/item_settings/index.php?view=downloadExcel&file_name=".$file_title);		
exit;

$file = $file_title.".xlsx"; // the path on the local file system - ie. don't use "http://www.example.com"!



        header('Content-Description: File Transfer');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename='.basename($file));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
readfile($file);
exit; // Could be a good idea to exit at the end

//header("Location: ".WEB_ROOT."lib/".$file_title.'.xlsx');		
//exit;
// Echo done
?>