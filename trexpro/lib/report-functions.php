<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("customer-functions.php");
require_once("account-functions.php");
require_once("inventory-sales-functions.php");
require_once("nonstock-sales-functions.php");
require_once("common.php");
require_once("bd.php");

function generalLrReports($from=false,$to=false,$from_branch_ledger_id=false,$to_branch_ledger_id=false,$lr_type=false,$untripped=false)
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
		
$admin_id = $_SESSION['edmsAdminSession']['admin_id'];
$current_company=getCurrentCompanyForUser($admin_id);	
$oc_id = $current_company[0];

$sql="SELECT edms_lr.lr_id,from_branch_ledger_id,from_ledger.ledger_name as from_branch_ledger_name,to_branch_ledger_id,to_ledger.ledger_name as to_branch_ledger_name,from_customer_id,from_customer.customer_name as from_customer_name,to_customer_id ,to_customer.customer_name as to_customer_name,total_freight,remarks,lr_date,lr_no,to_pay,paid,to_be_billed,( SELECT SUM(tax_amount) FROM edms_lr_tax  WHERE edms_lr_tax.lr_id = edms_lr.lr_id GROUP BY edms_lr_tax.lr_id) as tax_amount,tax_pay_type, SUM(qty_no) as qty_no, weight FROM edms_lr 
INNER JOIN edms_ac_ledgers as from_ledger ON from_ledger.ledger_id = edms_lr.from_branch_ledger_id 
INNER JOIN edms_ac_ledgers as to_ledger ON to_ledger.ledger_id = edms_lr.to_branch_ledger_id
INNER JOIN edms_customer as from_customer ON from_customer.customer_id = edms_lr.from_customer_id
INNER JOIN edms_customer as to_customer ON to_customer.customer_id = edms_lr.to_customer_id
INNER JOIN edms_lr_product ON edms_lr.lr_id = edms_lr_product.lr_id 
WHERE from_ledger.oc_id = $oc_id AND lr_updation_status!=-1 ";
if(isset($from) && validateForNull($from))
	$sql=$sql." AND lr_date >='$from' 
		  ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql." AND lr_date<='$to' ";	   	  
	if(isset($from_branch_ledger_id) && validateForNull($from_branch_ledger_id) && $from_branch_ledger_id>0)
	$sql=$sql." AND from_branch_ledger_id =$from_branch_ledger_id 
		  ";
	if(isset($to_branch_ledger_id) && validateForNull($to_branch_ledger_id) && $to_branch_ledger_id>0)  
	$sql=$sql." AND to_branch_ledger_id=$to_branch_ledger_id ";	 
	
	if(isset($untripped) && checkForNumeric($untripped) && $untripped==1)  
	$sql=$sql." AND edms_lr.lr_id NOT IN (SELECT lr_id FROM edms_trip_lr) ";	
	
	if(isset($untripped) && checkForNumeric($untripped) && $untripped==0)  
	$sql=$sql." AND edms_lr.lr_id IN (SELECT lr_id FROM edms_trip_lr) ";	 

	if(isset($lr_type) && (in_array(1,$lr_type) || in_array(2,$lr_type) || in_array(3,$lr_type)))    	   	 	
	{
		$sql=$sql." AND ( ";
		if(in_array(1,$lr_type))
		$sql=$sql." to_pay > 0";
		if(in_array(1,$lr_type) && (in_array(2,$lr_type) || in_array(3,$lr_type)))
		$sql=$sql." OR ";
		if(in_array(2,$lr_type))
		$sql=$sql." paid > 0";
		if(in_array(2,$lr_type) && in_array(3,$lr_type))
		$sql=$sql." OR ";
		if(in_array(3,$lr_type))
		$sql=$sql." to_be_billed > 0";
		$sql=$sql." )";
	}
	$sql=$sql." GROUP BY edms_lr.lr_id ";
	
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	
		if(dbNumRows($result)>0)
		return $resultArray;	 
		else
		return false;
	
}

function generateDeleteLrReports($from=false,$to=false)
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
		
$admin_id = $_SESSION['edmsAdminSession']['admin_id'];
$current_company=getCurrentCompanyForUser($admin_id);	
$oc_id = $current_company[0];

$sql="SELECT edms_lr_deleted.lr_id,from_branch_ledger_id,from_ledger.ledger_name as from_branch_ledger_name,to_branch_ledger_id,to_ledger.ledger_name as to_branch_ledger_name,from_customer_id,from_customer.customer_name as from_customer_name,to_customer_id ,to_customer.customer_name as to_customer_name,total_freight,remarks,lr_date,lr_no,to_pay,paid,to_be_billed,tax_pay_type FROM edms_lr_deleted 
INNER JOIN edms_ac_ledgers as from_ledger ON from_ledger.ledger_id = edms_lr_deleted.from_branch_ledger_id 
INNER JOIN edms_ac_ledgers as to_ledger ON to_ledger.ledger_id = edms_lr_deleted.to_branch_ledger_id
INNER JOIN edms_customer as from_customer ON from_customer.customer_id = edms_lr_deleted.from_customer_id
INNER JOIN edms_customer as to_customer ON to_customer.customer_id = edms_lr_deleted.to_customer_id 
WHERE  1=1 ";
if(isset($from) && validateForNull($from))
	$sql=$sql." AND lr_date >='$from' 
		  ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql." AND lr_date<='$to' ";	   	  
	
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	
		if(dbNumRows($result)>0)
		return $resultArray;	 
		else
		return false;

}

function generalServiceTaxReports($from=false,$to=false,$from_branch_ledger_id=false,$to_branch_ledger_id=false,$tax_type=false,$tax_string=false)
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
		
$admin_id = $_SESSION['edmsAdminSession']['admin_id'];
$current_company=getCurrentCompanyForUser($admin_id);	
$oc_id = $current_company[0];

$sql="SELECT edms_lr.lr_id,from_branch_ledger_id,from_ledger.ledger_name as from_branch_ledger_name,to_branch_ledger_id,to_ledger.ledger_name as to_branch_ledger_name,from_customer_id,from_customer.customer_name as from_customer_name,to_customer_id ,to_customer.customer_name as to_customer_name,total_freight,remarks,lr_date,lr_no,to_pay,paid,to_be_billed, SUM(tax_amount) as tax_amount,tax_pay_type FROM edms_lr 
INNER JOIN edms_lr_tax ON edms_lr_tax.lr_id = edms_lr.lr_id 
INNER JOIN edms_ac_ledgers as from_ledger ON from_ledger.ledger_id = edms_lr.from_branch_ledger_id 
INNER JOIN edms_ac_ledgers as to_ledger ON to_ledger.ledger_id = edms_lr.to_branch_ledger_id
INNER JOIN edms_customer as from_customer ON from_customer.customer_id = edms_lr.from_customer_id
INNER JOIN edms_customer as to_customer ON to_customer.customer_id = edms_lr.to_customer_id
WHERE from_ledger.oc_id = $oc_id  AND lr_updation_status!=-1  ";
if(isset($from) && validateForNull($from))
	$sql=$sql." AND lr_date >='$from' 
		  ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql." AND lr_date<='$to' ";	   	  
	if(isset($from_branch_ledger_id) && validateForNull($from_branch_ledger_id) && $from_branch_ledger_id>0)
	$sql=$sql." AND from_branch_ledger_id =$from_branch_ledger_id 
		  ";
	if(isset($to_branch_ledger_id) && validateForNull($to_branch_ledger_id) && $to_branch_ledger_id>0)  
	$sql=$sql." AND to_branch_ledger_id=$to_branch_ledger_id ";	 
	
	if(isset($tax_type) && (in_array(1,$tax_type) || in_array(2,$tax_type) || in_array(3,$tax_type)))    	   	 	
	{
		$sql=$sql." AND ( ";
		if(in_array(1,$tax_type))
		$sql=$sql." tax_pay_type = 1 ";
		if(in_array(1,$tax_type) && (in_array(2,$tax_type) || in_array(3,$tax_type)))
		$sql=$sql." OR ";
		if(in_array(2,$tax_type))
		$sql=$sql." tax_pay_type = 2 ";
		if(in_array(2,$tax_type) && in_array(3,$tax_type))
		$sql=$sql." OR ";
		if(in_array(3,$tax_type))
		$sql=$sql." tax_pay_type = 3 ";
		$sql=$sql." ) ";
	}
	$sql=$sql." GROUP BY edms_lr.lr_id ";
	
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	
		if(dbNumRows($result)>0)
		return $resultArray;	 
		else
		return false;
	
}

