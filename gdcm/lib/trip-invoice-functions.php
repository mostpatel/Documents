<?php 
require_once("cg.php");
require_once("common.php");
require_once("product-functions.php");
require_once("customer-functions.php");
require_once("truck-functions.php");
require_once("account-ledger-functions.php");
require_once("account-jv-functions.php");
require_once("our-company-function.php");
require_once("bd.php");
		

	
function checkForTripMemosInArray($lr_id_array)
{
	
	$has_items=false;
	if(is_array($lr_id_array) && count($lr_id_array)>0)
	{	
		for($i=0;$i<count($lr_id_array);$i++)
		{
			$lr_id=$lr_id_array[$i];
			
			
			
			if(checkForNumeric($lr_id) && $lr_id>0)
			{
					
				$has_items = true;
			}	
			
		}
				
}
	return $has_items;
	
}



function validateAgentNameAndAmountArray($agent_name_array,$agent_amount_array)
{
   $has_items=0;
   $agenct_id_array = array();
   $new_agent_amount_array = array();
	if(is_array($agent_name_array) && count($agent_name_array)>0)
	{	
		for($i=0;$i<count($agent_name_array);$i++)
		{
			$agenct_id = getCustomerLedgerIDFromLedgerNameLedgerId($agent_name_array[$i]);
			$numeric_agent_id = str_replace("L","",$agenct_id);
			$agenct_amount = $agent_amount_array[$i];
			
			
			if(checkForNumeric($numeric_agent_id,$agenct_amount) && $numeric_agent_id>0 && $agenct_amount>0)
			{
				$agenct_id_array[] = $agenct_id;
				$new_agent_amount_array[] = $agenct_amount;
				$has_items = $has_items+$agenct_amount;
			}	
			
		}
				
	}
	return array($agenct_id_array,$new_agent_amount_array,$has_items);	
	
}


