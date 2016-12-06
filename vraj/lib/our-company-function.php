<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("common.php");
require_once("bd.php");

			
function listOurCompanies(){
	
	$sql="SELECT our_company_id, our_company_name, our_company_address, our_company_pincode, edms_city.city_id, city_name, 	sub_heading,		     our_company_prefix,  tin_no, email, tin_date, cst_no, cst_date, service_tax_no,pan_no
		  FROM edms_our_company,edms_city
		  WHERE edms_our_company.city_id=edms_city.city_id";
		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray; 
		else
		return false;
	}

function listOurCompaniesNames(){
	
	$sql="SELECT our_company_id, our_company_name
	      FROM edms_our_company";
		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray; 
		else
		return false;
	}


function insertOurCompany($name,$address,$pincode,$city_id,$company_prefix,$sub_heading,$contact_no=false, $tin_no=0, $email="NA", $tin_date='1970-01-01',$cst_no=0, $cst_date='1970-01-01',$service_tax_no=NULL,$pan_no=NULL) // name,pincode,city_id,prefix validations
{
	try
	{
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$name = strtoupper($name);
		$address=clean_data($address);
		$name=clean_data($name);
		$company_prefix=clean_data($company_prefix);
		$service_tax_no = clean_data($service_tax_no);
		$pan_no = clean_data($pan_no);
		$tin_no = clean_data($tin_no);
		$cst_no = clean_data($cst_no);
		
		
		if(!validateForNull($service_tax_no))
		$service_tax_no="NA";
		if(!validateForNull($pan_no))
		$pan_no="NA";
		
			if(!validateForNull($tin_no))
		$tin_no="NA";
		if(!validateForNull($cst_no))
		$cst_no="NA";
		
		if(!validateForDate($tin_date))
		$tin_date="1970-01-01";
		else
		{
			if(isset($tin_date) && validateForNull($tin_date))
			{
		    $tin_date = str_replace('/', '-', $tin_date);
			$tin_date=date('Y-m-d',strtotime($tin_date));
			}	
		}
		if(!validateForNull($cst_date))
		$cst_date="1970-01-01";
		else
		{
			if(isset($cst_date) && validateForNull($cst_date))
			{
		    $cst_date = str_replace('/', '-', $cst_date);
			$cst_date=date('Y-m-d',strtotime($cst_date));
			}	
		}
		
		
		$cond=checkForNumeric($pincode,$city_id,$tin_no,$cst_no);
		
		if($cond==true && validateForNull($name,$address,$company_prefix,$tin_date,$email,$cst_date) && strlen($pincode)==6 && checkForAlphaNumeric($company_prefix) && strlen($company_prefix)<5 && !checkForDuplicateOurCompany($name,$address,$city_id,$pincode,$company_prefix))
		{
			
			$company_prefix=strtoupper($company_prefix);
			$sql="INSERT INTO 
			      edms_our_company(our_company_name, our_company_address, our_company_pincode, city_id, our_company_prefix, sub_heading, created_by, last_updated_by, date_added, date_modified, tin_no, email, tin_date, cst_no, cst_date, service_tax_no,pan_no)
				  VALUES
				  ('$name', '$address', '$pincode', $city_id, '$company_prefix', '$sub_heading' , $admin_id, $admin_id, NOW(), NOW(), $tin_no, '$email' , '$tin_date', $cst_no ,'$cst_date','$service_tax_no','$pan_no')";
			$result=dbQuery($sql);	  
			$ourCompanyId=dbInsertId();
			if(getAccountsStatus())
			{
			insertAccountSettingsForOC($ourCompanyId);
			}
			if($contact_no!=false)
			{
			addContactNoOurCompany($ourCompanyId,$contact_no);
			}
			return "success";
		}
		else
		{
			return "error";
			
		}
		return "error";
		
	}
	catch(Exception $e)
	{}
	
}
function insertAccountSettingsForOC($oc_id,$account_starting_date)
{
	$account_starting_date=ACCOUNT_STARTING_DATE;
	
	
	if(checkForNumeric($oc_id,$include_penalty,$include_ac) && validateForNull($account_starting_date) && ACCOUNT_STATUS==1 && !getAccountSettingsForOCID($oc_id))
	{
		
		$sql="INSERT INTO edms_ac_settings (our_company_id,ac_starting_date) VALUES ($oc_id,'$account_starting_date')";
		$result=dbQuery($sql);
		return "success";
		}
	
	}

