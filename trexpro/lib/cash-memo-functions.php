<?php 
require_once("cg.php");
require_once("bd.php");
require_once("common.php");
require_once("product-functions.php");
require_once("lr-functions.php");
require_once("customer-functions.php");
require_once("truck-functions.php");
require_once("account-ledger-functions.php");
require_once("our-company-function.php");
require_once("trip-memo-functions.php");
require_once("branch-counter-function.php");
require_once("product-functions.php");
require_once("packing-unit-functions.php");
require_once("account-head-functions.php");
require_once("truck-functions.php");
		

function insertCashMemo($memo_no,$memo_date,$lr_id,$lr_amount,$labour,$other_charges){
	
	try
	{
			$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
			$oc_id=$_SESSION['edmsAdminSession']['oc_id'];
		
	if(isset($memo_date) && validateForNull($memo_date))
    {
	$memo_date = str_replace('/', '-', $memo_date);
	$memo_date=date('Y-m-d',strtotime($memo_date));
	}
	
	
	
	if(validateForNull($memo_date) && (checkForNumeric($memo_no,$lr_amount,$labour,$other_charges))  && !checkForDuplicateCashMemo($memo_no,false,$lr_id))
		{
		$total_amount = $lr_amount + $labour + $other_charges;
		

			
			$sql="INSERT INTO edms_cash_memo
				  (memo_no,memo_date,lr_id, lr_amount, labour, other_charges, total_amount, created_by, last_updated_by, date_added, date_modified)
				  VALUES
				  ('$memo_no','$memo_date',$lr_id,$lr_amount, $labour, $other_charges, $total_amount,$admin_id, $admin_id, NOW(), NOW())";
			dbQuery($sql);
			$cash_memo_id = dbInsertId();
				  
				if(checkForNumeric($cash_memo_id))
				{
					 $memo_counter = getCashMemoCounterForOCID($oc_id);
					 if($memo_no==$memo_counter)
					 {
					 incrementCashMemoCounterForOCID($oc_id);
					 }
					 
					 
				}
			
		return $cash_memo_id;
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

function deleteCashMemo($id){
	
	try
	{
		if(checkForNumeric($id))
		{
		$sql="DELETE FROM edms_cash_memo
		      WHERE cash_memo_id=$id";
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

function updateCashMemo($memo_id,$memo_no,$memo_date,$lr_id,$lr_amount,$labour,$other_charges){
	
	try
	{
			$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
			$oc_id=$_SESSION['edmsAdminSession']['oc_id'];
		
	if(isset($memo_date) && validateForNull($memo_date))
    {
	$memo_date = str_replace('/', '-', $memo_date);
	$memo_date=date('Y-m-d',strtotime($memo_date));
	}
	
	
	
	if(validateForNull($memo_date) && (checkForNumeric($memo_no,$memo_id,$lr_amount,$labour,$other_charges))  && !checkForDuplicateCashMemo($memo_no,$memo_id))
		{
		$total_amount = $lr_amount + $labour + $other_charges;

			
			$sql="UPDATE edms_cash_memo
				  SET memo_no = '$memo_no',memo_date = '$memo_date',lr_id = $lr_id, lr_amount = $lr_amount, labour = $labour, other_charges = $other_charges, total_amount = $total_amount,  last_updated_by = $admin_id,  date_modified = NOW() WHERE cash_memo_id = $memo_id";
			dbQuery($sql);
			 
			
		return $memo_id;
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


function checkForDuplicateCashMemo($memo_no,$id=false,$lr_id=false)
{
	    if(validateForNull($memo_no))
		{
		$sql="SELECT cash_memo_id
		      FROM edms_cash_memo
			  WHERE (memo_no='$memo_no'";
		if($id==false)
		$sql=$sql.")";
		else
		$sql=$sql." AND cash_memo_id!=$id)";	
		if($lr_id==false)
		$sql=$sql."";
		else
		$sql=$sql." OR lr_id=$lr_id";
				  	  
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return true;
		else
		return false;
		}
}	

function getUnCashMemoPaidLrs()
{
	$sql="SELECT  edms_lr.lr_id,from_branch_ledger_id,from_ledger.ledger_name as from_branch_ledger_name,to_branch_ledger_id,to_ledger.ledger_name as to_branch_ledger_name,from_customer_id,from_customer.customer_name as from_customer_name,to_customer_id,to_customer.customer_name as to_customer_name,freight,total_freight,weight,builty_charge,remarks,lr_date,lr_no,to_pay,paid,to_be_billed, tax_pay_type, edms_lr.created_by, edms_lr.last_updated_by, edms_lr.date_added, edms_lr.date_modified, SUM(tax_amount) as total_tax,delivery_at
		      FROM edms_lr
			  INNER JOIN edms_ac_ledgers as from_ledger ON from_ledger.ledger_id = edms_lr.from_branch_ledger_id 
              INNER JOIN edms_ac_ledgers as to_ledger ON to_ledger.ledger_id = edms_lr.to_branch_ledger_id
			  INNER JOIN edms_customer as from_customer ON from_customer.customer_id = edms_lr.from_customer_id 
			    INNER JOIN edms_customer as to_customer ON to_customer.customer_id = edms_lr.to_customer_id 
			  LEFT JOIN edms_lr_tax ON edms_lr_tax.lr_id = edms_lr.lr_id 
			  WHERE edms_lr.lr_id NOT IN (SELECT lr_id FROM edms_cash_memo) AND to_pay>0 GROUP BY edms_lr.lr_id";
			 
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
	
		if(dbNumRows($result)>0)
		return $resultArray;	 
	
}

function getCashMemoById($id)
{
	if(checkForNumeric($id))
	{
	$sql="SELECT  edms_lr.lr_id,from_branch_ledger_id,from_ledger.ledger_name as from_branch_ledger_name,to_branch_ledger_id,to_ledger.ledger_name as to_branch_ledger_name,from_customer_id,to_customer_id,freight,total_freight,weight,builty_charge,remarks,lr_date,lr_no,to_pay,paid,to_be_billed, tax_pay_type, edms_cash_memo.created_by, edms_cash_memo.last_updated_by, edms_cash_memo.date_added, edms_cash_memo.date_modified, SUM(tax_amount) as total_tax,delivery_at, memo_no, memo_date, lr_amount, labour, other_charges, total_amount
		      FROM edms_lr
			  INNER JOIN edms_ac_ledgers as from_ledger ON from_ledger.ledger_id = edms_lr.from_branch_ledger_id 
              INNER JOIN edms_ac_ledgers as to_ledger ON to_ledger.ledger_id = edms_lr.to_branch_ledger_id
			  INNER JOIN edms_cash_memo ON edms_cash_memo.lr_id = edms_lr.lr_id
			    LEFT JOIN edms_lr_tax ON edms_lr_tax.lr_id = edms_lr.lr_id 
			  WHERE edms_cash_memo.cash_memo_id = $id GROUP BY edms_lr.lr_id";
			
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		
		if(dbNumRows($result)>0)
		return $resultArray[0];	 
	}
	
}

function getCashMemoBetweenDates($from=NULL,$to=NULL)
{
	
	  if(isset($from) && validateForNull($from))
    {
	$from = str_replace('/', '-', $from);
	$from = date('Y-m-d',strtotime($from));
	}
	
	if(isset($to) && validateForNull($to))
	{
	$to = str_replace('/', '-', $to);
	$to=date('Y-m-d',strtotime($to));
	}
	{
	$sql="SELECT  edms_lr.lr_id,from_branch_ledger_id,from_ledger.ledger_name as from_branch_ledger_name,to_branch_ledger_id,to_ledger.ledger_name as to_branch_ledger_name,from_customer_id,to_customer_id,freight,total_freight,weight,builty_charge,remarks,lr_date,lr_no,to_pay,paid,to_be_billed, tax_pay_type, created_by, last_updated_by, date_added, date_modified, SUM(tax_amount) as total_tax,delivery_at, memo_no, memo_date, lr_amount, labour, other_charges, total_amount
		      FROM edms_lr
			  INNER JOIN edms_ac_ledgers as from_ledger ON from_ledger.ledger_id = edms_lr.from_branch_ledger_id 
              INNER JOIN edms_ac_ledgers as to_ledger ON to_ledger.ledger_id = edms_lr.to_branch_ledger_id
			  INNER JOIN edms_cash_memo ON edms_cash_memo.lr_id = edms_lr.lr_id
			  WHERE edms_cash_memo.cash_memo_id = $id ";
			 if(isset($from) && validateForNull($from))
	$sql=$sql." AND memo_date >='$from' 
		  ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql." AND memo_date<='$to' ";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
	
		if(dbNumRows($result)>0)
		return $resultArray[0];	 
	}
	
}

function getUnImportedTripMemosFromServer()
{
	$branch_id=getServerBranchId();
	if(checkForNumeric($branch_id))
	{
	$sql="SELECT GROUP_CONCAT(sync_trip_memo_id) FROM edms_trip_memo ";
	
	$result=dbQuery($sql);
	$result_array = dbResultToArray($result);

	$trip_memo_string = $result_array[0][0];

	
global $dbHost1, $dbUser1, $dbPass1,$dbConn1,$dbConn,$dbName1;

$dbConn1 = mysql_connect ($dbHost1, $dbUser1, $dbPass1,true) or die ('MySQL connect failed. ' . mysql_error($dbConn));
if($dbConn1)
{
mysql_select_db($dbName1,$dbConn1) or die('Cannot select database. ' . mysql_error($dbConn1));
$time1="SET time_zone='+5:30'";
mysql_query($time1,$dbConn1) or die(mysql_error($dbConn1));


$sql="SELECT      edms_trip_memo.trip_memo_id,from_branch_ledger_id,from_ledger.ledger_name as from_branch_ledger_name,to_branch_ledger_id,to_ledger.ledger_name as to_branch_ledger_name,edms_trip_memo.truck_id,driver_id,edms_trip_memo.remarks,trip_date,trip_memo_no
		      FROM edms_trip_memo
			  INNER JOIN edms_ac_ledgers as from_ledger ON from_ledger.ledger_id = edms_trip_memo.from_branch_ledger_id 
              INNER JOIN edms_ac_ledgers as to_ledger ON to_ledger.ledger_id = edms_trip_memo.to_branch_ledger_id
			  INNER JOIN edms_trucks ON edms_trucks.truck_id =  edms_trip_memo.truck_id
			  WHERE to_branch_ledger_id=$branch_id ";
			  if(validateForNull($trip_memo_string))
			  $sql=$sql." AND edms_trip_memo.trip_memo_id NOT IN ($trip_memo_string) ";
			 
		$result=dbQuery($sql,true);
		$resultArray=dbResultToArray($result);

		if(dbNumRows($result)>0)
		return $resultArray;	 

}
else
return "error";

	
	}
	
}

function importTripMemo($trip_memo_id)
{
	global $dbHost1, $dbUser1, $dbPass1,$dbConn1,$dbConn,$dbName1;

$dbConn1 = mysql_connect ($dbHost1, $dbUser1, $dbPass1,true) or die ('MySQL connect failed. ' . mysql_error($dbConn));
if($dbConn1)
{
mysql_select_db($dbName1,$dbConn1) or die('Cannot select database. ' . mysql_error($dbConn1));
$time1="SET time_zone='+5:30'";
mysql_query($time1,$dbConn1) or die(mysql_error($dbConn1));
$concerned_branch_id=getServerBranchId();
	
	try
	{
		$trip_memo = getTripMemoById($trip_memo_id,true);
		$trip_lrs = getLRsByTripId($trip_memo_id,true);
		
		$memo_no = $trip_memo['trip_memo_no'];
		$trip_date = $trip_memo['trip_date'];
		$from_branch_ledger_id = $trip_memo['from_branch_ledger_id'];
		$to_branch_ledger_id = $trip_memo['to_branch_ledger_id'];
		$from_branch_ledger = getLedgerById($from_branch_ledger_id,true);
		$to_branch_ledger = getLedgerById($to_branch_ledger_id,true);
		$new_from_branch_ledger_id = getLedgerIdForLedgerName($from_branch_ledger['ledger_name'],NULL);
		$new_to_branch_ledger_id = getLedgerIdForLedgerName($to_branch_ledger['ledger_name'],NULL);
		if(!checkForNumeric($new_from_branch_ledger_id))
		{
		$new_from_branch_ledger_id=insertLedger($from_branch_ledger['ledger_name'],NULL,NULL,NULL,NULL,NULL,getSundryDebtorsId(),9999999999,0,0,'',0,0,DEFAULT_OC_ID,5);
		}
		if(!checkForNumeric($new_to_branch_ledger_id))
		{
		$new_to_branch_ledger_id=insertLedger($to_branch_ledger['ledger_name'],NULL,NULL,NULL,NULL,NULL,getSundryDebtorsId(),9999999999,0,0,'',0,0,DEFAULT_OC_ID,5);
		}
		$truck_id = $trip_memo['truck_id'];
		$truck = getTruckById($truck_id,true);
		$new_truck_id=insertTruckIfNotDuplicate($truck['truck_name'],$truck['truck_no'],$truck['remarks'],-1);
		$driver_id = $trip_memo['driver_id'];
		$driver = getLedgerById($driver_id,true);
		$new_driver_id = getLedgerIdForLedgerName($driver['ledger_name'],NULL);
		if(!checkForNumeric($new_driver_id))
		{
		$new_driver_id=insertLedger($driver['ledger_name'],NULL,NULL,NULL,NULL,NULL,getSundryDebtorsId(),9999999999,0,0,'',0,0,DEFAULT_OC_ID,7);
		}
		$remarks = $trip_memo['remarks'];
		$admin_id = DEFAULT_ADMIN_ID;
		$duplicate = checkForDuplicateTripMemo($memo_no,false);
		
		if(!$duplicate)
		{
		$sql="INSERT INTO edms_trip_memo
		      (trip_memo_no,trip_date,from_branch_ledger_id,to_branch_ledger_id, truck_id, driver_id, remarks, created_by, last_updated_by, date_added, date_modified,sync_trip_memo_id,trip_updation_status)
			  VALUES
			  ('$memo_no','$trip_date',$new_from_branch_ledger_id, $new_to_branch_ledger_id,$new_truck_id,$new_driver_id,'$remarks',$admin_id, $admin_id, NOW(), NOW(),$trip_memo_id,-1)";
			  
		dbQuery($sql);
		$new_trip_memo_id = dbInsertId();
		
		foreach($trip_lrs as $trip_lr)
		{
		  $lr_id = $trip_lr['lr_id'];
		  $sql="SELECT lr_id FROM edms_lr WHERE sync_lr_id = $lr_id AND lr_updation_status!=-1";
		  
		  $result = dbQuery($sql);
		  $resultArray = dbResultToArray($result);
		  $new_lr_id = $resultArray[0][0];
		  
		  if(!is_numeric($new_lr_id))
		  $new_lr_id=importLrFromServer($lr_id);
		  if(checkForNumeric($new_trip_memo_id,$new_lr_id))
		  {
		  $sql="INSERT INTO edms_trip_lr (lr_id,trip_memo_id) VALUES ($new_lr_id,$new_trip_memo_id)";
		  dbQuery($sql);	
		  }
	      
		} 
		
		
		$sql="UPDATE edms_trip_memo SET trip_updation_status = 0 WHERE trip_memo_id = $new_trip_memo_id";
		dbQuery($sql);
		
		}
	
	$sql="UPDATE edms_ac_main_settings SET sync_in_progress=1,sync_time=NOW()";	
    dbQuery($sql);
	}
	catch(Exception $e)
	{
		if(checkForNumeric($new_trip_memo_id))
		{
			$sql="delete FROM edms_trip_memo WHERE trip_memo_id = $new_trip_memo_id";
			dbQuery($sql);
		}
	}
	}
	
}



function importLrFromServer($lr_id)
{
	global $dbHost1, $dbUser1, $dbPass1,$dbConn1,$dbConn,$dbName1;

	$dbConn1 = mysql_connect ($dbHost1, $dbUser1, $dbPass1,true) or die ('MySQL connect failed. ' . mysql_error($dbConn));
	if($dbConn1)
	{
	mysql_select_db($dbName1,$dbConn1) or die('Cannot select database. ' . mysql_error($dbConn1));
	$time1="SET time_zone='+5:30'";
	mysql_query($time1,$dbConn1) or die(mysql_error($dbConn1));
	try{
		$lr=getLRById($lr_id,true);
		$lr_products = getProductsByLRId($lr_id,true);

		$from_customer_id = insertCustomer($lr['from_customer_name'],'NA',3,'NA',NULL,'9999999999',NULL,NULL,NULL,NULL,NULL,'',0,0);
		
		$to_customer_id = insertCustomer($lr['to_customer_name'],'NA',3,'NA',NULL,'9999999999',NULL,NULL,NULL,NULL,NULL,'',0,0);
		$from_branch_ledger_id = $lr['from_branch_ledger_id'];
		$to_branch_ledger_id = $lr['to_branch_ledger_id'];
	
		$from_branch_ledger = getLedgerById($from_branch_ledger_id,true);
		$to_branch_ledger = getLedgerById($to_branch_ledger_id,true);
			
		$new_from_branch_ledger_id = getLedgerIdForLedgerName($from_branch_ledger['ledger_name'],NULL);
		$new_to_branch_ledger_id = getLedgerIdForLedgerName($to_branch_ledger['ledger_name'],NULL);
		
		if(!checkForNumeric($new_from_branch_ledger_id))
		{
		$new_from_branch_ledger_id=insertLedger($from_branch_ledger['ledger_name'],NULL,NULL,NULL,NULL,NULL,getSundryDebtorsId(),9999999999,0,0,'',0,0,DEFAULT_OC_ID,5);
		}
		if(!checkForNumeric($new_to_branch_ledger_id))
		{
		$new_to_branch_ledger_id=insertLedger($to_branch_ledger['ledger_name'],NULL,NULL,NULL,NULL,NULL,getSundryDebtorsId(),9999999999,0,0,'',0,0,DEFAULT_OC_ID,5);
		}
	
		$delivery_at = $lr['delivery_at'];
		$freight = $lr['freight'];
		$total_freight = $lr['total_freight'];
		$builty_charge = $lr['builty_charge'];
		$tempo_fare = $lr['tempo_fare'];
		$rebooking_chares = $lr['rebooking_charges'];
		$product_qty_wt = $lr['weight'];
		$lr_date = $lr['lr_date'];
		$lr_no = $lr['lr_no'];
		$to_pay = $lr['to_pay'];
		$paid = $lr['paid'];
		$to_be_billed = $lr['to_be_billed'];
		$tax_pay_type = $lr['tax_pay_type'];
		$admin_id = DEFAULT_ADMIN_ID;
		$lr_type = $lr['lr_type'];
		
		if(!checkForNumeric($lr_type))
		$lr_type=0;
		
		$duplicate = checkForDuplicateLR($lr_no,$from_branch_ledger_id,false);
		
		if(!$duplicate)
		{	
			$sql="INSERT INTO edms_lr
		(from_branch_ledger_id,to_branch_ledger_id,delivery_at,from_customer_id,to_customer_id,freight,total_freight,builty_charge,tempo_fare,rebooking_charges,weight,remarks,lr_date,lr_no,to_pay,paid,to_be_billed,tax_pay_type, created_by, last_updated_by, date_added, date_modified,sync_lr_id,lr_updation_status,lr_type)
			  VALUES
			  ($new_from_branch_ledger_id, $new_to_branch_ledger_id,'$delivery_at',$from_customer_id,$to_customer_id,$freight,$total_freight,$builty_charge,$tempo_fare,$rebooking_chares,$product_qty_wt,'$remarks','$lr_date','$lr_no',$to_pay,$paid,$to_be_billed,$tax_pay_type,$admin_id, $admin_id, NOW(), NOW(),$lr_id,-1,$lr_type)";
			 
			dbQuery($sql);
			$new_lr_id = dbInsertId();
			
			foreach($lr_products as $lr_product)
			{
				$product_name = $lr_product['product_name'];
				$packinng_unit = $lr_product['packing_unit'];
				$product_id=insertProductIfNotDuplicate($product_name);
				$packinng_unit_id = insertPackingUnitIfNotDuplicate($packinng_unit);
				
				insertProductToLr($new_lr_id,$product_id,$lr_product['qty_no'],$packinng_unit_id);
				
			}
			
			if($lr['tax_pay_type']==3)
			{
				$lr_tax=getTaxForLr($lr_id,true);
				insertTaxToLr($new_lr_id,$lr_tax[0]['tax_group_id'],$lr['freight']);
			
			}
			
			$sql="UPDATE edms_lr SET lr_updation_status=0 WHERE lr_id = $new_lr_id";
			dbQuery($sql);
			return $new_lr_id;
		}
	}
	catch(Exception $e)
	{
		if(checkForNumeric($new_lr_id))
		{
			$sql="delete FROM edms_lr WHERE lr_id = $new_lr_id";
			dbQuery($sql);
		}
	}
	}
}


?>