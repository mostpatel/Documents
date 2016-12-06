<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("area-functions.php");
require_once("account-head-functions.php");
require_once("account-functions.php");
require_once("customer-functions.php");
require_once("account-period-functions.php");
require_once("account-payment-details-functions.php");
require_once("account-ledger-functions.php");
require_once("account-jv-functions.php");
require_once("common.php");
require_once("bd.php");

function updatePaymentReceiptReconciliationDate($id,$date)
{
	if(substr($id, 0, 1) == 'R')
	{
		$id=str_replace('R','',$id);
		$id=intval($id);
		$type=0;
		
		if(checkForNumeric($id) && validateForDate($date))
		{
			if(isset($date) && validateForNull($date))
			{
			$date = str_replace('/', '-', $date);
			$date=date('Y-m-d',strtotime($date));
			}	
		$sql="UPDATE edms_ac_receipt SET reconciliation_date = '$date' WHERE receipt_id = $id";
		dbQuery($sql);
		}
		}
	else if(substr($id, 0, 1) == 'P')
	{
		$id=str_replace('P','',$id);
	    $id=intval($id);
		
		$type=1;
		if(checkForNumeric($id) && validateForDate($date))
		{
			if(isset($date) && validateForNull($date))
			{
			$date = str_replace('/', '-', $date);
			$date=date('Y-m-d',strtotime($date));
			}	
		$sql="UPDATE edms_ac_payment SET reconciliation_date = '$date' WHERE payment_id = $id";
		
		dbQuery($sql);
		}
		}	
	return "success";	
	
}

function updatePaymentReceiptArrayReconciliationDate($id_array,$date_array)
{
	
	$i=0;
	foreach($id_array as $id)
	{
		$date = $date_array[$i++];
		updatePaymentReceiptReconciliationDate($id,$date);
	}
	return "success";
}

?>