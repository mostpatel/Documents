<?php
require_once('cg.php');
require_once('bd.php');
require_once('common.php');
require_once('file-functions.php');
require_once('loan-functions.php');
require_once('broker-functions.php');
require_once('phpExcel/PHPExcel/IOFactory.php');
require_once('addNewCustomer-functions.php');
require_once('city-functions.php');
require_once('area-functions.php');
require_once('vehicle-functions.php');
require_once('vehicle-company-functions.php');
require_once('vehicle-insurance-functions.php');
require_once('insurance-company-functions.php');
require_once('vehicle-model-functions.php');
require_once('vehicle-type-functions.php');
require_once('vehicle-dealer-functions.php');
require_once('customer-functions.php');
require_once('guarantor-functions.php');
require_once('loan-functions.php');
require_once "cheque-functions.php";

function insertFileFromExcelPali($file,$agency_id)
{
// IMP	
//SET THIS PARAMS FIRST 	

if(!validateForNull($agency_id) || $agency_id==-1)
return "error";
$inputFileName = $file;
//$inputFileName = 'jeet.xlsx';

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
$rowData=array();
$files_array = array();

//  Loop through each row of the worksheet in turn
for ($row = 2,$file_counter=0; $row <= $highestRow; $row++){ 
    //  Read a row of data into an array
    $rowDat = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                                    NULL,
                                    TRUE,
                                    FALSE);

	
	$loan_approval_date=$rowDat[0][0];
    $loan_approval_date=convertExcelSerialNumberToDate($loan_approval_date);
	
	$file_agr_no = $rowDat[0][1];
	$file_no = $file_agr_no;
	
	$broker_code = $rowDat[0][3];
	$broker_id = insertBrokerIfNotDuplicate($broker_code);
	
		$original_agency_id=$agency_id;
		$our_company_id=NULL;
		$type=substr($original_agency_id,0,2);
		$original_agency_id=substr($original_agency_id,2);
		if($type=="ag")
		{
		$original_agency_id=$original_agency_id;
		$our_company_id="NULL";
	
		}
		else if($type=="oc")
		{
		$our_company_id=$original_agency_id;
		$original_agency_id="NULL";	
		}	

	$broker_ledger_id=getBrokerLedgerId($broker_code,$our_company_id,$original_agency_id);
	
	if(!checkForNumeric($broker_id))
	$broker_id=1;
	//print_r($rowData);
	
	$reg_no = $rowDat[0][5];
	
	$guarantor_name = $rowDat[0][7];
	$guarantor_area = $rowDat[0][8];	
	
	$loan_amount = $rowDat[0][13];
	$loan_starting_date = $rowDat[0][14];
	$loan_starting_date = convertExcelSerialNumberToDate($loan_starting_date);
	$file_charges = $rowDat[0][16];
	
	$emi = $rowDat[0][17];
	
	$duration = $rowDat[0][42];
	
	$bill_no = $rowDat[0][22];
	$bill_date = $rowDat[0][23];
	
	$insurance_company_name = $rowDat[0][24];
	
	insertInsuranceCompany($insurance_company_name);
	$insurance_company_id = getInsuranceCompanyIdByName($insurance_company_name);
	$insurance_expiry_date = $rowDat[0][25];
	$insurance_expiry_date = convertExcelSerialNumberToDate($insurance_expiry_date);
	$insurance_issue_date = $insurance_expiry_date;
	
	$engine_no = $rowDat[0][27];
	$chasis_no = $rowDat[0][26];
	
	$key_no = $rowDat[0][28];
	$vehicle_company_name=NULL;
	$vehicle_company_name = $rowDat[0][29];
	
	$vehicle_model_name = $rowDat[0][30];
	
	$vehicle_company_id= NULL;
	insertVehicleCompany($vehicle_company_name);
	$vehicle_company_id = getVehicleCompanyIdByName($vehicle_company_name);
	if(!checkForNumeric($vehicle_company_id))
	$vehicle_company_id=getOthersVehicleCompanyId();
	
	$vehicle_model_id=insertVehicleModel($vehicle_model_name,$vehicle_company_id);
	
	if(!checkForNumeric($vehicle_model_id))
	$vehicle_model_id = getOthersModelByCompanyId($vehicle_company_id);
	
	$vehicle_color = $rowDat[0][31];
	
	$noc_no = $rowDat[0][32];
	$noc_date = $rowDat[0][33];
	
	$customer_name = $rowDat[0][34];
	
	
	$customer_city = $rowDat[0][36];
	$customer_city_id = insertCityIfNotDuplicate($customer_city);
	$guarantor_city_id = $customer_city_id;
	$customer_area = $rowDat[0][37];
	
	$customer_address = "";
	
	$customer_address = $customer_address . $rowDat[0][38];
	$customer_address = $customer_address ." , " . $rowDat[0][39];
	$customer_address = $customer_address ." , " .  $customer_area." , ". $customer_city;
	$customer_contact_no = $rowDat[0][40];
	if($customer_contact_no==0)
	$customer_contact_no_array = array(9999999999);
	else
	$customer_contact_no_array = explode(" ",$customer_contact_no);
	
	$guarantor_contact_no = $rowDat[0][41];
	
	if($guarantor_contact_no==0)
	$guarantor_contact_no_array = array(9999999999);
	else
	$guarantor_contact_no_array = explode(" ",$guarantor_contact_no);
	
	$guarantor_address=$guarantor_area;
	
	$collection = $duration*$emi;
	$interest = $collection - $loan_amount;
	$interest_per_month = $interest/$duration;
	$roi_per_month = ($interest_per_month/$loan_amount)*100;
	$roi = $roi_per_month*12;
	
	
	$file_id =  addNewCustomer($agency_id,$file_agr_no,$file_no,$broker_id,$customer_name,$customer_address,$customer_city_id,$customer_area,"",$customer_contact_no_array,array(),array(),array(),array(),$guarantor_name,$guarantor_address,$guarantor_city_id,$guarantor_area,"",$guarantor_contact_no_array,array(),array(),array(),array(),$loan_amount,2,$duration,1,1,$roi,$emi,$loan_approval_date,$loan_starting_date,array('NA'),array('NA'),array($loan_amount),array($loan_approval_date),array('000000'),array(),array($broker_ledger_id),0);
	echo " File_id : ".$file_id;
	if(!checkForNumeric($file_id))
	echo " File_id_error ";
	
	$customer_id = getCustomerIdByFileId($file_id);
	$loan_id = getLoanIdFromFileId($file_id);
	
	if(!checkForNumeric($customer_id))
	echo " customer_id_error ";
	if(!checkForNumeric($loan_id))
	echo " loan_id_error ";
	    if(!validateForNull($reg_no))
		$reg_no="NA";
		if(!validateForNull($engine_no))
		$engine_no="NA";
		if(!validateForNull($chasis_no))
		$chasis_no="NA";
		
	if(validateForNull($reg_no))
	{
		$reg_no = str_replace("-","",$reg_no);
		echo " Reg NO : ".strlen($reg_no);
		
		$reg_no = stripVehicleno($reg_no);
		$vehicle_dealer_id = getOthersDealerIdFromCompanyId($vehicle_company_id);
		$vehicle_type_id = getOthersVehicleTypeId();
		
		$model_year = 2016;
		
		$vehicle_id =insertVehicle($vehicle_model_id,$reg_no,"01/01/1970",$engine_no,$chasis_no,$vehicle_type_id,$model_year,1,$vehicle_company_id,$vehicle_dealer_id,'01/01/1970','01/01/1970','01/01/1970',$file_id,$customer_id,NULL,NULL,NULL,NULL);
		
		echo " vehicle_id : ".$vehicle_id;
		
	
}
	
}



}

