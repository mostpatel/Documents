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
require_once('loan-functions.php');
$inputFileName = 'c_and_d.xls';

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
/*	$file_number=$rowData[0][0];
	$agreement_no=$rowData[0][1];
	if($agreement_no!="" && $file_number!="")
	{
	$file_number=stripFileNo($file_number);
	$agreement_no=substr_replace($agreement_no,"O",1,1);
	$sql="UPDATE fin_file SET file_agreement_no='$agreement_no' WHERE file_number='$file_number'";	
	dbQuery($sql);		
		
	} */
    //  Insert row data array into your database of choice here
	$rowData[]=$rowDat[0];
	//print_r($rowDat[0]);
	//echo "<br>";
	if($rowDat[0][2]=="ANUPAM AHMEDABAD-AH")
	{
	$file_counter++;
	$hirer_address_row_no = -1;
	$article_row_no = -1;
	$due_chart_start_row_no =-1;
	$due_chart_end_row_no =-1;
	$emi_duration_array = array();
	$payments_array = array();
	}
	
	//$files_array[$file_counter][]=$rowDat[0];
	if($rowDat[0][2]=="Article :")
	{
	$file_agreement_no = $rowDat[0][50];
	$vehicle_model = $rowDat[0][9];
	
	$file_no = $rowDat[0][46];	
	$files_array[$file_counter]['file_number']=$file_no;
	$files_array[$file_counter]['vehicle']['model_name']=$vehicle_model;
	$file_agreement_no = str_replace(array("[","]"),"",$file_agreement_no);
	
	if(validateForNull($file_agreement_no))
	$files_array[$file_counter]['file_agreement_number']= $file_agreement_no;
	else
	$files_array[$file_counter]['file_agreement_number'] = $file_no;
	$article_row_no = $row;
	}
	if($rowDat[0][2]=="HIRER ADDRESS")
	{
	$hirer_address_row_no = $row;
	}
	
	if($rowDat[0][1]=="Due#.")
	{
	$due_chart_start_row_no = $row;
	}
	if($rowDat[0][2]=="Total")
	{
	$due_chart_end_row_no = $row;
	}
	
	if($hirer_address_row_no>=0 && $hirer_address_row_no+2==$row)
	{
			
	$files_array[$file_counter]['customer_name']=$rowDat[0][2];
	$files_array[$file_counter]['guarantor_name']=$rowDat[0][31];
	$files_array[$file_counter]['broker_name']=$rowDat[0][44];
	}
	if($hirer_address_row_no>=0 && $hirer_address_row_no+3==$row)
	{
	
	
		if(validateForNull($rowDat[0][2]) && preg_match("/.*[a-zA-Z]+\s*.*/",$rowDat[0][2]))
		{
			
			$customer_address=$rowDat[0][2];	
			$customer_alt_address=$rowDat[0][17];
			$customer_phone_array = array();
			
			preg_match_all( '/(Ph. :|Mob.:)\s[0-9\/\-]+/', $customer_address, $customer_phone_array );
			
				if(empty($customer_phone_array[0]))
				{	
				$customer_phone_array = array();
				$customer_phone_array[] = "9999999999";
				}
				else
				{
					$customer_phone_array_mixed = $customer_phone_array[0];
					$customer_phone_array = array();
					foreach($customer_phone_array_mixed as $customer_phone)
					{
						$customer_phone = preg_replace("/(Ph. :|Mob.:)\s*/","",$customer_phone);
						if(strstr($customer_phone,"/"))
						{
							$temp_phone_array=explode("/",$customer_phone);
							$temp_phone_array[0] = str_replace("-","",$temp_phone_array[0]);	
							if(!empty($temp_phone_array) && checkForNumeric($temp_phone_array[0]))
							{
								foreach($temp_phone_array as $temp_phone)
								{
								$temp_phone = str_replace("-","",$temp_phone);			
								if(checkForNumeric($temp_phone))	
								$customer_phone_array[]=$temp_phone;
								}
							}
						}
						else
						{
						$customer_phone = str_replace("-","",$customer_phone);		
						if(checkForNumeric($customer_phone))	
						$customer_phone_array[] = $customer_phone;
						}
					
					}
				}
				
				$customer_phone_alt_array = array();
				preg_match_all(  '/(Ph. :|Mob.:)\s[0-9\/\-]+/', $customer_alt_address, $customer_phone_alt_array );
				if(empty($customer_phone_alt_array[0]))
				{
				$customer_phone_alt_array = array();	
				$customer_phone_alt_array[] = "9999999999";
				}
				else
				{
					$customer_phone_array_mixed = $customer_phone_alt_array;
					$customer_phone_alt_array = array();
					foreach($customer_phone_array_mixed as $customer_phone)
					{
						$customer_phone = preg_replace("/(Ph. :|Mob.:)\s*/","",$customer_phone);
						if(strstr($customer_phone,"/"))
						{
							$temperory_phone_array=explode("/",$customer_phone);
							$temp_phone_array = array();
							foreach($temperory_phone_array as $temp_phone)
							{
							$temp_phone = str_replace("-","",$temp_phone);
							$temp_phone_array[] = $temp_phone;
							}
							if(!empty($temp_phone_array) && checkForNumeric($temp_phone_array[0]))
							{
								foreach($temp_phone_array as $temp_phone)
								$customer_phone_alt_array[]=$temp_phone;
								
								
							}
						}
						else
						{
						$customer_phone = str_replace("-","",$customer_phone);	
						if(checkForNumeric($customer_phone))	
						$customer_phone_alt_array[] = $customer_phone;
						}
					
					}
				}
			
				$customer_phone_array = array_merge($customer_phone_array,$customer_phone_alt_array);
				$customer_phone_array = array_unique($customer_phone_array);
				
				$customer_address_array = array();
				$customer_address = preg_replace( '/(Ph. :|Mob.:)\s[0-9\/\-]+/',"",$customer_address);
				
				$customer_address = preg_replace( '/AHAMEDABAD|AHAMADABAD/','AHMEDABAD',$customer_address);
				
				$customer_address = preg_replace( '/KHENA/','KHEDA',$customer_address);
				$customer_pincode_array = array();
				
				preg_match('/(P.O.)\s*[0-9\s]+/',$customer_address,$customer_pincode_array);
				$customer_address = preg_replace( '/(P.O.)\s*[0-9\-]+/','',$customer_address);
				
				$files_array[$file_counter]['customer_address'] = $customer_address;
				$files_array[$file_counter]['customer_mobile'] = $customer_phone_array;
				if(validateForNull($customer_pincode_array[0]))
				{
				$customer_pincode = str_replace("P.O.","",$customer_pincode_array[0]);
				$customer_pincode = str_replace(" ","",$customer_pincode);	
				$files_array[$file_counter]['customer_pincode'] = $customer_pincode;
				}
				$city_string=getCityNamesString();
				$city_regex = "/".$city_string."/";
				
				$customer_city_array = array();
				preg_match(  $city_regex, $customer_address, $customer_city_array );
				if(validateForNull($customer_city_array[0]))
				{
					$files_array[$file_counter]['customer_city_name'] = $customer_city_array[0];
					$area_name_regex = "/[A-Z]+[\s\.\-]*".$customer_city_array[0]."[\(D\)]*/";
					
					$customer_area_array = array();
					if(preg_match(  $area_name_regex, $customer_address, $customer_area_array))
					{
						$customer_area_name = trim(str_replace($customer_city_array[0],"",$customer_area_array[0])); 
						$customer_area_name_array =  array();
						preg_match( "/\s*[A-Z]+/", $customer_area_name, $customer_area_name_array);
						
						$files_array[$file_counter]['customer_area_name'] = $customer_area_name_array[0];
					}
				}
				else if(validateForNull($customer_address))
				{	
				echo "here";	
					echo $file_no;
					exit;
				}
				else
				{
					$files_array[$file_counter]['customer_city_name']="NA";
					$files_array[$file_counter]['customer_area_name']="NA";	
				}
		}
		else
		{
			$files_array[$file_counter]['customer_address']="NA";
			$files_array[$file_counter]['customer_city_name']="NA";
			$files_array[$file_counter]['customer_area_name']="NA";
			$files_array[$file_counter]['customer_mobile'][]=9999999999;
		}
		
		if(validateForNull($rowDat[0][32]) && preg_match("/.*[a-zA-Z]+\s*.*/",$rowDat[0][32]))
		{
			
			$guarantor_address=$rowDat[0][32];	
			
			$guarantor_phone_array = array();
			
			preg_match_all( '/(Ph. :|Mob.:)\s[0-9\/\-]+/', $guarantor_address, $guarantor_phone_array );
			
			if(empty($guarantor_phone_array[0]))
			{	
			$guarantor_phone_array = array();
			$guarantor_phone_array[] = "9999999999";
			}
			else
			{
				$guarantor_phone_array_mixed = $guarantor_phone_array[0];
				$guarantor_phone_array = array();
				foreach($guarantor_phone_array_mixed as $guarantor_phone)
				{
					$guarantor_phone = preg_replace("/(Ph. :|Mob.:)\s*/","",$guarantor_phone);
					if(strstr($guarantor_phone,"/"))
					{
						$temp_phone_array=explode("/",$guarantor_phone);
						$temp_phone_array[0] = str_replace("-","",$temp_phone_array[0]);	
						if(!empty($temp_phone_array) && checkForNumeric($temp_phone_array[0]))
						{
							foreach($temp_phone_array as $temp_phone)
							{
							$temp_phone = str_replace("-","",$temp_phone);			
							if(checkForNumeric($temp_phone))	
							$guarantor_phone_array[]=$temp_phone;
							}
						}
					}
					else
					{
					$guarantor_phone = str_replace("-","",$guarantor_phone);		
					if(checkForNumeric($guarantor_phone))	
					$guarantor_phone_array[] = $guarantor_phone;
					}
				
				}
			}
			
			$guarantor_phone_array = array_unique($guarantor_phone_array);
			
			$guarantor_address_array = array();
			$guarantor_address = preg_replace( '/(Ph. :|Mob.:)\s[0-9\/\-]+/',"",$guarantor_address);
			
			$guarantor_address = preg_replace( '/AHAMEDABAD|AHAMADABAD/','AHMEDABAD',$guarantor_address);
			
			$guarantor_address = preg_replace( '/KHENA/','KHEDA',$guarantor_address);
			$guarantor_pincode_array = array();
			
			preg_match('/(P.O.)\s*[0-9\s]+/',$guarantor_address,$guarantor_pincode_array);
			$guarantor_address = preg_replace( '/(P.O.)\s*[0-9\-\s]+/','',$guarantor_address);
			
			$files_array[$file_counter]['guarantor_address'] = $guarantor_address;
			$files_array[$file_counter]['guarantor_mobile'] = $guarantor_phone_array;
			$files_array[$file_counter]['guarantor_pincode'] = $guarantor_pincode_array;
			if(validateForNull($guarantor_pincode_array[0]))
				{
				$guarantor_pincode = str_replace("P.O.","",$guarantor_pincode_array[0]);
				$guarantor_pincode = str_replace(" ","",$guarantor_pincode);	
				$files_array[$file_counter]['guarantor_pincode'] = $guarantor_pincode;
				}
			$city_string=getCityNamesString();
			$city_regex = "/".$city_string."/";
			
			$guarantor_city_array = array();
			preg_match(  $city_regex, $guarantor_address, $guarantor_city_array );
			if(validateForNull($guarantor_city_array[0])) // matching our city database with the name of city in the whole address
			{
			$files_array[$file_counter]['guarantor_city_name'] = $guarantor_city_array[0];
			$area_name_regex = "/[A-Z]+[\s\.\-]*".$guarantor_city_array[0]."[\(D\)]*/";
			
			$guarantor_area_array = array();
			if(preg_match(  $area_name_regex, $guarantor_address, $guarantor_area_array))
			{
				
				$guarantor_area_name = trim(str_replace($guarantor_city_array[0],"",$guarantor_area_array[0])); 
				$guarantor_area_name_array =  array();
				preg_match( "/\s*[A-Z]+/", $guarantor_area_name, $guarantor_area_name_array);
				
				$files_array[$file_counter]['guarantor_area_name'] = $guarantor_area_name_array[0];
			}
			}
			else if(validateForNull($guarantor_address)) // if there is addressbut city name doesnot match with our cities
			{
			echo $guarantor_address;	
			echo "gaurantor_address";		
			echo $file_no;
			exit;
			}
			else
			{
			$files_array[$file_counter]['guarantor_city_name']="NA";
			$files_array[$file_counter]['guarantor_area_name']="NA";	
			}
		}
		else // if guarantor address not available
		{
			$files_array[$file_counter]['guarantor_address']="NA";
			$files_array[$file_counter]['guarantor_city_name']="NA";
			$files_array[$file_counter]['guarantor_area_name']="NA";
			$files_array[$file_counter]['guarantor_mobile'][]=9999999999;
		}
		
	} // customer and address if condtion

	if($article_row_no>=0 && $article_row_no+1==$row)
	{
		if(checkForNumeric($rowDat[0][9]))
		$files_array[$file_counter]['vehicle']['model_year'] = $rowDat[0][9];
		else
		$files_array[$file_counter]['vehicle']['model_year'] = 1970;
		
		$files_array[$file_counter]['insurance']['issue_date'] = $rowDat[0][28];
		$loan_approval_date_excel_serial_no = $rowDat[0][46]; // number of days from 01/01/1900
		
		 $loan_approval_date = convertExcelSerialNumberToDate($loan_approval_date_excel_serial_no);
		$files_array[$file_counter]['loan_approval_date']= $loan_approval_date;
		
	} // model, Agreement Date, Insurance issue date and Loan Approval Date and endorsement
	
	if($article_row_no>=0 && $article_row_no+2==$row)
	{
	$chasis_no = $rowDat[0][9];	
	if(validateForNull($chasis_no))
	$files_array[$file_counter]['vehicle']['chasis_no'] = $chasis_no;	
	else
	$files_array[$file_counter]['vehicle']['chasis_no'] = "NA";	
	$files_array[$file_counter]['insurance']['expiry_date'] = $rowDat[0][27];
	} // chasis,  Insurance expiry date 
	
	if($article_row_no>=0 && $article_row_no+3==$row)
	{
	$engine_no = $rowDat[0][9];
	if(validateForNull($engine_no))		
	$files_array[$file_counter]['vehicle']['engine_no'] = $engine_no;
	else
	$files_array[$file_counter]['vehicle']['engine_no'] = "NA";
	
	$fitness_expiry_date = $rowDat[0][27];
	if(validateForNull($fitness_expiry_date))	
	$files_array[$file_counter]['vehicle']['fitness_expiry_date'] = $fitness_expiry_date;
	else
	$files_array[$file_counter]['vehicle']['fitness_expiry_date'] = "01/01/1970";
	} // engine no and F.C date
	
	if($article_row_no>=0 && $article_row_no+4==$row)
	{
	$permit_expiry_date = $rowDat[0][27];
	if(validateForNull($permit_expiry_date))	
	$files_array[$file_counter]['vehicle']['permit_expiry_date'] = $permit_expiry_date;
	else
	$files_array[$file_counter]['vehicle']['permit_expiry_date'] = "01/01/1970";	
	
	 $vehicle_reg_no = $rowDat[0][46];
	 $vehicle_reg_no = str_replace("/","",$vehicle_reg_no);
	  $vehicle_reg_no = str_replace("-","",$vehicle_reg_no);
	  $files_array[$file_counter]['vehicle']['vehicle_reg_no'] = $vehicle_reg_no;	
	} // permit and tax and reg no
	
	if($article_row_no>=0 && $article_row_no+6==$row)
	{
		
		$files_array[$file_counter]['loan_amount'] = $rowDat[0][9];
		$files_array[$file_counter]['roi'] = $rowDat[0][28];
	} // loan_amount
	
	if($due_chart_start_row_no>=0 && $row>$due_chart_start_row_no && $due_chart_end_row_no==-1)
	{
		
		$emi_no=$rowDat[0][2];
		
		if(is_numeric($emi_no))
		{
			$emi_amount = $rowDat[0][4];
			$emi_amount = intval($emi_amount);
			if(checkForNumeric($emi_amount))
			{
				
				if(array_key_exists($emi_amount,$emi_duration_array))
				{
					
				$emi_duration_array[$emi_amount] = $emi_duration_array[$emi_amount] + 1;
				}
				else
				$emi_duration_array[$emi_amount] = 1;
			}
			if($due_chart_start_row_no+2==$row && $emi_no==1)
			{
				$files_array[$file_counter]['loan_starting_date'] = convertExcelSerialNumberToDate($rowDat[0][12]);
			}	
			
			
		}
		
			if(checkForNumeric($rowDat[0][19]))
				{
					
					$payment_amount = $rowDat[0][19];
					$payment_date = convertExcelSerialNumberToDate($rowDat[0][26]);
					$rasid_no = $rowDat[0][33];
					if(!checkForNumeric($rasid_no) || $rasid_no==0)
					$rasid_no = 999999;
					$remarks = $rowDat[0][45];
					$last_payment = end($payments_array);
					
					
					
					if(isset($last_payment['payment_date']))
					{
						$payment_date_d_m_y = str_replace('/', '-', $payment_date);
				$payment_date_y_m_d=date('Y-m-d',strtotime($payment_date_d_m_y));
				
				$last_payment_date = $last_payment['payment_date'];
				
				$last_payment_date_d_m_y = str_replace('/', '-', $last_payment_date);
				$last_payment_date_y_m_d=date('Y-m-d',strtotime($last_payment_date_d_m_y));
				
					if(strtotime($last_payment_date_y_m_d) == strtotime($payment_date_y_m_d) && $last_payment['rasid_no']==$rasid_no)
					{
						$payments_array_length=count($payments_array);
						$payments_array[$payments_array_length-1]['amount'] = $payments_array[$payments_array_length-1]['amount'] + $payment_amount;
					}
					else
					$payments_array[] = array("amount" => $payment_amount, "payment_date" => $payment_date, "rasid_no" => $rasid_no, "remarks" => $remarks);
				}
				else
				$payments_array[] = array("amount" => $payment_amount, "payment_date" => $payment_date, "rasid_no" => $rasid_no, "remarks" => $remarks);
			}
		
	} // loan_amount
	else if($due_chart_end_row_no>0)
	{
	$emi_array = array();
	$duration_array = array();	
	foreach	($emi_duration_array as $key => $value)
	{
		$emi_array[] = $key;
		$duration_array[] = $value;
	}
	$files_array[$file_counter]['emi_duration_array'] = $emi_duration_array;
	$files_array[$file_counter]['emi_array'] = $emi_array;
	$files_array[$file_counter]['duration_array'] = $duration_array;
	$files_array[$file_counter]['payments_array'] = $payments_array;	
	}
}