function getAccountSettingsForOCID($agency_id)
{
	if(checkForNumeric($agency_id))
	{
		$sql="SELECT * FROM edms_ac_settings WHERE our_company_id=$agency_id";
		$result=dbQuery($sql);
		if(dbNumRows($result)>0)
		{
			$resultArray=dbResultToArray($result);
			return $resultArray[0][0];
		}
		else
		return false;
	}
	return false;
}	
	
function deleteOurCompany($id)
{
	try{
		
		$ourCompanies=listOurCompaniesNames();
		if(checkForNumeric($id) && !checkIfOurCompanyInUse($id) && count($ourCompanies)>1)
		{
		$sql="DELETE FROM
		      edms_our_company 
			  WHERE our_company_id=$id";
		dbQuery($sql);	  
		return "success";
		}
		else if(count($ourCompanies)==1)
		{
			return "error1";
			}
		else
		{
			return "error";
			}
		}
	catch(Exception $e)
	{}
	
	}
	
function updateOurCompany($id,$name,$address,$pincode,$city_id,$company_prefix,$subheading,$contact_no, $tin_no=0, $email="NA", $tin_date='1970-01-01',$cst_no=0, $cst_date='1970-01-01',$service_tax_no=NULL,$pan_no=NULL)
{
	try{
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$name = strtoupper($name);
		$address=clean_data($address);
		$name=clean_data($name);
		$company_prefix=clean_data($company_prefix);
		$cond=checkForNumeric($pincode,$city_id);
		
		$service_tax_no = clean_data($service_tax_no);
		$pan_no = clean_data($pan_no);
		$tin_no = clean_data($tin_no);
		$cst_no = clean_data($cst_no);
		
		
		if(!validateForNull($service_tax_no))
		$service_tax_no="NA";
		if(!validateForNull($pan_no))
		$pan_no="NA";
		
		if(!validateForNull($tin_no))
		$tin_no="NA";
		if(!validateForNull($cst_no))
		$cst_no="NA";
		
		if(!validateForDate($tin_date))
		$tin_date="1970-01-01";
		else
		{
			if(isset($tin_date) && validateForNull($tin_date))
			{
		    $tin_date = str_replace('/', '-', $tin_date);
			$tin_date=date('Y-m-d',strtotime($tin_date));
			}	
		}
		if(!validateForNull($cst_date))
		$cst_date="1970-01-01";
		else
		{
			if(isset($cst_date) && validateForNull($cst_date))
			{
		    $cst_date = str_replace('/', '-', $cst_date);
			$cst_date=date('Y-m-d',strtotime($cst_date));
			}	
		}
		
		
		if($cond==true && validateForNull($name,$address,$company_prefix,$email) && $pincode>100000 && checkForAlphaNumeric($company_prefix) && strlen($company_prefix)<5 && !checkForDuplicateOurCompany($name,$address,$city_id,$pincode,$company_prefix,$id))
		{
			$company_prefix=strtoupper($company_prefix);
			$sql="UPDATE  
			      edms_our_company
				  SET our_company_name = '$name',
				      our_company_address = '$address',
					  our_company_pincode = $pincode,
					  city_id = $city_id,
					  our_company_prefix = '$company_prefix',
					  sub_heading = '$subheading',
					  last_updated_by = $admin_id,
					  date_modified = NOW(),
					  email='$email',
					  service_tax_no = '$service_tax_no',
					  pan_no = '$pan_no',
					  tin_no = '$tin_no',
					  tin_date = '$tin_date', 
					  cst_no = '$cst_no', 
					  cst_date = '$cst_date'
				  WHERE our_company_id=$id";
			dbQuery($sql);	
			updateContactNoOurCompany($id,$contact_no);  
			return "success";
		}
		else
		{
			return "error";
			}
		
		}
	catch(Exception $e)
	{}
	
	}	


	
