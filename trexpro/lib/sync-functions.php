<?php 
require_once('cg.php');

$dbConn1 = mysql_connect ($dbHost1, $dbUser1, $dbPass1,true) or die ('MySQL connect failed. ' . mysql_error($dbConn));
if($dbConn1)
{
mysql_select_db($dbName1,$dbConn1) or die('Cannot select database. ' . mysql_error($dbConn1));
$time1="SET time_zone='+5:30'";
mysql_query($time1,$dbConn1) or die(mysql_error($dbConn1));
}
else
exit;
require_once('bd.php');
require_once('customer-functions.php');
require_once("common.php");
require_once("backup.php");
require_once("lr-functions.php");
require_once("product-functions.php");
require_once("packing-unit-functions.php");
require_once("account-ledger-functions.php");
require_once("account-head-functions.php");
require_once("trip-memo-functions.php");
require_once("trip-summary-functions.php");
require_once("truck-functions.php");



function syncLrFromServer($lr_id)
{
	
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
			$sql="UPDATE edms_lr SET sync_lr_id = $new_lr_id WHERE lr_id = $lr_id";
			dbQuery($sql,true);
			return $new_lr_id;
		}
	
	
}

function syncLr($lr_id)
{
	
		$lr=getLRById($lr_id);
		$lr_products = getProductsByLRId($lr_id);

		$from_customer_id = insertCustomer($lr['from_customer_name'],'NA',3,'NA',NULL,'9999999999',NULL,NULL,NULL,NULL,NULL,'',0,0,true);
		
		$to_customer_id = insertCustomer($lr['to_customer_name'],'NA',3,'NA',NULL,'9999999999',NULL,NULL,NULL,NULL,NULL,'',0,0,true);
		$from_branch_ledger_id = $lr['from_branch_ledger_id'];
		$to_branch_ledger_id = $lr['to_branch_ledger_id'];
	
		$from_branch_ledger = getLedgerById($from_branch_ledger_id);
		$to_branch_ledger = getLedgerById($to_branch_ledger_id);
			
		$new_from_branch_ledger_id = getLedgerIdForLedgerName($from_branch_ledger['ledger_name'],NULL,true);
		$new_to_branch_ledger_id = getLedgerIdForLedgerName($to_branch_ledger['ledger_name'],NULL,true);
		
		if(!checkForNumeric($new_from_branch_ledger_id))
		{
		$new_from_branch_ledger_id=insertLedger($from_branch_ledger['ledger_name'],NULL,NULL,NULL,NULL,NULL,getSundryDebtorsId(true),9999999999,0,0,'',0,0,DEFAULT_OC_ID,5,true);
		}
		if(!checkForNumeric($new_to_branch_ledger_id))
		{
		$new_to_branch_ledger_id=insertLedger($to_branch_ledger['ledger_name'],NULL,NULL,NULL,NULL,NULL,getSundryDebtorsId(true),9999999999,0,0,'',0,0,DEFAULT_OC_ID,5,true);
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
		$duplicate = checkForDuplicateLR($lr_no,$from_branch_ledger_id,false,true);
		
		if(!checkForNumeric($lr_type))
		$lr_type=0;
		
		if(!$duplicate)
		{	
			$sql="INSERT INTO edms_lr
		(from_branch_ledger_id,to_branch_ledger_id,delivery_at,from_customer_id,to_customer_id,freight,total_freight,builty_charge,tempo_fare,rebooking_charges,weight,remarks,lr_date,lr_no,to_pay,paid,to_be_billed,tax_pay_type, created_by, last_updated_by, date_added, date_modified,sync_lr_id,lr_updation_status,lr_type)
			  VALUES
			  ($new_from_branch_ledger_id, $new_to_branch_ledger_id,'$delivery_at',$from_customer_id,$to_customer_id,$freight,$total_freight,$builty_charge,$tempo_fare,$rebooking_chares,$product_qty_wt,'$remarks','$lr_date','$lr_no',$to_pay,$paid,$to_be_billed,$tax_pay_type,$admin_id, $admin_id, NOW(), NOW(),$lr_id,-1,$lr_type)";
			 
			dbQuery($sql,true);
			$new_lr_id = dbInsertId(true);
			
			foreach($lr_products as $lr_product)
			{
				$product_name = $lr_product['product_name'];
				$packinng_unit = $lr_product['packing_unit'];
				$product_id=insertProductIfNotDuplicate($product_name,true);
				$packinng_unit_id = insertPackingUnitIfNotDuplicate($packinng_unit,true);
				
				insertProductToLr($new_lr_id,$product_id,$lr_product['qty_no'],$packinng_unit_id,true);
				
			}
			
			if($lr['tax_pay_type']==3)
			{
				$lr_tax=getTaxForLr($lr_id);
				insertTaxToLr($new_lr_id,$lr_tax[0]['tax_group_id'],$lr['freight'],true);
			}
			
			$sql="UPDATE edms_lr SET lr_updation_status = 0 WHERE lr_id = $new_lr_id";
			dbQuery($sql,true);
			$sql="UPDATE edms_lr SET sync_lr_id = $new_lr_id WHERE lr_id = $lr_id";
			dbQuery($sql);
			return $new_lr_id;
		}
	
	
}

function syncTripMemoFromServer($trip_memo_id)
{
	$concerned_branch_id=getServerBranchId();
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
			  $new_lr_id=syncLrFromServer($lr_id);
			  if(checkForNumeric($new_trip_memo_id,$new_lr_id))
			  {
			  $sql="INSERT INTO edms_trip_lr (lr_id,trip_memo_id) VALUES ($new_lr_id,$new_trip_memo_id)";
			  dbQuery($sql);	
			  }
			  
			} 
		
		
		$sql="UPDATE edms_trip_memo SET trip_updation_status = 0 WHERE trip_memo_id = $new_trip_memo_id";
		dbQuery($sql);
		$sql="UPDATE edms_trip_memo SET sync_trip_memo_id = $new_trip_memo_id WHERE trip_memo_id = $trip_memo_id";
		dbQuery($sql,true);
		
		return $new_trip_memo_id;
	
		}
}

