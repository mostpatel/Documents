<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("customer-functions.php");
require_once("common.php");
require_once("bd.php");


function insertFileCharge($file_charges,$stamp_charges,$share_money,$file_id,$gua_kyc,$file_rec){
	
	try
	{
		$customer_id = getCustomerIdByFileId($file_id);
		if(checkForNumeric($file_charges,$stamp_charges,$share_money,$file_id,$customer_id,$gua_kyc,$file_rec) && !checkForDuplicateFileCharges($file_id))
		{
		$admin_id=$_SESSION['adminSession']['admin_id'];
		$sql="INSERT INTO fin_file_charges
		      (file_charges, stamp_charges, share_money, file_id, customer_id,gua_kyc,file_rec)
			  VALUES
			  ($file_charges, $stamp_charges, $share_money, $file_id, $customer_id,$gua_kyc,$file_rec)";
			 
		dbQuery($sql);	  
		return dbInsertId();
		}
		else
		{
			return "error";
			}
	}
	catch(Exception $e)
	{
	}
	
}	


function checkForDuplicateFileCharges($file_id)
{
	if(checkForNumeric($file_id))
	{
		$sql="SELECT * from fin_file_charges WHERE file_id = $file_id";
		$result = dbQuery($sql);
		if(dbNumRows($result)>0)
		return true;
		else
		return false;
		$resultArray = dbResultToArray($result);
		
		
	}
}

function deleteFileCharges($file_id){
	
	try
	{
		if(checkForNumeric($file_id))
		{
		$sql="DELETE FROM fin_file_charges
		      WHERE file_id=$file_id";
		dbQuery($sql);
		
		return "success";	  
		}
		else
		{
			return "error";
			}
	}
	catch(Exception $e)
	{
	}
	
}	

function updateFileCharges($file_id,$file_charges,$stamp_charges,$share_money,$gua_kyc,$file_kyc){
	
	try
	{
		
		if(checkForNumeric($file_id,$file_charges,$stamp_charges,$share_money,$gua_kyc,$file_kyc))
		{
			
		$sql="UPDATE fin_file_charges
		      SET file_charges = $file_charges, stamp_charges = $stamp_charges, share_money = $share_money, gua_kyc=$gua_kyc, file_rec = $file_kyc
			  WHERE file_charge_id=$id";
		dbQuery($sql);	  
		return "success";
		}
		else
		{
			return "error";
			}
	}
	catch(Exception $e)
	{
	}
	
}	

function getFileChargesById($id){
	
	try
	{
		$sql="SELECT file_charge_id, file_charges, stamp_charges, share_money, file_id, customer_id, gua_kyc, file_rec
		      FROM fin_file_charges
			  WHERE file_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0];	 
	}
	catch(Exception $e)
	{
	}
	
}	


function getFileChargesForLoansBetweenDates($from=NULL,$to=NULL,$agency_id=NULL,$broker_id=NULL,$file_status=NULL)
{
	$oc_id=$_SESSION['adminSession']['oc_id'];
	$original_agency_id=$agency_id;
	$our_company_id=NULL;
	$type=substr($agency_id,0,2);
	$agency_id=substr($agency_id,2);
	if($type=="ag")
	{
	$agency_id=$agency_id;
	$our_company_id="NULL";
	}
	else if($type=="oc")
	{
	$our_company_id=$agency_id;
	$agency_id="NULL";	
	}
	if(isset($from) && validateForNull($from))
	{
			$from = str_replace('/', '-', $from);
			$from=date('Y-m-d',strtotime($from));
	}
	if(isset($to) && validateForNull($to))
	{
		$to = str_replace('/', '-', $to);
			$to=date('Y-m-d',strtotime($to));
	}
		
	$sql="SELECT fin_file.file_id, file_number, file_agreement_no, mem_no,fin_customer.customer_id, customer_name, file_charges, stamp_charges, share_money , gua_kyc, file_rec, vehicle_reg_no, loan_approval_date
		FROM fin_file 
		INNER JOIN fin_customer ON fin_file.file_id = fin_customer.file_id 
		INNER JOIN fin_loan ON fin_file.file_id = fin_loan.file_id 
		LEFT JOIN fin_file_charges ON fin_file.file_id = fin_file_charges.file_id
		LEFT JOIN fin_vehicle ON fin_file.file_id = fin_vehicle.file_id
		WHERE  file_status!=3
		  AND "; 
if($our_company_id=="NULL" && is_numeric($agency_id))
{
	$sql=$sql." agency_id=$agency_id AND ";
}
if($agency_id=="NULL" && is_numeric($our_company_id))
{
	$sql=$sql." oc_id=$our_company_id AND ";
}
if(!validateForNull($agency_id))
{
	$our_companies=listOurCompanyIds();
	$agencies = listAgencyIds();
	if(is_array($our_companies) && count($our_companies)>0 && is_array($agencies) && count($agencies)>0)
	{
		$our_companies_string = implode(",",$our_companies);
		$sql=$sql." (oc_id IN ($our_companies_string) OR ";
		$agencies_string = implode(',',$agencies);
		$sql=$sql."agency_id IN ($agencies_string)) AND ";
	}
	else
	{
	if(is_array($our_companies) && count($our_companies)>0)
	{
		$our_companies_string = implode(",",$our_companies);
		$sql=$sql."  oc_id IN ($our_companies_string)  AND ";
	}
	else
	$sql=$sql."  oc_id IS NULL AND";
	if(is_array($agencies) && count($agencies)>0)
	{
		$agencies_string = implode(',',$agencies);
		$sql=$sql."  agency_id IN ($agencies_string)  AND ";
	}
	else
	$sql=$sql."  agency_id IS NULL AND ";}
}

	if(isset($file_status) && is_numeric($file_status) && ($file_status==1 || $file_status==2 || $file_status==5 || $file_status==6))
	{
	if($file_status==1)	
	$sql=$sql."file_status = $file_status 
		  AND ";
	else if($file_status==2)
	$sql=$sql." (file_status=2 OR file_status=4) 
		  AND ";
	else if($file_status==5)	
	$sql=$sql."file_status = $file_status 
		  AND ";	  
	else if($file_status==6)
	$sql=$sql." (file_status=1 OR file_status=5) 
		  AND ";	    	  	  
	}
	if(isset($from) && validateForNull($from))
	$sql=$sql."fin_file.date_added>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."fin_file.date_added<='$to'
		  AND ";
		  if(isset($broker_id) && validateForNull($broker_id))  
	$sql=$sql." fin_file.broker_id IN ($broker_id)
		  AND ";
		  $sql=$sql."	 
		  
		   our_company_id=$oc_id 
		  ORDER BY fin_file.date_added";
		
		  $result= dbQuery($sql);
		  $resultArray = dbResultToArray($result);
		  return $resultArray;
}


function getFileChargesForFileId($file_id)
{
	$sql="SELECT file_charges FROM fin_file_Charges WHERE file_id = $file_id";
	 $result= dbQuery($sql);
		  $resultArray = dbResultToArray($result);
		  if(dbNumRows($result)>0)
		  return $resultArray[0][0];
		  else
		  return false;
}
?>