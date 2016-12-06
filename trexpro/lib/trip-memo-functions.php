<?php 
require_once("cg.php");
require_once("common.php");
require_once("product-functions.php");
require_once("account-ledger-functions.php");
require_once("lr-functions.php");
require_once("customer-functions.php");
require_once("truck-functions.php");
require_once("account-ledger-functions.php");
require_once("account-head-functions.php");
require_once("our-company-function.php");
require_once("branch-counter-function.php");
require_once("bd.php");
		

function checkForLRsInArray($lr_id_array)
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

function getBranchIdFromLrIdArray($lr_id_array)
{
	
	
	if(is_array($lr_id_array) && count($lr_id_array)>0)
	{	
		for($i=0;$i<count($lr_id_array);$i++)
		{
			$lr_id=$lr_id_array[$i];
			$lr=getLRById($lr_id);
			
			$to_pay = $lr['to_pay'];
			$paid = $lr['paid'];
			$to_be_billed = $lr['to_be_billed'];
			$from_branch = $lr['from_branch_ledger_id'];
			$to_branch = $lr['to_branch_ledger_id'];
			if($i==0)
			{
			if($paid>0)
			$main_branch_id_array = array($from_branch);
			else if($to_pay>0)
			$main_branch_id_array = array($to_branch);
			else 
			$main_branch_id_array = array($from_branch);
			}
			else
			{
			if($paid>0 && !in_array($from_branch,$main_branch_id_array))
			return false;
			else if($to_pay>0  && !in_array($to_branch,$main_branch_id_array))
			return false;
			if($to_be_billed>0 && !in_array($from_branch,$main_branch_id_array))
			return false;	
			}
			
			
			
		}
		return $main_branch_id_array[0];
				
	}
	
	
	}