function insertStalFromExcelPali($file,$agency_id)
{
// IMP	
//SET THIS PARAMS FIRST 	

if(!validateForNull($agency_id) || $agency_id==-1)
return "error";
$inputFileName = $file;
//$inputFileName = 'jeet.xlsx';

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
$rowData=array();
$files_array = array();

//  Loop through each row of the worksheet in turn
for ($row = 2,$file_counter=0; $row <= $highestRow; $row++){ 
    //  Read a row of data into an array
    $rowDat = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                                    NULL,
                                    TRUE,
                                    FALSE);
									
								
    $agr_no = $rowDat[0][0];
	$file_id=getFileIdFromAgreementNo($agr_no);
	
	$agency_oc_id=getAgencyOrCompanyIdFromFileId($file_id);
	$loan_id = getLoanIdFromFileId($file_id);
	$broker = $rowDat[0][15];
	if($agency_oc_id[0]=="agency")
	$broker_ledger_id=getBrokerLedgerId($broker,NULL,$agency_oc_id[1]);
	else
	$broker_ledger_id=getBrokerLedgerId($broker,$agency_oc_id[1],NULL);
	$rasid_no = $rowDat[0][5];
	$payment_date = convertExcelSerialNumberToDate($rowDat[0][6]);
	$amount = $rowDat[0][7];
	if(checkForNumeric($loan_id) && !in_array($agr_no,$files_array))
	{
		
		$loan_details=getLoanById($loan_id);
		$loan_ending_date=getEndingDateForLoan($payment_date,$loan_details['loan_duration'],1);
		$loan_starting_date = convertExcelSerialNumberToDatePHPFormat($rowDat[0][3]);
		$sql="UPDATE fin_loan SET loan_starting_date = '$loan_starting_date', loan_ending_date = '$loan_ending_date' WHERE loan_id = $loan_id";
		dbQuery($sql);
		
		updateEMIsForLoan($loan_id,$loan_details['loan_duration'],$loan_starting_date,false,1);

		$files_array[] = $agr_no;
		
	}
	$loan_emi_id = getOldestUnPaidEmi($loan_id);
	
	
	if(checkForNumeric($loan_emi_id,$amount))
	{
		
		
	$emi_payment_id=insertPayment($loan_emi_id,$amount,2,$payment_date,$rasid_no,"","",$broker,"NA",123456,$payment_date,0,$broker_ledger_id);
	}
	echo " ".$payment_date." ".$emi_payment_id." ";
}
									
}