function generalTripReports($from=false,$to=false,$from_branch_ledger_id=false,$to_branch_ledger_id=false)
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
		
$admin_id = $_SESSION['edmsAdminSession']['admin_id'];
$current_company=getCurrentCompanyForUser($admin_id);	
$oc_id = $current_company[0];

$sql="SELECT      edms_trip_memo.trip_memo_id,edms_trip_memo.from_branch_ledger_id,from_ledger.ledger_name as from_branch_ledger_name,edms_trip_memo.to_branch_ledger_id,to_ledger.ledger_name as to_branch_ledger_name,edms_trucks.truck_id,truck_no,driver_id,edms_trip_memo.remarks,trip_date,trip_memo_no, (SELECT GROUP_CONCAT(lr_no) FROM edms_lr,edms_trip_lr WHERE edms_lr.lr_id=edms_trip_lr.lr_id AND  edms_trip_lr.trip_memo_id = edms_trip_memo.trip_memo_id GROUP BY edms_trip_lr.trip_memo_id) as lr_nos, (SELECT SUM(qty_no) FROM edms_lr,edms_trip_lr,edms_lr_product WHERE edms_lr_product.lr_id = edms_lr.lr_id AND edms_lr.lr_id=edms_trip_lr.lr_id AND  edms_trip_lr.trip_memo_id = edms_trip_memo.trip_memo_id GROUP BY edms_trip_lr.trip_memo_id) as qty_nos, (SELECT SUM(to_pay) FROM edms_lr,edms_trip_lr WHERE edms_lr.lr_id=edms_trip_lr.lr_id AND  edms_trip_lr.trip_memo_id = edms_trip_memo.trip_memo_id GROUP BY edms_trip_lr.trip_memo_id) as to_pay, (SELECT SUM(paid) FROM edms_lr,edms_trip_lr WHERE edms_lr.lr_id=edms_trip_lr.lr_id AND  edms_trip_lr.trip_memo_id = edms_trip_memo.trip_memo_id GROUP BY edms_trip_lr.trip_memo_id) as paid , (SELECT SUM(to_be_billed) FROM edms_lr,edms_trip_lr WHERE edms_lr.lr_id=edms_trip_lr.lr_id AND  edms_trip_lr.trip_memo_id = edms_trip_memo.trip_memo_id GROUP BY edms_trip_lr.trip_memo_id) as to_be_billed , (SELECT SUM(tax_amount) FROM edms_lr_tax,edms_trip_lr WHERE edms_lr_tax.lr_id = edms_trip_lr.lr_id AND  edms_trip_lr.trip_memo_id = edms_trip_memo.trip_memo_id GROUP BY edms_trip_lr.trip_memo_id) as tax_amount 
		      FROM edms_trip_memo
			  INNER JOIN edms_ac_ledgers as from_ledger ON from_ledger.ledger_id = edms_trip_memo.from_branch_ledger_id 
              INNER JOIN edms_ac_ledgers as to_ledger ON to_ledger.ledger_id = edms_trip_memo.to_branch_ledger_id
			  INNER JOIN edms_trucks ON edms_trucks.truck_id = edms_trip_memo.truck_id
			  WHERE edms_trip_memo.trip_memo_id  AND trip_updation_status!=-1  ";
if(isset($from) && validateForNull($from))
	$sql=$sql." AND trip_date >='$from' 
		  ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql." AND trip_date<='$to' ";	   	  
	if(isset($from_branch_ledger_id) && validateForNull($from_branch_ledger_id) && $from_branch_ledger_id>0)
	$sql=$sql." AND edms_trip_memo.from_branch_ledger_id =$from_branch_ledger_id 
		  ";
	if(isset($to_branch_ledger_id) && validateForNull($to_branch_ledger_id) && $to_branch_ledger_id>0)  
	$sql=$sql." AND edms_trip_memo.to_branch_ledger_id=$to_branch_ledger_id ";	 
	
	
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	
		if(dbNumRows($result)>0)
		return $resultArray;	 
		else
		return false;
	
}	


function generalLRPaidReports($from=false,$to=false,$from_branch_ledger_id=false)
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
		
$admin_id = $_SESSION['edmsAdminSession']['admin_id'];
$current_company=getCurrentCompanyForUser($admin_id);	
$oc_id = $current_company[0];

$sql="SELECT   edms_paid_lr.paid_lr_id,branch_id,from_ledger.ledger_name as branch_name,paid_lr_date,page_no, (SELECT GROUP_CONCAT(lr_no) FROM edms_lr,edms_rel_paid_lr WHERE edms_lr.lr_id=edms_rel_paid_lr.lr_id AND  edms_rel_paid_lr.paid_lr_id = edms_paid_lr.paid_lr_id GROUP BY edms_rel_paid_lr.paid_lr_id) as lr_nos, (SELECT SUM(to_pay) FROM edms_lr,edms_rel_paid_lr WHERE edms_lr.lr_id=edms_rel_paid_lr.lr_id AND  edms_rel_paid_lr.paid_lr_id = edms_paid_lr.paid_lr_id GROUP BY edms_rel_paid_lr.paid_lr_id) as to_pay, (SELECT SUM(paid) FROM edms_lr,edms_rel_paid_lr WHERE edms_lr.lr_id=edms_rel_paid_lr.lr_id AND  edms_rel_paid_lr.paid_lr_id = edms_paid_lr.paid_lr_id GROUP BY edms_rel_paid_lr.paid_lr_id) as paid , (SELECT SUM(to_be_billed) FROM edms_lr,edms_rel_paid_lr WHERE edms_lr.lr_id=edms_rel_paid_lr.lr_id AND  edms_rel_paid_lr.paid_lr_id = edms_paid_lr.paid_lr_id GROUP BY edms_rel_paid_lr.paid_lr_id) as to_be_billed 
		      FROM edms_paid_lr
			  INNER JOIN edms_ac_ledgers as from_ledger ON from_ledger.ledger_id = edms_paid_lr.branch_id 
			  WHERE 1=1 ";
if(isset($from) && validateForNull($from))
	$sql=$sql." AND paid_lr_date >='$from' 
		  ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql." AND paid_lr_date<='$to' ";	   	  
	if(isset($from_branch_ledger_id) && validateForNull($from_branch_ledger_id) && $from_branch_ledger_id>0)
	$sql=$sql." AND edms_paid_lr.branch_id =$from_branch_ledger_id 
		  ";
	
	
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	
		if(dbNumRows($result)>0)
		return $resultArray;	 
		else
		return false;
	
}	