function getOurCompanyByID($id)
{
	$sql="SELECT our_company_id, sub_heading, our_company_name, our_company_address, our_company_pincode, sub_heading, edms_city.city_id, city_name, our_company_prefix, tin_no, email, tin_date, cst_no, cst_date, invoice_counter, tax_invoice_counter
	      FROM edms_our_company,edms_city
		  WHERE our_company_id=$id
		  AND edms_our_company.city_id=edms_city.city_id";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	{
		return $resultArray[0];
		}	  
	else
	{
		return false;
		}	
	}
	



			

function checkForDuplicateOurCompany($name,$address,$city,$pincode,$prefix,$id=false) // duplicate if name,address,pincode,city are all same or company prefix is same
{
	try{
		
		$sql="SELECT our_company_id
		       FROM edms_our_company
			   WHERE 
			   ((((our_company_name='$name'
			   AND our_company_address='$address'
			   AND our_company_pincode=$pincode
			   AND city_id=$city)
			   OR (our_company_prefix='$prefix')))
			   ";	   
		if($id==false)
		$sql=$sql.")";
		else
		$sql=$sql." AND (our_company_id!=$id))";	   
		$result=dbQuery($sql);	
		if(dbNumRows($result)>0)
		{
			
			$_SESSION['error']['submit_error']="Duplicate Entry!";
			return true;
			}   
		else
		{
			
			return false;
			}	
		}
	catch(Exception $e)
	{}
	
	}	
function addContactNoOurCompany($id,$contact_no)
{
	try
	{
		if(is_array($contact_no))
		{
			
			foreach($contact_no as $no)
			{
				
				insertContactNoOurCompany($id,$no);
			}
			
		}
		else
		{

			insertContactNoOurCompany($id,$contact_no);
		}
	}
	catch(Exception $e)
	{}
	
}

function insertContactNoOurCompany($id,$contact_no)
{
	try
	{
		if(checkForNumeric($id)==true && checkForNumeric($contact_no))
		{
		$sql="INSERT INTO 
			  edms_our_company_contact_no(our_company_contact_no, our_company_id)
			  VALUES
			  ('$contact_no', $id)";
		dbQuery($sql);
		}
	}
	catch(Exception $e)
	{}
	
	
}
function deleteContactNoOurCompany($id)
{
	try
	{
		$sql="DELETE FROM edms_our_company_contact_no
			  WHERE our_company_contact_no_id=$id";
		dbQuery($sql);	  
	}
	catch(Exception $e)
	{}
	
	
	
	}
function deleteAllContactNoOurCompany($id)
{
	try
	{
		$sql="DELETE FROM edms_our_company_contact_no
			  WHERE our_company_id=$id";
		dbQuery($sql);
	}
	catch(Exception $e)
	{}
	
	
	
	}	
function updateContactNoOurCompany($id,$contact_no)
{
	try
	{
		deleteAllContactNoOurCompany($id);
		addContactNoOurCompany($id,$contact_no);
	}
	catch(Exception $e)
	{}
	
	
	
	}
function getContactNoForOurCompany($id)
{
	$sql="SELECT our_company_id,our_company_contact_no
			FROM edms_our_company_contact_no
			WHERE our_company_id=$id";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);		
	if(dbNumRows($result)>0)
	return $resultArray;
	else
	return false;
}	

function checkIfOurCompanyInUse($id)
{
	
	$sql="SELECT our_company_id
			FROM edms_file
			WHERE our_company_id=$id OR oc_id=$id
			LIMIT 0, 1";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return true;
	else
	return false;		
	
	}

function getPrefixFromOCId($id)
{
	$sql="SELECT our_company_prefix FROM
		   edms_our_company
		   WHERE our_company_id=$id";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray[0][0];	   
	
	}	
function getChallanNoForOCID($id)
{
	$sql="SELECT our_company_prefix,challan_counter FROM
		   edms_our_company
		   WHERE our_company_id=$id";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray[0][0].$resultArray[0][1];	
}



function getChallanCounterForOCID($id)
{
	$sql="SELECT our_company_prefix,challan_counter FROM
		   edms_our_company
		   WHERE our_company_id=$id";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray[0][1];	
}

function incrementChallanNoForOCID($id)
{
	$r=getChallanCounterForOCID($id);
	$r++;
	$sql="UPDATE edms_our_company
	      SET challan_counter=$r
		  WHERE our_company_id=$id";
	dbQuery($sql);	  
	
	}
	
function getItemCodeCounterForOCID($id)
{
	$sql="SELECT item_code_counter FROM
		   edms_our_company
		   WHERE our_company_id=$id";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	{	
	return $resultArray[0][0];	
	}
}

