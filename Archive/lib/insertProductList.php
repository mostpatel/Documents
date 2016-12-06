<?php
require_once('cg.php');
require_once('bd.php');
require_once('common.php');
require_once('adminuser-functions.php');
require_once('adminuser-functions.php');
require_once('sub-category-functions.php');
require_once('category-functions.php');
require_once('customer-functions.php');
require_once('phpExcel/PHPExcel/IOFactory.php');

$inputFileName = 'product_list.xlsx';

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




//  Loop through each row of the worksheet in turn
for ($row = 1; $row <= $highestRow; $row++)
{ 

if($row == 1)
continue;
   //  Read a row of data into an array
    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                                    NULL,
                                    TRUE,
                                    FALSE);
	
	$sub_cat_name = $rowData[0][0];
	$cat_name = $rowData[0][1];
	
	
	
	
	
	echo $sub_cat_name." ".$cat_name." <br>";
	
	
	insertCategory($cat_name);
    echo $cat_name." <br> ";
	$cat_id = checkDuplicateCategory($cat_name);
	echo $cat_id;
	insertSubCategory($sub_cat_name, 0, "NULL", $cat_id);
	
	
	

	
    //  Insert row data array into your database of choice here
}

?>