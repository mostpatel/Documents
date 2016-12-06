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

function insertFileFromExcelSurat($file,$agency_id)
{
// IMP	
//SET THIS PARAMS FIRST 	
$broker_id = 1; // check direct broker


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
for ($row = 1,$file_counter=0; $row <= $highestRow; $row++){ 
    //  Read a row of data into an array
    $rowDat = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                                    NULL,
                                    TRUE,
                                    FALSE);

	
	$rowData=$rowDat[0][0];
    $rowData=trim($rowData);
	//print_r($rowData);
	//echo $row." ".$rowData."<br>";
	
	if(checkForNumeric(strpos($rowData,"File No.:")))
	{
		$file_no_position = strpos($rowData,"File No.:");
		
		$file_no=substr($rowData,$file_no_position);
		$file_no = str_replace("File No.:","",$file_no);
		$file_no=trim(substr($file_no,0,(strlen($file_no)-1)));
		$file_no_array = explode("/",$file_no);
		$file_number = $file_no_array[1];
		$file_year = $file_no_array[2];
		$file_year = str_replace("-","",$file_year);
		$file_year=(int)$file_year;
		$file_agr_no = $file_no_array[1]."/".$file_year;
		$file_agr_no = clean_data($file_agr_no);
		$file_no = $file_year."/".$file_number;
		$file_no = str_replace(" ","",$file_no);
		
		
	}
	
	if(checkForNumeric(strpos($rowData,"Name & Address of the Hirer")))
	{
		
		$row_name_of_customer_heading = $row;
		
	}
	
	if(checkForNumeric(strpos($rowData,"ENew/Used :")))
	{
		
		$row_vehicle_info_start = $row;	
		$chasis_no_start = strpos($rowData,"Chassis No. : ");
		$chasis_no = str_replace("Chassis No. : ","",substr($rowData,$chasis_no_start));
		$chasis_no = str_replace("F","",$chasis_no);
	
	}
	
	if(checkForNumeric(strpos($rowData,"Model..")))
	{
		
		$row_vehicle_model = $row;	
		$model_start = strpos($rowData,"Model... : ");
		$model_year = str_replace("Model... : ","",substr($rowData,$model_start));
		$model_year = str_replace("F","",$model_year);
		if(!checkForNumeric($model_year))
		$model_year = 2016;
	}
	
	if(checkForNumeric(strpos($rowData,"Engine No.. :")))
	{
		
		$engine_no_make_row = $row;	
		
		$engine_no_start = strpos($rowData,"Engine No.. : ");
		$engine_no = str_replace("Engine No.. : ","",substr($rowData,$engine_no_start));
		$engine_no = str_replace("F","",$engine_no);
	    $engine_no_array = explode("EMake :",$engine_no);
		$engine_no = $engine_no_array[0];
		$make = $engine_no_array[1];
	
	}
	
	if(checkForNumeric(strpos($rowData,"Date of Agreement :")))
	{
		$date_of_agreement_row = $row;	
		$agreement_date_array = explode("EDate of Agreement : ",$rowData);
		$agreement_date_array=$agreement_date_array[1];
		$agreement_date_array = explode("ERegestration No. :",$agreement_date_array);
		$agreement_date = $agreement_date_array[0];
		$loan_approval_date = clean_data(str_replace("F","",$agreement_date));
		$loan_approval_date= substr($loan_approval_date,1,10);
		$loan_approval_date = trim($loan_approval_date);
	   
		$reg_no = $agreement_date_array[1];
		$reg_no = substr($reg_no,1);
		
	}
	
	if(checkForNumeric(strpos($rowData,"Balance..........:")))
	{
		
		$balance_row = $row;	
		preg_match_all('!\d+!', $rowData, $matches);
		$loan_amount = $matches[0][0];
	//	$loan_amount_array = str_replace("EBalance..........:" , "",$rowData);
	//	$loan_amount = $loan_amount_array;
		
	//	$loan_amount = str_replace("F","",$loan_amount);
	//	$loan_amount = clean_data($loan_amount);
		
		
		
	}
	
	if(checkForNumeric(strpos($rowData,"Hire Purchase Charges.:")))
	{
		
		$hire_purchase_charge_row = $row;	
	}
	
	if(checkForNumeric(strpos($rowData,"Sr.No.")))
	{
		
		$sr_no_and_other_details_start_row = $row;	
		$installment_start_row = $row + 2;
		$loan_duration = 0;
	}
	
	if(isset($sr_no_and_other_details_start_row) && checkForNumeric($sr_no_and_other_details_start_row) && $row>($sr_no_and_other_details_start_row+2) && checkForNumeric(strpos($rowData,"--------------------------------------------------------------------------------")))
	{
		$end_of_installment_chart_row=$row;
		
	}
	
	if(checkForNumeric(strpos($rowData,"================================================================================")))
	{
		
		$end_of_file_row = $row;	
	}
	
	
	
	
	if(isset($row_name_of_customer_heading) && $row>$row_name_of_customer_heading && !isset($row_vehicle_info_start))
	{
		
	if(validateForNull($rowData))
	{
	
		
		if(!isset($customer_name))
		{
		$customer_name = $rowData;
		$customer_name = str_replace("SPEED","",$customer_name);
		$customer_name = clean_data($customer_name);
		}
		else if(!isset($customer_address))
		{
		$customer_address = $rowData;
		$customer_address = clean_data($customer_address);
		}
		else if(!isset($customer_area) && checkForNumeric(strpos($rowData,"TAL")))
		{
			
			$customer_area = $rowData;
			$customer_area = str_replace("TAL.","",$customer_area);
			$customer_area = str_replace(".","",$customer_area);
			$customer_area = str_replace(",","",$customer_area);
			$customer_area = clean_data($customer_area);
			$customer_address = $customer_address." ".$customer_area;
			
			}
		else if(checkForNumeric(strpos($rowData,"DIST")))
		{
			
			$customer_city = $rowData;
			$customer_city = str_replace("DIST.","",$customer_city);
			$customer_city = str_replace(".","",$customer_city);
			$customer_city = str_replace(",","",$customer_city);
			$customer_city = clean_data($customer_city);
			$customer_city_id = insertCityIfNotDuplicate($customer_city);
			$customer_address = $customer_address." ".$customer_city;
		}
		else if(checkForNumeric(strpos($rowData,"--------------------------------------------------------------------------------")))
		{
			if(isset($customer_contact_no) && checkForNumeric($customer_contact_no) && strlen($customer_contact_no)==10 && !isset($customer_contact_no_array))
			{
				$customer_contact_no_array = array($customer_contact_no);
			}
			else
			$customer_contact_no_array = array("9999999999");
			
		}
		else
		{
			$customer_contact_no = substr($rowData,0,10);
			$customer_contact_no = clean_data($customer_contact_no);
		}
	}
	
	}

	
	
	if(isset($installment_start_row) && $row>=$installment_start_row && !isset($end_of_installment_chart_row))
	{
		
		$installment_details = array_values(array_filter(explode(" ",$rowData)));
		
		$installment_no = $installment_details[0];
		
		if($row==$installment_start_row)
		{
		$actual_loan_duration = 1;
		$emi = $installment_details[1];
		$loan_starting_date = $installment_details[2];
		$installment_array_array = array();
		}
		
		if($installment_no==$actual_loan_duration)
		{
			$actual_loan_duration++;
			$payment_amount = $installment_details[3];
			$payment_date = $installment_details[4];
			if(isset($installment_details[6]))
			$rasid_no = $installment_details[5];
			else
			$rasid_no =9999;
		}
		else
		{
			$payment_amount = $installment_details[0];
			$payment_date = $installment_details[1];
			if(isset($installment_details[3]))
			$rasid_no = $installment_details[2];
			else
			$rasid_no =9999;
			
		}
		
		$installment_array_array[] = array($payment_amount,$payment_date,$rasid_no);
		
		
	}
	
	
	
	
	if(isset($end_of_file_row) && $row==$end_of_file_row)
	{
	$duration = $actual_loan_duration-1;
	
	echo "<br>Excel : ".$inputFileName." Loan No: ".$file_agr_no;
		
	$collection = $duration*$emi;

	$interest = $collection - $loan_amount;
	
	$interest_per_month = $interest/$duration;
	
	$roi_per_month = ($interest_per_month/$loan_amount)*100;
	$roi = $roi_per_month*12;
		
	
	
	$file_id =  addNewCustomer($agency_id,$file_agr_no,$file_no,$broker_id,$customer_name,$customer_address,$customer_city_id,$customer_area,"",$customer_contact_no_array,array(),array(),array(),array(),$guarantor_name,$guarantor_address,$guarantor_city_id,$guarantor_area,"",$guarantor_contact_no_array,array(),array(),array(),array(),$loan_amount,1,$duration,1,1,$roi,$emi,$loan_approval_date,$loan_starting_date,array(),array(),array(),array(),array(),array());
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
		echo " Reg NO : ".strlen($reg_no);
		
		$vehicle_company_id=getOthersVehicleCompanyId();
		$vehicle_model_id = getOthersModelByCompanyId($vehicle_company_id);
		$vehicle_dealer_id = getOthersDealerIdFromCompanyId($vehicle_company_id);
		$vehicle_type_id = getOthersVehicleTypeId();
		
		
		
		$vehicle_id =insertVehicle($vehicle_model_id,$reg_no,"01/01/1970",$engine_no,$chasis_no,$vehicle_type_id,$model_year,1,$vehicle_company_id,$vehicle_dealer_id,'01/01/1970','01/01/1970','01/01/1970',$file_id,$customer_id,NULL,NULL,NULL,NULL);
		
		echo " vehicle_id : ".$vehicle_id;
		
	}
	
	
	
	
}
	
}

foreach($installment_array_array as $installment_array){ 
    //  Read a row of data into an array
    

	
	$loan_emi_id = getOldestUnPaidEmi($loan_id);
	
	$emi_payment_id=insertPayment($loan_emi_id,$installment_array[0],1,$installment_array[1],$installment_array[2],"","");
		
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