function incrementItemCodeCounterForOCID($id)
{
	$r=getItemCodeCounterForOCID($id);
	$r++;
	$sql="UPDATE edms_our_company
	      SET item_code_counter=$r
		  WHERE our_company_id=$id";
	dbQuery($sql);	  
	
	}
	
function getJobCounterForOCID($id)
{
	$sql="SELECT job_counter FROM
		   edms_our_company
		   WHERE our_company_id=$id";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	{	
	return $resultArray[0][0];	
	}
}

function incrementJobCounterForOCID($id)
{
	$r=getJobCounterForOCID($id);
	$r++;
	$sql="UPDATE edms_our_company
	      SET job_counter=$r
		  WHERE our_company_id=$id";
	dbQuery($sql);	  
	
	}	
	
function getTransCounterForOCID($id,$trans_type) // 1= pur, 3= credit_note, 4 = debit_note, 5= payment, 6 = receipt, 7 =jv, 8=contra
{
	$sql="SELECT ";
	switch($trans_type)
	{
		case 1 : $sql=$sql." purchase_counter ";
				break;	
		case 3 : $sql=$sql." credit_note_counter ";
				break;	
		case 4 : $sql=$sql." debit_note_counter ";
				break;	
		case 5 : $sql=$sql." payment_counter ";
				break;	
		case 6 : $sql=$sql." receipt_counter ";
				break;	
		case 7 : $sql=$sql." jv_counter ";
				break;	
		case 8 : $sql=$sql." contra_counter ";
				break;																
	}
	$sql=$sql." FROM
		   edms_our_company
		   WHERE our_company_id=$id";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	{	
	return $resultArray[0][0];	
	}
}

function incrementTransCounterForOCID($id,$trans_type)
{
	$r=getTransCounterForOCID($id,$trans_type);
	$r++;
	$sql="UPDATE edms_our_company
	      SET ";
		  switch($trans_type)
	{
		case 1 : $sql=$sql." purchase_counter = $r ";
				break;	
		case 3 : $sql=$sql." credit_note_counter = $r ";
				break;	
		case 4 : $sql=$sql." debit_note_counter = $r ";
				break;	
		case 5 : $sql=$sql." payment_counter = $r ";
				break;	
		case 6 : $sql=$sql." receipt_counter = $r ";
				break;	
		case 7 : $sql=$sql." jv_counter = $r ";
				break;	
		case 8 : $sql=$sql." contra_counter = $r ";
				break;																
	}
		 $sql=$sql." WHERE our_company_id=$id";
	dbQuery($sql);	  
	
	}				

		
function resetAllChallanCountersOC()
{
		$sql="UPDATE edms_our_company SET rasid_reset_date=NOW(), challan_counter=1";
		dbQuery($sql);
		return "success";
		}
		
	

function getRasidResetDateOC($oc_id)
{
	$sql="SELECT rasid_reset_date FROM edms_our_company WHERE our_company_id=$oc_id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0];
	}	

function resetChallanCounterForOC($oc_id)
{
	$sql="UPDATE edms_our_company SET rasid_reset_date=NOW(), challan_counter=1 WHERE our_company_id=$oc_id";
		dbQuery($sql);
		return "success";
}			
?>