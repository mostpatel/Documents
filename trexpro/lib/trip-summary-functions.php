<?php 
require_once("cg.php");
require_once("common.php");
require_once("product-functions.php");
require_once("customer-functions.php");
require_once("truck-functions.php");
require_once("trip-memo-functions.php");
require_once("branch-counter-function.php");
require_once("our-company-function.php");
require_once("bd.php");
		

	
function checkForTripMemoInArray($lr_id_array)
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

function getFromBranchForTripMemoArray($lr_id_array,$bd2=false)
{
	
	$has_items=false;
	if(is_array($lr_id_array) && count($lr_id_array)>0)
	{	
		for($i=0;$i<count($lr_id_array);$i++)
		{
			$lr_id=$lr_id_array[$i];
			
			
			
			if(checkForNumeric($lr_id) && $lr_id>0)
			{
				$trip_memo = getTripMemoById($lr_id,$bd2);
				if(!$has_items)
				$has_items = $trip_memo['from_branch_ledger_id'];
				else if($trip_memo['from_branch_ledger_id']!=$has_items)
				return false;
			}	
			
		}
				
}
	return $has_items;
	
}

function insertTripSummary($invoice_date,$invoice_no,$memo_id_array,$remarks,$advance=0){
	
	try
	{
		$oc_id=$_SESSION['edmsAdminSession']['oc_id'];
		$invoice_date=clean_data($invoice_date);
		$invoice_no=clean_data($invoice_no);
		$remarks = clean_data($remarks);
		$from_branch_ledger_id = getFromBranchForTripMemoArray($memo_id_array);
		if(!checkForNumeric($from_branch_ledger_id))
		return "error";
		$summary_counter = getSummaryCounterForBranchID($from_branch_ledger_id);
		$branch_code = getBranchCodeForBranchID($from_branch_ledger_id);
		
		$has_trip_memos = checkForTripMemoInArray($memo_id_array);
		if(isset($invoice_date) && validateForNull($invoice_date))
    	{
		$invoice_date = str_replace('/', '-', $invoice_date);
		$invoice_date=date('Y-m-d',strtotime($invoice_date));
		}

	if(!validateForNull($remarks))
	$remarks="NA";
	if(!checkForNumeric($advance))
	$advance=0;
	     $summary_no = $branch_code.$invoice_no;
		if(validateForNull($invoice_date,$summary_no) && checkForNumeric($from_branch_ledger_id,$advance)  && !checkForDuplicateTripSummary($summary_no) && $has_trip_memos && $advance>=0)
		{
		

		
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$sql="INSERT INTO edms_trip_memo_summary
		      (trip_memo_summary_no,trip_memo_summary_date, remarks, created_by, last_updated_by, date_added, date_modified, from_branch_ledger_id,advance)
			  VALUES
			  ('$summary_no','$invoice_date','$remarks',$admin_id, $admin_id, NOW(), NOW(),$from_branch_ledger_id,$advance)";
		dbQuery($sql);
		$invoice_id = dbInsertId();
			  
		if(checkForNumeric($invoice_id))
		{
			
			 insertTripMemosToSummary($invoice_id,$memo_id_array);
			 
			
			 if($invoice_no==$summary_counter)
			 {
			 incrementSummaryNoForBranchID($from_branch_ledger_id);
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

function insertTripSummaryDeleted($invoice_id,$invoice_date,$summary_no,$remarks,$from_branch_ledger_id,$sync_trip_memo_summary_id,$advance,$bd2=false){
	
	try
	{
		$oc_id=$_SESSION['edmsAdminSession']['oc_id'];
		$invoice_date=clean_data($invoice_date);
		$summary_no=clean_data($summary_no);
		$remarks = clean_data($remarks);
		
		if(isset($invoice_date) && validateForNull($invoice_date))
    	{
		$invoice_date = str_replace('/', '-', $invoice_date);
		$invoice_date=date('Y-m-d',strtotime($invoice_date));
		}

	if(!validateForNull($remarks))
	$remarks="NA";
	if(!checkForNumeric($advance))
	$advance=0;
	   
		if(validateForNull($invoice_date,$summary_no) && checkForNumeric($from_branch_ledger_id,$invoice_id,$sync_trip_memo_summary_id))
		{
		

		
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$sql="INSERT INTO edms_trip_memo_summary_deleted
		      (trip_memo_summary_id,trip_memo_summary_no,trip_memo_summary_date, remarks, created_by, last_updated_by, date_added, date_modified, from_branch_ledger_id,sync_trip_memo_summary_id,advance)
			  VALUES
			  ($invoice_id,'$summary_no','$invoice_date','$remarks',$admin_id, $admin_id, NOW(), NOW(),$from_branch_ledger_id,$sync_trip_memo_summary_id,$advance)";
		dbQuery($sql,$bd2);
		$invoice_id = dbInsertId();
			  
		
			 return $invoice_id;	  
		
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



function insertTripMemosToSummary($trip_invoice_id,$memo_id_array)
{
	if(is_array($memo_id_array) && count($memo_id_array)>0)
	{
		for($i=0;$i<count($memo_id_array);$i++)
		{
			$memo_id=$memo_id_array[$i];
			
			
			if(checkForNumeric($memo_id,$trip_invoice_id) && $memo_id>0 && $trip_invoice_id>0)
			{
					
				$invoice_memo_id=insertMemoToSummary($trip_invoice_id,$memo_id);
			}	
			
		}
	
				
	}
	
}
function insertMemoToSummary($trip_invoice_id,$memo_id)
{
	
	if(checkForNumeric($memo_id,$trip_invoice_id) && $memo_id>0 && $trip_invoice_id>0 && !checkForDuplicateMemoToSummary($memo_id))
			{
		
		
		$sql="INSERT INTO edms_rel_trip_memo_summary (trip_memo_id,trip_memo_summary_id) VALUES ($memo_id,$trip_invoice_id)";
		dbQuery($sql);
		$lr_product_id = dbInsertId();
		return $lr_product_id;
	}
	return false;
	
}

function checkForDuplicateMemoToSummary($memo_id)
{
	if(checkForNumeric($memo_id) && $memo_id>0)
	{
		$sql="SELECT trip_memo_id FROM edms_rel_trip_memo_summary WHERE trip_memo_id = $memo_id";
		$result=dbQuery($sql);
		if(dbNumRows($result)>0)
		return true;
	}
	return false;
	
}

function deleteTripMemoToSummary($invoice_id)
{
	if(checkForNumeric($invoice_id))
	{
		$sql="DELETE FROM edms_rel_trip_memo_summary WHERE trip_memo_summary_id = $invoice_id";
		dbQuery($sql);
		return true;
	}
	return false;
}



function deleteTripSummary($id,$bd2=false){
	
	try
	{
		if(checkForNumeric($id))
		{
		$trip_summary = getTripSummaryById($id,$bd2);		
		$sql="DELETE FROM edms_trip_memo_summary
		      WHERE trip_memo_summary_id=$id";
		dbQuery($sql,$bd2);
		
		insertTripSummaryDeleted($trip_summary['trip_memo_summary_id'],$trip_summary['trip_memo_summary_date'],$trip_summary['trip_memo_summary_no'],$trip_summary['remarks'],$trip_summary['from_branch_ledger_id'],$trip_summary['sync_trip_memo_summary_id'],$trip_summary['advance'],$bd2);
		
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

function updateTripSummary($invoice_id,$invoice_date,$invoice_no,$memo_id_array,$remarks,$advance){
	
	try
	{
		
		
	  	$oc_id=$_SESSION['edmsAdminSession']['oc_id'];
		$invoice_date=clean_data($invoice_date);
		$invoice_no=clean_data($invoice_no);
		$remarks = clean_data($remarks);
		$from_branch_ledger_id = getFromBranchForTripMemoArray($memo_id_array);
		$summary_counter = getSummaryCounterForBranchID($from_branch_ledger_id);
		$branch_code = getBranchCodeForBranchID($from_branch_ledger_id);
		
		$has_trip_memos = checkForTripMemoInArray($memo_id_array);
		if(isset($invoice_date) && validateForNull($invoice_date))
    	{
		$invoice_date = str_replace('/', '-', $invoice_date);
		$invoice_date=date('Y-m-d',strtotime($invoice_date));
		}

	if(!validateForNull($remarks))
	$remarks="NA";
	     $summary_no = $branch_code.$invoice_no;
		if(!checkForNumeric($advance))
		$advance=0;
		if(validateForNull($invoice_date,$summary_no) && checkForNumeric($from_branch_ledger_id,$advance)  && !checkForDuplicateTripSummary($summary_no,$invoice_id) && $has_trip_memos)
		{
	
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$sql="UPDATE edms_trip_memo_summary
		      SET trip_memo_summary_no = '$summary_no',trip_memo_summary_date = '$invoice_date', remarks = '$remarks',  last_updated_by = $admin_id,  date_modified = NOW(), from_branch_ledger_id = $from_branch_ledger_id, advance=$advance, summary_updation_status = 1
			  WHERE trip_memo_summary_id = $invoice_id";
		dbQuery($sql);
		
			  
		if(checkForNumeric($invoice_id))
		{
			deleteTripMemoToSummary($invoice_id);
			insertTripMemosToSummary($invoice_id,$memo_id_array);
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

function getTripSummaryById($id,$bd2=false){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT      edms_trip_memo_summary.trip_memo_summary_id,trip_memo_summary_no,trip_memo_summary_date,edms_trip_memo_summary.remarks,edms_trip_memo_summary.created_by,edms_trip_memo_summary.last_updated_by,edms_trip_memo_summary.date_modified,edms_trip_memo_summary.date_added, from_branch_ledger_id, advance, sync_trip_memo_summary_id, summary_updation_status
		      FROM edms_trip_memo_summary,edms_rel_trip_memo_summary
			  WHERE edms_trip_memo_summary.trip_memo_summary_id= edms_rel_trip_memo_summary.trip_memo_summary_id AND edms_trip_memo_summary.trip_memo_summary_id = $id";
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

function getTripSummaryIdByNo($id){
	
	try
	{
		
		if(validateForNull($id))
		{
		$sql="SELECT      edms_trip_memo_summary.trip_memo_summary_id
		      FROM edms_trip_memo_summary
			  WHERE trip_memo_summary_no='$id'";
			  
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


function getTripsBySummaryId($id,$bd2=false)
{
	if(checkForNumeric($id))
	{
		$sql="SELECT  edms_trip_memo.trip_memo_id,from_branch_ledger_id,from_ledger.ledger_name as from_branch_ledger_name,to_branch_ledger_id,to_ledger.ledger_name as to_branch_ledger_name,truck_id,driver_id,remarks,trip_date,trip_memo_no, (SELECT SUM(qty_no) FROM edms_lr_product, edms_trip_lr WHERE edms_lr_product.lr_id = edms_trip_lr.lr_id AND edms_trip_lr.trip_memo_id = edms_trip_memo.trip_memo_id GROUP BY edms_trip_lr.trip_memo_id) as total_qty,(SELECT SUM(to_pay) FROM edms_lr,edms_trip_lr WHERE edms_lr.lr_id=edms_trip_lr.lr_id AND  edms_trip_lr.trip_memo_id = edms_trip_memo.trip_memo_id GROUP BY edms_trip_lr.trip_memo_id) as to_pay, (SELECT SUM(paid) FROM edms_lr,edms_trip_lr WHERE edms_lr.lr_id=edms_trip_lr.lr_id AND  edms_trip_lr.trip_memo_id = edms_trip_memo.trip_memo_id GROUP BY edms_trip_lr.trip_memo_id) as paid , (SELECT SUM(to_be_billed) FROM edms_lr,edms_trip_lr WHERE edms_lr.lr_id=edms_trip_lr.lr_id AND  edms_trip_lr.trip_memo_id = edms_trip_memo.trip_memo_id GROUP BY edms_trip_lr.trip_memo_id) as to_be_billed , (SELECT SUM(tax_amount) FROM edms_lr_tax,edms_trip_lr, edms_lr WHERE edms_lr.lr_id = edms_lr_tax.lr_id AND edms_lr_tax.lr_id = edms_trip_lr.lr_id AND  edms_trip_lr.trip_memo_id = edms_trip_memo.trip_memo_id AND to_pay > 0 GROUP BY edms_trip_lr.trip_memo_id) as to_pay_tax_amount ,(SELECT SUM(tax_amount) FROM edms_lr_tax,edms_trip_lr, edms_lr WHERE edms_lr.lr_id = edms_lr_tax.lr_id AND edms_lr_tax.lr_id = edms_trip_lr.lr_id AND  edms_trip_lr.trip_memo_id = edms_trip_memo.trip_memo_id AND paid > 0 GROUP BY edms_trip_lr.trip_memo_id) as paid_tax_amount, (SELECT SUM(tax_amount) FROM edms_lr_tax,edms_trip_lr, edms_lr WHERE edms_lr.lr_id = edms_lr_tax.lr_id AND edms_lr_tax.lr_id = edms_trip_lr.lr_id AND  edms_trip_lr.trip_memo_id = edms_trip_memo.trip_memo_id AND to_be_billed > 0 GROUP BY edms_trip_lr.trip_memo_id) as to_be_billed_tax_amount , (SELECT GROUP_CONCAT(lr_no) FROM edms_lr, edms_trip_lr WHERE edms_lr.lr_id = edms_trip_lr.lr_id AND edms_trip_lr.trip_memo_id = edms_trip_memo.trip_memo_id AND paid>0 GROUP BY edms_trip_memo.trip_memo_id) as paid_lr_nos, (SELECT GROUP_CONCAT(lr_no) FROM edms_lr, edms_trip_lr WHERE edms_lr.lr_id = edms_trip_lr.lr_id AND edms_trip_lr.trip_memo_id = edms_trip_memo.trip_memo_id AND to_be_billed>0 GROUP BY  edms_trip_memo.trip_memo_id) as to_be_billed_lr_nos , (SELECT SUM(tax_amount) FROM edms_lr_tax,edms_trip_lr WHERE  edms_lr_tax.lr_id = edms_trip_lr.lr_id AND  edms_trip_lr.trip_memo_id = edms_trip_memo.trip_memo_id GROUP BY edms_trip_lr.trip_memo_id) as tax_amount
		      FROM edms_rel_trip_memo_summary
			  INNER JOIN edms_trip_memo ON edms_trip_memo.trip_memo_id = edms_rel_trip_memo_summary.trip_memo_id
			  INNER JOIN edms_ac_ledgers as from_ledger ON from_ledger.ledger_id = edms_trip_memo.from_branch_ledger_id 
              INNER JOIN edms_ac_ledgers as to_ledger ON to_ledger.ledger_id = edms_trip_memo.to_branch_ledger_id
			  WHERE edms_rel_trip_memo_summary.trip_memo_summary_id = $id";
	    $result=dbQuery($sql,$bd2);
		$resultArray=dbResultToArray($result);
		
		if(dbNumRows($result)>0)
		return $resultArray;	 
		}
		return false;
	
}

function getTripIdsBySummaryId($id,$bd2=false)
{
	if(checkForNumeric($id))
	{
		$sql="SELECT  edms_rel_trip_memo_summary.trip_memo_id
		      FROM edms_rel_trip_memo_summary
			 
		 WHERE  trip_memo_summary_id = $id";
		
	    $result=dbQuery($sql,$bd2);
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


function checkForDuplicateTripSummary($invoice_no,$id=false,$bd2=false)
{
	    if(validateForNull($invoice_no))
		{
		$sql="SELECT trip_memo_summary_id
		      FROM edms_trip_memo_summary
			  WHERE trip_memo_summary_no='$invoice_no'";
		if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND trip_memo_summary_id!=$id";		  	  
		$result=dbQuery($sql,$bd2);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return true;
		else
		return false;
		}
}	

function getUnSummarizedTrips()
{
	$sql="SELECT      edms_trip_memo.trip_memo_id,from_branch_ledger_id,from_ledger.ledger_name as from_branch_ledger_name,to_branch_ledger_id,to_ledger.ledger_name as to_branch_ledger_name,truck_id,driver_id,remarks,trip_date,trip_memo_no
		      FROM edms_trip_memo
			  INNER JOIN edms_ac_ledgers as from_ledger ON from_ledger.ledger_id = edms_trip_memo.from_branch_ledger_id 
              INNER JOIN edms_ac_ledgers as to_ledger ON to_ledger.ledger_id = edms_trip_memo.to_branch_ledger_id
			  WHERE edms_trip_memo.trip_memo_id NOT IN (SELECT trip_memo_id FROM edms_rel_trip_memo_summary) ";
			  if(validateForNull(SUMMARY_START_DATE))
			 {
				 $trip_start_date = SUMMARY_START_DATE;
				$sql = $sql." AND trip_date>='$trip_start_date'"; 
				}
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
	
		if(dbNumRows($result)>0)
		return $resultArray;	 
	
}
	
?>