function insertTripInvoice($invoice_date,$invoice_no,$memo_id_array,$agent_name_array,$agent_amount_array,$expenses_name_array,$expenses_amount_array,$remarks){
	
	try
	{
		$oc_id=$_SESSION['edmsAdminSession']['oc_id'];
		$invoice_date=clean_data($invoice_date);
		$invoice_no=clean_data($invoice_no);
		$remarks = clean_data($remarks);
		
		$trip_memo_counter = getInvoiceCounterForOCID($oc_id);
		$has_trip_memos = checkForTripMemosInArray($memo_id_array);
		$total_agent_amount_array = validateAgentNameAndAmountArray($agent_name_array,$agent_amount_array);
		$total_expenses_amount_array = validateAgentNameAndAmountArray($expenses_name_array,$expenses_amount_array);
		$agent_id_array = $total_agent_amount_array[0];
		$agent_amount_array = $total_agent_amount_array[1];
		$total_agent_amount = $total_agent_amount_array[2];
		$expenses_id_array = $total_expenses_amount_array[0];
		$expenses_amount_array = $total_expenses_amount_array[1];
		$total_expenses_amount = $total_expenses_amount_array[2];
		if(isset($invoice_date) && validateForNull($invoice_date))
    	{
		$invoice_date = str_replace('/', '-', $invoice_date);
		$invoice_date=date('Y-m-d',strtotime($invoice_date));
		}

	if(!validateForNull($remarks))
	$remarks="NA";
	
		if(validateForNull($invoice_date) && (checkForNumeric($invoice_no,$total_agent_amount,$total_expenses_amount))  && !checkForDuplicateTripInvoice($invoice_no) && $has_trip_memos && $total_agent_amount>=0 && $total_expenses_amount>=0)
		{
		

		
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$sql="INSERT INTO edms_invoice
		      (invoice_no,invoice_date, remarks, created_by, last_updated_by, date_added, date_modified)
			  VALUES
			  ('$invoice_no','$invoice_date','$remarks',$admin_id, $admin_id, NOW(), NOW())";
		dbQuery($sql);
		$invoice_id = dbInsertId();
			  
		if(checkForNumeric($invoice_id))
		{
			
			 insertTripMemosToInvoice($invoice_id,$memo_id_array);
			 
			$trip_id_array = getTripIdsByInvoiceId($invoice_id);
			$jv_details_array = getJvAmountAndIdsForTripMemoArray($trip_id_array);
			
			$debit_amount = $jv_details_array[5];
			$credit_amount = $jv_details_array[4];
		
			$credit_amount = $credit_amount +$total_agent_amount + $total_expenses_amount;
			
			$income = $debit_amount - $credit_amount;
				
			$from_ledger_id_array = $jv_details_array[0];
			$from_ledger_amount_array = $jv_details_array[1];
			$to_ledger_id_array = $jv_details_array[2];
			$to_ledger_amount_array = $jv_details_array[3];
			
			if($income>0)
			{
			$from_ledger_id_array[] = getLedgerNameFromLedgerId(DEF_INCOME_LED).' | [L'.DEF_INCOME_LED."]";  
			$from_ledger_amount_array[] = $income;
			}
			else
			{
			$to_ledger_id_array[] = getLedgerNameFromLedgerId(DEF_INCOME_LED).' | [L'.DEF_INCOME_LED."]";  
			$to_ledger_amount_array[] = -$income;
			}
			for($i=0;$i<count($agent_id_array);$i++)
			{
			$from_ledger_id_array[] = getLedgerNameFromLedgerId(str_replace("L","",$agent_id_array[$i])).' | ['.$agent_id_array[$i]."]";  
			$from_ledger_amount_array[] = $agent_amount_array[$i];
			}
			
			for($i=0;$i<count($expenses_id_array);$i++)
			{
			$from_ledger_id_array[] = getLedgerNameFromLedgerId(str_replace("L","",$expenses_id_array[$i])).' | ['.$expenses_id_array[$i]."]";  
			$from_ledger_amount_array[] = $expenses_amount_array[$i];
			}
			$from_ledger_id_amount_array = removeDuplicatesForJvIdAndAmountArray($from_ledger_id_array,$from_ledger_amount_array);
			$to_ledger_id_amount_array = removeDuplicatesForJvIdAndAmountArray($to_ledger_id_array,$to_ledger_amount_array);
			$from_ledger_id_array = $from_ledger_id_amount_array[0];
			$from_ledger_amount_array = $from_ledger_id_amount_array[1];
			$to_ledger_id_array = $to_ledger_id_amount_array[0];
			$to_ledger_amount_array = $to_ledger_id_amount_array[1];
			$res=addMultiJV($debit_amount,date('d/m/Y',strtotime($invoice_date)),$to_ledger_id_array,$to_ledger_amount_array,$from_ledger_id_array,$from_ledger_amount_array,$invoice_no." JV",10,$invoice_id);
			
			if($res!="success")
			deleteInvoice($invoice_id);
			 if($invoice_no==$trip_memo_counter)
			 {
			 incrementInvoiceNoForOCID($oc_id);
			 }
			 return $invoice_id;
		}	  
		return "error";
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

function getBranchWiseTotalForInvoice($invoice_id)
{
	$branches_ids = listBranchIds();
	
	$branches_ids_string = implode(",",$branches_ids);
	
	if(checkForNumeric($invoice_id) && validateForNull($branches_ids_string))
	{
		$sql = "SELECT edms_ac_jv_cd.amount,to_ledger_id,ledger_name,(SELECT SUM(amount) FROM edms_ac_receipt WHERE auto_rasid_type = 11 AND auto_id = $invoice_id AND edms_ac_receipt.to_ledger_id=edms_ac_jv_cd.to_ledger_id) as paid_amount FROM edms_ac_jv INNER JOIN edms_ac_jv_cd ON edms_ac_jv.jv_id=edms_ac_jv_cd.jv_id INNER JOIN edms_ac_ledgers ON edms_ac_ledgers.ledger_id = edms_ac_jv_cd.to_ledger_id WHERE auto_rasid_type = 10 AND auto_id=$invoice_id AND to_ledger_id IN ($branches_ids_string) ";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
	
		if(dbNumRows($result)>0)
		return $resultArray;	 
		else
		return false;
	}
	return false;
}

function getAgentWiseTotalForInvoice($invoice_id)
{
	$branches_ids = listAgentIds();
	$branches_ids_string = implode(",",$branches_ids);
	if(checkForNumeric($invoice_id) && validateForNull($branches_ids_string))
	{
		$sql = "SELECT edms_ac_jv_cd.amount,from_ledger_id,ledger_name,(SELECT SUM(amount) FROM edms_ac_payment WHERE auto_rasid_type = 11 AND auto_id = $invoice_id AND edms_ac_payment.from_ledger_id=edms_ac_jv_cd.from_ledger_id) as paid_amount FROM edms_ac_jv INNER JOIN edms_ac_jv_cd ON edms_ac_jv.jv_id=edms_ac_jv_cd.jv_id INNER JOIN edms_ac_ledgers ON edms_ac_ledgers.ledger_id = edms_ac_jv_cd.from_ledger_id WHERE auto_rasid_type = 10 AND auto_id=$invoice_id AND from_ledger_id IN ($branches_ids_string) ";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray;	 
		else
		return false;
	}
	return false;
}

function getExpenseWiseTotalForInvoice($invoice_id)
{
	$branches_ids = listExpenseLedgerIds();

	$branches_ids_string = implode(",",$branches_ids);
	
	if(checkForNumeric($invoice_id) && validateForNull($branches_ids_string))
	{
		$sql = "SELECT edms_ac_jv_cd.amount,from_ledger_id,ledger_name,(SELECT SUM(amount) FROM edms_ac_payment WHERE auto_rasid_type = 11 AND auto_id = $invoice_id AND edms_ac_payment.from_ledger_id=edms_ac_jv_cd.from_ledger_id) as paid_amount FROM edms_ac_jv INNER JOIN edms_ac_jv_cd ON edms_ac_jv.jv_id=edms_ac_jv_cd.jv_id INNER JOIN edms_ac_ledgers ON edms_ac_ledgers.ledger_id = edms_ac_jv_cd.from_ledger_id WHERE auto_rasid_type = 10 AND auto_id=$invoice_id AND from_ledger_id IN ($branches_ids_string) ";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray;	 
		else
		return false;
	}
	return false;
}

function insertTripMemosToInvoice($trip_invoice_id,$memo_id_array)
{
	if(is_array($memo_id_array) && count($memo_id_array)>0)
	{
		for($i=0;$i<count($memo_id_array);$i++)
		{
			$memo_id=$memo_id_array[$i];
			
			
			if(checkForNumeric($memo_id,$trip_invoice_id) && $memo_id>0 && $trip_invoice_id>0)
			{
					
				$invoice_memo_id=insertMemoToInvoice($trip_invoice_id,$memo_id);
			}	
			
		}
	
				
	}
	
}
function insertMemoToInvoice($trip_invoice_id,$memo_id)
{
	
	if(checkForNumeric($memo_id,$trip_invoice_id) && $memo_id>0 && $trip_invoice_id>0 && !checkForDuplicateMemoToInvoice($memo_id))
			{
		
		
		$sql="INSERT INTO edms_invoice_trip_memo (trip_memo_id,invoice_id) VALUES ($memo_id,$trip_invoice_id)";
		dbQuery($sql);
		$lr_product_id = dbInsertId();
		return $lr_product_id;
	}
	return false;
	
}

function checkForDuplicateMemoToInvoice($memo_id)
{
	if(checkForNumeric($memo_id) && $memo_id>0)
	{
		$sql="SELECT invoice_id FROM edms_invoice_trip_memo WHERE trip_memo_id = $memo_id";
		$result=dbQuery($sql);
		if(dbNumRows($result)>0)
		return true;
	}
	return false;
	
}

function deleteTripMemoToInvoice($invoice_id)
{
	if(checkForNumeric($invoice_id))
	{
		$sql="DELETE FROM edms_invoice_trip_memo WHERE invoice_id = $invoice_id";
		dbQuery($sql);
		return true;
	}
	return false;
}



function deleteInvoice($id){
	
	try
	{
		if(checkForNumeric($id) && checkifTripInUse($id))
		{
		$sql="DELETE FROM edms_invoice
		      WHERE invoice_id=$id";
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

function updateTripInvoice($invoice_id,$invoice_date,$invoice_no,$memo_id_array,$agent_name_array,$agent_amount_array,$expenses_name_array,$expenses_amount_array,$remarks){
	
	try
	{
		
		
	    $oc_id=$_SESSION['edmsAdminSession']['oc_id'];
		$invoice_date=clean_data($invoice_date);
		$invoice_no=clean_data($invoice_no);
		$remarks = clean_data($remarks);
		
		$trip_memo_counter = getInvoiceCounterForOCID($oc_id);
		$has_trip_memos = checkForTripMemosInArray($memo_id_array);
		$total_agent_amount_array = validateAgentNameAndAmountArray($agent_name_array,$agent_amount_array);
		$total_expenses_amount_array = validateAgentNameAndAmountArray($expenses_name_array,$expenses_amount_array);
		$agent_id_array = $total_agent_amount_array[0];
		$agent_amount_array = $total_agent_amount_array[1];
		$total_agent_amount = $total_agent_amount_array[2];
		$expenses_id_array = $total_expenses_amount_array[0];
		$expenses_amount_array = $total_expenses_amount_array[1];
		$total_expenses_amount = $total_expenses_amount_array[2];
		if(isset($invoice_date) && validateForNull($invoice_date))
    	{
		$invoice_date = str_replace('/', '-', $invoice_date);
		$invoice_date=date('Y-m-d',strtotime($invoice_date));
		}

	if(!validateForNull($remarks))
	$remarks="NA";
	
		if(validateForNull($invoice_date) && (checkForNumeric($invoice_no,$total_agent_amount,$total_expenses_amount,$invoice_id))  && !checkForDuplicateTripInvoice($invoice_no) && $has_trip_memos && $total_agent_amount>=0 && $total_expenses_amount>=0)
		{
		

		
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$sql="UPDATE edms_invoice
		      SET invoice_no = $invoice_no,invoice_date = '$invoice_date', remarks = '$remarks',  last_updated_by = $admin_id,  date_modified = NOW()
			  WHERE invoice_id = $invoice_id";
		dbQuery($sql);
		
			  
		if(checkForNumeric($invoice_id))
		{
			deleteTripMemoToInvoice($invoice_id);
			$jv_id=getJVIdForInvoice($invoice_id);
			removeJV($jv_id);
			insertTripMemosToInvoice($invoice_id,$memo_id_array);
			 
			 $trip_id_array = getTripIdsByInvoiceId($invoice_id);
			
			$jv_details_array = getJvAmountAndIdsForTripMemoArray($trip_id_array);
			
			$debit_amount = $jv_details_array[5];
			$credit_amount = $jv_details_array[4];
		
			$credit_amount = $credit_amount +$total_agent_amount + $total_expenses_amount;
			
			$income = $debit_amount - $credit_amount;
				
			$from_ledger_id_array = $jv_details_array[0];
			$from_ledger_amount_array = $jv_details_array[1];
			$to_ledger_id_array = $jv_details_array[2];
			$to_ledger_amount_array = $jv_details_array[3];
			if($income>0)
			{
			$from_ledger_id_array[] = getLedgerNameFromLedgerId(DEF_INCOME_LED).' | [L'.DEF_INCOME_LED."]";  
			$from_ledger_amount_array[] = $income;
			}
			else
			{
			$to_ledger_id_array[] = getLedgerNameFromLedgerId(DEF_INCOME_LED).' | [L'.DEF_INCOME_LED."]";  
			$to_ledger_amount_array[] = -$income;
			}
			for($i=0;$i<count($agent_id_array);$i++)
			{
			$from_ledger_id_array[] = getLedgerNameFromLedgerId(str_replace("L","",$agent_id_array[$i])).' | ['.$agent_id_array[$i]."]";  
			$from_ledger_amount_array[] = $agent_amount_array[$i];
			}
			
			for($i=0;$i<count($expenses_id_array);$i++)
			{
			$from_ledger_id_array[] = getLedgerNameFromLedgerId(str_replace("L","",$expenses_id_array[$i])).' | ['.$expenses_id_array[$i]."]";  
			$from_ledger_amount_array[] = $expenses_amount_array[$i];
			}
			
			$res=addMultiJV($debit_amount,date('d/m/Y',strtotime($invoice_date)),$to_ledger_id_array,$to_ledger_amount_array,$from_ledger_id_array,$from_ledger_amount_array,$invoice_no." JV",10,$invoice_id);
			if($res!="success")
			deleteInvoice($invoice_id);
			 if($invoice_no==$trip_memo_counter)
			 {
			 incrementInvoiceNoForOCID($oc_id);
			 }
			 return $invoice_id;
		}	  
		return "error";
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

function getTripInvoiceById($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT      edms_invoice.invoice_id,invoice_no,invoice_date,edms_invoice.remarks,edms_invoice.created_by,edms_invoice.last_updated_by,edms_invoice.date_modified,edms_invoice.date_added,jv_id
		      FROM edms_invoice,edms_invoice_trip_memo,edms_ac_jv
			  WHERE edms_invoice_trip_memo.invoice_id= edms_invoice.invoice_id AND edms_invoice.invoice_id = edms_ac_jv.auto_id AND auto_rasid_type=10 AND edms_invoice.invoice_id = $id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0];	 
		}
		return false;
	}
	catch(Exception $e)
	{
	}
	
}	

function getTripInvoiceIdByNo($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT      invoice_id
		      FROM edms_invoice
			  WHERE invoice_no=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)==1)
		return $resultArray[0][0];	
		else if(dbNumRows($result)>1)
		return $resultArray;
		else return false; 
		}
		return false;
	}
	catch(Exception $e)
	{
	}
	
}	


function getTripsByInvoiceId($id)
{
	if(checkForNumeric($id))
	{
		$sql="SELECT  edms_trip_memo.trip_memo_id,from_branch_ledger_id,from_ledger.ledger_name as from_branch_ledger_name,to_branch_ledger_id,to_ledger.ledger_name as to_branch_ledger_name,truck_id,driver_id,remarks,trip_date,trip_memo_no
		      FROM edms_invoice_trip_memo
			  INNER JOIN edms_trip_memo ON edms_trip_memo.trip_memo_id = edms_invoice_trip_memo.trip_memo_id
			  INNER JOIN edms_ac_ledgers as from_ledger ON from_ledger.ledger_id = edms_trip_memo.from_branch_ledger_id 
              INNER JOIN edms_ac_ledgers as to_ledger ON to_ledger.ledger_id = edms_trip_memo.to_branch_ledger_id
			  WHERE edms_invoice_trip_memo.invoice_id = $id";
		
	    $result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray;	 
		}
		return false;
	
}

function getTripIdsByInvoiceId($id)
{
	if(checkForNumeric($id))
	{
		$sql="SELECT  edms_invoice_trip_memo.trip_memo_id
		      FROM edms_invoice_trip_memo
			 
		 WHERE  invoice_id = $id";
		
	    $result=dbQuery($sql);
		$return_array = array();
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
		foreach($resultArray as $re)		
		$return_array[] = $re[0];
		return $return_array;	 
		}
		}
		return false;
	
}


function checkForDuplicateTripInvoice($invoice_no,$id=false)
{
	    if(validateForNull($invoice_no))
		{
		$sql="SELECT invoice_id
		      FROM edms_invoice
			  WHERE invoice_no='$invoice_no'";
		if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND invoice_id!=$id";		  	  
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return true;
		else
		return false;
		}
}	
function checkifInvoiceInUse($id)
{
	if(checkForNumeric($id))
	{
	$sql="SELECT trip_memo_id FROM
			edms_invoice_trip_memo
			WHERE trip_memo_id=$id";
	$result=dbQuery($sql);
	if(dbNumRows($result)>0)
	return true;
	else
	return false;		
	}
	
	}	
?>