function generateBranchOutstandingReport($upto=null,$branch=null,$oc_id_string=null)
{
	
	$oc_id=$_SESSION['edmsAdminSession']['oc_id'];
	
	if(isset($upto) && validateForNull($upto))
    {
	$upto = str_replace('/', '-', $upto);
	$upto=date('Y-m-d',strtotime($upto));
	}
	
	$sql="SELECT edms_invoice.invoice_id,invoice_no,invoice_date,GROUP_CONCAT(edms_trip_memo.trip_memo_id) as trip_memo_ids,GROUP_CONCAT(DISTINCT trip_memo_no) as trip_memo_nos,GROUP_CONCAT(from_branch_ledger_id) as from_branch_ids,GROUP_CONCAT(to_branch_ledger_id) as to_branch_ids,GROUP_CONCAT(DISTINCT ledger_name SEPARATOR '<br>') as branches,edms_ac_jv_cd.amount,(SELECT SUM(amount) FROM edms_ac_receipt WHERE auto_rasid_type = 11 AND auto_id = edms_invoice.invoice_id AND edms_ac_receipt.to_ledger_id=edms_ac_jv_cd.to_ledger_id) as paid_amount 
    FROM edms_invoice
	INNER JOIN edms_ac_jv ON edms_invoice.invoice_id = edms_ac_jv.auto_id AND edms_ac_jv.auto_rasid_type=10
	INNER JOIN edms_ac_jv_cd ON edms_ac_jv.jv_id = edms_ac_jv_cd.jv_id		   
	INNER JOIN edms_invoice_trip_memo ON edms_invoice_trip_memo.invoice_id = edms_invoice.invoice_id
	INNER JOIN edms_trip_memo  ON edms_invoice_trip_memo.trip_memo_id = edms_trip_memo.trip_memo_id
	INNER JOIN edms_ac_ledgers  ON edms_ac_jv_cd.to_ledger_id = edms_ac_ledgers.ledger_id AND ledger_type=5
	WHERE  edms_ac_ledgers.our_company_id = $oc_id ";
	if(isset($city_id) && validateForNull($city_id) && !in_array('0',$area_id_array))  
	$sql=$sql." AND main_customer.city_id=$city_id
		   ";
	if(isset($area_id) && validateForNull($area_id) )  
	$sql=$sql." AND main_customer.area_id IN ($area_id)
		   "; 
	if(isset($oc_id_string) && validateForNull($oc_id_string))  
	$sql=$sql." AND main_customer.oc_id IN ($oc_id_string)
		   "; 	   	  	  
	if(isset($branch) && validateForNull($branch) )  
	$sql=$sql." AND edms_ac_jv_cd.to_ledger_id IN ($branch)
		   "; 	 	   
	$sql=$sql." GROUP BY edms_ac_ledgers.ledger_id
	";
	
	$result=dbQuery($sql);
    $resultArray=dbResultToArray($result);	

	$returnArray=array();
	if(dbNumRows($result)>0)
	{
		
		return $resultArray;	
		
		}  
		else
		return false;
}

function generalWRCReports($from=null,$to=null,$broker=null,$city_id=null,$area_id=null,$oc_id_string=null)
{
	if(validateForNull($area_id))
	$area_id_array=explode(",",$area_id);
	else
	$area_id_array=array();
	$oc_id=$_SESSION['edmsAdminSession']['oc_id'];
	
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
	
	$sql="SELECT delivery_challan_id , delivery_date,  edms_vehicle.vehicle_id, vehicle_reg_no, vehicle_engine_no, vehicle_chasis_no, edms_customer.customer_id, customer_name, edms_customer.area_id, area_name, (SELECT customer_contact_no FROM edms_customer_contact_no WHERE edms_customer_contact_no.customer_id= edms_customer.customer_id LIMIT 1) as contact_no, service_book 
	FROM edms_vehicle_delivery_challan 
	INNER JOIN edms_vehicle ON edms_vehicle.vehicle_id = edms_vehicle_delivery_challan.vehicle_id 
	INNER JOIN edms_customer ON edms_customer.customer_id = edms_vehicle.customer_id
	INNER JOIN edms_city_area ON edms_customer.area_id = edms_city_area.area_id
	WHERE is_deleted =0  AND our_company_id = $oc_id ";	
	if(isset($city_id) && validateForNull($city_id) && !in_array('0',$area_id_array))  
	$sql=$sql." AND edms_customer.city_id=$city_id
		   ";
	if(isset($area_id) && validateForNull($area_id) )  
	$sql=$sql." AND edms_customer.area_id IN ($area_id)
		   "; 
	if(isset($oc_id_string) && validateForNull($oc_id_string))  
	$sql=$sql." AND edms_customer.oc_id IN ($oc_id_string)
		   "; 	   	  	  
	if(isset($broker) && validateForNull($broker) )  
	$sql=$sql." AND edms_vehicle.ledger_id IN ($broker)
		   "; 
	if(isset($from) && validateForNull($from))
	$sql=$sql." AND delivery_date >='$from' 
		  ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."AND delivery_date<='$to' ";	   	   	 	   
	
	$result=dbQuery($sql);

$resultArray=dbResultToArray($result);


	$j=0;
	if(dbNumRows($result)>0)
	{
		return $resultArray;
	}
	else return false;
	
}

function generalSalesReportsForLedger($id,$from=null,$to=null,$city_id=null,$area_id=null,$oc_id_string=nul)
{
	if(validateForNull($id))
	{
	if(substr($id, 0, 1) == 'L')
	{
		$id=str_replace('L','',$id);
		$id=intval($id);
		$customer_id="NULL";
		$head_type=getLedgerHeadType($id);
	}
	else if(substr($id, 0, 1) == 'C')
	{
		$id=str_replace('C','',$id);
		$customer_id=intval($id);
		$id="NULL";
		
		}	
	if(validateForNull($area_id))
	$area_id_array=explode(",",$area_id);
	else
	$area_id_array=array();
	$oc_id=$_SESSION['edmsAdminSession']['oc_id'];
	
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
	
	$sql="SELECT edms_ac_sales.sales_id,sales_ref_type,sales_ref,amount,from_ledger_id,to_ledger_id,to_customer_id,edms_ac_sales.oc_id,auto_rasid_type,auto_id,trans_date,delivery_date,edms_ac_sales.created_by,edms_ac_sales.last_updated_by,edms_ac_sales.date_added,edms_ac_sales.date_modified, retail_tax, invoice_no, edms_ac_sales.remarks, customer_name FROM edms_ac_sales 
	LEFT JOIN edms_customer ON edms_customer.customer_id = edms_ac_sales.to_customer_id
	LEFT JOIN edms_ac_ledgers to_ledger ON to_ledger.ledger_id = edms_ac_sales.to_ledger_id 
	LEFT JOIN edms_ac_ledgers from_ledger ON from_ledger.ledger_id = edms_ac_sales.from_ledger_id 
	WHERE ((is_deleted = 0 AND to_customer_id>0) OR is_deleted IS NULL)  AND (to_ledger.our_company_id = $oc_id OR edms_customer.our_company_id = $oc_id ) ";
	if(checkForNumeric($id))  	  
	$sql=$sql." AND ( from_ledger_id=$id OR to_ledger_id = $id) ";
	else if(checkForNumeric($customer_id))
	$sql=$sql." AND  to_customer_id=$customer_id ";
	if(isset($city_id) && validateForNull($city_id) && !in_array('0',$area_id_array))  
	$sql=$sql." AND edms_customer.city_id=$city_id
		   ";
	if(isset($area_id) && validateForNull($area_id) )  
	$sql=$sql." AND edms_customer.area_id IN ($area_id)
		   "; 
	if(isset($oc_id_string) && validateForNull($oc_id_string))  
	$sql=$sql." AND edms_customer.oc_id IN ($oc_id_string)
		   "; 	   	  	  
	if(isset($from) && validateForNull($from))
	$sql=$sql." AND trans_date >='$from' 
		  ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."AND trans_date<='$to' ";	
		
	$result=dbQuery($sql);

$resultArray=dbResultToArray($result);


	$j=0;
	if(dbNumRows($result)>0)
	{
		return $resultArray;
	}
	else return false;
	
	}
	
	
}


