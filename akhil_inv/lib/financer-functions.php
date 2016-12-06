<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("common.php");
require_once("bd.php");
require_once("account-payment-functions.php");
	require_once("account-receipt-functions.php");
		
function insertFinancerPayment($amount,$trans_date,$to_ledger_id,$from_ledger_id,$remarks,$receipt_id_array) 
{
	
	try
	{
		$amount = checkforReceiptsInFinancerPayments($receipt_id_array);
		if(checkForNumeric($amount) && $amount>0)
		{
		 $payment_id=addPayment($amount,$trans_date,$to_ledger_id,$from_ledger_id,$remarks,$auto_rasid_type=2); // auto_rasid_type = 2 for financer payment
		
		 if(checkForNumeric($payment_id))
		 {
		 insertFinancerPaymentToReceipts($payment_id,$receipt_id_array);
		 return true;
		 }
		 else
		 return false;
		}
		return false;
	}
	catch(Exception $e)
	{
	}
	
}	

function checkforReceiptsInFinancerPayments($receip_id_array)
{
	$ret = 0;
	if(is_array($receip_id_array))
	{
		foreach($receip_id_array as $receip_id)
		{
			if(checkForNumeric($receip_id))
			{
				
				$receipt = getReceiptById($receip_id);
				
				$ret = $ret + $receipt['amount'];	
			}
		}
		return $ret;
	}
	return false;
}

function updateFinancerPayment($id,$amount,$trans_date,$to_ledger_id,$from_ledger_id,$remarks,$receipt_id_array)
{
	try
	{
		
		$amount = checkforReceiptsInFinancerPayments($receipt_id_array);
		
		if(checkForNumeric($amount) && $amount>0)
		{
		 $payment_id=updatePayment($id,$amount,$trans_date,$to_ledger_id,$from_ledger_id,$remarks); // auto_rasid_type = 2 for financer payment
		
		 if(checkForNumeric($id))
		 {
		 updateFinancerPaymentToReceipts($id,$receipt_id_array);
		 return true;
		 }
		 else
		 return false;
		}
		return false;
	}
	catch(Exception $e)
	{
	}
}


function getAllPaymentsForFinancer($financer_id)
{
	if(checkForNumeric($financer_id))
	{
		$sql="SELECT payment_id,payment_ref_type,payment_ref,amount,from_ledger_id,to_ledger_id,from_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,remarks,created_by,last_updated_by,date_added,date_modified
			  FROM edms_ac_payment WHERE from_ledger_id = $financer_id AND auto_rasid_type = 2";	
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray;
	else
	return "error";
	}
	
}

function insertFinancerPaymentToReceipts($payment_id,$receipt_id_array){
	
	try
	{
		$ret = false;
		if(checkForNumeric($payment_id))
		{
		foreach($receipt_id_array as $receipt_id)
		{
			if(checkForNumeric($receipt_id,$payment_id) && !checkforduplicateFinancerPaymentToReceipt($receipt_id))
			{
				$sql="INSERT INTO edms_financer_payment (payment_id,receipt_id) VALUES ($payment_id,$receipt_id)";
				$result = dbQuery($sql);
				$ret=true;
			}
			
		}
		}
		return $ret;
	}
	catch(Exception $e)
	{
	}
	
}	

function deleteFinancerPayment($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		deleteReceiptsToFinancerPayment($id);
		removePayment($id);
		return true;
		}
		return false;
	}
	catch(Exception $e)
	{
	}
	
}	

function deleteReceiptsToFinancerPayment($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="DELETE FROM edms_financer_payment WHERE payment_id = $id";
		dbQuery($sql);
		return true;
		}
		return false;
	}
	catch(Exception $e)
	{
	}
	
}	



function updateFinancerPaymentToReceipts($payment_id,$receipt_id_array){
	
	try
	{
		deleteReceiptsToFinancerPayment($payment_id);
		$ret=insertFinancerPaymentToReceipts($payment_id,$receipt_id_array);
		return $ret;
	}
	catch(Exception $e)
	{
	}
	
}	

function getReceiptsForFinancerPaymentId($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT edms_ac_receipt.receipt_id, amount, trans_date, to_customer_id, customer_name FROM edms_financer_payment, edms_ac_receipt, edms_customer WHERE edms_financer_payment.receipt_id = edms_ac_receipt.receipt_id AND edms_ac_receipt.to_customer_id = edms_customer.customer_id AND payment_id = $id";
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);	
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

function getUnPaidReceiptsForFinancerId($id)
{
	if(checkForNumeric($id))
	{
		$sql="SELECT receipt_id FROM edms_ac_receipt,edms_ac_jv,edms_ac_jv_cd WHERE edms_ac_jv.jv_id = edms_ac_jv_cd.jv_id  AND edms_ac_receipt.receipt_id = edms_ac_jv.auto_id AND edms_ac_receipt.auto_rasid_type=2 AND edms_ac_jv.auto_rasid_type=1 AND edms_ac_jv_cd.from_ledger_id = $id AND receipt_id NOT IN (SELECT receipt_id FROM edms_financer_payment)";
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray;
		else
		return false;
	}
	return false;
}

function getUnPaidReceiptsBeforeDate($date)
{
	if(!validateForNull($date))
	$date = getTodaysDate();
	
	if(validateForNull($date))
	{
	
		
		
		$sql="SELECT receipt_id, edms_ac_receipt.amount, ledger_name, edms_ac_receipt.trans_date, customer_name, edms_customer.customer_id FROM edms_ac_receipt,edms_ac_jv,edms_ac_ledgers, edms_customer, edms_ac_jv_cd WHERE edms_ac_jv.jv_id = edms_ac_jv_cd.jv_id  AND  edms_ac_receipt.receipt_id = edms_ac_jv.auto_id AND edms_ac_receipt.auto_rasid_type=2 AND edms_ac_jv.auto_rasid_type=1  AND receipt_id NOT IN (SELECT receipt_id FROM edms_financer_payment) AND edms_ac_receipt.trans_date < '$date' AND edms_ac_jv_cd.from_ledger_id = edms_ac_ledgers.ledger_id AND edms_ac_receipt.to_customer_id = edms_customer.customer_id";
		
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray;
		else
		return false;
	}
	return false;
}

function checkforduplicateFinancerPaymentToReceipt($receipt_id)
{
	if(checkForNumeric($payment_id,$receipt_id))
	{
		$sql="SELECT payment_id FROM edms_financer_payment WHERE receipt_id = $receipt_id";
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0];
		else
		return false;
	}
	return false;
}
?>		