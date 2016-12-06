<?php
ini_set('memory_limit', '-1');
require_once('cg.php');
require_once('bd.php');
require_once('common.php');
require_once('inventory-functions.php');
require_once('item-manufacturer-functions.php');
require_once('inventory-item-functions.php');
require_once('customer-functions.php');
require_once('area-functions.php');
require_once('city-functions.php');
require_once('customer-group-functions.php');
require_once('account-ledger-functions.php');
require_once('phpExcel/PHPExcel/IOFactory.php');

$inputFileName = 'Ledgers_with_address.xlsx';

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
for ($row = 2; $row <= $highestRow; $row++){ 
//  Read a row of data into an array
$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
								NULL,
								TRUE,
								FALSE);
								


foreach($rowData as $cell_value)
{
	
	$customer_name = $cell_value[0];
	$head = $cell_value[1];
	
	if($head=="Capital Account")
	$head_id = 6;
	else if($head=="Current Assets")
	$head_id = 2;
	else if($head=="current Liability")
	$head_id = 7;
	else if($head=="Deposits(Assets)")
	$head_id = 37;
	else if($head=="Direct Expenses")
	$head_id = 22;
	else if($head=="Fixed Asset")
	$head_id = 19;
	else if($head=="Indirect Expenses")
	$head_id = 23;
	else if($head=="Indirect Incomes")
	$head_id = 21;
	else if($head=="Loans & Advances(Asset)")
	$head_id = 12;
	else if($head=="Loans (Liability)")
	$head_id = 40;
	else if($head=="Sundry Creditors")
	$head_id = 18;
	else if($head=="Unsecured Loans")
	$head_id = 28;
	else if($head=="Sundry Debtors")
	$head_id = -1;
	else
	$head_id=-1;

	$customer_state = $cell_value[4];
	
	if($customer_state=="Bihar")
	$city="Patna";
	else if($customer_state=="Assam")
	$city="Guhawati";
	else if($customer_state=="Andhra Pradesh")
	$city="Hyderabad";
	else if($customer_state=="Chandigarh")
	$city="Chandigarh";
	else if($customer_state=="Delhi")
	$city="Delhi";
	else if($customer_state=="Goa")
	$city="Goa";
	else if($customer_state=="Gujarat")
	$city="Ahmedabad";
	else if($customer_state=="Haryana")
	$city="Faridabad";
	else if($customer_state=="Himachal Pradesh")
	$city="Dharamshala";
	else if($customer_state=="Jammu & Kashmir")
	$city="Jammu and Kashmir";
	else if($customer_state=="Jharkhand")
	$city="Dhanbad";
	else if($customer_state=="Karnataka")
	$city="Bengalore";
	else if($customer_state=="Kerala")
	$city="Kollam";
	else if($customer_state=="Madhya Pradesh")
	$city="Indore";
	else if($customer_state=="Maharashtra")
	$city="Mumbai";
	else if($customer_state=="Odisha")
	$city="Orissa";
	else if($customer_state=="Puducherry")
	$city="Puducherry";
	else if($customer_state=="Punjab")
	$city="Ludhiana";
	else if($customer_state=="Rajasthan")
	$city="Jaipur";
	else if($customer_state=="Tamil Nadu")
	$city="Chennai";
	else if($customer_state=="Uttar Pradesh")
	$city="Noida";
	else if($customer_state=="Uttarakhand")
	$city="Uttarakhand";
	else if($customer_state=="West Bengal")
	$city="Kolkatta";
	
	
	
	
	$city_id = insertCityIfNotDuplicate($city);
	
	$area_id = insertArea('NA',$city_id);
	
	$pincode=$cell_value[5];
	$pan_no = $cell_value[6];
	$cst_no = $cell_value[7];
	$tin_no = $cell_value[8];
	$phone_array=array();
	$phone1=$cell_value[9];
	$phone2=$cell_value[10];
	$contact_person=$cell_value[11];
	$phone4=$cell_value[12];
	$address1=$cell_value[13];
	$address2=$cell_value[14];
	$address3=$cell_value[15];
	$address4=$cell_value[16];
	$address5=$cell_value[17];
	$area_name = "NA";
	$first=0;
	if(validateForNull($address1))
	{
	$address = $address1;
	$first=1;
	}
	if(validateForNull($address2))
	{
	if($first==1)
	$address = $address.", \r\n";	
	$address = $address.$address2;
	$first=1;
	}
	if(validateForNull($address3))
	{
	if($first==1)
	$address = $address.", \r\n";	
	$address = $address.$address3;
	$first=1;
	}
	if(validateForNull($address4))
	{
	if($first==1)
	$address = $address.", \r\n";	
	$address = $address.$address4;
	$first=1;
	}
	if(validateForNull($address5))
	{
	if($first==1)
	$address = $address.", \r\n";	
	$address = $address.$address5;
	$first=1;
	}
	$replace_array = array(' ','-');
	$phone1=str_replace($replace_array,'',$phone1);
	$phone2=str_replace($replace_array,'',$phone2);
	$phone3=str_replace($replace_array,'',$phone3);
	
	$replace_array = array('///','//',',');
	$phone1=str_replace($replace_array,'/',$phone1);
	$phone2=str_replace($replace_array,'/',$phone2);
	$phone3=str_replace($replace_array,'/',$phone3);
	
	if(validateForNull($phone1))
	$phone1_array=explode('/',$phone1);
	else
	$phone1_array=array();
	if(validateForNull($phone2))
	$phone2_array=explode('/',$phone2);
	else
	$phone2_array=array();
	if(validateForNull($phone3))
	$phone3_array=explode('/',$phone3);
	else
	$phone3_array = array();
	if(validateForNull($phone4))
	$phone4_array=explode('/',$phone4);
	else
	$phone4_array=array();
	
	$new_phone_array = array_merge($phone1_array,$phone2_array,$phone3_array,$phone4_array);
	$valid_phone_index_array=array();
	$invalid_phone_index_array = array();
	$has_valid_mobile = 0;$has_invalid_mobile=0;
	
	foreach($new_phone_array as $index => $phone)
	{
		if(strlen($phone)==10)
		{$has_valid_mobile++;
		$valid_phone_index_array[]=$index;
		}
		else
		{
		$invalid_phone_index_array[]=$index;
		$has_invalid_mobile++;
		}
	}
	
	if($has_valid_mobile>0)
	{
		foreach($valid_phone_index_array as $index)
		$phone_array[] = $new_phone_array[$index];
	}
	
	if($has_valid_mobile==0)
	$phone_array[]="9999999999";
	if($has_invalid_mobile>0)
	{
		foreach($invalid_phone_index_array as $index)
		$phone_array[] = $new_phone_array[$index];
	}
	$contact_person=str_replace("'","",$contact_person);
	$contact_person = clean_data($contact_person);
	
	if(!checkForNumeric($pincode))
	$pincode="";
	
	if($head_id==-1)
	{
		
	$customer_id=insertCustomer($customer_name,$address,$city_id,"NA",$pincode,$phone_array,NULL,NULL,NULL,"NA",$tin_no,'',0,0,$cst_no,"",$cell_value[3],"");
	if(is_numeric($customer_id))
	addRemainder($customer_id,NULL,$contact_person);
	}
	else
	{
//insertLedger($customer_name,'','',3,2,0,$head_id,NULL,$pan_no,$tin_no,$contact_person,0,0,NULL,0,'NULL',$cst_no,NULL);}
	echo $row."<br>";
}
	
}

/*	$item_name=$rowData[0][1];
$item_name = clean_data($item_name);
$mfg_item_code = $rowData[0][0];
$mrp=round($rowData[0][3],2); */

//	$mfg = $rowData[0][2];
//	$openning_quantity = $rowData[0][4];
//	$openning_balance = $rowData[0][5];
//	$opening_rate = $openning_balance/$openning_quantity;
//	if(!is_numeric($mrp))
//	$mrp=0;


if( $mfg_item_code!="")
{
/*	if($row<=35160)	
{
$sql="UPDATE edms_tem_part_number SET item_name = '$item_name', mrp = $mrp WHERE part_number ='$mfg_item_code' ";
dbQuery($sql);	
}
else */
{
//	echo $row." <br>";
//	$sql="INSERT INTO edms_tem_part_number (part_number,item_name,mrp) VALUES ('$mfg_item_code','$item_name',$mrp)";
//	dbQuery($sql);
}
} 


}

?>