function generalJobCardReports($from=null,$to=null,$broker=null,$city_id=null,$area_id=null,$oc_id_string=null,$type=null)
{
	if(validateForNull($area_id))
	$area_id_array=explode(",",$area_id);
	else
	$area_id_array=array();
	$oc_id=$_SESSION['edmsAdminSession']['oc_id'];
	
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
	
	$sql="SELECT job_card_no, job_card_datetime, edms_service_types.service_type_id , service_type, free_service_no, date_of_sale,kms_covered, estimated_repair_cost, bay_in, bay_out, delivery_promise, actual_delivery, technician_id, edms_vehicle.vehicle_id, vehicle_reg_no, vehicle_engine_no, vehicle_chasis_no, edms_customer.customer_id, customer_name, created_by, last_updated_by, date_added, date_modified 
	FROM edms_job_card 
	INNER JOIN edms_vehicle ON edms_vehicle.vehicle_id = edms_job_card.vehicle_id 
	INNER JOIN edms_customer ON edms_customer.customer_id = edms_job_card.customer_id
	INNER JOIN edms_technician ON edms_technician.technician_id = edms_job_card.technician_id
	INNER JOIN edms_service_types ON edms_service_types.service_type_id = edms_job_card.service_type_id
	WHERE is_deleted =0  AND our_company_id = $oc_id ";	
	if(isset($city_id) && validateForNull($city_id) && !in_array('0',$area_id_array))  
	$sql=$sql." AND edms_customer.city_id=$city_id
		   ";
	if(isset($area_id) && validateForNull($area_id) )  
	$sql=$sql." AND edms_customer.area_id IN ($area_id)
		   "; 
	if(isset($oc_id_string) && validateForNull($oc_id_string))  
	$sql=$sql." AND edms_customer.oc_id IN ($oc_id_string)
		   "; 	   	  	  
	if(isset($broker) && validateForNull($broker) )  
	$sql=$sql." AND edms_vehicle.ledger_id IN ($broker)
		   "; 
	if(isset($type) && validateForNull($type) )  
	$sql=$sql." AND edms_service_types.service_type_id IN ($type)
		   "; 
	if(isset($from) && validateForNull($from))
	$sql=$sql." AND job_card_datetime >='$from' 
		  ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."AND job_card_datetime<='$to' ";	   	   	 	   
	
	$result=dbQuery($sql);

$resultArray=dbResultToArray($result);


	$j=0;
	if(dbNumRows($result)>0)
	{
		return $resultArray;
	}
	else return false;
	
}

function generalJobCardFSCReports($from=null,$to=null,$broker=null,$city_id=null,$area_id=null,$oc_id_string=null,$type=null)
{
	if(validateForNull($area_id))
	$area_id_array=explode(",",$area_id);
	else
	$area_id_array=array();
	$oc_id=$_SESSION['edmsAdminSession']['oc_id'];
	
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
	
	$sql="SELECT job_card_no, job_card_datetime, edms_service_types.service_type_id , service_type, free_service_no, date_of_sale,kms_covered, estimated_repair_cost, bay_in, bay_out, delivery_promise, actual_delivery, edms_job_card.technician_id, edms_vehicle.vehicle_id, vehicle_reg_no, vehicle_chasis_no, vehicle_engine_no, edms_customer.customer_id, customer_name, edms_job_card.created_by, edms_job_card.last_updated_by, edms_job_card.date_added, edms_job_card.date_modified 
	FROM edms_job_card 
	INNER JOIN edms_vehicle ON edms_vehicle.vehicle_id = edms_job_card.vehicle_id 
	INNER JOIN edms_customer ON edms_customer.customer_id = edms_job_card.customer_id
	INNER JOIN edms_technician ON edms_technician.technician_id = edms_job_card.technician_id
	INNER JOIN edms_service_types ON edms_service_types.service_type_id = edms_job_card.service_type_id
	WHERE is_deleted =0 AND edms_job_card.service_type_id = 5  AND our_company_id = $oc_id ";	
	if(isset($city_id) && validateForNull($city_id) && !in_array('0',$area_id_array))  
	$sql=$sql." AND edms_customer.city_id=$city_id
		   ";
	if(isset($area_id) && validateForNull($area_id) )  
	$sql=$sql." AND edms_customer.area_id IN ($area_id)
		   "; 
	if(isset($oc_id_string) && validateForNull($oc_id_string))  
	$sql=$sql." AND edms_customer.oc_id IN ($oc_id_string)
		   "; 	   	  	  
	if(isset($broker) && validateForNull($broker) )  
	$sql=$sql." AND edms_vehicle.ledger_id IN ($broker)
		   "; 
	 
	if(isset($from) && validateForNull($from))
	$sql=$sql." AND job_card_datetime >='$from' 
		  ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."AND job_card_datetime <='$to' ";	   	   	 	   
	
	$result=dbQuery($sql);

$resultArray=dbResultToArray($result);


	$j=0;
	if(dbNumRows($result)>0)
	{
		return $resultArray;
	}
	else return false;
	
}

function generateItemWiseSalesReports($from=null,$to=null,$oc_id_string=null,$type=null,$item_id_string=null)
{
	$oc_id=$_SESSION['edmsAdminSession']['oc_id'];
	
	$sql="SELECT item_id, SUM(quantity), AVG(rate), SUM(net_amount), auto_rasid_type FROM edms_ac_sales INNER JOIN edms_ac_sales_item ON edms_ac_sales_item.sales_id = edms_ac_sales.sales_id INNER JOIN edms_customer ON edms_customer.customer_id = edms_ac_sales.customer_id WHERE is_deleted = 0  AND our_company_id = $oc_id ";
	if(isset($item_id_string) && validateForNull($item_id_string) )  
	$sql=$sql." AND edms_ac_sales_item.item_id IN ($item_id_string)
		   "; 
	if(isset($type) && validateForNull($type) )  
	$sql=$sql." AND auto_rasid_type IN ($type)
		   "; 
	if(isset($from) && validateForNull($from))
	$sql=$sql." AND trans_date >='$from' 
		  ";
	
	if(isset($to) && validateForNull($to))  
	$sql=$sql." AND trans_date <='$to' ";
	
	if(isset($oc_id_string) && validateForNull($oc_id_string))  
	$sql=$sql." AND edms_customer.oc_id IN ($oc_id_string)
		   ";	   
	$sql=$sql." GROUP BY item_id";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	$j=0;
	if(dbNumRows($result)>0)
	{
		return $resultArray;
	}
	else return false;	   
	
}



function generateServiceWiseSalesReports($from=null,$to=null,$oc_id_string=null,$type=null,$item_id_string=null)
{
	$oc_id=$_SESSION['edmsAdminSession']['oc_id'];
	$sql="SELECT item_id,AVG(rate),SUM(net_amount),auto_rasid_type FROM edms_ac_sales INNER JOIN edms_ac_sales_nonstock ON edms_ac_sales_item.sales_id = edms_ac_sales.sales_id INNER JOIN edms_customer ON edms_customer.customer_id = edms_ac_sales.customer_id WHERE is_deleted = 0  AND our_company_id = $oc_id ";
	if(isset($item_id_string) && validateForNull($item_id_string) )  
	$sql=$sql." AND edms_ac_sales_item.item_id IN ($item_id_string)
		   "; 
	if(isset($type) && validateForNull($type) )  
	$sql=$sql." AND auto_rasid_type IN ($type)
		   "; 
	if(isset($from) && validateForNull($from))
	$sql=$sql." AND trans_date >='$from' 
		  ";
	
	if(isset($to) && validateForNull($to))  
	$sql=$sql." AND trans_date <='$to' ";
	
	if(isset($oc_id_string) && validateForNull($oc_id_string))  
	$sql=$sql." AND edms_customer.oc_id IN ($oc_id_string)
		   ";	   
	$sql=$sql." GROUP BY item_id";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	$j=0;
	if(dbNumRows($result)>0)
	{
		return $resultArray;
	}
	else return false;	   
	
}
function getItemReports($from=null,$to=null,$oc_id_string=null,$type=null,$item_name_array=null) // type = sales or job_card
{
	
	$item_id_array=ConvertItemNameArrayInToIdArray($item_name_array);
	$item_id_string = implode(",",$item_id_array);
	
	$oc_id=$_SESSION['edmsAdminSession']['oc_id'];
	
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
	
	if(validateForNull($item_id_string) && checkForNumeric($item_id_array[0]))
	{
	$oc_id=$_SESSION['edmsAdminSession']['oc_id'];
	$sql="SELECT edms_ac_sales.sales_id,sales_ref_type,sales_ref,edms_ac_sales.amount as sales_amount,from_ledger_id,to_ledger_id,to_customer_id,from_ledger.oc_id,auto_rasid_type,auto_id,trans_date,delivery_date,edms_ac_sales.created_by,edms_ac_sales.last_updated_by,edms_ac_sales.date_added,edms_ac_sales.date_modified, retail_tax, invoice_no, remarks, item_id, quantity, rate, edms_ac_sales_item.amount, discount, net_amount, edms_ac_sales_item.godown_id, godown_name, warranty, customer_id, customer_name, from_ledger.ledger_name, to_ledger.ledger_name  FROM edms_ac_sales 
	INNER JOIN edms_ac_sales_item ON edms_ac_sales_item.sales_id = edms_ac_sales.sales_id 
	INNER JOIN edms_godown ON edms_ac_sales_item.godown_id = edms_godown.godown_id
	INNER JOIN edms_ac_ledgers from_ledger ON from_ledger.ledger_id = edms_ac_sales.from_ledger_id
	LEFT JOIN edms_customer ON edms_ac_sales.to_customer_id = edms_customer.customer_id
	LEFT JOIN edms_ac_ledgers to_ledger ON to_ledger.ledger_id = edms_ac_sales.to_ledger_id WHERE ((to_customer_id IS NOT NULL AND is_deleted =0  AND edms_customer.our_company_id = $oc_id) OR (to_ledger_id IS NOT NULL AND  to_ledger.our_company_id = $oc_id)) ";
	if(isset($item_id_string) && validateForNull($item_id_string) )  
	$sql=$sql." AND edms_ac_sales_item.item_id IN ($item_id_string)
		   "; 
	if(isset($type) && validateForNull($type) )  
	$sql=$sql." AND auto_rasid_type IN ($type)
		   "; 
	if(isset($from) && validateForNull($from))
	$sql=$sql." AND trans_date >='$from' 
		  ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql." AND trans_date <='$to' ";
	
	if(isset($oc_id_string) && validateForNull($oc_id_string))  
	$sql=$sql." AND edms_customer.oc_id IN ($oc_id_string)
		   ";	   
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	$j=0;
	if(dbNumRows($result)>0)
	{
		return $resultArray;
	}
	else return false;		   
	}
	return false;
}

