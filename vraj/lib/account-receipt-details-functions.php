<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("area-functions.php");
require_once("account-head-functions.php");
require_once("account-functions.php");
require_once("customer-functions.php");
require_once("account-period-functions.php");
require_once("account-ledger-functions.php");
require_once("account-jv-functions.php");
require_once("common.php");
require_once("bd.php");


function getReceiptDetailsForReceiptId($id) // type > 100
{
	if(checkForNumeric($id))
	{
		$sql="SELECT receipt_details_id,bank_name,branch_name,chq_date,chq_no,payment_mode_id,receipt_id,created_by,last_updated_by,date_added,date_modified
			  FROM edms_ac_receipt_details
			  WHERE receipt_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0];
		else
		return false; 	  
		
	}
}

function addReceiptDetails($receipt_id,$payment_mode_id,$chq_date,$chq_no,$bank_name,$branch_name) //$from_ledger should start with C for customer or L for ledger, from_ledger: debit and to_ledger: credit 
// auto_rasid_type = 2 for financer receipt
{
	$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
	removeReceiptDetails($receipt_id);
	if(!validateForNull($chq_date))
	{
	$chq_date="1970-01-01";
	}
	else
	{
			if(isset($chq_date) && validateForNull($chq_date))
			{
		    $chq_date = str_replace('/', '-', $chq_date);
			$chq_date=date('Y-m-d',strtotime($chq_date));
			}	
	}
	if($payment_mode_id==-1)
	$payment_mode_id="NULL";
	
	if(!validateForNull($chq_no))
	$chq_no="000000";
	
	if(!validateForNull($bank_name))
	$bank_name="NA";
	
	if(!validateForNull($branch_name))
	$branch_name="NA";
	
	if(checkForNumeric($receipt_id,$payment_mode_id,$admin_id)  && validateForNull($chq_date,$chq_no,$bank_name,$branch_name))
	{
			
			$sql="INSERT INTO edms_ac_receipt_details (bank_name,branch_name,chq_date,chq_no,payment_mode_id,receipt_id,created_by,last_updated_by,date_added,date_modified)
			VALUES ('$bank_name','$branch_name','$chq_date','$chq_no',$payment_mode_id,$receipt_id,$admin_id,$admin_id,NOW(),NOW())";
			
			$result=dbQuery($sql);
			$receipt_details_id = dbInsertId();
			
			
			return $receipt_details_id;
	}
	return "error";	
}



function removeReceiptDetails($id)
{
	if(checkForNumeric($id))
	{
		$sql="DELETE FROM edms_ac_receipt_details where receipt_id=$id";
		dbQuery($sql);
		return "success";
		}
		return "error";
	}
?>