<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("file-functions.php");
require_once("loan-functions.php");
require_once("common.php");
require_once("bd.php");


function listWelcomesForFileID($file_id){
	
	try
	{
		if(checkForNumeric($file_id))
		{
			$sql="SELECT welcome_id, welcome_date, customer_name, customer_address, guarantor_name, guarantor_address, vehicle_model, file_id, welcome_type, reg_ad, received, created_by, last_updated_by, date_added, date_modified FROM fin_loan_welcome WHERE file_id=$file_id ORDER BY welcome_date DESC";
			$result=dbQuery($sql);
			$resultArray=dbResultToArray($result);
			if(dbNumRows($result)>0)
			return $resultArray;
			else
			return false;
			}
	}
	catch(Exception $e)
	{
	}
	
}	

function listUnreceivedWelcomesForFileID($file_id){
	
	try
	{
		if(checkForNumeric($file_id))
		{
			$sql="SELECT welcome_id, welcome_date, customer_name, customer_address, guarantor_name, guarantor_address, vehicle_model, file_id, welcome_type, reg_ad, received, created_by, last_updated_by, date_added, date_modified, not_received_type FROM fin_loan_welcome INNER JOIN fin_reg_ad_not_received_types ON fin_loan_welcome.not_received_type_id = fin_reg_ad_not_received_types.not_received_type_id WHERE file_id=$file_id AND received=2 ORDER BY welcome_date DESC";
			$result=dbQuery($sql);
			$resultArray=dbResultToArray($result);
			if(dbNumRows($result)>0)
			return $resultArray;
			else
			return false;
			}
	}
	catch(Exception $e)
	{
	}
	
}	

function listUnreceivedCustomerWelcomesForFileID($file_id){
	
	try
	{
		if(checkForNumeric($file_id))
		{
			$sql="SELECT welcome_id, welcome_date, customer_name, customer_address, guarantor_name, guarantor_address, vehicle_model, file_id, welcome_type, reg_ad, received, created_by, last_updated_by, date_added, date_modified, not_received_type FROM fin_loan_welcome INNER JOIN fin_reg_ad_not_received_types ON fin_loan_welcome.not_received_type_id = fin_reg_ad_not_received_types.not_received_type_id WHERE file_id=$file_id AND received=2 AND welcome_type=0 ORDER BY welcome_date DESC";
			$result=dbQuery($sql);
			$resultArray=dbResultToArray($result);
			if(dbNumRows($result)>0)
			return $resultArray;
			else
			return false;
			}
	}
	catch(Exception $e)
	{
	}
	
}	

function listUnreceivedGuarantorWelcomesForFileID($file_id){
	
	try
	{
		if(checkForNumeric($file_id))
		{
			$sql="SELECT welcome_id, welcome_date, customer_name, customer_address, guarantor_name, guarantor_address, vehicle_model, file_id, welcome_type, reg_ad, received, created_by, last_updated_by, date_added, date_modified, not_received_type FROM fin_loan_welcome INNER JOIN fin_reg_ad_not_received_types ON fin_loan_welcome.not_received_type_id = fin_reg_ad_not_received_types.not_received_type_id WHERE file_id=$file_id AND received=2 AND welcome_type=1 ORDER BY welcome_date DESC";
			$result=dbQuery($sql);
			$resultArray=dbResultToArray($result);
			if(dbNumRows($result)>0)
			return $resultArray;
			else
			return false;
			}
	}
	catch(Exception $e)
	{
	}
	
}	

function getNumberOfWelcomesForFileID($file_id)
{
	if(checkForNumeric($file_id))
	{
		
		$sql="SELECT count(welcome_id) FROM fin_loan_welcome WHERE file_id=$file_id ";
			$result=dbQuery($sql);
			$resultArray=dbResultToArray($result);
			if(dbNumRows($result)>0)
			return $resultArray[0][0];
			else
			return 0;
		
		}
	
}

function getLatestWelcomeDateForFile($file_id)
{
	if(checkForNumeric($file_id))
	{
		
		$sql="SELECT welcome_date FROM fin_loan_welcome WHERE file_id=$file_id ORDER BY welcome_date DESC";
			$result=dbQuery($sql);
			$resultArray=dbResultToArray($result);
			if(dbNumRows($result)>0)
			return $resultArray[0][0];
			else
			return 0;
		
		}
	}