function getServiceReports($from=null,$to=null,$oc_id_string=null,$type=null,$item_name_array=null) // type = sales or job_card
{

	$item_id_array=ConvertItemNameArrayInToIdArray($item_name_array);
	$item_id_string = implode(",",$item_id_array);
	
	$oc_id=$_SESSION['edmsAdminSession']['oc_id'];
	
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
	
	if(validateForNull($item_id_string) && checkForNumeric($item_id_array[0]))
	{
	$sql="SELECT  edms_ac_sales.sales_id,sales_ref_type,sales_ref,edms_ac_sales.amount as sales_amount,from_ledger_id,to_ledger_id,to_customer_id,from_ledger.oc_id,auto_rasid_type,auto_id,trans_date,delivery_date,edms_ac_sales.created_by,edms_ac_sales.last_updated_by,edms_ac_sales.date_added,edms_ac_sales.date_modified, retail_tax, invoice_no, remarks, item_id,edms_ac_sales_nonstock.amount, discount, net_amount,  customer_id, customer_name, from_ledger.ledger_name, to_ledger.ledger_name  FROM edms_ac_sales 
	INNER JOIN edms_ac_sales_nonstock ON edms_ac_sales_nonstock.sales_id = edms_ac_sales.sales_id 
	INNER JOIN edms_ac_ledgers from_ledger ON from_ledger.ledger_id = edms_ac_sales.from_ledger_id
	LEFT JOIN edms_customer ON edms_ac_sales.to_customer_id = edms_customer.customer_id
	LEFT JOIN edms_ac_ledgers to_ledger ON to_ledger.ledger_id = edms_ac_sales.to_ledger_id  WHERE ((to_customer_id IS NOT NULL AND is_deleted =0  AND edms_customer.our_company_id = $oc_id) OR (to_ledger_id IS NOT NULL AND  to_ledger.our_company_id = $oc_id)) ";
	if(isset($item_id_string) && validateForNull($item_id_string) )  
	$sql=$sql." AND edms_ac_sales_nonstock.item_id IN ($item_id_string)
		   "; 
	if(isset($type) && validateForNull($type) )  
	$sql=$sql." AND auto_rasid_type IN ($type)
		   "; 
	if(isset($from) && validateForNull($from))
	$sql=$sql." AND trans_date >='$from' 
		  ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql." AND trans_date <='$to' ";
	
	if(isset($oc_id_string) && validateForNull($oc_id_string))  
	$sql=$sql." AND edms_customer.oc_id IN ($oc_id_string)";	   
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	$j=0;
	if(dbNumRows($result)>0)
	{
		return $resultArray;
	}
	else return false;		   
	}
	return false;
}

function getItemWarrantyReports($from=null,$to=null,$oc_id_string=null,$type=null,$item_id_string=null) // type = sales or job_card
{
	$oc_id=$_SESSION['edmsAdminSession']['oc_id'];
	$sql="SELECT edms_ac_sales.sales_id,sales_ref_type,sales_ref,from_ledger_id,to_ledger_id,to_customer_id,edms_ac_sales.oc_id,auto_rasid_type,auto_id,trans_date,edms_job_card.created_by,edms_job_card.last_updated_by,edms_job_card.date_added,edms_job_card.date_modified, retail_tax, invoice_no, edms_ac_sales_item.item_id, quantity,  warranty, edms_customer.customer_id, customer_name, from_ledger.ledger_name, to_ledger.ledger_name, job_card_id, job_card_datetime,kms_covered, edms_vehicle.vehicle_id, vehicle_reg_no, vehicle_chasis_no, vehicle_engine_no, vehicle_reg_date, edms_vehicle_delivery_challan.delivery_date, item_name, mfg_item_code  FROM edms_ac_sales 
	INNER JOIN edms_ac_sales_item ON edms_ac_sales_item.sales_id = edms_ac_sales.sales_id 
	INNER JOIN edms_inventory_item ON edms_ac_sales_item.item_id = edms_inventory_item.item_id 
	
	INNER JOIN edms_ac_ledgers from_ledger ON from_ledger.ledger_id = edms_ac_sales.from_ledger_id
	INNER JOIN edms_job_card ON edms_job_card.job_card_id = edms_ac_sales.auto_id
	INNER JOIN edms_vehicle ON edms_job_card.vehicle_id = edms_vehicle.vehicle_id
	LEFT JOIN edms_vehicle_delivery_challan ON edms_vehicle_delivery_challan.vehicle_id = edms_vehicle.vehicle_id
	LEFT JOIN edms_customer ON edms_ac_sales.to_customer_id = edms_customer.customer_id
	LEFT JOIN edms_ac_ledgers to_ledger ON to_ledger.ledger_id = edms_ac_sales.to_ledger_id WHERE is_deleted = 0 AND warranty = 1 AND auto_rasid_type = 3 AND auto_id>0  AND our_company_id = $oc_id ";
	if(isset($item_id_string) && validateForNull($item_id_string) )  
	$sql=$sql." AND edms_ac_sales_item.item_id IN ($item_id_string)
		   "; 
	if(isset($type) && validateForNull($type) )  
	$sql=$sql." AND auto_rasid_type IN ($type)
		   "; 
	if(isset($from) && validateForNull($from))
	$sql=$sql." AND job_card_datetime >='$from' 
		  ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql." AND job_card_datetime <='$to' ";
	
	if(isset($oc_id_string) && validateForNull($oc_id_string))  
	$sql=$sql." AND edms_customer.oc_id IN ($oc_id_string)
		   ";	   
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	$j=0;
	if(dbNumRows($result)>0)
	{
		return $resultArray;
	}
	else return false;		   
	
}
function generalRemianderReports($from=null,$to=null,$remainder_status=0,$city_id=null,$area_id=null,$file_status=null,$agency_id=null)
{
	if(validateForNull($area_id))
	$area_id_array=explode(",",$area_id);
	else
	$area_id_array=array();
	$oc_id=$_SESSION['edmsAdminSession']['oc_id'];
	$original_agency_id=$agency_id;
	$our_company_id=NULL;
	$type=substr($agency_id,0,2);
$agency_id=substr($agency_id,2);
if($type=="oc")
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
	
$sql="SELECT remainder_id as id, date as date, remarks, remainder_status, edms_remainder.customer_id
      FROM edms_remainder
	  INNER JOIN edms_customer
	  ON edms_customer.customer_id=edms_remainder.customer_id
	  WHERE 1 AND is_deleted!=1 
		  AND 
	  ";
if(is_numeric($remainder_status))
{
	$sql=$sql."remainder_status=$remainder_status AND ";
}	  	
if($agency_id=="NULL" && is_numeric($our_company_id))
{
	$sql=$sql."oc_id=$our_company_id AND ";
}
	if(isset($from) && validateForNull($from))
	$sql=$sql."date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."date<='$to'
		  AND ";
	if(isset($city_id) && validateForNull($city_id) && !in_array('0',$area_id_array))  
	$sql=$sql."city_id=$city_id
		  AND ";
	if(isset($area_id) && validateForNull($area_id) )  
	$sql=$sql." area_id IN ($area_id)
		  AND ";  	  	  
	$sql=$sql."	 our_company_id=$oc_id";	 
$sql=$sql." ORDER BY date DESC";

		  			  
$result=dbQuery($sql);

$resultArray=dbResultToArray($result);

$returnArray=array();
	$j=0;
	if(dbNumRows($result)>0)
	{
		for($xy=0;$xy<count($resultArray);$xy++)
		{
			$reportRow=$resultArray[$xy];
			$customer_id=$reportRow['customer_id'];
			
			
			$customer=getCustomerDetailsByCustomerId($customer_id);
			
			$returnArray[$j]['id']=$reportRow['id'];	
			$returnArray[$j]['date']=$reportRow['date'];
			$returnArray[$j]['remarks']=$reportRow['remarks'];
			$returnArray[$j]['customer']=$customer;
			$j++;
			}
		return $returnArray;	
		
		}
}	

