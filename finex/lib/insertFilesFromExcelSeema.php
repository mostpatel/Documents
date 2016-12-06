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
require_once('vehicle-model-functions.php');
require_once('vehicle-type-functions.php');
require_once('vehicle-dealer-functions.php');
require_once('customer-functions.php');
require_once('guarantor-functions.php');
require_once('loan-functions.php');
require_once "cheque-functions.php";

function insertFileFromExcelSeema($file)
{
// IMP	
//SET THIS PARAMS FIRST 	
$broker_id = 1; // check direct broker
$agency_id = "oc10003";	
	
	
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
for ($row = 1,$file_counter=0; $row <= $highestRow; $row++){ 
    //  Read a row of data into an array
    $rowDat = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                                    NULL,
                                    TRUE,
                                    FALSE);

	
	$rowData=$rowDat[0];
    
	
	if($row==1)
	{
	$file_agr_no = $rowData[1];
	$file_no = str_replace("-","",$rowData[1]);
	$vehicle_reg_no=str_replace("-","",$rowData[4]);
	$loan_starting_date = convertExcelSerialNumberToDate($rowData[7]);
	$loan_starting_date_php_format = convertExcelSerialNumberToDatePHPFormat($rowData[7]);
	$loan_approval_date = date('d/m/Y',strtotime($loan_starting_date_php_format.'-1month'));
	}
	
	if($row==2)
	{
	$customer_name = $rowData[1];	
	
	}
	
	if($row==3)
	{
		$customer_address_line1 = $rowData[1];
		
	}
	
	if($row==4)
	{
		$customer_address_line2 = $rowData[1];
		
	}
	
	if($row==5)
	{
		$customer_address_line3 = $rowData[1];
		$customer_address = $customer_address_line1." ".$customer_address_line2." ".$customer_address_line3;
		if(validateForNull($customer_address_line3))
		{
		$customer_area_city_array = explode(",",$customer_address_line3);
		$cusomer_area = $customer_area_city_array[0];
		$customer_city_id = insertCityIfNotDuplicate($customer_area_city_array[1]);
		}
	}
	
	if($row==6)
	{
		
		$customer_contact_no = $rowData[1];
		
		if(validateForNull($customer_contact_no))
		{
		$customer_contact_no_array = explode(",",$customer_contact_no);
		if(!checkForNumeric($customer_contact_no_array[0]) && sizeof($customer_contact_no_array[0]!=10))
		$customer_contact_no_array=array('9999999999');
	    }
	}
	
	if($row==8)
	{
	$vehicle_chasis_no = $rowData[1];
   if(!validateForNull($vehicle_chasis_no))
	$vehicle_chasis_no="NA";	
	}	
	
	if($row==9)
	{
	$ac_no = $rowData[1];
	}	
	
	if($row==10)
	{
	$bank_name = $rowData[1];
	}	
	
	if($row==11)
	{
	$branch_name = $rowData[1];
	}	
	
	if($row==15)	
	{
		$loan_amount = $rowData[1];
		
	}	
	
	if($row==16)	
	{
		
		$emi = $rowData[1];
	}	
	if($row==17)	
	{
		
		$duration = $rowData[1];
	}	
	
	if($row==18)
	{
	$guarantor_name = $rowData[1];	
	
	}
	
	if($row==19)
	{
		$guarantor_address_line1 = $rowData[1];
		
	}
	
	if($row==20)
	{
		$guarantor_address_line2 = $rowData[1];
		$guarantor_address = $guarantor_address_line1." ".$guarantor_address_line2;
		if(validateForNull($guarantor_address_line2))
		{
		$guarantor_area_city_array = explode(",",$guarantor_address_line2);
		$guarantor_area = $guarantor_area_city_array[0];
		$guarantor_city_id = insertCityIfNotDuplicate($guarantor_area_city_array[1]);
		}
	}
	
		if($row==21)
	{
		
		$guarantor_contact_no = $rowData[1];
		
		if(validateForNull($guarantor_contact_no))
		{
		$guarantor_contact_no_array = explode(",",$guarantor_contact_no);
		if(!checkForNumeric($guarantor_contact_no_array[0]) && sizeof($guarantor_contact_no_array[0]!=10))
		$guarantor_contact_no_array=array('9999999999');
	    }
	}
	
	if($row==23)
	{
	$guarantor_name_2 = $rowData[1];	
	
	}
	
	if($row==24)
	{
		$guarantor_address_line1_2 = $rowData[1];
		
	}
	
	if($row==25)
	{
		$guarantor_address_line2_2 = $rowData[1];
		$guarantor_address_2 = $guarantor_address_line1_2." ".$guarantor_address_line2_2;
		if(validateForNull($guarantor_address_line2_2))
		{
		$guarantor_area_city_array_2 = explode(",",$guarantor_address_line2_2);
		$guarantor_area_2 = $guarantor_area_city_array_2[0];
		$guarantor_city_id_2 = insertCityIfNotDuplicate($guarantor_area_city_array_2[1]);
		}
	}
	
		if($row==26)
	{
		
		$guarantor_contact_no_2 = $rowData[1];
		
		if(validateForNull($guarantor_contact_no_2))
		{
		$guarantor_contact_no_array_2 = explode(",",$guarantor_contact_no_2);
		if(!checkForNumeric($guarantor_contact_no_array_2[0]) && sizeof($guarantor_contact_no_array_2[0]!=10))
		$guarantor_contact_no_array_2=array('9999999999');
	    }
	}
	
	if($row==42)
	{
		$received_cheques = $rowData[8];
		$received_cheques = str_replace(" CHE","",$received_cheques);
		if(!checkForNumeric($received_cheques))
		$received_cheques=0;
	}
	
	
	if($row==$highestRow)
	{
	
	echo "<br>Excel : ".$inputFileName." Loan No: ".$file_agr_no;
		
	$collection = $duration*$emi;

	$interest = $collection - $loan_amount;
	
	$interest_per_month = $interest/$duration;
	
	$roi_per_month = ($interest_per_month/$loan_amount)*100;
	$roi = $roi_per_month*12;
		
	
	$file_id =  addNewCustomer($agency_id,$file_agr_no,$file_no,$broker_id,$customer_name,$customer_address,$customer_city_id,$cusomer_area,"",$customer_contact_no_array,array(),array(),array(),array(),$guarantor_name,$guarantor_address,$guarantor_city_id,$guarantor_area,"",$guarantor_contact_no_array,array(),array(),array(),array(),$loan_amount,1,$duration,1,1,$roi,$emi,$loan_approval_date,$loan_starting_date,array(),array(),array(),array(),array(),array());
	echo " File_id : ".$file_id;
	if(!checkForNumeric($file_id))
	echo " File_id_error ";
	
	$customer_id = getCustomerIdByFileId($file_id);
	$loan_id = getLoanIdFromFileId($file_id);
	
	if(!checkForNumeric($customer_id))
	echo " customer_id_error ";
	if(!checkForNumeric($loan_id))
	echo " loan_id_error ";
	if(checkForNumeric($file_id, $guarantor_city_id_2) && validateForNull($guarantor_name_2,$guarantor_address_2,$guarantor_area_2))
	{
		
		
		if(checkForNumeric($customer_id))
		insertGuarantor($guarantor_name_2,$guarantor_address_2,$guarantor_city_id_2,$guarantor_area_2,NULL,$file_id,$customer_id,$guarantor_contact_no_array_2,NULL,NULL,NULL,NULL,NULL,NULL);
		
	}	
	
	if(validateForNull($vehicle_reg_no))
	{
		echo " Reg NO : ".$vehicle_reg_no;
		
		$vehicle_company_id=getOthersVehicleCompanyId();
		$vehicle_model_id = getOthersModelByCompanyId($vehicle_company_id);
		$vehicle_dealer_id = getOthersDealerIdFromCompanyId($vehicle_company_id);
		$vehicle_type_id = getOthersVehicleTypeId();
		$vehicle_id =insertVehicle($vehicle_model_id,$vehicle_reg_no,"01/01/1970","NA",$vehicle_chasis_no,$vehicle_type_id,2016,1,$vehicle_company_id,$vehicle_dealer_id,'01/01/1970','01/01/1970','01/01/1970',$file_id,$customer_id,NULL,NULL,NULL,NULL);
		
		echo " vehicle_id : ".$vehicle_id;
		
	}
	
	if(validateForNull($bank_name,$branch_name) && checkForNumeric($ac_no))
	{
	$cheque_details_id=insertLoanCheques($file_id,$bank_name,$branch_name,$duration,$received_cheques,0,0,$customer_id,"",array(),$ac_no);	
	}
	
	echo " Bank_and_chq_details : ".$cheque_details_id;
	
	
}
	
}

for ($row = 4,$file_counter=0; $row <= $highestRow; $row++){ 
    //  Read a row of data into an array
    $rowDat = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                                    NULL,
                                    TRUE,
                                    FALSE);

	$cheque_details=ListChequesForFileId($file_id);
	$rowData=$rowDat[0];
	$payment_date = $rowData[3];
	$payment_date = convertExcelSerialNumberToDate($payment_date);
    $amount = $rowData[7];
	$cheque_no = $rowData[9];
	$remarks = $rowData[10];
	$loan_emi_id = getOldestUnPaidEmi($loan_id);
	if(checkForNumeric($cheque_no))
	{
		$cheque_no=str_pad($cheque_no,6,"0",STR_PAD_LEFT);
		
		$emi_payment_id=insertPayment($loan_emi_id,$amount,2,$payment_date,9999,$remarks,"",$cheque_details['bank_name'],$cheque_details['branch_name'],$cheque_no,$payment_date,0);
		
	}
	else
	$emi_payment_id=insertPayment($loan_emi_id,$amount,1,$payment_date,9999,$remarks,"");
		
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
		
		$loan_approval_date = date("d/m/Y",strtotime($loan_approval_date_add_days));
		return $loan_approval_date;
	}
}
?>