foreach($files_array as $file_sheet_data)
{
	print_r($file_sheet_data);
	echo "<br><br>";
	/* if(validateForNull($file_sheet_data['file_number'],$file_sheet_data['customer_name']))
	 {
		 
		 
		$oc_id = 10004;
		$agency_id = "oc10004";
		$file_prefix = "AA";
		 $broker_name = $file_sheet_data['broker_name'];
		 if(validateForNull($broker_name))
		 $broker_id = insertBrokerIfNotDuplicate($broker_name);
		 else
		 $broker_id=getDirectBrokerId();
		 $customer_city_name = $file_sheet_data['customer_city_name'];
		 $customer_city_id = insertCityIfNotDuplicate($customer_city_name);
		  $guarantor_city_name = $file_sheet_data['guarantor_city_name'];
		 $guarantor_city_id = insertCityIfNotDuplicate($guarantor_city_name);
		
		 
		 $file_id = addNewCustomer($agency_id,$file_sheet_data['file_agreement_number'],$file_sheet_data['file_number'],$broker_id,$file_sheet_data['customer_name'],$file_sheet_data['customer_address'],$customer_city_id,$file_sheet_data['customer_area_name'],$file_sheet_data['customer_pincode'],$file_sheet_data['customer_mobile'],array(),array(),array(),array(),$file_sheet_data['guarantor_name'],$file_sheet_data['guarantor_address'],$guarantor_city_id,$file_sheet_data['guarantor_area_name'],$file_sheet_data['guarantor_pincode'],$file_sheet_data['guarantor_mobile'],array(),array(),array(),array(),$file_sheet_data['loan_amount'],1,$file_sheet_data['duration_array'],1,2,$file_sheet_data['roi'],$file_sheet_data['emi_array'],$file_sheet_data['loan_approval_date'],$file_sheet_data['loan_starting_date']);
		 if(!checkForNumeric($file_id))
		{
		echo $file_sheet_data['file_number'];
		exit;
		}
		else
		{
			$others_Company_id=getOthersVehicleCompanyId();
			
			$vehicle_model_name = $file_sheet_data['vehicle']['model_name'];
			if(!validateForNull($vehicle_model_name))
			{
				$vehicle_model_id = getOthersModelByCompanyId($others_Company_id);
				}
			else
			{
				$vehicle_model_id=insertVehicleModel($vehicle_model_name,$others_Company_id);
			}	
			
			$vehicle_condition = 1;
			$vehicle_reg_date = "01/01/1970";
			$dealer_id = getOthersDealerIdFromCompanyId($others_Company_id);
			$model_year = $file_sheet_data['vehicle']['model_year'];
			$chasis_no = $file_sheet_data['vehicle']['chasis_no'];
			$engine_no = $file_sheet_data['vehicle']['engine_no'];
			$fitness_date = $file_sheet_data['vehicle']['fitness_expiry_date'];
			$permit_date = $file_sheet_data['vehicle']['permit_expiry_date'];
			$reg_no = $file_sheet_data['vehicle']['vehicle_reg_no'];
			$vehicle_type_id = getOthersVehicleTypeId();
			$customer_id = getCustomerIdByFileId($file_id);
			$loan_id = getLoanIdFromFileId($file_id);
			$vehicle_id = insertVehicle($vehicle_model_id,$reg_no,$vehicle_reg_date,$engine_no,$chasis_no,$vehicle_type_id,$model_year,$vehicle_condition,$others_Company_id,$dealer_id,$fitness_date,$permit_date,$file_id,$customer_id,array(),array(),array(),array(),0,0,0,0);
			$payments_array = $file_sheet_data['payments_array'];
			foreach($payments_array as $payment)
			{
				$emi_id = getOldestUnPaidEmi($loan_id);
				insertPayment($emi_id,$payment['amount'],1,$payment['payment_date'],$payment['rasid_no'],$payment['remarks']);
			}
		}
	 } */
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
?>