function generalRemianderReportsWidget($from=null,$to=null,$remainder_status=0,$city_id=null,$area_id=null,$file_status=null,$agency_id=null)
{
	
	$oc_id=$_SESSION['edmsAdminSession']['oc_id'];
	$original_agency_id=$agency_id;
	$our_company_id=NULL;
	$type=substr($agency_id,0,2);
$agency_id=substr($agency_id,2);
 if($type=="oc")
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
	
$sql="SELECT remainder_id as id, date as date, remarks, remainder_status, edms_remainder.customer_id
      FROM edms_remainder
	  INNER JOIN edms_customer
	  ON edms_customer.customer_id=edms_remainder.customer_id
	  WHERE 1 AND is_deleted!=1
		  AND 
	  ";
if(is_numeric($remainder_status))
{
	$sql=$sql."remainder_status=$remainder_status AND ";
}	  	
if($agency_id=="NULL" && is_numeric($our_company_id))
{
	$sql=$sql."oc_id=$our_company_id AND ";
}
	if(isset($from) && validateForNull($from))
	$sql=$sql."date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."date<='$to'
		  AND ";
	if(isset($city_id) && validateForNull($city_id) && !in_array('0',$area_id_array))  
	$sql=$sql."city_id=$city_id
		  AND ";
	if(isset($area_id) && validateForNull($area_id) )  
	$sql=$sql." area_id IN ($area_id)
		  AND ";  	  	  
	$sql=$sql."	 our_company_id=$oc_id";	 
$sql=$sql." ORDER BY date DESC";
		 	  
	$sql.=" LIMIT 0,5";
			  
$result=dbQuery($sql);
$resultArray=dbResultToArray($result);

$returnArray=array();
	$j=0;
	if(dbNumRows($result)>0)
	{
		for($xy=0;$xy<count($resultArray);$xy++)
		{
			$reportRow=$resultArray[$xy];
			$customer_id=$reportRow['customer_id'];
			
			
			$customer=getCustomerDetailsByCustomerId($customer_id);
			
			$returnArray[$j]['id']=$reportRow['id'];	
			$returnArray[$j]['date']=$reportRow['date'];
			$returnArray[$j]['remarks']=$reportRow['remarks'];
			$returnArray[$j]['customer']=$customer;
			$j++;
			}	
		return $returnArray;	
		
		}
}	

function EMIDatesComparatorForEmiReports($a,$b){
	$aEMIDate=$a['emi_date'];
	$bEMIDate=$b['emi_date'];
	$aEMIDate = str_replace('/', '-', $aEMIDate);
	$aEMIDate=date('Y-m-d',strtotime($aEMIDate));
	$bEMIDate = str_replace('/', '-', $bEMIDate);
	$bEMIDate=date('Y-m-d',strtotime($bEMIDate));
if (strtotime($aEMIDate) < strtotime($bEMIDate)) return -1;
if (strtotime($aEMIDate) > strtotime($bEMIDate)) return 1;
return 0;
}

function EMIPaymentDatesComparatorForEmiReports($a,$b){
	$aEMIDate=$a['payment_date'];
	$bEMIDate=$b['payment_date'];
	$aEMIDate = str_replace('/', '-', $aEMIDate);
	$aEMIDate=date('Y-m-d',strtotime($aEMIDate));
	$bEMIDate = str_replace('/', '-', $bEMIDate);
	$bEMIDate=date('Y-m-d',strtotime($bEMIDate));
if (strtotime($aEMIDate) < strtotime($bEMIDate)) return -1;
if (strtotime($aEMIDate) > strtotime($bEMIDate)) return 1;
return 0;
}

function EMIDatesComparatorForEmiReportsUpcomingDate($a,$b){
	$aEMIDate=$a['upcoming_emi_date'];
	$bEMIDate=$b['upcoming_emi_date'];
	$aEMIDate = str_replace('/', '-', $aEMIDate);
	$aEMIDate=date('Y-m-d',strtotime($aEMIDate));
	$bEMIDate = str_replace('/', '-', $bEMIDate);
	$bEMIDate=date('Y-m-d',strtotime($bEMIDate));
if (strtotime($aEMIDate) < strtotime($bEMIDate)) return -1;
if (strtotime($aEMIDate) > strtotime($bEMIDate)) return 1;
return 0;
}

function generateSalesReport($from=null,$to=null,$ledger_in_array=null,$ledger_not_in_array=null,$customer_in_array=null)
{
	$oc_id=$_SESSION['edmsAdminSession']['oc_id'];
	
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
	if(isset($ledger_in_array) && is_array($ledger_in_array) && count($ledger_in_array)>0 && checkForNumeric($ledger_in_array[0]))
	$ledger_in_string = implode(',',$ledger_in_array);
	
	if(isset($customer_in_array) && is_array($customer_in_array) && count($customer_in_array)>0 && checkForNumeric($customer_in_array[0]))
	$customer_in_string = implode(',',$customer_in_array);
	
	if(isset($ledger_not_in_array) && is_array($ledger_not_in_array) && count($ledger_not_in_array)>0 && checkForNumeric($ledger_not_in_array[0]))
	$ledger_not_in_string = implode(',',$ledger_not_in_array);
	
	$sql="SELECT sales_id, amount, from_ledger_id, from_ledger.ledger_name as from_ledger_name, to_ledger_id, to_ledger.ledger_name as to_ledger_name, to_customer_id, customer_name, auto_rasid_type, auto_id, trans_date, invoice_no
	 FROM edms_ac_sales
	 LEFT JOIN edms_customer ON edms_ac_sales.to_customer_id = edms_customer.customer_id
	 LEFT JOIN edms_ac_ledgers AS from_ledger ON edms_ac_sales.from_ledger_id = from_ledger.ledger_id 
	 LEFT JOIN edms_ac_ledgers AS to_ledger ON edms_ac_sales.to_ledger_id = to_ledger.ledger_id  
	 WHERE auto_rasid_type=2 AND edms_ac_sales.oc_id = $oc_id ";
	if(isset($from) && validateForNull($from))
	$sql=$sql." AND trans_date>='$from' 
		   ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql." AND trans_date<='$to'
		  ";
	if(isset($ledger_in_array) && checkForNumeric($ledger_in_array))
	$sql=$sql." AND (from_ledger_id = $ledger_in_array OR to_ledger_id = $ledger_in_array) ";
	else if(isset($ledger_in_string) && validateForNull($ledger_in_string))
	$sql=$sql." AND (from_ledger_id IN ($ledger_in_string) OR to_ledger_id IN ($ledger_in_string)) ";
	if(isset($customer_in_array) && checkForNumeric($customer_in_array))
	$sql=$sql." AND (to_customer_id = $customer_in_array) ";
	else if(isset($customer_in_string) && validateForNull($customer_in_string))
	$sql=$sql." AND (to_customer_id IN ($customer_in_string)) ";
	if(isset($ledger_not_in_array) && checkForNumeric($ledger_not_in_array))
	$sql=$sql." AND ((from_ledger_id != $ledger_not_in_array OR from_ledger_id IS NULL)  AND (to_ledger_id != $ledger_not_in_array OR to_ledger_id IS NULL )) ";
	else if(isset($ledger_not_in_string) && validateForNull($ledger_not_in_string))
	$sql=$sql." AND (from_ledger_id NOT IN ($ledger_not_in_string) AND to_ledger_id NOT IN ($ledger_not_in_string)) ";
	
	$result=dbQuery($sql);
    $resultArray=dbResultToArray($result);	
	$returnArray=array();
	if(dbNumRows($result)>0)
	{
		
		return $resultArray;	
		
		}  
		else
		return false;
		  
}

