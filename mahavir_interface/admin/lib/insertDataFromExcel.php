<?php
require_once('cg.php');
require_once('bd.php');
require_once('common.php');
require_once('adminuser-functions.php');
require_once('phpExcel/PHPExcel/IOFactory.php');


function insertSpecDataFromExcel($spec_sheet_path,$sub_cat_id)
{
	
$inputFileName = SRV_ROOT."images/excel/".$spec_sheet_path;

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
for ($row = 0; $row <= $highestRow; $row++)
{ 

   //  Read a row of data into an array
    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                                    NULL,
                                    TRUE,
                                    FALSE);
	$rowData = $rowData[0];								
	foreach($rowData as $col=>$value)
    {
		if(validateForNull($value))
		{
		//echo $row." ".$col." ".$value." <br>";
		$sql="INSERT INTO ems_excel_spec_data (excel_data_row, excel_data_column, excel_data, sub_cat_id)
			  VALUES ($row, $col, '$value', $sub_cat_id)";
		
		dbQuery($sql);
		}
	}
    //  Insert row data array into your database of choice here
}
exit;
}

?>