function syncTripMemo($trip_memo_id)
{
	$concerned_branch_id=getServerBranchId();
	    $trip_memo = getTripMemoById($trip_memo_id);
		$trip_lrs = getLRsByTripId($trip_memo_id);
		
		$memo_no = $trip_memo['trip_memo_no'];
		$trip_date = $trip_memo['trip_date'];
		$from_branch_ledger_id = $trip_memo['from_branch_ledger_id'];
		$to_branch_ledger_id = $trip_memo['to_branch_ledger_id'];
		$from_branch_ledger = getLedgerById($from_branch_ledger_id);
		$to_branch_ledger = getLedgerById($to_branch_ledger_id);
		$new_from_branch_ledger_id = getLedgerIdForLedgerName($from_branch_ledger['ledger_name'],NULL,true);
		$new_to_branch_ledger_id = getLedgerIdForLedgerName($to_branch_ledger['ledger_name'],NULL,true);
		if(!checkForNumeric($new_from_branch_ledger_id))
		{
		$new_from_branch_ledger_id=insertLedger($from_branch_ledger['ledger_name'],NULL,NULL,NULL,NULL,NULL,getSundryDebtorsId(true),9999999999,0,0,'',0,0,DEFAULT_OC_ID,5,true);
		}
		if(!checkForNumeric($new_to_branch_ledger_id))
		{
		$new_to_branch_ledger_id=insertLedger($to_branch_ledger['ledger_name'],NULL,NULL,NULL,NULL,NULL,getSundryDebtorsId(true),9999999999,0,0,'',0,0,DEFAULT_OC_ID,5,true);
		}
		$truck_id = $trip_memo['truck_id'];
		$truck = getTruckById($truck_id);
		$new_truck_id=insertTruckIfNotDuplicate($truck['truck_name'],$truck['truck_no'],$truck['remarks'],-1,true);
		$driver_id = $trip_memo['driver_id'];
		$driver = getLedgerById($driver_id);
		$new_driver_id = getLedgerIdForLedgerName($driver['ledger_name'],NULL,true);
		if(!checkForNumeric($new_driver_id))
		{
		$new_driver_id=insertLedger($driver['ledger_name'],NULL,NULL,NULL,NULL,NULL,getSundryDebtorsId(true),9999999999,0,0,'',0,0,DEFAULT_OC_ID,7,true);
		}
		$remarks = $trip_memo['remarks'];
		$admin_id = DEFAULT_ADMIN_ID;
		$duplicate = checkForDuplicateTripMemo($memo_no,false,true);
		
		if(!$duplicate)
		{
		$sql="INSERT INTO edms_trip_memo
		      (trip_memo_no,trip_date,from_branch_ledger_id,to_branch_ledger_id, truck_id, driver_id, remarks, created_by, last_updated_by, date_added, date_modified,sync_trip_memo_id,trip_updation_status)
			  VALUES
			  ('$memo_no','$trip_date',$new_from_branch_ledger_id, $new_to_branch_ledger_id,$new_truck_id,$new_driver_id,'$remarks',$admin_id, $admin_id, NOW(), NOW(),$trip_memo_id,-1)";
			  
		dbQuery($sql,true);
		$new_trip_memo_id = dbInsertId(true);
		
		foreach($trip_lrs as $trip_lr)
		{
		  $lr_id = $trip_lr['lr_id'];
		 
		  $sql="SELECT lr_id FROM edms_lr WHERE sync_lr_id = $lr_id AND lr_updation_status!=-1 AND from_branch_ledger_id = $concerned_branch_id";
		 
		  $result = dbQuery($sql,true);
		  $resultArray = dbResultToArray($result);
		  $new_lr_id = $resultArray[0][0];
		 
		  if(!is_numeric($new_lr_id))
		  $new_lr_id=syncLr($lr_id);
		  if(checkForNumeric($new_trip_memo_id,$new_lr_id))
		  {
		  $sql="INSERT INTO edms_trip_lr (lr_id,trip_memo_id) VALUES ($new_lr_id,$new_trip_memo_id)";
		  dbQuery($sql,true);	
		  }
	      
		} 
		
		
		$sql="UPDATE edms_trip_memo SET trip_updation_status = 0 WHERE trip_memo_id = $new_trip_memo_id";
		dbQuery($sql,true);
		$sql="UPDATE edms_trip_memo SET sync_trip_memo_id = $new_trip_memo_id WHERE trip_memo_id = $trip_memo_id";
		dbQuery($sql);
		}
	$sql="UPDATE edms_ac_main_settings SET sync_in_progress=1,sync_time=NOW()";	
    dbQuery($sql);	
}

$sql = "SELECT sync_in_progress,sync_time FROM edms_ac_main_settings";
$result = dbQuery($sql);
$resultArray = dbResultToArray($result);