function generateInventoryJVReport($from=null,$to=null,$ledger_in_array=null,$ledger_not_in_array=null)
{
	$oc_id=$_SESSION['edmsAdminSession']['oc_id'];
	
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
	if(isset($ledger_in_array) && is_array($ledger_in_array) && count($ledger_in_array)>0 && checkForNumeric($ledger_in_array[0]))
	$ledger_in_string = implode(',',$ledger_in_array);
	
	if(isset($ledger_not_in_array) && is_array($ledger_not_in_array) && count($ledger_not_in_array)>0 && checkForNumeric($ledger_not_in_array[0]))
	$ledger_not_in_string = implode(',',$ledger_not_in_array);
	
	$sql="SELECT edms_inventory_jv.inventory_jv_id, trans_date, edms_inventory_jv.remarks, GROUP_CONCAT(inventory_item_jv_id), GROUP_CONCAT(edms_inventory_item_jv.item_id), GROUP_CONCAT(item_name), GROUP_CONCAT(rate), GROUP_CONCAT(quantity), GROUP_CONCAT(amount), GROUP_CONCAT(godown_id), GROUP_CONCAT(type), edms_inventory_jv.created_by, edms_inventory_jv.last_updated_by, edms_inventory_jv.date_added, edms_inventory_jv.date_modified
	 FROM edms_inventory_jv
	 INNER JOIN edms_inventory_item_jv ON edms_inventory_item_jv.inventory_jv_id = edms_inventory_item_jv.inventory_jv_id
	 INNER JOIN edms_inventory_item ON edms_inventory_item_jv.item_id = edms_inventory_item.item_id 
	 WHERE edms_inventory_jv.oc_id = $oc_id GROUP BY edms_inventory_jv.inventory_jv_id  ORDER BY type DESC";
	if(isset($from) && validateForNull($from))
	$sql=$sql." AND trans_date>='$from' 
		   ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql." AND trans_date<='$to'
		  ";
	$result=dbQuery($sql);
    $resultArray=dbResultToArray($result);	
	$returnArray=array();
	if(dbNumRows($result)>0)
	{
		
		return $resultArray;	
		
		}  
		else
		return false;
		  
}

function generalInsuranceReports($from=null,$to=null,$city_id=null,$area_id=null)
{
	if(validateForNull($area_id))
	$area_id_array=explode(",",$area_id);
	else
	$area_id_array=array();
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
	$today=date('Y-m-d');
	$oc_id=$_SESSION['edmsAdminSession']['oc_id'];

	$sql="SELECT edms_customer.customer_id, edms_vehicle_insurance.insurance_id, customer_name, customer_address, GROUP_CONCAT(customer_contact_no) as customer_contact_no, insurance_expiry_date, insurance_issue_date,idv, insurance_premium, edms_vehicle_insurance.insurance_company_id, insurance_company_name, vehicle_reg_no, edms_vehicle.vehicle_id
	 	  FROM 
	      edms_vehicle_insurance
		  INNER JOIN edms_vehicle
		  ON edms_vehicle.vehicle_id = edms_vehicle_insurance.vehicle_id 
		  INNER JOIN edms_insurance_company
		  ON edms_vehicle_insurance.insurance_company_id = edms_insurance_company.insurance_company_id 
		  INNER JOIN edms_customer
          ON edms_customer.customer_id = edms_vehicle.customer_id
		  INNER JOIN edms_customer_contact_no
          ON edms_customer.customer_id = edms_customer_contact_no.customer_id
		  INNER JOIN (SELECT  max(insurance_expiry_date) as max_expiry_date,vehicle_id FROM edms_vehicle_insurance GROUP BY vehicle_id)s
		  ON  edms_vehicle_insurance.vehicle_id=s.vehicle_id
		  WHERE
		  edms_vehicle_insurance.insurance_expiry_date=s.max_expiry_date
		  AND is_deleted=0
		  AND "; 
	if(isset($from) && validateForNull($from))
	$sql=$sql."insurance_expiry_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."insurance_expiry_date<='$to'
		  AND ";
	if(isset($city_id) && validateForNull($city_id) && !in_array('0',$area_id_array))  
	$sql=$sql." city_id=$city_id
		  AND ";
		if(isset($area_id) && validateForNull($area_id) )  
			$sql=$sql." area_id IN ($area_id)
				  AND ";	 	  	  
	$sql=$sql."	our_company_id=$oc_id 
		  GROUP BY edms_vehicle.vehicle_id
		  ORDER BY insurance_expiry_date";
	
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);


	$j=0;
	if(dbNumRows($result)>0)
	{
		
		return $resultArray;	
		}
	return false;		
}

function generalInsuranceReportsWidget($from=null,$to=null,$city_id=null,$area_id=null)
{
	
	$today=date('Y-m-d');
	
	$to = new DateTime(date('Y-m-d'));
	$to->add(new DateInterval('P30D'));
	$to=$to->format('Y-m-d');
	$oc_id=$_SESSION['edmsAdminSession']['oc_id'];

	$sql="SELECT edms_customer.customer_id, edms_vehicle_insurance.insurance_id, customer_name, customer_address, GROUP_CONCAT(customer_contact_no) , insurance_expiry_date, insurance_issue_date,idv, insurance_premium, edms_vehicle_insurance.insurance_company_id, insurance_company_name, vehicle_reg_no, edms_vehicle.vehicle_id
	 	  FROM 
	      edms_vehicle_insurance
		  INNER JOIN edms_vehicle
		  ON edms_vehicle.vehicle_id = edms_vehicle_insurance.vehicle_id 
		  INNER JOIN edms_insurance_company
		  ON edms_vehicle_insurance.insurance_company_id = edms_insurance_company.insurance_company_id 
		  INNER JOIN edms_customer
          ON edms_customer.customer_id = edms_vehicle.customer_id
		  INNER JOIN edms_customer_contact_no
          ON edms_customer.customer_id = edms_customer_contact_no.customer_id
		  INNER JOIN (SELECT  max(insurance_expiry_date) as max_expiry_date,vehicle_id FROM edms_vehicle_insurance GROUP BY vehicle_id)s
		  ON  edms_vehicle_insurance.vehicle_id=s.vehicle_id
		  WHERE
		  edms_vehicle_insurance.insurance_expiry_date=s.max_expiry_date
		  AND is_deleted=0
		  AND "; 
	if(isset($from) && validateForNull($from))
	$sql=$sql."insurance_expiry_date>='$today' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."insurance_expiry_date<='$to'
		  AND ";
	if(isset($city_id) && validateForNull($city_id))  
	$sql=$sql." city_id=$city_id
		  AND ";
		if(isset($area_id) && validateForNull($area_id))  
			$sql=$sql." area_id IN ($area_id)
				  AND ";	  	  	  
	$sql=$sql."	our_company_id=$oc_id 
		  GROUP BY edms_vehicle.vehicle_id
		  ORDER BY insurance_expiry_date
		  LIMIT 0,5";
	
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	$returnArray=array();
	$j=0;
	if(dbNumRows($result)>0)
	{
		
		return $resultArray;	
		}
	return false;	
}		

