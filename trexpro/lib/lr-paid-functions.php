<?php 
require_once("cg.php");
require_once("common.php");
require_once("product-functions.php");
require_once("lr-functions.php");
require_once("customer-functions.php");
require_once("truck-functions.php");
require_once("account-ledger-functions.php");
require_once("our-company-function.php");
require_once("trip-memo-functions.php");
require_once("branch-counter-function.php");
require_once("bd.php");
		

function insertLrPaid($paid_date,$page_no,$lr_id_array,$cash_memo_number_array){
	
	try
	{
		$admin_branches = getBranchesForAdminId($_SESSION['edmsAdminSession']['admin_id']);
		$paid_date=clean_data($paid_date);
		$page_no=clean_data($page_no);
		$has_lrs = checkForLRsInArray($lr_id_array);
		$branch_id = getBranchIdFromLrIdArray($lr_id_array);
		
	if(isset($paid_date) && validateForNull($paid_date))
    {
	$paid_date = str_replace('/', '-', $paid_date);
	$paid_date=date('Y-m-d',strtotime($paid_date));
	}
	$branch_code = getBranchCodeForBranchID($branch_id);
	$page_noo=$branch_code.$page_no; 
	$no_of_lrs_in_array = count($lr_id_array);
	if($no_of_lrs_in_array>NO_OF_PAID_LRS_IN_PAGE)
	$no_of_pages = ceil($no_of_lrs_in_array / NO_OF_PAID_LRS_IN_PAGE);
	else $no_of_pages=1;
	
	if(validateForNull($paid_date) && (checkForNumeric($page_no))  && !checkForDuplicatePaidLr($page_noo) && $has_lrs)
		{
		

			for($i=0;$i<$no_of_pages;$i++)
			{
			if($i!=$no_of_pages-1)	
			{
			$lr_id_sub_array  = array_slice($lr_id_array,$i*NO_OF_PAID_LRS_IN_PAGE,NO_OF_PAID_LRS_IN_PAGE);	
			$cash_memo_number_sub_array =  array_slice($cash_memo_number_array,$i*NO_OF_PAID_LRS_IN_PAGE,NO_OF_PAID_LRS_IN_PAGE);	
			}
			else
			{
			$lr_id_sub_array  = array_slice($lr_id_array,$i*NO_OF_PAID_LRS_IN_PAGE);	
			$cash_memo_number_sub_array =  array_slice($cash_memo_number_array,$i*NO_OF_PAID_LRS_IN_PAGE);	
			}
			$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
			$sql="INSERT INTO edms_paid_lr
				  (page_no,paid_lr_date,branch_id, created_by, last_updated_by, date_added, date_modified)
				  VALUES
				  ('$page_noo','$paid_date',$branch_id,$admin_id, $admin_id, NOW(), NOW())";
			dbQuery($sql);
			$paid_lr_id = dbInsertId();
				  
				if(checkForNumeric($paid_lr_id))
				{
					 $page_counter = getPageCounterForBranchID($branch_id);
					 insertLRsToPaidLrs($paid_lr_id,$lr_id_sub_array,$cash_memo_number_sub_array);
					 if($page_no==$page_counter)
					 {
					 incrementPageNoForBranchID($branch_id);
					 }
					 $page_no = getPageCounterForBranchID($branch_id);
					 $page_noo = getPageNoForBranchID($branch_id);
					 
				}
			}
		return $paid_lr_id;
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
function insertLRsToPaidLrs($trip_memo_id,$lr_id_array,$cash_memo_number_sub_array)
{
	if(is_array($lr_id_array) && count($lr_id_array)>0)
	{
		for($i=0;$i<count($lr_id_array);$i++)
		{
			$lr_id=$lr_id_array[$i];
			$cash_memo_no = $cash_memo_number_sub_array[$i];
			
			if(checkForNumeric($lr_id,$trip_memo_id) && $lr_id>0 && $trip_memo_id>0 && (!validateForNull($cash_memo_no) || is_numeric($cash_memo_no)))
			{	
				$lr_product_id=insertLrToPaidLrs($trip_memo_id,$lr_id,$cash_memo_no);
			}	
			
		}
	
				
	}
	
}
function insertLrToPaidLrs($trip_memo_id,$lr_id,$cash_memo_no)
{
	
	if(checkForNumeric($trip_memo_id,$lr_id) && $lr_id>0 && $trip_memo_id>0 && !LRNotAddedToPaidLr($lr_id) && (!validateForNull($cash_memo_no) || is_numeric($cash_memo_no)))
	{
		if(!validateForNull($cash_memo_no))
		$cash_memo_no="";
		
		$sql="INSERT INTO edms_rel_paid_lr (paid_lr_id,lr_id,cash_memo_no) VALUES ($trip_memo_id,$lr_id,'$cash_memo_no')";
		dbQuery($sql);
		$lr_product_id = dbInsertId();
		return $lr_product_id;
	}
	return false;
	
}
function LRNotAddedToPaidLr($lr_id)
{
	if(checkForNumeric($lr_id))
	{
		$sql="SELECT lr_id FROM  edms_rel_paid_lr WHERE lr_id =$lr_id";
		$result=dbQuery($sql);
		if(dbNumRows($result)>0)
		return true;
		else
		return false;
	}
	return false;
}

function deleteLrToPaidLr($trip_id)
{
	if(checkForNumeric($trip_id))
	{
		$sql="DELETE FROM edms_rel_paid_lr WHERE paid_lr_id = $trip_id";
		dbQuery($sql);
		return true;
	}
	return false;
}

function deletePaidLr($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="DELETE FROM edms_paid_lr
		      WHERE paid_lr_id=$id";
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

function updatePaidLr($page_id,$paid_date,$page_no,$lr_id_array){
	
	try
	{
		$admin_branches = getBranchesForAdminId($_SESSION['edmsAdminSession']['admin_id']);
		$paid_date=clean_data($paid_date);
		$page_no=clean_data($page_no);
		$has_lrs = checkForLRsInArray($lr_id_array);
		
	if(isset($paid_date) && validateForNull($paid_date))
    {
	$paid_date = str_replace('/', '-', $paid_date);
	$paid_date=date('Y-m-d',strtotime($paid_date));
	}
	
	$no_of_lrs_in_array = count($lr_id_array);
	if($no_of_lrs_in_array>NO_OF_PAID_LRS_IN_PAGE)
	return "error";
	
		if(validateForNull($paid_date) && (checkForNumeric($page_no))  && !checkForDuplicatePaidLr($page_no,$page_id) && $has_lrs)
		{
		
		$oc_id = $from_customer['our_company_id'];
			
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
			
		$sql="UPDATE edms_paid_lr
		      SET paid_lr_date = '$paid_date', $page_no = '$page_no'
			  WHERE paid_lr_id=$page_id";
		dbQuery($sql);	  
		deleteLrToPaidLr($page_id);
		insertLRsToPaidLrs($page_id,$lr_id_array);

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

function getPaidLrsByPageId($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT  paid_lr_date, page_no, branch_id,created_by, last_updated_by, date_added, date_modified
		      FROM edms_paid_lr
			  WHERE edms_paid_lr.paid_lr_id=$id";
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

function getPaidLrIdByPageNo($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="SELECT       paid_lr_date, page_no,branch_id, created_by, last_updated_by, date_added, date_modified
		      FROM edms_paid_lr
			  WHERE edms_paid_lr.page_no='$id'";
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


function getLRsByPaidLrId($id)
{
	if(checkForNumeric($id))
	{
		$sql="SELECT edms_lr.lr_id,cash_memo_no,from_branch_ledger_id,from_ledger.ledger_name as from_branch_ledger_name,to_branch_ledger_id,to_ledger.ledger_name as to_branch_ledger_name,from_customer_id,from_customer.customer_name as from_customer_name,to_customer_id ,to_customer.customer_name as to_customer_name,total_freight,remarks,lr_date,lr_no,to_pay,paid,to_be_billed, edms_lr.created_by, edms_lr.last_updated_by, edms_lr.date_added, edms_lr.date_modified FROM edms_rel_paid_lr 
INNER JOIN edms_lr ON edms_rel_paid_lr.lr_id = edms_lr.lr_id
INNER JOIN edms_ac_ledgers as from_ledger ON from_ledger.ledger_id = edms_lr.from_branch_ledger_id 
INNER JOIN edms_ac_ledgers as to_ledger ON to_ledger.ledger_id = edms_lr.to_branch_ledger_id
INNER JOIN edms_customer as from_customer ON from_customer.customer_id = edms_lr.from_customer_id
INNER JOIN edms_customer as to_customer ON to_customer.customer_id = edms_lr.to_customer_id		
		 WHERE  edms_rel_paid_lr.paid_lr_id = $id";
		
	    $result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
	
		if(dbNumRows($result)>0)
		return $resultArray;	 
		}
		return false;
	
}

function checkForDuplicatePaidLr($memo_no,$id=false)
{
	    if(validateForNull($memo_no))
		{
		$sql="SELECT paid_lr_id
		      FROM edms_paid_lr
			  WHERE page_no='$memo_no'";
		if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND paid_lr_id!=$id";		  	  
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return true;
		else
		return false;
		}
}	

function getUnPaidLrs()
{
	$sql="SELECT  edms_lr.lr_id,from_branch_ledger_id,from_ledger.ledger_name as from_branch_ledger_name,to_branch_ledger_id,to_ledger.ledger_name as to_branch_ledger_name,from_customer_id,to_customer_id,freight,total_freight,weight,builty_charge,remarks,lr_date,lr_no,to_pay,paid,to_be_billed, tax_pay_type, created_by, last_updated_by, date_added, date_modified, SUM(tax_amount) as total_tax,delivery_at
		      FROM edms_lr
			  INNER JOIN edms_ac_ledgers as from_ledger ON from_ledger.ledger_id = edms_lr.from_branch_ledger_id 
              INNER JOIN edms_ac_ledgers as to_ledger ON to_ledger.ledger_id = edms_lr.to_branch_ledger_id
			  WHERE edms_lr.lr_id NOT IN (SELECT lr_id FROM edms_rel_paid_lr) ";
			 
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
	
		if(dbNumRows($result)>0)
		return $resultArray;	 
	
}
function getUnPaidLrsByBranch($branch_id_string)
{
	$sql="SELECT  edms_lr.lr_id,from_branch_ledger_id,from_ledger.ledger_name as from_branch_ledger_name,to_branch_ledger_id,to_ledger.ledger_name as to_branch_ledger_name,from_customer_id,from_customer.customer_name as from_customer_name,to_customer_id ,to_customer.customer_name as to_customer_name,freight,total_freight,weight,builty_charge,remarks,lr_date,lr_no,to_pay,paid,to_be_billed, tax_pay_type, edms_lr.created_by, edms_lr.last_updated_by, edms_lr.date_added, edms_lr.date_modified, (SELECT SUM(tax_amount) FROM edms_lr_tax WHERE edms_lr.lr_id = edms_lr_tax.lr_id GROUP BY edms_lr_tax.lr_id )  as total_tax,delivery_at
		      FROM edms_lr
			  INNER JOIN edms_ac_ledgers as from_ledger ON from_ledger.ledger_id = edms_lr.from_branch_ledger_id 
              INNER JOIN edms_ac_ledgers as to_ledger ON to_ledger.ledger_id = edms_lr.to_branch_ledger_id
			  INNER JOIN edms_customer as from_customer ON from_customer.customer_id = edms_lr.from_customer_id
INNER JOIN edms_customer as to_customer ON to_customer.customer_id = edms_lr.to_customer_id		
			  WHERE edms_lr.lr_id NOT IN (SELECT lr_id FROM edms_rel_paid_lr) AND ((to_branch_ledger_id IN ($branch_id_string) AND to_pay>0)) "; 
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
	
		if(dbNumRows($result)>0)
		return $resultArray;	 
	
}
?>