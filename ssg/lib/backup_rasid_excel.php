<?php
/** PHPExcel */
require_once('phpExcel/PHPExcel.php');
require_once('loan-functions.php');
require_once('file-functions.php');
/** PHPExcel_Writer_Excel2007 */
require_once('phpExcel/PHPExcel/Writer/Excel2007.php');

// Create new PHPExcel object

$payment_id_array = $_POST['selectTR'];
$emi_payment_id_array = array();
$penalty_id_array = array();
$forced_closure_array = array();

foreach($payment_id_array as $payment_id)
{
	if(strpos($payment_id,",e")>0)
	{
		$emi_payment_id = str_replace(",e","",$payment_id);
		$emi_payment_id_array[]=$emi_payment_id;
	}
	else if(strpos($payment_id,",f")>0)
	{
		$forced_closure_id = str_replace(",f","",$payment_id);
		$forced_closure_array[] = $forced_closure_id;
	}
	else
	{
		$penalty_id =str_replace(",p","",$payment_id);
		$penalty_id_array[]=$penalty_id;
		
	}
}

$objPHPExcel = new PHPExcel();

// Set properties

$objPHPExcel->getProperties()->setCreator("Tap And Type");
$objPHPExcel->getProperties()->setLastModifiedBy("Finex Balliauw");
$objPHPExcel->getProperties()->setTitle("FINEX RASID REPORT");
$objPHPExcel->getProperties()->setSubject("FINEX RASID REPORT");
$objPHPExcel->getProperties()->setDescription("FINEX RASID REPORT");

$emi_payment_array = 

// Add some data

$objPHPExcel->setActiveSheetIndex(0);



$row_no = 1;

if(is_array($emi_payment_id_array) && count($emi_payment_id_array)>0)
{
$column_char="A";
// Rename sheet
$rasid_details = getAllRasidDeatilsForEmiPaymentId($emi_payment_id_array[0]);
$rasid_details=$rasid_details[0];

foreach($rasid_details as $key=>$value)
{

if(!is_numeric($key))
{ 	
$objPHPExcel->getActiveSheet()->SetCellValue($column_char.$row_no, $key);
$column_char++;
}
}
$row_no++;
foreach($emi_payment_id_array as $emi_payment_id)
{
	
	$rasid_details = getAllRasidDeatilsForEmiPaymentId($emi_payment_id);
	$rasid_details=$rasid_details[0];
	$num=count($rasid_details);
	$i=0;
	$column_char = "A";
	while($i<$num)
	{	
	$objPHPExcel->getActiveSheet()->SetCellValue($column_char.$row_no, $rasid_details[$i]);
	$i++;
	$column_char++;
	}
   $row_no++;
	
}
}


if(is_array($penalty_id_array) && count($penalty_id_array)>0)
{
$column_char="A";
// Rename sheet
$rasid_details = getPenaltyById($penalty_id_array[0]);


foreach($rasid_details as $key=>$value)
{

if(!is_numeric($key))
{
	
$objPHPExcel->getActiveSheet()->SetCellValue($column_char.$row_no, $key);
$column_char++;
}
}
$row_no++;
foreach($penalty_id_array as $emi_payment_id)
{
	
	$rasid_details = getPenaltyById($emi_payment_id);

	$num=count($rasid_details);
	$i=0;
	$column_char = "A";
	while($i<$num)
	{	
	$objPHPExcel->getActiveSheet()->SetCellValue($column_char.$row_no, $rasid_details[$i]);
	$i++;
	$column_char++;
	}
   $row_no++;
	
}
}


if(is_array($forced_closure_array) && count($forced_closure_array)>0)
{
$column_char="A";

// Rename sheet
$rasid_details = getPrematureClosureDetails(getFileIdFromFileClosedId($forced_closure_array[0]));


foreach($rasid_details as $key=>$value)
{

if(!is_numeric($key))
{ 	
$objPHPExcel->getActiveSheet()->SetCellValue($column_char.$row_no, $key);
$column_char++;
}
}
$row_no++;
foreach($forced_closure_array as $emi_payment_id)
{
	
	$rasid_details = getPrematureClosureDetails(getFileIdFromFileClosedId($emi_payment_id));
	
	$num=count($rasid_details);
	$i=0;
	$column_char = "A";
	while($i<$num)
	{	
	$objPHPExcel->getActiveSheet()->SetCellValue($column_char.$row_no, $rasid_details[$i]);
	$i++;
	$column_char++;
	}
   $row_no++;
	
}
}


$objPHPExcel->getActiveSheet()->setTitle("Rasid");

	$file_title = "Rasid".date('d_m_Y_H_i_s');	
// Save Excel 2007 file
$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$objWriter->save(str_replace('.php', '.xlsx',$file_title.".php"));

header("Location: ".WEB_ROOT."lib/".$file_title.'.xlsx');		
exit;
// Echo done
?>