function expiredInsuranceReports($from=null,$to=null,$city_id=null,$area_id=null)
{
	if(validateForNull($area_id))
	$area_id_array=explode(",",$area_id);
	else
	$area_id_array=array();
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
	$today=date('Y-m-d');
	$oc_id=$_SESSION['edmsAdminSession']['oc_id'];

	$sql="SELECT edms_customer.customer_id, edms_vehicle_insurance.insurance_id, customer_name, customer_address, GROUP_CONCAT(customer_contact_no), insurance_expiry_date, insurance_issue_date,idv, insurance_premium, edms_vehicle_insurance.insurance_company_id, insurance_company_name, vehicle_reg_no, edms_vehicle.vehicle_id
	 	  FROM 
	      edms_vehicle_insurance
		  INNER JOIN edms_vehicle
		  ON edms_vehicle.vehicle_id = edms_vehicle_insurance.vehicle_id 
		  INNER JOIN edms_insurance_company
		  ON edms_vehicle_insurance.insurance_company_id = edms_insurance_company.insurance_company_id 
		  INNER JOIN edms_customer
          ON edms_customer.customer_id = edms_vehicle.customer_id
		  INNER JOIN edms_customer_contact_no
          ON edms_customer.customer_id = edms_customer_contact_no.customer_id
		  INNER JOIN (SELECT  max(insurance_expiry_date) as max_expiry_date,vehicle_id FROM edms_vehicle_insurance GROUP BY vehicle_id)s
		  ON  edms_vehicle_insurance.vehicle_id=s.vehicle_id
		  WHERE
		  edms_vehicle_insurance.insurance_expiry_date=s.max_expiry_date
		  AND is_deleted=0
		  AND "; 
	if(isset($from) && validateForNull($from))
	$sql=$sql."insurance_expiry_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."insurance_expiry_date<='$to'
		  AND ";
	if(isset($city_id) && validateForNull($city_id) && !in_array('0',$area_id_array))  
	$sql=$sql." city_id=$city_id
		  AND ";
		if(isset($area_id) && validateForNull($area_id))  
			$sql=$sql." area_id IN ($area_id)
				  AND ";	  	  	  
	$sql=$sql."	
		 
		  AND our_company_id=$oc_id 
		  GROUP BY edms_vehicle.vehicle_id
		  ORDER BY insurance_expiry_date";
	
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	
	
	
	
	$returnArray=array();
	$j=0;
	if(dbNumRows($result)>0)
	{
		
		return $resultArray;
		}
	return false;	
}		

function expiredInsuranceReportsWidget($from=null,$to=null,$city_id=null,$area_id=null)
{
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
	$today=date('Y-m-d');
	$oc_id=$_SESSION['edmsAdminSession']['oc_id'];

$sql="SELECT edms_customer.customer_id, edms_vehicle_insurance.insurance_id, customer_name, customer_address, GROUP_CONCAT(customer_contact_no), insurance_expiry_date, insurance_issue_date,idv, insurance_premium, edms_vehicle_insurance.insurance_company_id, insurance_company_name, vehicle_reg_no, edms_vehicle.vehicle_id
	 	  FROM 
	      edms_vehicle_insurance
		  INNER JOIN edms_vehicle
		  ON edms_vehicle.vehicle_id = edms_vehicle_insurance.vehicle_id 
		  INNER JOIN edms_insurance_company
		  ON edms_vehicle_insurance.insurance_company_id = edms_insurance_company.insurance_company_id 
		  INNER JOIN edms_customer
          ON edms_customer.customer_id = edms_vehicle.customer_id
		  INNER JOIN edms_customer_contact_no
          ON edms_customer.customer_id = edms_customer_contact_no.customer_id
		  INNER JOIN (SELECT  max(insurance_expiry_date) as max_expiry_date,vehicle_id FROM edms_vehicle_insurance GROUP BY vehicle_id)s
		  ON  edms_vehicle_insurance.vehicle_id=s.vehicle_id
		  WHERE
		  edms_vehicle_insurance.insurance_expiry_date=s.max_expiry_date
		  AND is_deleted=0
		  AND insurance_expiry_date<='$today'
		  AND our_company_id=$oc_id 
		  GROUP BY edms_vehicle.vehicle_id
		  ORDER BY insurance_expiry_date
		  LIMIT 0,5"; 
	
	
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	
	
	
	$returnArray=array();
	$j=0;
	if(dbNumRows($result)>0)
	{
		return $resultArray;
		}
return false;		
}

function generalSalesReports($id,$from=null,$to=null,$city_id=null,$area_id=null,$oc_id_string=nul)
{
	if(validateForNull($id))
	{
	if(substr($id, 0, 1) == 'L')
	{
		$id=str_replace('L','',$id);
		$id=intval($id);
		$customer_id="NULL";
		$head_type=getLedgerHeadType($id);
		}
	else if(substr($id, 0, 1) == 'C')
	{
		$id=str_replace('C','',$id);
		$customer_id=intval($id);
		$id="NULL";
		
		}	
	if(validateForNull($area_id))
	$area_id_array=explode(",",$area_id);
	else
	$area_id_array=array();
	$oc_id=$_SESSION['edmsAdminSession']['oc_id'];
	
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
	
	$sql="SELECT edms_ac_sales.sales_id,sales_ref_type,sales_ref,amount,from_ledger_id,to_ledger_id,to_customer_id,edms_ac_sales.oc_id,auto_rasid_type,auto_id,trans_date,delivery_date,edms_ac_sales.created_by,edms_ac_sales.last_updated_by,edms_ac_sales.date_added,edms_ac_sales.date_modified, retail_tax, invoice_no, edms_ac_sales.remarks, customer_name FROM edms_ac_sales 
	LEFT JOIN edms_customer ON edms_customer.customer_id = edms_ac_sales.to_customer_id
	LEFT JOIN edms_ac_ledgers to_ledger ON to_ledger.ledger_id = edms_ac_sales.to_ledger_id 
	LEFT JOIN edms_ac_ledgers from_ledger ON from_ledger.ledger_id = edms_ac_sales.from_ledger_id 
	WHERE ((is_deleted = 0 AND to_customer_id>0) OR is_deleted IS NULL)  AND (to_ledger.our_company_id = $oc_id OR edms_customer.our_company_id = $oc_id ) ";
	if(checkForNumeric($id))  	  
	$sql=$sql." AND ( from_ledger_id=$id OR to_ledger_id = $id) ";
	else if(checkForNumeric($customer_id))
	$sql=$sql." AND  to_customer_id=$customer_id ";
	if(isset($city_id) && validateForNull($city_id) && !in_array('0',$area_id_array))  
	$sql=$sql." AND edms_customer.city_id=$city_id
		   ";
	if(isset($area_id) && validateForNull($area_id) )  
	$sql=$sql." AND edms_customer.area_id IN ($area_id)
		   "; 
	if(isset($oc_id_string) && validateForNull($oc_id_string))  
	$sql=$sql." AND edms_customer.oc_id IN ($oc_id_string)
		   "; 	   	  	  
	if(isset($from) && validateForNull($from))
	$sql=$sql." AND trans_date >='$from' 
		  ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."AND trans_date<='$to' ";	
		
	$result=dbQuery($sql);

$resultArray=dbResultToArray($result);


	$j=0;
	if(dbNumRows($result)>0)
	{
		return $resultArray;
	}
	else return false;
	
	}	
}
?>