function insertWelcome($file_id,$welcome_date,$customer_name,$customer_address, $guarantor_name, $guarantor_address ,$vehicle_model,$welcome_type=0,$reg_ad="",$received=0,$received_date="01/01/1970"){
	
	try
	{
		 $loan_id=getLoanIdFromFileId($file_id);
		 $admin_id=$_SESSION['adminSession']['admin_id'];
		 $customer_name=clean_data($customer_name);
		 $customer_address=clean_data($customer_address);
		 $welcome_date=clean_data($welcome_date);
		 $vehicle_model=clean_data($vehicle_model);
		 $guarantor_name=clean_data($guarantor_name);
		 $guarantor_address=clean_data($guarantor_address);
		 
		 if(!validateForNull($reg_ad))
		$reg_ad = "";
		 
		 if(checkForNumeric($file_id) && validateForNull($customer_name,$customer_address,$welcome_date,$vehicle_model))
		 {
		$customer_address='<pre>'.$customer_address.'</pre>';
$guarantor_address='<pre>'.$guarantor_address.'</pre>';
		$welcome_date = str_replace('/', '-', $welcome_date);// converts dd/mm/yyyy to dd-mm-yyyy
		$welcome_date = date('Y-m-d',strtotime($welcome_date)); // converts date to Y-m-d format
		
		$received_date = str_replace('/', '-', $received_date);// converts dd/mm/yyyy to dd-mm-yyyy
		$received_date = date('Y-m-d',strtotime($received_date)); // converts date to Y-m-d format
				 
		$sql="INSERT INTO fin_loan_welcome(welcome_date,  customer_name, customer_address,  guarantor_name, guarantor_address , vehicle_model, file_id,  welcome_type, reg_ad, received, received_date, created_by, last_updated_by, date_added, date_modified) VALUES ('$welcome_date', '$customer_name', '$customer_address',  '$guarantor_name', '$guarantor_address' , '$vehicle_model', $file_id, $welcome_type, '$reg_ad', $received, '$received_date', $admin_id, $admin_id, NOW(), NOW())";
	
		$result=dbQuery($sql);
		return dbInsertId();
		 }
		 return "error";
	}
	catch(Exception $e)
	{
	}
	
}	

function deleteWelcome($id){
	
	try
	{
		if(checkForNumeric($id))
		{
			$sql="DELETE FROM fin_loan_welcome WHERE welcome_id=$id";
			dbQuery($sql);
			return "success";
			}
		return "error";	
	}
	catch(Exception $e)
	{
	}
	
}	

function updateWelcome($welcome_id,$reg_ad="",$received=0,$not_received_type_id=NULL,$received_date="01/01/1970"){
	try
	{
		
		
		$admin_id=$_SESSION['adminSession']['admin_id'];
		$reg_ad = clean_data($reg_ad);
		
		 if(!validateForNull($not_received_type_id) || $received!=2)
		 $not_received_type_id="NULL";
		 
		  if(!validateForNull($received_date))
		 $received_date=getTodaysDate();
		 
		$received_date = str_replace('/', '-', $received_date);// converts dd/mm/yyyy to dd-mm-yyyy
		$received_date = date('Y-m-d',strtotime($received_date)); // converts date to Y-m-d format
		 if(checkForNumeric($welcome_id,$received) && validateForNull($reg_ad))
		 {
		$sql="UPDATE fin_loan_welcome SET reg_ad = '$reg_ad', received = $received, not_received_type_id = $not_received_type_id, received_date = '$received_date', last_updated_by=$admin_id,  date_modified = NOW() WHERE welcome_id = $welcome_id";
		dbQuery($sql);
		return "success";	
		 }
	}
	catch(Exception $e)
	{
	}
	
}	

function getWelcomeById($id){
	
	try
	{
		
		if(checkForNumeric($id))
		{
			$sql="SELECT welcome_id, welcome_date, customer_name, customer_address,  guarantor_name, guarantor_address , vehicle_model,  welcome_type, reg_ad, received, received_date,file_id, created_by, last_updated_by, date_added, date_modified, not_received_type_id FROM fin_loan_welcome WHERE welcome_id=$id";
			$result=dbQuery($sql);
			$resultArray=dbResultToArray($result);
			if(dbNumRows($result)>0)
			return $resultArray[0];
			else
			return false;
			}
	}
	catch(Exception $e)
	{
	}
	
}	

function listRegAdNotReceivedTypes()
{
	$sql="SELECT * FROM fin_reg_ad_not_received_types";
	$result=dbQuery($sql);
			$resultArray=dbResultToArray($result);
			if(dbNumRows($result)>0)
			return $resultArray;
			else
			return false;
}
function getRegAdNotReceivedTypeById($id)
{
	if(checkForNumeric($id))
	{
		$sql="SELECT * FROM fin_reg_ad_not_received_types WHERE not_received_type_id=$id";
	$result=dbQuery($sql);
			$resultArray=dbResultToArray($result);
			if(dbNumRows($result)>0)
			return $resultArray[0];
			else
			return false;
		
	}
} 	
?>