function getCityNamesString()
{
	$sql="SELECT city_name FROM fin_city WHERE city_name !='Na'";
	$result = dbQuery($sql);
	$resultArray = dbResultToArray($result);
	$city_name_array = array();
	foreach($resultArray as $re)
	{
		$city_name_array[] = strtoupper($re[0]);
	}
	return implode("|",$city_name_array);
}

function convertExcelSerialNumberToDate($serial_no)
{
	if(checkForNumeric($serial_no))
	{
		$loan_approval_date_excel_serial_no = $serial_no; // number of days from 01/01/1900
		
		 $date_01_01_1970 = date("Y-m-d",mktime(0,0,0,1,1,1970));
 		
		 $days_between_1900_1970 = 25569;
		
		 $days_from_1970 = $loan_approval_date_excel_serial_no - $days_between_1900_1970;
		 
		  $loan_approval_date_add_days = $date_01_01_1970.' + '.$days_from_1970.' Days';
		
		$loan_approval_date = date("d/m/Y",strtotime($loan_approval_date_add_days));
		return $loan_approval_date;
	}
}

function convertExcelSerialNumberToDatePHPFormat($serial_no)
{
	if(checkForNumeric($serial_no))
	{
		$loan_approval_date_excel_serial_no = $serial_no; // number of days from 01/01/1900
		
		 $date_01_01_1970 = date("Y-m-d",mktime(0,0,0,1,1,1970));
 		
		 $days_between_1900_1970 = 25569;
		
		 $days_from_1970 = $loan_approval_date_excel_serial_no - $days_between_1900_1970;
		 
		  $loan_approval_date_add_days = $date_01_01_1970.' + '.$days_from_1970.' Days';
		
		$loan_approval_date = date("Y-m-d",strtotime($loan_approval_date_add_days));
		return $loan_approval_date;
	}
}
?>