function insertTripMemo($trip_date,$memo_no,$from_branch_ledger_id,$to_branch_ledger_id,$lr_id_array,$truck_no,$driver_name,$remarks,$select_lr_option=0){
	
	try
	{
		if(!checkForNumeric($select_lr_option))
		$select_lr_option=0;
		$trip_date=clean_data($trip_date);
		$from_branch_ledger_id=clean_data($from_branch_ledger_id);
		$to_branch_ledger_id=clean_data($to_branch_ledger_id);
		$memo_no=clean_data($memo_no);
		$truck_no=clean_data($truck_no);
		$driver_name = clean_data($driver_name);
		$remarks = clean_data($remarks);
		$truck_id =insertTruckIfNotDuplicate("NA",$truck_no,'',-1);
		$from_branch = getLedgerById($from_branch_ledger_id);
		$oc_id = $from_branch['oc_id'];
		$trip_memo_counter = getTripMemoCounterForBranchID($from_branch_ledger_id);
		$branch_code = getBranchCodeForBranchID($from_branch_ledger_id);
		$memo_noo = $branch_code.$memo_no;
		$debtors_head_id=getSundryDebtorsId();
		$driver_id = insertLedger($driver_name,'','',null,null,'',$debtors_head_id,'','','','',0,0,$oc_id,7);
		if($select_lr_option==1)
		$lr_id_array = getUnTrippedLrIDsFromBranchToBranch($from_branch_ledger_id,$to_branch_ledger_id);
		$has_lrs = checkForLRsInArray($lr_id_array);
		
	if(isset($trip_date) && validateForNull($trip_date))
    {
	$trip_date = str_replace('/', '-', $trip_date);
	$trip_date=date('Y-m-d',strtotime($trip_date));
	}

	if(!validateForNull($remarks))
	$remarks="NA";
	
		if(validateForNull($trip_date) && (checkForNumeric($from_branch_ledger_id,$to_branch_ledger_id,$memo_no,$driver_id,$truck_id,$select_lr_option))  && !checkForDuplicateTripMemo($memo_noo) && $has_lrs && $from_branch_ledger_id>0 && $to_branch_ledger_id>0 && $select_lr_option>=0)
		{
		

		
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$sql="INSERT INTO edms_trip_memo
		      (trip_memo_no,trip_date,from_branch_ledger_id,to_branch_ledger_id, truck_id, driver_id, remarks, created_by, last_updated_by, date_added, date_modified,sync_trip_memo_id,select_lr_option)
			  VALUES
			  ('$memo_noo','$trip_date',$from_branch_ledger_id, $to_branch_ledger_id,$truck_id,$driver_id,'$remarks',$admin_id, $admin_id, NOW(), NOW(),-1,$select_lr_option)";
		dbQuery($sql);
		$trip_memo_id = dbInsertId();
			  
		if(checkForNumeric($trip_memo_id))
		{
			
			 insertLRsToTrip($trip_memo_id,$lr_id_array);
			 if($memo_no==$trip_memo_counter)
			 {
			 incrementTripMemoCounterForBranchID($from_branch_ledger_id);
			 }
			 $sql="UPDATE edms_trip_memo SET sync_trip_memo_id = NULL WHERE trip_memo_id = $trip_memo_id ";
			 dbQuery($sql);
			 return $trip_memo_id;
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
function insertLRsToTrip($trip_memo_id,$lr_id_array)
{
	if(is_array($lr_id_array) && count($lr_id_array)>0)
	{
		for($i=0;$i<count($lr_id_array);$i++)
		{
			$lr_id=$lr_id_array[$i];
			
			
			if(checkForNumeric($lr_id,$trip_memo_id) && $lr_id>0 && $trip_memo_id>0)
			{
					
				$lr_product_id=insertLrToTrip($trip_memo_id,$lr_id);
			}	
			
		}
	
				
	}
	
}
function insertLrToTrip($trip_memo_id,$lr_id)
{
	
	if(checkForNumeric($trip_memo_id,$lr_id) && $lr_id>0 && $trip_memo_id>0 && !LRNotAddedToTripMemo($lr_id) && checkforCorrectCityTripToLr($trip_memo_id,$lr_id))
	{
		
		
		$sql="INSERT INTO edms_trip_lr (trip_memo_id,lr_id) VALUES ($trip_memo_id,$lr_id)";
		dbQuery($sql);
		$lr_product_id = dbInsertId();
		return $lr_product_id;
	}
	return false;
	
}
function LRNotAddedToTripMemo($lr_id)
{
	if(checkForNumeric($lr_id))
	{
		$sql="SELECT lr_id FROM  edms_trip_lr WHERE lr_id =$lr_id";
		$result=dbQuery($sql);
		if(dbNumRows($result)>0)
		return true;
		else
		return false;
	}
	return false;
}

function checkforCorrectCityTripToLr($trip_memo_id,$lr_id)
{
	$lr=getLRById($lr_id);
	$trip_memo = getTripMemoById($trip_memo_id);
	
	if($lr['to_branch_ledger_id']==$trip_memo['to_branch_ledger_id'])
	return true;
	else
	return false;
	
}

function deleteLrToTrip($trip_id,$bd2=false)
{
	if(checkForNumeric($trip_id))
	{
		$sql="DELETE FROM edms_trip_lr WHERE trip_memo_id = $trip_id";
		dbQuery($sql,$bd2);
		return true;
	}
	return false;
}



function deleteTrip($id,$bd2=false){
	
	try
	{
		if(checkForNumeric($id) && !checkifTripInUse($id,$bd2))
		{
		$trip = getTripMemoById($id,$bd2);
	
		$sql="DELETE FROM edms_trip_memo
		      WHERE trip_memo_id=$id";	  
		dbQuery($sql,$bd2);
		
		if($trip)
		insertDeletedTrip($trip['trip_date'],$trip['trip_memo_no'],$trip['from_branch_ledger_id'],$trip['to_branch_ledger_id'],$trip['truck_id'],$trip['driver_id'],$trip['remarks'],$trip['sync_trip_memo_id'],$bd2);
		
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

function insertDeletedTrip($trip_date,$memo_no,$from_branch_ledger_id,$to_branch_ledger_id,$truck_id,$driver_id,$remarks,$sync_trip_id,$bd2=false){
	
	try
	{
		$trip_date=clean_data($trip_date);
		$from_branch_ledger_id=clean_data($from_branch_ledger_id);
		$to_branch_ledger_id=clean_data($to_branch_ledger_id);
		$memo_no=clean_data($memo_no);
		$truck_id=clean_data($truck_id);
		$driver_id = clean_data($driver_id);
		$remarks = clean_data($remarks);
		
		if(!validateForNull($sync_trip_id))
		$sync_trip_id = "NULL";
		
		
		
	if(isset($trip_date) && validateForNull($trip_date))
    {
	$trip_date = str_replace('/', '-', $trip_date);
	$trip_date=date('Y-m-d',strtotime($trip_date));
	}

	if(!validateForNull($remarks))
	$remarks="NA";
	
		if(validateForNull($trip_date,$memo_no) && (checkForNumeric($from_branch_ledger_id,$to_branch_ledger_id,$driver_id,$truck_id))   && $from_branch_ledger_id>0 && $to_branch_ledger_id>0)
		{
		

		
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$sql="INSERT INTO edms_trip_memo_deleted
		      (trip_memo_no,trip_date,from_branch_ledger_id,to_branch_ledger_id, truck_id, driver_id, remarks, created_by, last_updated_by, date_added, date_modified,sync_trip_memo_id)
			  VALUES
			  ('$memo_no','$trip_date',$from_branch_ledger_id, $to_branch_ledger_id,$truck_id,$driver_id,'$remarks',$admin_id, $admin_id, NOW(), NOW(),$sync_trip_id)";
		dbQuery($sql,$bd2);
		
			 return true;
		
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

function updateTripMemo($trip_memo_id,$trip_date,$memo_no,$from_branch_ledger_id,$to_branch_ledger_id,$lr_id_array,$truck_no,$driver_name,$remarks){
	
	try
	{
		
		$trip_memo = getTripMemoById($trip_memo_id);
		if(SLAVE==1)
		{
		$trip_added_date = $trip_memo['date_added'];
		$edit_date_allowed = getTodaysDateTimeAfterDays(UPDATE_CONTRAINT,$trip_added_date);
		if(strtotime($edit_date_allowed)<strtotime(getTodaysDateTime()) && SLAVE==1)
		{
			return "error";
		}
		}
		$trip_date=clean_data($trip_date);
		$from_branch_ledger_id=clean_data($from_branch_ledger_id);
		$to_branch_ledger_id=clean_data($to_branch_ledger_id);
		$memo_no=clean_data($memo_no);
		$truck_no=clean_data($truck_no);
		$driver_name = clean_data($driver_name);
		$remarks = clean_data($remarks);
		$truck_id =insertTruckIfNotDuplicate("NA",$truck_no,'',-1);
		
		
		$from_branch = getLedgerById($from_branch_ledger_id);
		$oc_id = $from_branch['oc_id'];
		$trip_memo_counter = getTripMemoCounterForOCID($oc_id);
		$branch_code = getBranchCodeForBranchID($from_branch_ledger_id);
		$debtors_head_id=getSundryDebtorsId();
		$driver_id = insertLedger($driver_name,'','',null,null,'',$debtors_head_id,'','','','',0,0,$oc_id,7);
		
		$has_lrs = checkForLRsInArray($lr_id_array);
		
	if(isset($trip_date) && validateForNull($trip_date))
    {
	$trip_date = str_replace('/', '-', $trip_date);
	$trip_date=date('Y-m-d',strtotime($trip_date));
	}

	if(!validateForNull($remarks))
	$remarks="NA";
	
	$memo_noo = $branch_code.$memo_no;	
		if(validateForNull($trip_date) && (checkForNumeric($trip_memo_id,$from_branch_ledger_id,$to_branch_ledger_id,$memo_no,$driver_id,$truck_id))  && !checkForDuplicateTripMemo($memo_noo,$trip_memo_id) && $has_lrs && $from_branch_ledger_id>0 && $to_branch_ledger_id>0 && !checkIfInvoiceGeneratedForTripMemo($trip_memo_id))
		{

		$oc_id = $from_customer['our_company_id'];
			
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		
		$sql="UPDATE edms_trip_memo
		      SET from_branch_ledger_id = $from_branch_ledger_id, to_branch_ledger_id = $to_branch_ledger_id, truck_id = $truck_id, driver_id = $driver_id, trip_memo_no = '$memo_noo', trip_date='$trip_date', remarks = '$remarks' , last_updated_by = $admin_id, date_modified = NOW()
			  WHERE trip_memo_id=$trip_memo_id";
		dbQuery($sql);	  
		deleteLrToTrip($trip_memo_id);
		insertLRsToTrip($trip_memo_id,$lr_id_array);
		$sql="UPDATE edms_trip_memo SET  trip_updation_status = 1 WHERE trip_memo_id=$trip_memo_id";
		dbQuery($sql);
		return "success";
		}
		else if(checkIfInvoiceGeneratedForTripMemo($trip_memo_id))
		return "invoice_error";
		else
		{
			return "error";
			}
	}
	catch(Exception $e)
	{
	}
	
}	

function getTripMemoById($id,$bd2=false){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT      edms_trip_memo.trip_memo_id,from_branch_ledger_id,to_branch_ledger_id,truck_id,driver_id,remarks,trip_date,trip_memo_no, trip_updation_status, sync_trip_memo_id
		      FROM edms_trip_memo
			  WHERE edms_trip_memo.trip_memo_id=$id";
		$result=dbQuery($sql,$bd2);
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


function getTripMemoByLrId($id)
{	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT      edms_trip_memo.trip_memo_id,from_branch_ledger_id,to_branch_ledger_id,truck_id,driver_id,remarks,trip_date,trip_memo_no
		      FROM edms_trip_memo,edms_trip_lr
			  WHERE edms_trip_lr.lr_id=$id AND edms_trip_memo.trip_memo_id = edms_trip_lr.trip_memo_id ";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0];	 
		else
		return false;
		}
		return false;
	}
	catch(Exception $e)
	{
	}
	
}	


function checkIfInvoiceGeneratedForTripMemo($id)
{
	if(checkForNumeric($id))
	{
	$sql="SELECT invoice_id FROM
			edms_invoice_trip_memo
			WHERE  trip_memo_id=$id";
	$result=dbQuery($sql);
	if(dbNumRows($result)>0)
	return true;
	else
	return false;		
	}
}	
	

function getTripMemoIdByNo($id){
	
	try
	{
		if(validateForNull($id))
		{
		$sql="SELECT      edms_trip_memo.trip_memo_id,from_branch_ledger_id,to_branch_ledger_id,truck_id,driver_id,remarks,trip_date,trip_memo_no
		      FROM edms_trip_memo
			  WHERE edms_trip_memo.trip_memo_no='$id'";
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


function getLRsByTripId($id,$bd2=false)
{
	if(checkForNumeric($id))
	{
		$sql="SELECT edms_trip_lr.trip_lr_id,edms_lr.lr_id,trip_memo_id,from_branch_ledger_id,from_ledger.ledger_name as from_branch_ledger_name,to_branch_ledger_id,to_ledger.ledger_name as to_branch_ledger_name,from_customer_id,from_customer.customer_name as from_customer_name,to_customer_id ,to_customer.customer_name as to_customer_name,total_freight,remarks,lr_date,lr_no,to_pay,paid,to_be_billed, edms_lr.created_by, edms_lr.last_updated_by, edms_lr.date_added, edms_lr.date_modified, lr_type FROM edms_trip_lr 
INNER JOIN edms_lr ON edms_trip_lr.lr_id = edms_lr.lr_id
INNER JOIN edms_ac_ledgers as from_ledger ON from_ledger.ledger_id = edms_lr.from_branch_ledger_id 
INNER JOIN edms_ac_ledgers as to_ledger ON to_ledger.ledger_id = edms_lr.to_branch_ledger_id
INNER JOIN edms_customer as from_customer ON from_customer.customer_id = edms_lr.from_customer_id
INNER JOIN edms_customer as to_customer ON to_customer.customer_id = edms_lr.to_customer_id		
		 WHERE  edms_trip_lr.trip_memo_id = $id ORDER BY lr_no";
		
	    $result=dbQuery($sql,$bd2);
		$resultArray=dbResultToArray($result);
	
		if(dbNumRows($result)>0)
		return $resultArray;	 
		}
		return false;
	
}

function checkForDuplicateTripMemo($memo_no,$id=false,$bd2=false)
{
	    if(validateForNull($memo_no))
		{
		$sql="SELECT trip_memo_id
		      FROM edms_trip_memo
			  WHERE trip_memo_no='$memo_no' AND trip_updation_status !=-1 ";
		if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND trip_memo_id!=$id";		  	  
		$result=dbQuery($sql,$bd2);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return true;
		else
		return false;
		}
}	
function checkifTripInUse($id,$bd2=false)
{
	if(checkForNumeric($id))
	{
	$sql="SELECT trip_memo_id FROM
			edms_invoice_trip_memo
			WHERE trip_memo_id=$id
		UNION SELECT trip_memo_id FROM edms_rel_trip_memo_summary WHERE trip_memo_id=$id	
			";
	$result=dbQuery($sql,$bd2);
	if(dbNumRows($result)>0)
	return true;
	else
	return false;		
	}
	
	}	
	
function getTotalFreightForTripMemo($trip_memo_id)
{
	$total_freight = 0;
	if(checkForNumeric($trip_memo_id))
	{
		$trip_lrs=getLRsByTripId($trip_memo_id);
		foreach($trip_lrs as $lr)
		{
			$lr=getLRById($lr['lr_id']);
			$freight = $lr['total_freight'];
			$total_freight = $total_freight + $freight;
		}
	}
	return $total_freight;
}	

function getUnInvoicesTrips()
{
	$sql="SELECT      edms_trip_memo.trip_memo_id,from_branch_ledger_id,from_ledger.ledger_name as from_branch_ledger_name,to_branch_ledger_id,to_ledger.ledger_name as to_branch_ledger_name,truck_id,driver_id,remarks,trip_date,trip_memo_no
		      FROM edms_trip_memo
			  INNER JOIN edms_ac_ledgers as from_ledger ON from_ledger.ledger_id = edms_trip_memo.from_branch_ledger_id 
              INNER JOIN edms_ac_ledgers as to_ledger ON to_ledger.ledger_id = edms_trip_memo.to_branch_ledger_id
			  WHERE edms_trip_memo.trip_memo_id NOT IN (SELECT trip_memo_id FROM edms_invoice_trip_memo) ";
			 
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
	
		if(dbNumRows($result)>0)
		return $resultArray;	 
	
}

function getJvAmountAndIdsForTripMemo($memo_id)
{
	$debit_array = array();
	$credit_array = array();
	if(checkForNumeric($memo_id))
	{
		$lrs=getLRsByTripId($memo_id);
		foreach($lrs as $lr)
		{
			$lr_id = $lr['lr_id'];
			$lr_jvs_details=getJvAmountsAndIdsForLr($lr_id);
			
			$debit_array = array_merge($debit_array,$lr_jvs_details['debit']);
			$credit_array = array_merge($credit_array,$lr_jvs_details['credit']);
		}
	}
	return array('debit'=>$debit_array,"credit"=>$credit_array);
}

function getJvAmountAndIdsForTripMemoArray($memo_id_array)
{
	
	$from_ledger_id_array = array();
	$to_ledger_id_array = array();
	$from_ledger_amount_array = array();
	$to_ledger_amount_array = array();
	$debit_total = 0;
	$credit_total = 0;
	foreach($memo_id_array as $memo_id)
	{

	if(checkForNumeric($memo_id))
	{
		$lrs=getLRsByTripId($memo_id);
		
		foreach($lrs as $lr)
		{
			$lr_id = $lr['lr_id'];
			$lr_jvs_details=getJvAmountsAndIdsForLr($lr_id);	
		
			foreach($lr_jvs_details['debit'] as $debit_trans)
			{
				$debit_total = $debit_total + $debit_trans[1];
				if($debit_trans[2]=="L")
				$to_ledger_id_array[] = getLedgerNameFromLedgerId($debit_trans[0])." | [".$debit_trans[2].$debit_trans[0]."]";
				else
				$to_ledger_id_array[] = getFullCustomerNameByCustomerID($debit_trans[0]);
				$to_ledger_amount_array[] = $debit_trans[1];
			}
				foreach($lr_jvs_details['credit'] as $debit_trans)
			{
				$credit_total = $credit_total + $debit_trans[1];
				if($debit_trans[2]=="L")
				$from_ledger_id_array[] = getLedgerNameFromLedgerId($debit_trans[0])." | [".$debit_trans[2].$debit_trans[0]."]";
				else
				$from_ledger_id_array[] = getFullCustomerNameByCustomerID($debit_trans[0]);
				$from_ledger_amount_array[] = $debit_trans[1];
			}
			
		}
	}
	}
	return array($from_ledger_id_array,$from_ledger_amount_array,$to_ledger_id_array,$to_ledger_amount_array,$credit_total,$debit_total);

}
?>