if($resultArray['sync_in_progress']==0 || (strtotime(getTodaysDateTime()) - strtotime($resultArray[1]))>300)
{
$concerned_branch_id=getServerBranchId();

$sql = "DELETE FROM edms_lr WHERE lr_updation_status=-1 AND from_branch_ledger_id = $concerned_branch_id";

dbQuery($sql,true);

$sql = "DELETE FROM edms_trip_memo WHERE trip_updation_status=-1 AND from_branch_ledger_id = $concerned_branch_id";

dbQuery($sql,true);	
	
$sql="UPDATE edms_ac_main_settings SET sync_in_progress=1,sync_time=NOW(), sync_status = 'start'";	

dbQuery($sql);








//SERVER TO LOCAL

// NEW LR INSERTION



$sql="SELECT lr_id FROM edms_lr WHERE sync_lr_id IS NULL AND from_branch_ledger_id = $concerned_branch_id";

$result = dbQuery($sql,true);
$num_fields=mysql_num_fields($result);

if(dbNumRows($result)>0)
{
	$resultArray = dbResultToArray($result);
	
	foreach($resultArray as $lr_id)
	{
		syncLrFromServer($lr_id[0]);
		$sql="UPDATE edms_ac_main_settings SET sync_in_progress=1,sync_time=NOW()";	
        dbQuery($sql);
	}
}

$sql="UPDATE edms_ac_main_settings SET sync_in_progress=1,sync_time=NOW(), sync_status = 'server_lr_insert'";	

dbQuery($sql);


// END LR INSERTION

// TRIP MEMO INSERTION
$sql="SELECT trip_memo_id FROM edms_trip_memo WHERE sync_trip_memo_id IS NULL AND from_branch_ledger_id = $concerned_branch_id";
$result = dbQuery($sql,true);
$num_fields=mysql_num_fields($result);
if(dbNumRows($result)>0)
{
	$resultArray = dbResultToArray($result);
	
	foreach($resultArray as $trip_memo_id)
	{
		$trip_memo_id = $trip_memo_id[0];
		syncTripMemoFromServer($trip_memo_id);
		$sql="UPDATE edms_ac_main_settings SET sync_in_progress=1,sync_time=NOW()";	
  		dbQuery($sql);
	}
}
	
$sql="UPDATE edms_ac_main_settings SET sync_in_progress=1,sync_time=NOW(), sync_status = 'server_trip_insert'";	

dbQuery($sql);
// END TRIP INSERTION

// TRIP SUMMARY INSERTION
$sql="SELECT trip_memo_summary_id FROM edms_trip_memo_summary WHERE sync_trip_memo_summary_id IS NULL AND from_branch_ledger_id = $concerned_branch_id";
$result = dbQuery($sql,true);
$num_fields=mysql_num_fields($result);
if(dbNumRows($result)>0)
{
	$resultArray = dbResultToArray($result);
	
	foreach($resultArray as $trip_memo_id)
	{
		$trip_memo_summary_id = $trip_memo_id[0];
		$trip_summary = getTripSummaryById($trip_memo_summary_id,true);
		$trips = getTripIdsBySummaryId($trip_memo_summary_id,true);
		
		$advance = $trip_summary['advance'];
		$summary_no = $trip_summary['trip_memo_summary_no'];
		$summary_date = $trip_summary['trip_memo_summary_date'];
		$from_branch_ledger_id = $trip_summary['from_branch_ledger_id'];
		$from_branch_ledger = getLedgerById($from_branch_ledger_id,true);
		$new_from_branch_ledger_id = getLedgerIdForLedgerName($from_branch_ledger['ledger_name'],NULL);
		if(!checkForNumeric($new_from_branch_ledger_id))
		{
		$new_from_branch_ledger_id=insertLedger($from_branch_ledger['ledger_name'],NULL,NULL,NULL,NULL,NULL,getSundryDebtorsId(),9999999999,0,0,'',0,0,DEFAULT_OC_ID,5);
		}

		$remarks = $trip_summary['remarks'];
		$admin_id = DEFAULT_ADMIN_ID;
		$duplicate = checkForDuplicateTripSummary($summary_no,false);
		
		if(!$duplicate)
		{
		$sql="INSERT INTO edms_trip_memo_summary
		      (trip_memo_summary_no,trip_memo_summary_date,from_branch_ledger_id, remarks, created_by, last_updated_by, date_added, date_modified,sync_trip_memo_summary_id,summary_updation_status,advance)
			  VALUES
			  ('$summary_no','$summary_date',$new_from_branch_ledger_id,'$remarks',$admin_id, $admin_id, NOW(), NOW(),$trip_memo_summary_id,-1,$advance)";
		 
		 dbQuery($sql);
		
		$new_trip_summary_id = dbInsertId();
		
		
		foreach($trips as $memo_id)
		{
			
		  $sql="SELECT trip_memo_id FROM edms_trip_memo WHERE sync_trip_memo_id = $memo_id AND trip_updation_status!=-1";
		  
		  $result = dbQuery($sql);
		  $resultArray = dbResultToArray($result);
		  $new_trip_id = $resultArray[0][0];
		  
		  if(!is_numeric($new_trip_id))
		  $new_trip_id=syncTripMemoFromServer($memo_id);
		  if(checkForNumeric($new_trip_summary_id,$new_trip_id))
		  {
		  $sql="INSERT INTO edms_rel_trip_memo_summary (trip_memo_id,trip_memo_summary_id) VALUES ($new_trip_id,$new_trip_summary_id)";
		 
		  dbQuery($sql);	
		  }
	      
		} 
		
		
		$sql="UPDATE edms_trip_memo_summary SET summary_updation_status = 0 WHERE trip_memo_summary_id = $new_trip_summary_id";
		dbQuery($sql);
		$sql="UPDATE edms_trip_memo_summary SET sync_trip_memo_summary_id = $new_trip_summary_id WHERE trip_memo_summary_id = $trip_memo_summary_id";
		dbQuery($sql,true);
		}
	$sql="UPDATE edms_ac_main_settings SET sync_in_progress=1,sync_time=NOW()";	
    dbQuery($sql);
	}
	
}

$sql="UPDATE edms_ac_main_settings SET sync_in_progress=1,sync_time=NOW(), sync_status = 'server_trip_summary_insert'";	

dbQuery($sql);
// END TRIP SUMMARY INSERTION

// LR UPDATION

$sql="SELECT lr_id FROM edms_lr WHERE sync_lr_id IS NOT NULL AND lr_updation_status=1 AND from_branch_ledger_id = $concerned_branch_id";

$result = dbQuery($sql,true);
$num_fields=mysql_num_fields($result);

if(dbNumRows($result)>0)
{
	$resultArray = dbResultToArray($result);
	
	foreach($resultArray as $lr_id)
	{
		$lr_id = $lr_id[0];
		$lr=getLRById($lr_id,true);
		$new_lr_id = $lr['sync_lr_id'];
		
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
		$duplicate = checkForDuplicateLR($lr_no,$from_branch_ledger_id,$new_lr_id);
		
		if(!$duplicate)
		{	
			$sql="UPDATE edms_lr
		      SET from_branch_ledger_id = $new_from_branch_ledger_id, to_branch_ledger_id = $new_to_branch_ledger_id, delivery_at='$delivery_at',from_customer_id = $from_customer_id, to_customer_id = $to_customer_id, freight = $freight, total_freight = $total_freight, builty_charge = $builty_charge, tempo_fare = $tempo_fare, rebooking_charges = $rebooking_chares, weight = $product_qty_wt, remarks = '$remarks' ,lr_no='$lr_no' , lr_date = '$lr_date' , to_pay = $to_pay , paid = $paid, to_be_billed = $to_be_billed, tax_pay_type = $tax_pay_type, last_updated_by = $admin_id, date_modified = NOW(), lr_type = $lr_type
			  WHERE lr_id=$new_lr_id";
			 
			dbQuery($sql);
			
			deleteProductToLr($new_lr_id);
			foreach($lr_products as $lr_product)
			{
				$product_name = $lr_product['product_name'];
				$packinng_unit = $lr_product['packing_unit'];
				$product_id=insertProductIfNotDuplicate($product_name);
				$packinng_unit_id = insertPackingUnitIfNotDuplicate($packinng_unit);
				
				insertProductToLr($new_lr_id,$product_id,$lr_product['qty_no'],$packinng_unit_id);
				
			}
			deleteTaxForLR($new_lr_id);
			if($lr['tax_pay_type']==3)
			{
				$lr_tax=getTaxForLr($lr_id,true);
				
			insertTaxToLr($new_lr_id,$lr_tax[0]['tax_group_id'],$lr['freight']);
			
			}
			$sql="UPDATE edms_lr SET lr_updation_status = 0 WHERE lr_id = $lr_id";
			dbQuery($sql,true);
			
		}
		$sql="UPDATE edms_ac_main_settings SET sync_in_progress=1,sync_time=NOW()";	
        dbQuery($sql);
	}
}

$sql="UPDATE edms_ac_main_settings SET sync_in_progress=1,sync_time=NOW(), sync_status = 'server_lr_update'";	

dbQuery($sql);

// END LR UPDATION

// TRIP MEMO UPDATION
$sql="SELECT trip_memo_id FROM edms_trip_memo WHERE sync_trip_memo_id IS NOT NULL AND trip_updation_status=1 AND from_branch_ledger_id = $concerned_branch_id";
$result = dbQuery($sql,true);
$num_fields=mysql_num_fields($result);
if(dbNumRows($result)>0)
{
	$resultArray = dbResultToArray($result);
	
	foreach($resultArray as $trip_memo_id)
	{
		$trip_memo_id = $trip_memo_id[0];
		
		$trip_memo = getTripMemoById($trip_memo_id,true);
		$new_trip_memo_id = $trip_memo['sync_trip_memo_id'];
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
		$duplicate = checkForDuplicateTripMemo($memo_no,$new_trip_memo_id);
		
		if(!$duplicate)
		{
		$sql="UPDATE edms_trip_memo
		      SET from_branch_ledger_id = $new_from_branch_ledger_id, to_branch_ledger_id = $new_to_branch_ledger_id, truck_id = $new_truck_id, driver_id = $new_driver_id, trip_memo_no = '$memo_no', trip_date='$trip_date', remarks = '$remarks' , last_updated_by = $admin_id, date_modified = NOW()
			  WHERE trip_memo_id=$new_trip_memo_id";
			  
		dbQuery($sql);
		deleteLrToTrip($new_trip_memo_id);
		foreach($trip_lrs as $trip_lr)
		{
		  $lr_id = $trip_lr['lr_id'];
		  $sql="SELECT lr_id FROM edms_lr WHERE sync_lr_id = $lr_id  AND lr_updation_status!=-1";
		  
		  $result = dbQuery($sql);
		  $resultArray = dbResultToArray($result);
		  $new_lr_id = $resultArray[0][0];
		  if(!is_numeric($new_lr_id))
		  $new_lr_id=syncLrFromServer($lr_id);
		  if(checkForNumeric($new_trip_memo_id,$new_lr_id))
		  {
		  $sql="INSERT INTO edms_trip_lr (lr_id,trip_memo_id) VALUES ($new_lr_id,$new_trip_memo_id)";
		  dbQuery($sql);	
		  }
	      
		} 
		
		
		$sql="UPDATE edms_trip_memo SET trip_updation_status = 0 WHERE trip_memo_id = $trip_memo_id";
		dbQuery($sql,true);
		}
		$sql="UPDATE edms_ac_main_settings SET sync_in_progress=1,sync_time=NOW()";	
    dbQuery($sql);	
	}
}
// END TRIP UPDATION

$sql="UPDATE edms_ac_main_settings SET sync_in_progress=1,sync_time=NOW(), sync_status = 'server_trip_update'";	

dbQuery($sql);

// TRIP Summary UPDATION
$sql="SELECT trip_memo_summary_id FROM edms_trip_memo_summary WHERE sync_trip_memo_summary_id IS NOT NULL AND summary_updation_status=1 AND from_branch_ledger_id = $concerned_branch_id";
$result = dbQuery($sql,true);
$num_fields=mysql_num_fields($result);
if(dbNumRows($result)>0)
{
	$resultArray = dbResultToArray($result);
	
	foreach($resultArray as $trip_memo_id)
	{
		$trip_memo_summary_id = $trip_memo_id[0];
		
		$trip_memo_summary = getTripSummaryById($trip_memo_summary_id,true);
		$new_trip_memo_summary_id = $trip_memo_summary['sync_trip_memo_summary_id'];
		$trips = getTripsBySummaryId($trip_memo_summary_id,true);
		
		$advance = $trip_memo_summary['advance'];
		$summary_no = $trip_memo_summary['trip_memo_summary_no'];
		$summary_date = $trip_memo_summary['trip_memo_summary_date'];
		$from_branch_ledger_id = $trip_memo_summary['from_branch_ledger_id'];
		
		$from_branch_ledger = getLedgerById($from_branch_ledger_id,true);
		
		$new_from_branch_ledger_id = getLedgerIdForLedgerName($from_branch_ledger['ledger_name'],NULL);
	
		if(!checkForNumeric($new_from_branch_ledger_id))
		{
		$new_from_branch_ledger_id=insertLedger($from_branch_ledger['ledger_name'],NULL,NULL,NULL,NULL,NULL,getSundryDebtorsId(),9999999999,0,0,'',0,0,DEFAULT_OC_ID,5);
		}
		
		$remarks = $trip_memo['remarks'];
		$admin_id = DEFAULT_ADMIN_ID;
		$duplicate = checkForDuplicateTripSummary($summary_no,$new_trip_memo_summary_id);
		
		if(!$duplicate)
		{
		$sql="UPDATE edms_trip_memo_summary
		      SET from_branch_ledger_id = $new_from_branch_ledger_id, trip_memo_summary_no = '$summary_no', trip_memo_summary_date='$summary_date', remarks = '$remarks' , last_updated_by = $admin_id, date_modified = NOW(), advance = $advance
			  WHERE trip_memo_summary_id=$new_trip_memo_summary_id";
			  
		dbQuery($sql);
		deleteTripMemoToSummary($new_trip_memo_summary_id);
		foreach($trips as $trip)
		{
		  $trip_memo_id = $trip['trip_memo_id'];
		  $sql="SELECT trip_memo_id FROM edms_trip_memo WHERE sync_trip_memo_id = $trip_memo_id  AND trip_updation_status!=-1";
		  
		  $result = dbQuery($sql);
		  $resultArray = dbResultToArray($result);
		  $new_trip_id = $resultArray[0][0];
		  if(!is_numeric($new_trip_id))
		  $new_trip_id=syncTripMemoFromServer($trip_memo_id);
		  if(checkForNumeric($new_trip_memo_summary_id,$new_trip_id))
		  {
		  $sql="INSERT INTO edms_rel_trip_memo_summary (trip_memo_summary_id,trip_memo_id) VALUES ($new_trip_memo_summary_id,$new_trip_id)";
		  dbQuery($sql);	
		  }
	      
		} 
		
		
		$sql="UPDATE edms_trip_memo_summary SET summary_updation_status = 0 WHERE trip_memo_summary_id = $trip_memo_summary_id";
		dbQuery($sql,true);
		}
		$sql="UPDATE edms_ac_main_settings SET sync_in_progress=1,sync_time=NOW()";	
    dbQuery($sql);	
	}
}
// END TRIP Summary UPDATION

$sql="UPDATE edms_ac_main_settings SET sync_in_progress=1,sync_time=NOW(), sync_status = 'server_trip_summary_update'";	

dbQuery($sql);

// TRIP Summary DELETION 

$sql="SELECT sync_trip_memo_summary_id,trip_memo_summary_id FROM edms_trip_memo_summary_deleted WHERE sync_trip_memo_summary_id IS NOT NULL AND summary_updation_status=0 AND from_branch_ledger_id = $concerned_branch_id";

$result = dbQuery($sql,true);

$num_fields=mysql_num_fields($result);

if(dbNumRows($result)>0)
{
	$resultArray = dbResultToArray($result);
	
	foreach($resultArray as $lr)
	{
		deleteTripSummary($lr['sync_trip_memo_summary_id']);
	
$lr_id = $lr['trip_memo_summary_id'];
$sql="UPDATE edms_trip_memo_summary_deleted SET summary_updation_status = 1 WHERE trip_memo_summary_id = $lr_id ";
dbQuery($sql,true);
$sql="UPDATE edms_ac_main_settings SET sync_in_progress=1,sync_time=NOW()";	
    dbQuery($sql);
	}
}
$sql="UPDATE edms_ac_main_settings SET sync_in_progress=1,sync_time=NOW(), sync_status = 'server_trip_summary_delete'";	
dbQuery($sql);

// TRIP DELETION 

$sql="SELECT sync_trip_memo_id,trip_memo_id FROM edms_trip_memo_deleted WHERE sync_trip_memo_id IS NOT NULL AND trip_updation_status=0 AND from_branch_ledger_id = $concerned_branch_id";

$result = dbQuery($sql,true);

$num_fields=mysql_num_fields($result);

if(dbNumRows($result)>0)
{
	$resultArray = dbResultToArray($result);
	
	foreach($resultArray as $lr)
	{
		deleteTrip($lr['sync_trip_memo_id']);
	

$lr_id = $lr['trip_memo_id'];
$sql="UPDATE edms_trip_memo_deleted SET trip_updation_status = 1 WHERE trip_memo_id = $lr_id ";
dbQuery($sql,true);
$sql="UPDATE edms_ac_main_settings SET sync_in_progress=1,sync_time=NOW()";	
    dbQuery($sql);
	}
}
$sql="UPDATE edms_ac_main_settings SET sync_in_progress=1,sync_time=NOW(), sync_status = 'server_trip_delete'";	
dbQuery($sql);


// LR DELETION 

$sql="SELECT sync_lr_id,lr_id FROM edms_lr_deleted WHERE sync_lr_id IS NOT NULL AND lr_updation_status=0 AND from_branch_ledger_id = $concerned_branch_id";

$result = dbQuery($sql,true);
$num_fields=mysql_num_fields($result);

if(dbNumRows($result)>0)
{
	$resultArray = dbResultToArray($result);
	
	foreach($resultArray as $lr)
	{
		deleteLR($lr['sync_lr_id']);
	

$lr_id = $lr['lr_id'];
$sql="UPDATE edms_lr_deleted SET lr_updation_status = 1 WHERE lr_id = $lr_id ";
dbQuery($sql,true);
$sql="UPDATE edms_ac_main_settings SET sync_in_progress=1,sync_time=NOW()";	
    dbQuery($sql);
	}
}

$sql="UPDATE edms_ac_main_settings SET sync_in_progress=1,sync_time=NOW(), sync_status = 'server_lr_delete'";	
dbQuery($sql);




// LOCAL TO SERVER
// NEW LR INSERTION

$sql="SELECT lr_id FROM edms_lr WHERE sync_lr_id IS NULL";

$result = dbQuery($sql);
$num_fields=mysql_num_fields($result);

if(dbNumRows($result)>0)
{
	$resultArray = dbResultToArray($result);
	
	foreach($resultArray as $lr_id)
	{
		
		syncLr($lr_id[0]);
		$sql="UPDATE edms_ac_main_settings SET sync_in_progress=1,sync_time=NOW()";	
        dbQuery($sql);
	}
}


$sql="UPDATE edms_ac_main_settings SET sync_in_progress=1,sync_time=NOW(), sync_status = 'lr_insert'";	
dbQuery($sql);


// END LR INSERTION

// TRIP MEMO INSERTION
$sql="SELECT trip_memo_id FROM edms_trip_memo WHERE sync_trip_memo_id IS NULL";
$result = dbQuery($sql);
$num_fields=mysql_num_fields($result);
if(dbNumRows($result)>0)
{
	$resultArray = dbResultToArray($result);
	
	foreach($resultArray as $trip_memo_id)
	{
		$trip_memo_id = $trip_memo_id[0];
		syncTripMemo($trip_memo_id);
	}
}

$sql="UPDATE edms_ac_main_settings SET sync_in_progress=1,sync_time=NOW(), sync_status = 'trip_insert'";	
dbQuery($sql);

// END TRIP INSERTION


// TRIP Summary INSERTION
$sql="SELECT trip_memo_summary_id FROM edms_trip_memo_summary WHERE sync_trip_memo_summary_id IS NULL";
$result = dbQuery($sql);
$num_fields=mysql_num_fields($result);
if(dbNumRows($result)>0)
{
	$resultArray = dbResultToArray($result);
	
	foreach($resultArray as $trip_memo_id)
	{
		$trip_memo_summary_id = $trip_memo_id[0];
		
		$trip_memo_summary = getTripSummaryById($trip_memo_summary_id);
		$trips = getTripsBySummaryId($trip_memo_summary_id);
		$advance = $trip_memo_summary['advance'];
		$summary_no = $trip_memo_summary['trip_memo_summary_no'];
		$summary_date = $trip_memo_summary['trip_memo_summary_date'];
		$from_branch_ledger_id = $trip_memo_summary['from_branch_ledger_id'];
		$from_branch_ledger = getLedgerById($from_branch_ledger_id);
		
		$new_from_branch_ledger_id = getLedgerIdForLedgerName($from_branch_ledger['ledger_name'],NULL,true);
		
		if(!checkForNumeric($new_from_branch_ledger_id))
		{
		$new_from_branch_ledger_id=insertLedger($from_branch_ledger['ledger_name'],NULL,NULL,NULL,NULL,NULL,getSundryDebtorsId(true),9999999999,0,0,'',0,0,DEFAULT_OC_ID,5,true);
		}
		
		$remarks = $trip_memo_summary['remarks'];
		$admin_id = DEFAULT_ADMIN_ID;
		$duplicate = checkForDuplicateTripSummary($summary_no,false,true);
		
		if(!$duplicate)
		{
		$sql="INSERT INTO edms_trip_memo_summary
		      (trip_memo_summary_no,trip_memo_summary_date,from_branch_ledger_id, remarks, created_by, last_updated_by, date_added, date_modified,sync_trip_memo_summary_id,summary_updation_status, advance)
			  VALUES
			  ('$summary_no','$summary_date',$new_from_branch_ledger_id,'$remarks',$admin_id, $admin_id, NOW(), NOW(),$trip_memo_summary_id,-1,$advance)";
			 
		dbQuery($sql,true);
		$new_trip_memo_summary_id = dbInsertId(true);
		
		foreach($trips as $trip)
		{
			
		  $trip_id = $trip['trip_memo_id'];
		  
		  $sql="SELECT trip_memo_id FROM edms_trip_memo WHERE sync_trip_memo_id = $trip_id AND trip_updation_status!=-1 AND from_branch_ledger_id = $concerned_branch_id";
		 
		  $result = dbQuery($sql,true);
		  $resultArray = dbResultToArray($result);
		  $new_trip_id = $resultArray[0][0];
		
		  if(!is_numeric($new_trip_id))
		  $new_trip_id=syncTripMemo($trip_id);
		  if(checkForNumeric($new_trip_memo_summary_id,$new_trip_id))
		  {
		  $sql="INSERT INTO edms_rel_trip_memo_summary (trip_memo_id,trip_memo_summary_id) VALUES ($new_trip_id,$new_trip_memo_summary_id)";
		  dbQuery($sql,true);	
		  }
	      
		} 
		
		
		$sql="UPDATE edms_trip_memo_summary SET summary_updation_status = 0 WHERE trip_memo_summary_id = $new_trip_memo_summary_id";
		dbQuery($sql,true);
		$sql="UPDATE edms_trip_memo_summary SET sync_trip_memo_summary_id = $new_trip_memo_summary_id WHERE trip_memo_summary_id = $trip_memo_summary_id";
		dbQuery($sql);
		}
	$sql="UPDATE edms_ac_main_settings SET sync_in_progress=1,sync_time=NOW()";	
    dbQuery($sql);	
	}
}

$sql="UPDATE edms_ac_main_settings SET sync_in_progress=1,sync_time=NOW(), sync_status = 'trip_summary_insert'";	
dbQuery($sql);

// END TRIP INSERTION

// LR UPDATION

$sql="SELECT lr_id FROM edms_lr WHERE sync_lr_id IS NOT NULL AND lr_updation_status=1";

$result = dbQuery($sql);
$num_fields=mysql_num_fields($result);

if(dbNumRows($result)>0)
{
	$resultArray = dbResultToArray($result);
	
	foreach($resultArray as $lr_id)
	{
		$lr_id = $lr_id[0];
		$lr=getLRById($lr_id);
		$new_lr_id = $lr['sync_lr_id'];
		
		$lr_products = getProductsByLRId($lr_id);

		$from_customer_id = insertCustomer($lr['from_customer_name'],'NA',3,'NA',NULL,'9999999999',NULL,NULL,NULL,NULL,NULL,'',0,0,true);
		
		$to_customer_id = insertCustomer($lr['to_customer_name'],'NA',3,'NA',NULL,'9999999999',NULL,NULL,NULL,NULL,NULL,'',0,0,true);
		$from_branch_ledger_id = $lr['from_branch_ledger_id'];
		$to_branch_ledger_id = $lr['to_branch_ledger_id'];
	
		$from_branch_ledger = getLedgerById($from_branch_ledger_id);
		$to_branch_ledger = getLedgerById($to_branch_ledger_id);
			
		$new_from_branch_ledger_id = getLedgerIdForLedgerName($from_branch_ledger['ledger_name'],NULL,true);
		$new_to_branch_ledger_id = getLedgerIdForLedgerName($to_branch_ledger['ledger_name'],NULL,true);
		
		if(!checkForNumeric($new_from_branch_ledger_id))
		{
		$new_from_branch_ledger_id=insertLedger($from_branch_ledger['ledger_name'],NULL,NULL,NULL,NULL,NULL,getSundryDebtorsId(true),9999999999,0,0,'',0,0,DEFAULT_OC_ID,5,true);
		}
		if(!checkForNumeric($new_to_branch_ledger_id))
		{
		$new_to_branch_ledger_id=insertLedger($to_branch_ledger['ledger_name'],NULL,NULL,NULL,NULL,NULL,getSundryDebtorsId(true),9999999999,0,0,'',0,0,DEFAULT_OC_ID,5,true);
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
		
		$duplicate = checkForDuplicateLR($lr_no,$from_branch_ledger_id,$new_lr_id,true);
		
		if(!$duplicate)
		{	
			$sql="UPDATE edms_lr
		      SET from_branch_ledger_id = $new_from_branch_ledger_id, to_branch_ledger_id = $new_to_branch_ledger_id, delivery_at='$delivery_at',from_customer_id = $from_customer_id, to_customer_id = $to_customer_id, freight = $freight, total_freight = $total_freight, builty_charge = $builty_charge, tempo_fare = $tempo_fare, rebooking_charges = $rebooking_chares, weight = $product_qty_wt, remarks = '$remarks' ,lr_no='$lr_no' , lr_date = '$lr_date' , to_pay = $to_pay , paid = $paid, to_be_billed = $to_be_billed, tax_pay_type = $tax_pay_type, last_updated_by = $admin_id, date_modified = NOW(), lr_type = $lr_type
			  WHERE lr_id=$new_lr_id";
			 
			dbQuery($sql,true);
			
			deleteProductToLr($new_lr_id,true);
			foreach($lr_products as $lr_product)
			{
				$product_name = $lr_product['product_name'];
				$packinng_unit = $lr_product['packing_unit'];
				$product_id=insertProductIfNotDuplicate($product_name,true);
				$packinng_unit_id = insertPackingUnitIfNotDuplicate($packinng_unit,true);
				
				insertProductToLr($new_lr_id,$product_id,$lr_product['qty_no'],$packinng_unit_id,true);
				
			}
			deleteTaxForLR($new_lr_id,true);
			if($lr['tax_pay_type']==3)
			{
				$lr_tax=getTaxForLr($lr_id);
				
			insertTaxToLr($new_lr_id,$lr_tax[0]['tax_group_id'],$lr['freight'],true);
			
			}
			$sql="UPDATE edms_lr SET lr_updation_status = 0 WHERE lr_id = $lr_id";
			dbQuery($sql);
			
		}
		$sql="UPDATE edms_ac_main_settings SET sync_in_progress=1,sync_time=NOW()";	
    dbQuery($sql);
	}
}

// END LR UPDATION

$sql="UPDATE edms_ac_main_settings SET sync_in_progress=1,sync_time=NOW(), sync_status = 'lr_update'";	
dbQuery($sql);

// TRIP MEMO UPDATION
$sql="SELECT trip_memo_id FROM edms_trip_memo WHERE sync_trip_memo_id IS NOT NULL AND trip_updation_status=1";
$result = dbQuery($sql);
$num_fields=mysql_num_fields($result);
if(dbNumRows($result)>0)
{
	$resultArray = dbResultToArray($result);
	
	foreach($resultArray as $trip_memo_id)
	{
		$trip_memo_id = $trip_memo_id[0];
		
		$trip_memo = getTripMemoById($trip_memo_id);
		$new_trip_memo_id = $trip_memo['sync_trip_memo_id'];
		$trip_lrs = getLRsByTripId($trip_memo_id);
		
		$memo_no = $trip_memo['trip_memo_no'];
		$trip_date = $trip_memo['trip_date'];
		$from_branch_ledger_id = $trip_memo['from_branch_ledger_id'];
		$to_branch_ledger_id = $trip_memo['to_branch_ledger_id'];
		$from_branch_ledger = getLedgerById($from_branch_ledger_id);
		$to_branch_ledger = getLedgerById($to_branch_ledger_id);
		$new_from_branch_ledger_id = getLedgerIdForLedgerName($from_branch_ledger['ledger_name'],NULL,true);
		$new_to_branch_ledger_id = getLedgerIdForLedgerName($to_branch_ledger['ledger_name'],NULL,true);
		if(!checkForNumeric($new_from_branch_ledger_id))
		{
		$new_from_branch_ledger_id=insertLedger($from_branch_ledger['ledger_name'],NULL,NULL,NULL,NULL,NULL,getSundryDebtorsId(true),9999999999,0,0,'',0,0,DEFAULT_OC_ID,5,true);
		}
		if(!checkForNumeric($new_to_branch_ledger_id))
		{
		$new_to_branch_ledger_id=insertLedger($to_branch_ledger['ledger_name'],NULL,NULL,NULL,NULL,NULL,getSundryDebtorsId(true),9999999999,0,0,'',0,0,DEFAULT_OC_ID,5,true);
		}
		$truck_id = $trip_memo['truck_id'];
		$truck = getTruckById($truck_id);
		$new_truck_id=insertTruckIfNotDuplicate($truck['truck_name'],$truck['truck_no'],$truck['remarks'],-1,true);
		$driver_id = $trip_memo['driver_id'];
		$driver = getLedgerById($driver_id);
		$new_driver_id = getLedgerIdForLedgerName($driver['ledger_name'],NULL,true);
		if(!checkForNumeric($new_driver_id))
		{
		$new_driver_id=insertLedger($driver['ledger_name'],NULL,NULL,NULL,NULL,NULL,getSundryDebtorsId(true),9999999999,0,0,'',0,0,DEFAULT_OC_ID,7,true);
		}
		$remarks = $trip_memo['remarks'];
		$admin_id = DEFAULT_ADMIN_ID;
		$duplicate = checkForDuplicateTripMemo($memo_no,$new_trip_memo_id,true);
		
		if(!$duplicate)
		{
		$sql="UPDATE edms_trip_memo
		      SET from_branch_ledger_id = $new_from_branch_ledger_id, to_branch_ledger_id = $new_to_branch_ledger_id, truck_id = $new_truck_id, driver_id = $new_driver_id, trip_memo_no = '$memo_no', trip_date='$trip_date', remarks = '$remarks' , last_updated_by = $admin_id, date_modified = NOW()
			  WHERE trip_memo_id=$new_trip_memo_id";
			  
		dbQuery($sql,true);
		deleteLrToTrip($new_trip_memo_id,true);
		foreach($trip_lrs as $trip_lr)
		{
		  $lr_id = $trip_lr['lr_id'];
		  $sql="SELECT lr_id FROM edms_lr WHERE sync_lr_id = $lr_id  AND lr_updation_status!=-1 AND from_branch_ledger_id = $concerned_branch_id";
		  
		  $result = dbQuery($sql,true);
		  $resultArray = dbResultToArray($result);
		  $new_lr_id = $resultArray[0][0];
		  if(!is_numeric($new_lr_id))
		  $new_lr_id=syncLr($lr_id);
		  if(checkForNumeric($new_trip_memo_id,$new_lr_id))
		  {
		  $sql="INSERT INTO edms_trip_lr (lr_id,trip_memo_id) VALUES ($new_lr_id,$new_trip_memo_id)";
		  dbQuery($sql,true);	
		  }
	      
		} 
	
		$sql="UPDATE edms_trip_memo SET trip_updation_status = 0 WHERE trip_memo_id = $trip_memo_id";
		dbQuery($sql);
		}
		$sql="UPDATE edms_ac_main_settings SET sync_in_progress=1,sync_time=NOW()";	
    dbQuery($sql);
	}
}
// END TRIP UPDATION


$sql="UPDATE edms_ac_main_settings SET sync_in_progress=1,sync_time=NOW(), sync_status = 'trip_update'";	
dbQuery($sql);

// TRIP SUMMARY UPDATION
$sql="SELECT trip_memo_summary_id FROM edms_trip_memo_summary WHERE sync_trip_memo_summary_id IS NOT NULL AND summary_updation_status=1";
$result = dbQuery($sql);
$num_fields=mysql_num_fields($result);
if(dbNumRows($result)>0)
{
	$resultArray = dbResultToArray($result);
	
	foreach($resultArray as $trip_memo_id)
	{
		$trip_memo_summary_id = $trip_memo_id[0];
		
		$trip_memo_summary = getTripSummaryById($trip_memo_summary_id);
		
		$new_trip_memo_summary_id = $trip_memo_summary['sync_trip_memo_summary_id'];
		$trips = getTripsBySummaryId($trip_memo_summary_id);
		$advance = $trip_memo_summary['advance'];
		$summary_no = $trip_memo_summary['trip_memo_summary_no'];
		$summary_date = $trip_memo_summary['trip_memo_summary_date'];
		$from_branch_ledger_id = $trip_memo_summary['from_branch_ledger_id'];
		$from_branch_ledger = getLedgerById($from_branch_ledger_id);
		$new_from_branch_ledger_id = getLedgerIdForLedgerName($from_branch_ledger['ledger_name'],NULL,true);
		
		if(!checkForNumeric($new_from_branch_ledger_id))
		{
		$new_from_branch_ledger_id=insertLedger($from_branch_ledger['ledger_name'],NULL,NULL,NULL,NULL,NULL,getSundryDebtorsId(true),9999999999,0,0,'',0,0,DEFAULT_OC_ID,5,true);
		}

		
		$remarks = $trip_memo_summary['remarks'];
		$admin_id = DEFAULT_ADMIN_ID;
		
		$duplicate = checkForDuplicateTripSummary($summary_no,$new_trip_memo_summary_id,true);
		
		if(!$duplicate)
		{
		$sql="UPDATE edms_trip_memo_summary
		      SET from_branch_ledger_id = $new_from_branch_ledger_id, trip_memo_summary_no = '$summary_no', trip_memo_summary_date='$summary_date', remarks = '$remarks' , last_updated_by = $admin_id, date_modified = NOW(), advance = $advance
			  WHERE trip_memo_summary_id=$new_trip_memo_summary_id";
		
		dbQuery($sql,true);
		deleteTripMemoToSummary($new_trip_memo_summary_id,true);
		foreach($trips as $trip)
		{
		  $trip_memo_id = $trip['trip_memo_id'];
		  $sql="SELECT trip_memo_id FROM edms_trip_memo WHERE sync_trip_memo_id = $trip_memo_id  AND trip_updation_status!=-1 AND from_branch_ledger_id = $concerned_branch_id";
		  
		  $result = dbQuery($sql,true);
		  $resultArray = dbResultToArray($result);
		  $new_trip_id = $resultArray[0][0];
		  if(!is_numeric($new_trip_id))
		  $new_trip_id=syncTripMemo($trip_memo_id);
		  if(checkForNumeric($new_trip_memo_summary_id,$new_trip_id))
		  {
		  $sql="INSERT INTO edms_rel_trip_memo_summary (trip_memo_summary_id,trip_memo_id) VALUES ($new_trip_memo_summary_id,$new_trip_id)";
		  dbQuery($sql,true);	
		  }
	      
		} 
	
		$sql="UPDATE edms_trip_memo_summary SET summary_updation_status = 0 WHERE trip_memo_summary_id = $trip_memo_summary_id";
		dbQuery($sql);
		}
		$sql="UPDATE edms_ac_main_settings SET sync_in_progress=1,sync_time=NOW()";	
    dbQuery($sql);
	}
}
// END TRIP UPDATION


$sql="UPDATE edms_ac_main_settings SET sync_in_progress=1,sync_time=NOW(), sync_status = 'trip_summary_update'";	
dbQuery($sql);

// TRIP SUMMARY DELETION 

$sql="SELECT sync_trip_memo_summary_id,trip_memo_summary_id FROM edms_trip_memo_summary_deleted WHERE sync_trip_memo_summary_id IS NOT NULL AND summary_updation_status=0";

$result = dbQuery($sql);

$num_fields=mysql_num_fields($result);

if(dbNumRows($result)>0)
{
	$resultArray = dbResultToArray($result);
	
	foreach($resultArray as $lr)
	{
		deleteTripSummary($lr['sync_trip_memo_summary_id'],true);
	

$lr_id = $lr['trip_memo_summary_id'];
$sql="UPDATE edms_trip_memo_summary_deleted SET summary_updation_status = 1 WHERE trip_memo_summary_id = $lr_id ";
dbQuery($sql);
$sql="UPDATE edms_ac_main_settings SET sync_in_progress=1,sync_time=NOW()";	
dbQuery($sql);
	}
}

$sql="UPDATE edms_ac_main_settings SET sync_in_progress=0";	
dbQuery($sql);

}

$sql="UPDATE edms_ac_main_settings SET sync_in_progress=1,sync_time=NOW(), sync_status = 'trip_delete'";	
dbQuery($sql);

// TRIP DELETION 

$sql="SELECT sync_trip_memo_id,trip_memo_id FROM edms_trip_memo_deleted WHERE sync_trip_memo_id IS NOT NULL AND trip_updation_status=0";

$result = dbQuery($sql);

$num_fields=mysql_num_fields($result);

if(dbNumRows($result)>0)
{
	$resultArray = dbResultToArray($result);
	
	foreach($resultArray as $lr)
	{
		deleteTrip($lr['sync_trip_memo_id'],true);
	

$lr_id = $lr['trip_memo_id'];
$sql="UPDATE edms_trip_memo_deleted SET trip_updation_status = 1 WHERE trip_memo_id = $lr_id ";
dbQuery($sql);
$sql="UPDATE edms_ac_main_settings SET sync_in_progress=1,sync_time=NOW()";	
dbQuery($sql);
	}
}

$sql="UPDATE edms_ac_main_settings SET sync_in_progress=0";	
dbQuery($sql);


$sql="UPDATE edms_ac_main_settings SET sync_in_progress=1,sync_time=NOW(), sync_status = 'trip_delete'";	
dbQuery($sql);

// LR DELETION 

$sql="SELECT sync_lr_id,lr_id FROM edms_lr_deleted WHERE sync_lr_id IS NOT NULL AND sync_lr_id>0 AND lr_updation_status=0";

$result = dbQuery($sql);
$num_fields=mysql_num_fields($result);

if(dbNumRows($result)>0)
{
	$resultArray = dbResultToArray($result);
	
	foreach($resultArray as $lr)
	{
		deleteLR($lr['sync_lr_id'],true);
	

$lr_id = $lr['lr_id'];
$sql="UPDATE edms_lr_deleted SET lr_updation_status = 1 WHERE lr_id = $lr_id ";
dbQuery($sql);
$sql="UPDATE edms_ac_main_settings SET sync_in_progress=1,sync_time=NOW()";	
    dbQuery($sql);
	}
}

$sql="UPDATE edms_ac_main_settings SET sync_in_progress=1,sync_time=NOW(), sync_status = 'lr_delete'";	
dbQuery($sql);

$sql="SELECT edms_trip_memo.trip_memo_id, sync_trip_memo_id, from_branch_ledger_id, trip_memo_no, invoice_id , (SELECT COUNT(*) FROM edms_trip_memo as trip_tab_2 WHERE trip_tab_2.sync_trip_memo_id = edms_trip_memo.sync_trip_memo_id AND trip_tab_2.from_branch_ledger_id = edms_trip_memo.from_branch_ledger_id GROUP BY trip_tab_2.sync_trip_memo_id) as no_of_entries FROM edms_trip_memo LEFT JOIN edms_invoice_trip_memo ON edms_trip_memo.trip_memo_id = edms_invoice_trip_memo.trip_memo_id WHERE  invoice_id IS NULL AND from_branch_ledger_id=$concerned_branch_id HAVING no_of_entries>1";
$result = dbQuery($sql,true);
$resultArray = dbResultToArray($result);

if(is_array($resultArray) && count($resultArray)>0)
{
	foreach($resultArray as $re)
	{
	$trip_id = $re['trip_memo_id'];
	$sql1="DELETE FROM edms_trip_memo WHERE trip_memo_id = $trip_id";
	dbQuery($sql1,true);	
	} 
	$sql="UPDATE edms_ac_main_settings SET sync_in_progress=1,sync_time=NOW()";	
	dbQuery($sql);
	
	$sql="SELECT trip_memo_id,sync_trip_memo_id FROM edms_trip_memo WHERE from_branch_ledger_id = $concerned_branch_id";
	$result = dbQuery($sql,true);
	$resultArray = dbResultToArray($result);
	$server_trip_id_array = array();
	
	if(is_array($resultArray) && count($resultArray)>0)
	{
	
	foreach($resultArray as $re)
	{
		$server_trip_id_array[]=$re[0];
		$sync_trip_id_array[]=$re[1];
	}
	
	$server_trip_id_string = implode(",",$server_trip_id_array);
	$sync_trip_id_string = implode(",",$sync_trip_id_array);
	}
	if(isset($server_trip_id_array) && is_array($server_trip_id_array) && count($server_trip_id_array)>0)
	{
	for($i=0;$i<count($server_trip_id_array);$i++)
	{
		$trip_id=$sync_trip_id_array[$i];
		$server_trip_id = $server_trip_id_array[$i];
		
		$sql="UPDATE edms_trip_memo SET sync_trip_memo_id = $server_trip_id WHERE trip_memo_id = $trip_id";
		dbQuery($sql);  
		
	}
	}
	
	$sql="UPDATE edms_ac_main_settings SET sync_in_progress=1,sync_time=NOW()";	
	dbQuery($sql);
	
	if(isset($server_trip_id_string) && validateForNull($server_trip_id_string))
	{
	$sql="SELECT trip_memo_id,sync_trip_memo_id FROM edms_trip_memo WHERE sync_trip_memo_id NOT IN ($server_trip_id_string)";
	$result = dbQuery($sql);
	$resultArray = dbResultToArray($result);
	}
	
	foreach($resultArray as $re)
	{
		$trip_id = $re['trip_memo_id'];
		$sql="UPDATE edms_trip_memo SET sync_trip_memo_id = NULL WHERE trip_memo_id = $trip_id";
		dbQuery($sql);
	}
}

$sql="UPDATE edms_ac_main_settings SET sync_in_progress=1,sync_time=NOW(), sync_status = 'dup_trip'";	
dbQuery($sql);


//DUPLICATE LR 

$sql="SELECT edms_lr.lr_id, sync_lr_id, trip_memo_id, from_branch_ledger_id,lr_no, (SELECT COUNT(*) FROM edms_lr as lr_tab_2 WHERE lr_tab_2.sync_lr_id = edms_lr.sync_lr_id AND lr_tab_2.from_branch_ledger_id = edms_lr.from_branch_ledger_id GROUP BY lr_tab_2.sync_lr_id) as no_of_entries FROM edms_lr LEFT JOIN edms_trip_lr ON edms_lr.lr_id = edms_trip_lr.lr_id WHERE trip_memo_id IS NULL AND from_branch_ledger_id=$concerned_branch_id HAVING no_of_entries>1";
$result = dbQuery($sql,true);
$resultArray = dbResultToArray($result);

if(is_array($resultArray) && count($resultArray)>0)
{
	foreach($resultArray as $re)
	{
	$lr_id = $re['lr_id'];
	$sql1="DELETE FROM edms_lr WHERE lr_id = $lr_id";
	dbQuery($sql1,true);	
	} 
	$sql="UPDATE edms_ac_main_settings SET sync_in_progress=1,sync_time=NOW()";	
	dbQuery($sql);
	
	$sql="SELECT lr_id,sync_lr_id FROM edms_lr WHERE from_branch_ledger_id = $concerned_branch_id";
	$result = dbQuery($sql,true);
	$resultArray = dbResultToArray($result);
	$server_lr_id_array = array();
	
	if(is_array($resultArray) && count($resultArray)>0)
	{
	
	foreach($resultArray as $re)
	{
		$server_lr_id_array[]=$re[0];
		$sync_lr_id_array[]=$re[1];
	}
	
	$server_lr_id_string = implode(",",$server_lr_id_array);
	$sync_lr_id_string = implode(",",$sync_lr_id_array);
	}
	if(isset($server_lr_id_array) && is_array($server_lr_id_array) && count($server_lr_id_array)>0)
	{
	for($i=0;$i<count($server_lr_id_array);$i++)
	{
		$lr_id =$sync_lr_id_array[$i];
		$server_lr_id = $server_lr_id_array[$i];
		$sql="UPDATE edms_lr SET sync_lr_id = $server_lr_id WHERE lr_id = $lr_id";
		dbQuery($sql);  
	}
	}
	
	$sql="UPDATE edms_ac_main_settings SET sync_in_progress=1,sync_time=NOW()";	
	dbQuery($sql);
	
	if(isset($server_lr_id_string) && validateForNull($server_lr_id_string))
	{
	$sql="SELECT lr_id,sync_lr_id FROM edms_lr WHERE sync_lr_id NOT IN ($server_lr_id_string)";
	$result = dbQuery($sql);
	$resultArray = dbResultToArray($result);
	}
	
	foreach($resultArray as $re)
	{
		$lr_id = $re['lr_id'];
		$sql="UPDATE edms_lr SET sync_lr_id = NULL WHERE lr_id = $lr_id";
		dbQuery($sql);
	}
}


$sql="UPDATE edms_ac_main_settings SET sync_in_progress=1,sync_time=NOW(), sync_status = 'complete'";	
dbQuery($sql);



?>