<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("customer-functions.php");
require_once("vehicle-insurance-functions.php");
require_once("insurance-company-functions.php");
require_once("vehicle-functions.php");
require_once("account-functions.php");
require_once("vehicle-sales-functions.php");
require_once("inventory-sales-functions.php");
require_once("inventory-item-functions.php");
require_once("nonstock-sales-functions.php");
require_once("common.php");
require_once("bd.php");

function generateVehicleOutstandingReportForCustomer($upto=null,$broker=null,$city_id=null,$area_id=null,$oc_id_string=null,$customer_group=NULL)
{
	if(validateForNull($area_id))
	$area_id_array=explode(",",$area_id);
	else
	$area_id_array=array();
	
	
	$oc_id=$_SESSION['edmsAdminSession']['oc_id'];
	
	if(isset($upto) && validateForNull($upto))
    {
	$upto = str_replace('/', '-', $upto);
	$upto=date('Y-m-d',strtotime($upto));
	}
	
	$sql="SELECT main_vehicle.vehicle_id,vehicle_reg_no,vehicle_chasis_no,vehicle_engine_no,main_vehicle.ledger_id,edms_ac_ledgers.ledger_name as broker_name,model_name,main_customer.customer_id, customer_name, customer_address, GROUP_CONCAT(customer_contact_no SEPARATOR ' <br> ') as contact_no, ledger_name,  (SELECT IF(opening_cd=0,opening_balance,-opening_balance) as amount FROM edms_vehicle WHERE vehicle_id = main_vehicle.vehicle_id )  as vehicle_opening_balance , (SELECT IF(opening_cd=0,opening_balance,-opening_balance) as amount FROM edms_customer WHERE customer_id = main_customer.customer_id )  as customer_opening_balance , (SELECT SUM(IF((from_ledger_id IS NULL AND from_customer_id IS NOT NULL) OR (auto_rasid_type=5 AND from_ledger_id IS NULL AND from_customer_id IS NOT NULL),-edms_ac_jv_cd.amount,edms_ac_jv_cd.amount)) as amount
			  FROM edms_ac_jv, edms_ac_jv_cd
			  WHERE auto_id = main_vehicle.vehicle_id AND edms_ac_jv.jv_id = edms_ac_jv_cd.jv_id
			   AND (auto_rasid_type=4 OR auto_rasid_type=5) AND (from_ledger_id IS NOT NULL OR from_customer_id IS NOT NULL) GROUP BY auto_id) as vehicle_jv_amount, 
			   (SELECT  SUM(-amount)
			  FROM edms_ac_receipt
			  WHERE auto_id = main_vehicle.vehicle_id
			   AND auto_rasid_type=4 AND to_customer_id IS NOT NULL GROUP BY auto_id) as vehicle_receipt_amount,
			   (SELECT  SUM(amount)
			  FROM edms_ac_payment
			  WHERE auto_id=main_vehicle.vehicle_id
			   AND auto_rasid_type=4 AND from_customer_id IS NOT NULL GROUP BY auto_id) as vehicle_payment_amount,
			   (SELECT SUM(amount) FROM edms_ac_sales, edms_vehicle_invoice WHERE edms_ac_sales.sales_id = edms_vehicle_invoice.sales_id AND vehicle_id = main_vehicle.vehicle_id GROUP BY auto_id) as vehicle_sale_amount,
			   (SELECT SUM(tax_amount) as tax_amount FROM edms_ac_sales_tax, edms_tax_grp, edms_tax , edms_vehicle_invoice WHERE edms_tax_grp.tax_group_id = edms_ac_sales_tax.tax_group_id AND edms_tax.tax_id = edms_ac_sales_tax.tax_id AND edms_ac_sales_tax.sales_id = edms_vehicle_invoice.sales_id AND edms_ac_sales_tax.vehicle_id = main_vehicle.vehicle_id GROUP BY edms_ac_sales_tax.vehicle_id) as vehicle_tax_amount,
			   (SELECT -basic_price FROM edms_vehicle , edms_vehicle_invoice WHERE edms_vehicle_invoice.exchange_vehicle_id = edms_vehicle.vehicle_id AND edms_vehicle_invoice.vehicle_id = main_vehicle.vehicle_id) as vehicle_exchange_amount, 
			   (SELECT sum(amount) FROM edms_ac_payment WHERE ";
			   if(isset($upto) && validateForNull($upto))  
	$sql=$sql."trans_date<='$upto'
		  AND ";
			   $sql=$sql." from_customer_id=main_customer.customer_id GROUP BY from_customer_id) as customer_payment_amount,
			   (SELECT -sum(amount) FROM edms_ac_receipt WHERE ";
			   if(isset($upto) && validateForNull($upto))  
	$sql=$sql."trans_date<='$upto'
		  AND ";
			   $sql=$sql." to_customer_id=main_customer.customer_id GROUP BY to_customer_id) as customer_receipt_amount,
			   (SELECT SUM(edms_ac_jv_cd.amount) as amount FROM edms_ac_jv,edms_ac_jv_cd WHERE edms_ac_jv.jv_id = edms_ac_jv_cd.jv_id AND ";
			   if(isset($upto) && validateForNull($upto))  
	$sql=$sql."trans_date<='$upto'
		  AND ";
			   $sql=$sql."  to_customer_id = main_customer.customer_id GROUP BY to_customer_id) as customer_debit_jv_amount,
			   (SELECT SUM(-edms_ac_jv_cd.amount) as amount FROM edms_ac_jv ,edms_ac_jv_cd WHERE edms_ac_jv.jv_id = edms_ac_jv_cd.jv_id AND ";
			   if(isset($upto) && validateForNull($upto))  
	$sql=$sql."trans_date<='$upto'
		  AND ";
			   $sql=$sql."  from_customer_id = main_customer.customer_id GROUP BY from_customer_id) as customer_credit_jv_amount,
			   (SELECT -sum(amount) FROM edms_ac_purchase WHERE ";
			   if(isset($upto) && validateForNull($upto))  
	$sql=$sql."trans_date<='$upto'
		  AND ";
			   $sql=$sql." from_customer_id=main_customer.customer_id GROUP BY from_customer_id) as customer_purchase_amount,
			   (SELECT sum(amount) FROM edms_ac_sales WHERE ";
			   if(isset($upto) && validateForNull($upto))  
	$sql=$sql."trans_date<='$upto'
		  AND ";
			   $sql=$sql." to_customer_id=main_customer.customer_id GROUP BY to_customer_id) as customer_sales_amount,
			    (SELECT sum(amount) FROM edms_ac_debit_note WHERE ";
			   if(isset($upto) && validateForNull($upto))  
	$sql=$sql."trans_date<='$upto'
		  AND ";
			   $sql=$sql." from_customer_id=main_customer.customer_id GROUP BY from_customer_id) as customer_debit_note_amount,
			   (SELECT -sum(amount) FROM edms_ac_credit_note WHERE ";
			   if(isset($upto) && validateForNull($upto))  
	$sql=$sql."trans_date<='$upto'
		  AND ";
			   $sql=$sql." to_customer_id=main_customer.customer_id GROUP BY to_customer_id) as customer_credit_note_amount,
			   (SELECT SUM(IF(in_out=0,-tax_amount,tax_amount)) FROM edms_ac_purchase, edms_ac_purchase_tax,edms_tax WHERE  "; 
			   if(isset($upto) && validateForNull($upto))  
	$sql=$sql." trans_date<='$upto'
		  AND ";
		  $sql=$sql." edms_ac_purchase.purchase_id = edms_ac_purchase_tax.purchase_id AND edms_ac_purchase_tax.tax_id = edms_tax.tax_id AND from_customer_id = main_customer.customer_id GROUP BY from_customer_id) as customer_purchase_tax_amount,
		  (SELECT SUM(IF(in_out=1 OR in_out=3,tax_amount,-tax_amount)) FROM edms_ac_sales, edms_ac_sales_tax,edms_tax WHERE 
		  "; 
			   if(isset($upto) && validateForNull($upto))  
	$sql=$sql." trans_date<='$upto'
		  AND ";
		  $sql=$sql." edms_ac_sales.sales_id = edms_ac_sales_tax.sales_id AND edms_ac_sales_tax.tax_id = edms_tax.tax_id AND  to_customer_id = main_customer.customer_id GROUP BY to_customer_id) as customer_sales_tax_amount,
		  (SELECT SUM(IF(in_out=0,tax_amount,-tax_amount)) FROM edms_ac_debit_note, edms_ac_debit_note_tax,edms_tax WHERE   "; 
			   if(isset($upto) && validateForNull($upto))  
	$sql=$sql." trans_date<='$upto'
		  AND ";
		  $sql=$sql." edms_ac_debit_note.debit_note_id = edms_ac_debit_note_tax.debit_note_id AND edms_ac_debit_note_tax.tax_id = edms_tax.tax_id AND  from_customer_id = main_customer.customer_id GROUP BY from_customer_id) as customer_debit_note_tax_amount,
		  (SELECT SUM(IF(in_out=1 OR in_out=3,-tax_amount,tax_amount)) FROM edms_ac_credit_note, edms_ac_credit_note_tax,edms_tax WHERE  "; 
			   if(isset($upto) && validateForNull($upto))  
	$sql=$sql." trans_date<='$upto'
		  AND ";
		  $sql=$sql."  edms_ac_credit_note.credit_note_id = edms_ac_credit_note_tax.credit_note_id AND edms_ac_credit_note_tax.tax_id = edms_tax.tax_id  AND to_customer_id = main_customer.customer_id GROUP BY to_customer_id) as customer_credit_note_tax_amount
			   FROM edms_vehicle as main_vehicle
	LEFT JOIN edms_customer as main_customer ON main_vehicle.customer_id = main_customer.customer_id
	LEFT JOIN edms_vehicle_model  ON main_vehicle.model_id = edms_vehicle_model.model_id
	LEFT JOIN edms_customer_contact_no ON main_customer.customer_id = edms_customer_contact_no.customer_id
	LEFT JOIN edms_ac_ledgers ON main_vehicle.ledger_id = edms_ac_ledgers.ledger_id
	WHERE is_purchased!=1 AND main_customer.is_deleted = 0 AND main_customer.our_company_id = $oc_id ";
	if(isset($city_id) && validateForNull($city_id) && !in_array('0',$area_id_array))  
	$sql=$sql." AND main_customer.city_id=$city_id
		   ";
	if(isset($area_id) && validateForNull($area_id) )  
	$sql=$sql." AND main_customer.area_id IN ($area_id)
		   "; 
	if(isset($oc_id_string) && validateForNull($oc_id_string))  
	$sql=$sql." AND main_customer.oc_id IN ($oc_id_string)
		   "; 	   	  	  
	if(isset($broker) && validateForNull($broker) )  
	$sql=$sql." AND main_vehicle.ledger_id IN ($broker)
		   "; 
	if(isset($customer_group) && checkForNumeric($customer_group) && $customer_group>0 )  
	$sql=$sql." AND main_customer.customer_id IN (SELECT customer_id FROM edms_rel_groups_customer WHERE group_id = $customer_group)
		   "; 	   	 	   
	$sql=$sql." GROUP BY vehicle_id
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

function generateOutstandingReportForCustomer($upto=null,$broker=null,$city_id=null,$area_id=null,$oc_id_string=null,$customer_group=NULL)
{
	if(validateForNull($area_id))
	$area_id_array=explode(",",$area_id);
	else
	$area_id_array=array();
	$oc_id=$_SESSION['edmsAdminSession']['oc_id'];
	
	if(isset($upto) && validateForNull($upto))
    {
	$upto = str_replace('/', '-', $upto);
	$upto=date('Y-m-d',strtotime($upto));
	}
	
	$sql="SELECT GROUP_CONCAT(main_vehicle.vehicle_id),GROUP_CONCAT(DISTINCT  vehicle_reg_no SEPARATOR '<br>') as vehicle_reg_no,main_customer.customer_id, customer_name, customer_address, GROUP_CONCAT(customer_contact_no SEPARATOR ' <br> ') as contact_no,  (SELECT IF(opening_cd=0,opening_balance,-opening_balance) as amount FROM edms_customer WHERE customer_id = main_customer.customer_id )  as customer_opening_balance , 
			   (SELECT -basic_price FROM edms_vehicle , edms_vehicle_invoice WHERE edms_vehicle_invoice.exchange_vehicle_id = edms_vehicle.vehicle_id AND edms_vehicle_invoice.vehicle_id = main_vehicle.vehicle_id) as vehicle_exchange_amount, 
			   (SELECT sum(amount) FROM edms_ac_payment WHERE ";
			   if(isset($upto) && validateForNull($upto))  
	$sql=$sql."trans_date<='$upto'
		  AND ";
			   $sql=$sql." from_customer_id=main_customer.customer_id GROUP BY from_customer_id) as customer_payment_amount,
			   (SELECT -sum(amount) FROM edms_ac_receipt WHERE ";
			   if(isset($upto) && validateForNull($upto))  
	$sql=$sql."trans_date<='$upto'
		  AND ";
			   $sql=$sql." to_customer_id=main_customer.customer_id GROUP BY to_customer_id) as customer_receipt_amount,
			   (SELECT SUM(edms_ac_jv_cd.amount) as amount FROM edms_ac_jv,edms_ac_jv_cd WHERE edms_ac_jv.jv_id = edms_ac_jv_cd.jv_id AND ";
			   if(isset($upto) && validateForNull($upto))  
	$sql=$sql."trans_date<='$upto'
		  AND ";
		  if(TAX_MODE==1)
		  $sql=$sql." auto_rasid_type!=10 AND ";
		  
			   $sql=$sql."  to_customer_id = main_customer.customer_id GROUP BY to_customer_id) as customer_debit_jv_amount,
			   (SELECT SUM(-edms_ac_jv_cd.amount) as amount FROM edms_ac_jv ,edms_ac_jv_cd WHERE edms_ac_jv.jv_id = edms_ac_jv_cd.jv_id AND ";
			   if(isset($upto) && validateForNull($upto))  
	$sql=$sql."trans_date<='$upto'
		  AND ";
		   if(TAX_MODE==1)
		  $sql=$sql." auto_rasid_type!=10 AND ";
			   $sql=$sql."  from_customer_id = main_customer.customer_id GROUP BY from_customer_id) as customer_credit_jv_amount,
			   (SELECT -sum(amount) FROM edms_ac_purchase WHERE ";
			   if(isset($upto) && validateForNull($upto))  
	$sql=$sql."trans_date<='$upto'
		  AND ";
			   $sql=$sql." from_customer_id=main_customer.customer_id GROUP BY from_customer_id) as customer_purchase_amount,
			   (SELECT sum(amount) FROM edms_ac_sales WHERE ";
			   if(isset($upto) && validateForNull($upto))  
	$sql=$sql."trans_date<='$upto'
		  AND ";
			   $sql=$sql." to_customer_id=main_customer.customer_id GROUP BY to_customer_id) as customer_sales_amount,
			    (SELECT sum(amount) FROM edms_ac_debit_note WHERE ";
			   if(isset($upto) && validateForNull($upto))  
	$sql=$sql."trans_date<='$upto'
		  AND ";
			   $sql=$sql." from_customer_id=main_customer.customer_id GROUP BY from_customer_id) as customer_debit_note_amount,
			   (SELECT -sum(amount) FROM edms_ac_credit_note WHERE ";
			   if(isset($upto) && validateForNull($upto))  
	$sql=$sql."trans_date<='$upto'
		  AND ";
			   $sql=$sql." to_customer_id=main_customer.customer_id GROUP BY to_customer_id) as customer_credit_note_amount,
			   (SELECT SUM(IF(in_out=0,-tax_amount,tax_amount)) FROM edms_ac_purchase, edms_ac_purchase_tax,edms_tax WHERE  "; 
			   if(isset($upto) && validateForNull($upto))  
	$sql=$sql." trans_date<='$upto'
		  AND ";
		  $sql=$sql." edms_ac_purchase.purchase_id = edms_ac_purchase_tax.purchase_id AND edms_ac_purchase_tax.tax_id = edms_tax.tax_id AND from_customer_id = main_customer.customer_id GROUP BY from_customer_id) as customer_purchase_tax_amount,
		  (SELECT SUM(IF(in_out=1 OR in_out=3,tax_amount,-tax_amount)) FROM edms_ac_sales, edms_ac_sales_tax,edms_tax WHERE 
		  "; 
			   if(isset($upto) && validateForNull($upto))  
	$sql=$sql." trans_date<='$upto'
		  AND ";
		  $sql=$sql." edms_ac_sales.sales_id = edms_ac_sales_tax.sales_id AND edms_ac_sales_tax.tax_id = edms_tax.tax_id AND  to_customer_id = main_customer.customer_id GROUP BY to_customer_id) as customer_sales_tax_amount,
		  (SELECT SUM(IF(in_out=0,tax_amount,-tax_amount)) FROM edms_ac_debit_note, edms_ac_debit_note_tax,edms_tax WHERE   "; 
			   if(isset($upto) && validateForNull($upto))  
	$sql=$sql." trans_date<='$upto'
		  AND ";
		  $sql=$sql." edms_ac_debit_note.debit_note_id = edms_ac_debit_note_tax.debit_note_id AND edms_ac_debit_note_tax.tax_id = edms_tax.tax_id AND  from_customer_id = main_customer.customer_id GROUP BY from_customer_id) as customer_debit_note_tax_amount,
		  (SELECT SUM(IF(in_out=1 OR in_out=3,-tax_amount,tax_amount)) FROM edms_ac_credit_note, edms_ac_credit_note_tax,edms_tax WHERE  "; 
			   if(isset($upto) && validateForNull($upto))  
	$sql=$sql." trans_date<='$upto'
		  AND ";
		  $sql=$sql."  edms_ac_credit_note.credit_note_id = edms_ac_credit_note_tax.credit_note_id AND edms_ac_credit_note_tax.tax_id = edms_tax.tax_id  AND to_customer_id = main_customer.customer_id GROUP BY to_customer_id) as customer_credit_note_tax_amount
			   FROM edms_customer as main_customer
	LEFT JOIN edms_vehicle as main_vehicle ON main_vehicle.customer_id = main_customer.customer_id
	LEFT JOIN edms_customer_contact_no ON main_customer.customer_id = edms_customer_contact_no.customer_id
	WHERE  main_customer.is_deleted = 0 ";
	if(CUSTOMER_MULTI_COMPANY==0)
	$sql=$sql." AND main_customer.our_company_id = $oc_id ";
	if(isset($city_id) && validateForNull($city_id) && !in_array('0',$area_id_array))  
	$sql=$sql." AND main_customer.city_id=$city_id
		   ";
	if(isset($area_id) && validateForNull($area_id) )  
	$sql=$sql." AND main_customer.area_id IN ($area_id)
		   "; 
	if(isset($oc_id_string) && validateForNull($oc_id_string))  
	$sql=$sql." AND main_customer.oc_id IN ($oc_id_string)
		   "; 	   	  	  
	if(isset($broker) && validateForNull($broker) )  
	$sql=$sql." AND main_vehicle.ledger_id IN ($broker)
		   "; 	 
	if(isset($customer_group) && checkForNumeric($customer_group) && $customer_group>0 )  
	$sql=$sql." AND main_customer.customer_id IN (SELECT customer_id FROM edms_rel_groups_customer WHERE group_id = $customer_group)
		   "; 	   	   
	$sql=$sql." GROUP BY customer_id
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

function generalSalesReportsForLedger($id,$from=null,$to=null,$city_id=null,$area_id=null,$oc_id_string=NULL)
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


function generalJobCardReportsByNextServiceDate($from=null,$to=null,$broker=null,$city_id=null,$area_id=null,$oc_id_string=null,$type=null)
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

	
	$sql="SELECT edms_job_card.job_card_id,job_card_no, job_card_datetime, edms_service_types.service_type_id , service_type, free_service_no, date_of_sale,kms_covered, estimated_repair_cost, bay_in, bay_out, delivery_promise, actual_delivery, edms_technician.technician_id, edms_vehicle.vehicle_id, vehicle_reg_no, vehicle_engine_no, vehicle_chasis_no, edms_customer.customer_id, customer_name, edms_job_card.created_by, edms_job_card.last_updated_by, edms_job_card.date_added, edms_job_card.date_modified , MAX(next_service_date) as max_next_service_date, (SELECT GROUP_CONCAT(customer_contact_no SEPARATOR '<br>') FROM edms_customer_contact_no WHERE edms_customer_contact_no.customer_id = edms_customer.customer_id GROUP BY edms_customer.customer_id) as contact_no
	FROM edms_job_card 
	INNER JOIN edms_vehicle ON edms_vehicle.vehicle_id = edms_job_card.vehicle_id 
	INNER JOIN edms_customer ON edms_customer.customer_id = edms_job_card.customer_id
	INNER JOIN edms_technician ON edms_technician.technician_id = edms_job_card.technician_id
	INNER JOIN edms_service_types ON edms_service_types.service_type_id = edms_job_card.service_type_id
	WHERE is_deleted =0  AND our_company_id = $oc_id";	
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
	$sql= $sql." GROUP BY edms_vehicle.vehicle_id HAVING  max_next_service_date IS NOT NULL ";
	
	if(isset($from) && validateForNull($from))
	$sql=$sql." AND max_next_service_date >='$from' 
		  ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."AND max_next_service_date <='$to' ";
	$sql=$sql." ORDER BY next_service_date";
	
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

function generateItemWisePurchaseReports($from=null,$to=null,$oc_id_string=null,$type=null,$item_name_array=null) // type = sales or job_card
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
	$sql="SELECT edms_ac_purchase.purchase_id,purchase_ref_type,purchase_ref,edms_ac_purchase.amount as purchase_amount,from_ledger_id,to_ledger_id,from_customer_id,from_ledger.oc_id,auto_rasid_type,auto_id,trans_date,delivery_date,edms_ac_purchase.created_by,edms_ac_purchase.last_updated_by,edms_ac_purchase.date_added,edms_ac_purchase.date_modified, remarks, item_id, quantity, rate, edms_ac_purchase_item.amount, discount, net_amount, edms_ac_purchase_item.godown_id, godown_name, customer_id, customer_name, from_ledger.ledger_name, to_ledger.ledger_name  FROM edms_ac_purchase 
	INNER JOIN edms_ac_purchase_item ON edms_ac_purchase_item.purchase_id = edms_ac_purchase.purchase_id 
	INNER JOIN edms_godown ON edms_ac_purchase_item.godown_id = edms_godown.godown_id
	INNER JOIN edms_ac_ledgers to_ledger ON to_ledger.ledger_id = edms_ac_purchase.to_ledger_id
	LEFT JOIN edms_ac_ledgers from_ledger ON from_ledger.ledger_id = edms_ac_purchase.from_ledger_id
	LEFT JOIN edms_customer ON edms_ac_purchase.from_customer_id = edms_customer.customer_id
	 WHERE ((from_customer_id IS NOT NULL AND is_deleted =0  AND edms_customer.our_company_id = $oc_id) OR (from_ledger_id IS NOT NULL AND  from_ledger.our_company_id = $oc_id)) ";
	if(isset($item_id_string) && validateForNull($item_id_string) )  
	$sql=$sql." AND edms_ac_purchase_item.item_id IN ($item_id_string)
		   "; 
	if(isset($type) && validateForNull($type) )  
	$sql=$sql." AND auto_rasid_type IN ($type)
		   "; 
	if(isset($from) && validateForNull($from))
	$sql=$sql." AND trans_date >='$from' 
		  ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql." AND trans_date <='$to' ";
	
	   
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
	LEFT JOIN edms_ac_ledgers to_ledger ON to_ledger.ledger_id = edms_ac_sales.to_ledger_id  WHERE ((to_customer_id IS NOT NULL AND is_deleted =0 
	";
	if(TAX_MODE==0)
	 $sql=$sql." AND edms_customer.our_company_id = $oc_id"; 
	 $sql=$sql." ) OR (to_ledger_id IS NOT NULL ";
	 if(TAX_MODE==0)
	 $sql=$sql." AND  to_ledger.our_company_id = $oc_id ";
	 $sql=$sql." )) ";
	 if(TAX_MODE==1)
	 $sql=$sql." AND edms_ac_sales.oc_id = $oc_id ";
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
if($agency_id=="NULL" && is_numeric($our_company_id) && CUSTOMER_MULTI_COMPANY==0)
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
	if(CUSTOMER_MULTI_COMPANY==0)	   
	$sql=$sql."	 our_company_id=$oc_id";
	else
	$sql=$sql." 1=1";	 
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
 	
if($agency_id=="NULL" && is_numeric($our_company_id) && CUSTOMER_MULTI_COMPANY==0)
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
    if(CUSTOMER_MULTI_COMPANY==0)	    	  
	$sql=$sql."	 our_company_id=$oc_id";
	else
	$sql=$sql." 1=1";	 
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

function generateSalesReport($from=null,$to=null,$ledger_in_array=null,$ledger_not_in_array=null,$customer_in_array=null,$outstanding_amount=0)
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
	
	$sql="SELECT sales_id, edms_ac_sales.amount, edms_ac_sales.from_ledger_id, from_ledger.ledger_name as from_ledger_name, edms_ac_sales.to_ledger_id, to_ledger.ledger_name as to_ledger_name, edms_ac_sales.to_customer_id, customer_name, edms_ac_sales.auto_rasid_type, edms_ac_sales.auto_id, edms_ac_sales.trans_date, invoice_no, edms_ac_sales.oc_id, our_company_name, IF(SUM(edms_ac_receipt.amount)>0,SUM(edms_ac_receipt.amount),0) as received_amount
	 FROM edms_ac_sales
	 LEFT JOIN edms_customer ON edms_ac_sales.to_customer_id = edms_customer.customer_id
	 LEFT JOIN edms_ac_ledgers AS from_ledger ON edms_ac_sales.from_ledger_id = from_ledger.ledger_id 
	 LEFT JOIN edms_ac_ledgers AS to_ledger ON edms_ac_sales.to_ledger_id = to_ledger.ledger_id 
	 LEFT JOIN edms_our_company  ON edms_our_company.our_company_id = edms_ac_sales.oc_id
	 LEFT JOIN edms_ac_receipt ON edms_ac_receipt.auto_id = edms_ac_sales.sales_id AND edms_ac_receipt.auto_rasid_type=5
	 WHERE edms_ac_sales.auto_rasid_type=2  ";
	  if(defined('CUSTOMER_MULTI_COMPANY') && CUSTOMER_MULTI_COMPANY==0)
	  {
	  $sql=$sql." AND (edms_ac_sales.to_customer_id IS NULL OR edms_customer.oc_id=$oc_id) AND (edms_ac_sales.to_ledger_id IS NULL OR  edms_ac_sales.oc_id=$oc_id)  ";
	 $sql=$sql." AND edms_ac_sales.oc_id = $oc_id";
	  }
	if(isset($from) && validateForNull($from))
	$sql=$sql." AND edms_ac_sales.trans_date>='$from' 
		   ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql." AND edms_ac_sales.trans_date<='$to'
		  ";
	if(isset($ledger_in_array) && checkForNumeric($ledger_in_array))
	$sql=$sql." AND (edms_ac_sales.from_ledger_id = $ledger_in_array OR edms_ac_sales.to_ledger_id = $ledger_in_array) ";
	else if(isset($ledger_in_string) && validateForNull($ledger_in_string))
	$sql=$sql." AND (edms_ac_sales.from_ledger_id IN ($ledger_in_string) OR edms_ac_sales.to_ledger_id IN ($ledger_in_string)) ";
	if(isset($customer_in_array) && checkForNumeric($customer_in_array))
	$sql=$sql." AND (edms_ac_sales.to_customer_id = $customer_in_array) ";
	else if(isset($customer_in_string) && validateForNull($customer_in_string))
	$sql=$sql." AND (edms_ac_sales.to_customer_id IN ($customer_in_string)) ";
	if(isset($ledger_not_in_array) && checkForNumeric($ledger_not_in_array))
	$sql=$sql." AND ((edms_ac_sales.from_ledger_id != $ledger_not_in_array OR edms_ac_sales.from_ledger_id IS NULL)  AND (edms_ac_sales.to_ledger_id != $ledger_not_in_array OR edms_ac_sales.to_ledger_id IS NULL )) ";
	else if(isset($ledger_not_in_string) && validateForNull($ledger_not_in_string))
	$sql=$sql." AND (edms_ac_sales.from_ledger_id NOT IN ($ledger_not_in_string) AND edms_ac_sales.to_ledger_id NOT IN ($ledger_not_in_string)) ";
	if(is_numeric($outstanding_amount) && $outstanding_amount>0)
	$sql = $sql." AND (SUM(edms_ac_receipt.amount) IS NULL OR (edms_ac_sales.amount - SUM(edms_ac_receipt.amount))>0)";
	$sql=$sql." GROUP BY edms_ac_sales.sales_id";

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

function generateInventoryJVReport($from=null,$to=null,$ledger_in_array=null,$ledger_not_in_array=null,$jv_type=0)
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
	
	$sql="SELECT edms_inventory_jv.inventory_jv_id, trans_date, edms_inventory_jv.remarks, GROUP_CONCAT(inventory_item_jv_id), GROUP_CONCAT(edms_inventory_item_jv.item_id), GROUP_CONCAT(item_name) as items_string, GROUP_CONCAT(rate), GROUP_CONCAT(quantity), GROUP_CONCAT(amount), GROUP_CONCAT(godown_id), GROUP_CONCAT(type), edms_inventory_jv.created_by, edms_inventory_jv.last_updated_by, edms_inventory_jv.date_added, edms_inventory_jv.date_modified, edms_inventory_jv.jv_type_id, jv_type, inventory_jv_mode, ledger_id, customer_id
	 FROM edms_inventory_jv
	 INNER JOIN edms_inventory_item_jv ON edms_inventory_item_jv.inventory_jv_id = edms_inventory_jv.inventory_jv_id
	 INNER JOIN edms_inventory_item ON edms_inventory_item_jv.item_id = edms_inventory_item.item_id
	 LEFT JOIN edms_ac_jv_types ON edms_ac_jv_types.jv_type_id =  edms_inventory_jv.jv_type_id 
	 WHERE edms_inventory_jv.oc_id = $oc_id  ";
	
	if(isset($from) && validateForNull($from))
	$sql=$sql." AND trans_date>='$from' 
		   ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql." AND trans_date<='$to'
		  ";
		  
	$sql=$sql." GROUP BY edms_inventory_jv.inventory_jv_id ORDER BY type DESC";
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

function generalSalesReports($id,$from=null,$to=null,$city_id=null,$area_id=null,$oc_id_string=null,$outstanding_amount=0,$omit_receipt_ids=NULL,$customer_group_id=NULL)
{
	$id = trim($id);
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
	
	$sql="SELECT edms_ac_sales.sales_id,edms_ac_sales.sales_ref_type,edms_ac_sales.sales_ref,edms_ac_sales.amount,edms_ac_sales.from_ledger_id,edms_ac_sales.to_ledger_id,edms_ac_sales.to_customer_id,to_ledger.ledger_name as to_ledger_name,from_ledger.ledger_name as from_ledger_name,edms_ac_sales.oc_id,edms_ac_sales.auto_rasid_type,edms_ac_sales.auto_id,edms_ac_sales.trans_date,edms_ac_sales.delivery_date,edms_ac_sales.created_by,edms_ac_sales.last_updated_by,edms_ac_sales.date_added,edms_ac_sales.date_modified, retail_tax, invoice_no, edms_ac_sales.remarks, customer_name, IF(edms_ac_sales.auto_rasid_type=3,'JOB CARD','SALES') as type,  job_card_datetime, job_card_no,edms_job_card.vehicle_id, IFNULL((SELECT SUM(amount) FROM edms_ac_receipt WHERE edms_ac_sales.sales_id = edms_ac_receipt.auto_id AND edms_ac_receipt.auto_rasid_type=5),0) as received_amount, (edms_ac_sales.amount-IFNULL((SELECT SUM(edms_ac_receipt.amount) + SUM(IFNULL(edms_ac_jv.amount,0)) FROM edms_ac_receipt LEFT JOIN edms_ac_jv ON edms_ac_jv.auto_id = edms_ac_receipt.receipt_id AND edms_ac_jv.auto_rasid_type=7 WHERE edms_ac_sales.sales_id = edms_ac_receipt.auto_id AND  edms_ac_receipt.auto_rasid_type=5 ";
	if(validateForNull($omit_receipt_ids))
	$sql=$sql." AND edms_ac_receipt.receipt_id NOT IN ($omit_receipt_ids) ";
	$sql=$sql." GROUP BY edms_ac_sales.sales_id),0) + IFNULL((SELECT SUM(tax_amount) FROM edms_ac_sales_tax WHERE edms_ac_sales_tax.sales_id = edms_ac_sales.sales_id GROUP BY edms_ac_sales.sales_id ),0)) as outstanding_amount   FROM edms_ac_sales 
	LEFT JOIN edms_customer ON edms_customer.customer_id = edms_ac_sales.to_customer_id
	LEFT JOIN edms_ac_ledgers to_ledger ON to_ledger.ledger_id = edms_ac_sales.to_ledger_id 
	LEFT JOIN edms_ac_ledgers from_ledger ON from_ledger.ledger_id = edms_ac_sales.from_ledger_id 
	LEFT JOIN edms_job_card ON edms_job_card.job_card_id = edms_ac_sales.auto_id AND edms_ac_sales.auto_rasid_type = 3 
	
	";
	$sql=$sql." WHERE ((is_deleted = 0 AND edms_ac_sales.to_customer_id>0) OR is_deleted IS NULL) ";
	if(TAX_MODE==0)
	$sql=$sql." AND (to_ledger.our_company_id = $oc_id OR edms_customer.our_company_id = $oc_id ) ";
	else
	$sql=$sql." AND edms_ac_sales.oc_id = $oc_id ";
	
	if(checkForNumeric($id))  	  
	$sql=$sql." AND ( edms_ac_sales.from_ledger_id=$id OR edms_ac_sales.to_ledger_id = $id) ";
	else if(checkForNumeric($customer_id))
	$sql=$sql." AND  edms_ac_sales.to_customer_id=$customer_id ";
	
	if(isset($city_id) && validateForNull($city_id) && !in_array('0',$area_id_array))  
	$sql=$sql." AND edms_customer.city_id=$city_id
		   ";
		   
	if(isset($customer_group_id) && checkForNumeric($customer_group_id) && $customer_group_id>0)  
	$sql=$sql." AND edms_customer.customer_id  IN (SELECT customer_id FROM edms_rel_groups_customer WHERE group_id = $customer_group_id)
		   ";
	
	if(isset($area_id) && validateForNull($area_id) )  
	$sql=$sql." AND edms_customer.area_id IN ($area_id)
		   "; 
	
	if(isset($oc_id_string) && validateForNull($oc_id_string))  
	$sql=$sql." AND edms_customer.oc_id IN ($oc_id_string)
		   "; 	   	  	  
	
	if(isset($from) && validateForNull($from))
	$sql=$sql." AND edms_ac_sales.trans_date >='$from' 
		  ";
	
	if(isset($to) && validateForNull($to))  
	$sql=$sql."AND edms_ac_sales.trans_date<='$to' ";	
	
	
	
	$sql=$sql." GROUP BY edms_ac_sales.sales_id ";	
	if(is_numeric($outstanding_amount) && $outstanding_amount>0)
	$sql = $sql." HAVING outstanding_amount>0 OR received_amount IS NULL";
    
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


function generalSalesJObCardReports($from=null,$to=null,$city_id=null,$area_id=null,$oc_id_string=null,$type=null) // all sales and jobcard for period union all unfinalized jobcards union all current period paid transactions with jobcards
{
	$bank_and_cash_ledgers_array = listAccountingLedgerIDs();
	
	$bank_and_cash_ledgers_string = implode(",",$bank_and_cash_ledgers_array);	
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
	
	$sql="SELECT edms_ac_sales.sales_id as id,sales_ref_type as ref_type,sales_ref as ref,amount,from_ledger_id ,to_ledger_id,to_customer_id,to_ledger.ledger_name as to_ledger_name,from_ledger.ledger_name as from_ledger_name,edms_ac_sales.oc_id,auto_rasid_type,auto_id,trans_date,edms_ac_sales.created_by,edms_ac_sales.last_updated_by,edms_ac_sales.date_added,edms_ac_sales.date_modified, retail_tax, invoice_no, edms_ac_sales.remarks, customer_name, IF(auto_rasid_type=3,'JOB CARD','SALES') as type,  job_card_datetime, job_card_no,edms_job_card.vehicle_id FROM edms_ac_sales 
	LEFT JOIN edms_customer ON edms_customer.customer_id = edms_ac_sales.to_customer_id
	LEFT JOIN edms_ac_ledgers to_ledger ON to_ledger.ledger_id = edms_ac_sales.to_ledger_id 
	LEFT JOIN edms_ac_ledgers from_ledger ON from_ledger.ledger_id = edms_ac_sales.from_ledger_id 
	LEFT JOIN edms_job_card ON edms_job_card.job_card_id = edms_ac_sales.auto_id AND auto_rasid_type = 3 
	WHERE ((is_deleted = 0 AND to_customer_id>0) OR is_deleted IS NULL) ";
	if(TAX_MODE==0)
	$sql=$sql." AND (to_ledger.our_company_id = $oc_id OR edms_customer.our_company_id = $oc_id ) ";
	else
	$sql=$sql." AND edms_ac_sales.oc_id = $oc_id ";
	
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
	
	$sql=$sql." AND ((bay_out>'1970-01-01' AND auto_rasid_type=3 ";	       //compul	  	  
	
	if(isset($from) && validateForNull($from))
	$sql=$sql." AND trans_date >='$from' 
		  ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql." AND trans_date<='$to' ";	
	
	$sql=$sql." ) OR (bay_out='1970-01-01' AND auto_rasid_type=3)  "; // compul
	

	$sql=$sql." OR ( auto_rasid_type!=3 ";
	if(isset($from) && validateForNull($from))
	$sql=$sql." AND trans_date >='$from' 
		  ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql." AND trans_date<='$to' ";
	
	$sql=$sql." )) ";	
	if(isset($type) && checkForNumeric($type) && $type==1)  
	$sql=$sql."AND ((bay_out>'1970-01-01' AND auto_rasid_type=3) OR auto_rasid_type!=3) ";	
	
	$sql=$sql."UNION ALL SELECT edms_ac_purchase.purchase_id as id,purchase_ref_type as ref_type,purchase_ref as ref,amount,from_ledger_id,to_ledger_id,to_ledger.ledger_name as to_ledger_name,from_ledger.ledger_name as from_ledger_name,from_customer_id,edms_ac_purchase.oc_id,auto_rasid_type,auto_id,trans_date,edms_ac_purchase.created_by,edms_ac_purchase.last_updated_by,edms_ac_purchase.date_added,edms_ac_purchase.date_modified,'NA' as retail_tax, 'NA' as invoice_no, edms_ac_purchase.remarks, customer_name, 'PURCHASE' as type,'NA' as  job_card_datetime,'NA' as job_card_no,'NA' as vehicle_id FROM edms_ac_purchase 
	LEFT JOIN edms_customer ON edms_customer.customer_id = edms_ac_purchase.from_customer_id
	LEFT JOIN edms_ac_ledgers to_ledger ON to_ledger.ledger_id = edms_ac_purchase.to_ledger_id 
	LEFT JOIN edms_ac_ledgers from_ledger ON from_ledger.ledger_id = edms_ac_purchase.from_ledger_id 
	WHERE ((is_deleted = 0 AND from_customer_id>0) OR is_deleted IS NULL)   AND ((from_ledger_id IS NOT NULL AND from_ledger_id IN ($bank_and_cash_ledgers_string)) OR from_customer_id>0) ";
	if(TAX_MODE==0)
	$sql=$sql." AND (to_ledger.our_company_id = $oc_id OR edms_customer.our_company_id = $oc_id ) ";
	else
	$sql=$sql." AND edms_ac_purchase.oc_id = $oc_id ";
	
	if(checkForNumeric($id))  	  
	$sql=$sql." AND ( from_ledger_id=$id OR to_ledger_id = $id) ";
	else if(checkForNumeric($customer_id))
	$sql=$sql." AND  from_customer_id=$customer_id ";
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
	
	$sql=$sql."UNION ALL SELECT edms_ac_receipt.receipt_id as id,receipt_ref_type as ref_type,receipt_ref as ref,edms_ac_receipt.amount,edms_ac_receipt.from_ledger_id,edms_ac_receipt.to_ledger_id,to_ledger.ledger_name as to_ledger_name,from_ledger.ledger_name as from_ledger_name,edms_ac_receipt.to_customer_id,edms_ac_receipt.oc_id,edms_ac_receipt.auto_rasid_type,edms_ac_receipt.auto_id,edms_ac_receipt.trans_date,edms_ac_receipt.created_by,edms_ac_receipt.last_updated_by,edms_ac_receipt.date_added,edms_ac_receipt.date_modified, 'NA' as retail_tax, invoice_no, edms_ac_receipt.remarks, customer_name, 'RECEIPT' as type,  job_card_datetime,  job_card_no,  vehicle_id FROM edms_ac_receipt 
	LEFT JOIN edms_customer ON edms_customer.customer_id = edms_ac_receipt.to_customer_id
	LEFT JOIN edms_ac_ledgers to_ledger ON to_ledger.ledger_id = edms_ac_receipt.to_ledger_id 
	LEFT JOIN edms_ac_ledgers from_ledger ON from_ledger.ledger_id = edms_ac_receipt.from_ledger_id 
	LEFT JOIN edms_job_card ON edms_job_card.job_card_id = edms_ac_receipt.auto_id AND edms_ac_receipt.auto_rasid_type = 3 
	LEFT JOIN edms_ac_sales ON edms_ac_sales.sales_id = edms_ac_receipt.auto_id AND edms_ac_receipt.auto_rasid_type = 5 
	WHERE ((is_deleted = 0 AND edms_ac_receipt.to_customer_id>0) OR is_deleted IS NULL)   AND ((edms_ac_receipt.auto_rasid_type=3 ";
	if(TAX_MODE==0)
	$sql=$sql." AND (to_ledger.our_company_id = $oc_id OR edms_customer.our_company_id = $oc_id ) ";
	else
	$sql=$sql." AND edms_ac_receipt.oc_id = $oc_id ";
	
	if(isset($from) && validateForNull($from))
	{
	if(isset($to) && validateForNull($to))  	
	$sql=$sql." AND (job_card_datetime < '$from' OR 
		  ";
	else 
	$sql=$sql." AND job_card_datetime < '$from' 
		  ";	  
	}
	if(isset($to) && validateForNull($to)) 
	{ 
	if(isset($from) && validateForNull($from))
	$sql=$sql." job_card_datetime > '$to 23:59:59' ) ";
	else
	$sql=$sql."AND job_card_datetime > '$to 23:59:59' ";
	}
	$sql=$sql.") OR (edms_ac_receipt.auto_rasid_type=5 ";
	if(isset($from) && validateForNull($from))
	{
	if(isset($to) && validateForNull($to))  	
	$sql=$sql." AND (edms_ac_sales.trans_date < '$from' OR 
		  ";
	else 
	$sql=$sql." AND edms_ac_sales.trans_date < '$from' 
		  ";	  
	}
	if(isset($to) && validateForNull($to)) 
	{ 
	if(isset($from) && validateForNull($from))
	$sql=$sql." edms_ac_sales.trans_date > '$to' ) ";
	else
	$sql=$sql."AND edms_ac_sales.trans_date > '$to' ";
	}
	$sql=$sql.") OR (edms_ac_receipt.auto_rasid_type !=3 AND edms_ac_receipt.auto_rasid_type!=5))";
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
	$sql=$sql." AND edms_ac_receipt.trans_date >='$from' 
		  ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."AND edms_ac_receipt.trans_date<='$to' ";	 
	if(isset($type) && checkForNumeric($type) && $type==1)  
	$sql=$sql."AND (((bay_out>'1970-01-01' AND edms_ac_receipt.auto_rasid_type=5) OR (edms_ac_receipt.auto_rasid_type!=5 AND edms_ac_receipt.auto_rasid_type!=3))) ";	
	$sql=$sql." GROUP BY CASE WHEN (edms_ac_receipt.auto_rasid_type = 3 OR edms_ac_receipt.auto_rasid_type=5) THEN edms_ac_receipt.auto_id ELSE edms_ac_receipt.receipt_id END ";
	$sql=$sql."UNION ALL SELECT edms_ac_payment.payment_id as id,payment_ref_type as ref_type,payment_ref as ref,amount,from_ledger_id,to_ledger_id,to_ledger.ledger_name as to_ledger_name,from_ledger.ledger_name as from_ledger_name,from_customer_id,edms_ac_payment.oc_id,auto_rasid_type,auto_id,trans_date,edms_ac_payment.created_by,edms_ac_payment.last_updated_by,edms_ac_payment.date_added,edms_ac_payment.date_modified, 'NA' as retail_tax, 'NA' as invoice_no, edms_ac_payment.remarks, customer_name, 'PAYMENT' as type,'NA' as  job_card_datetime,'NA' as job_card_no,'NA' as vehicle_id FROM edms_ac_payment 
	LEFT JOIN edms_customer ON edms_customer.customer_id = edms_ac_payment.from_customer_id
	LEFT JOIN edms_ac_ledgers to_ledger ON to_ledger.ledger_id = edms_ac_payment.to_ledger_id 
	LEFT JOIN edms_ac_ledgers from_ledger ON from_ledger.ledger_id = edms_ac_payment.from_ledger_id 
	WHERE auto_rasid_type!=5 AND ((is_deleted = 0 AND from_customer_id>0) OR is_deleted IS NULL)   ";
	if(TAX_MODE==0)
	$sql=$sql." AND (to_ledger.our_company_id = $oc_id OR edms_customer.our_company_id = $oc_id ) ";
	else
	$sql=$sql." AND edms_ac_payment.oc_id = $oc_id ";
	
	if(checkForNumeric($id))  	  
	$sql=$sql." AND ( from_ledger_id=$id OR to_ledger_id = $id) ";
	else if(checkForNumeric($customer_id))
	$sql=$sql." AND  from_customer_id=$customer_id ";
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



function generalPurchaseReports($from_ledger,$from=NULL,$to=NULL)
{
	if(substr($from_ledger, 0, 1) == 'L')
	{
		$from_ledger=str_replace('L','',$from_ledger);
		$from_ledger=intval($from_ledger);
		$from_customer="NULL";
		$head_type=getLedgerHeadType($from_ledger);
		}
	else if(substr($from_ledger, 0, 1) == 'C')
	{
		$from_ledger=str_replace('C','',$from_ledger);
		$from_customer=intval($from_ledger);
		$from_ledger="NULL";
		
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
	
	if(checkForNumeric($from_customer) || (isset($head_type) && checkForNumeric($from_ledger)  && $head_type!=2 && $head_type!=4))
	{
	$sql="SELECT edms_ac_purchase.purchase_id, amount  , IF(total_tax IS NOT NULL,total_tax,0) AS tax_amount ";
	$sql=$sql." ,from_ledger_id,to_ledger_id,from_customer_id,oc_id,auto_rasid_type,auto_id,trans_date,CONCAT_WS(' ',DATE_FORMAT(trans_date,'%b'),DATE_FORMAT(trans_date,'%Y')) AS month_year,DATE_FORMAT(trans_date,'%c') as month_id, DATE_FORMAT(trans_date,'%Y') as year, created_by,last_updated_by,date_added,date_modified, remarks
			  FROM edms_ac_purchase LEFT JOIN (SELECT f.purchase_id, SUM( tax_amount ) AS total_tax, e.tax_group_id, e.in_out
FROM edms_ac_purchase_tax f, edms_tax_grp e, edms_rel_tax_grp_tax g
WHERE f.tax_id = g.tax_id
AND g.tax_group_id = e.tax_group_id
GROUP BY f.purchase_id
)h ON edms_ac_purchase.purchase_id = h.purchase_id
WHERE ";
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(isset($head_type) && checkForNumeric($head_type) && $head_type==3)  	  
	$sql=$sql." to_ledger_id=$from_ledger";
	else if(isset($head_type) && checkForNumeric($head_type) && ($head_type==1 || $head_type==0))  
	$sql=$sql." from_ledger_id=$from_ledger";
	else if(!isset($head_type))
	$sql=$sql." from_customer_id=$from_customer";  		  
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray;
	else
	return array();
	}
	return array();
}	

function unreceivedPurchaseOrderReports($from=NULL,$to=NULL,$from_ledger=NULL)
{
	if(substr($from_ledger, 0, 1) == 'L')
	{
		$from_ledger=str_replace('L','',$from_ledger);
		$from_ledger=intval($from_ledger);
		$from_customer="NULL";
		$head_type=getLedgerHeadType($from_ledger);
		}
	else if(substr($from_ledger, 0, 1) == 'C')
	{
		$from_ledger=str_replace('C','',$from_ledger);
		$from_customer=intval($from_ledger);
		$from_ledger="NULL";
		
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
	
	
	$sql="SELECT edms_ac_purchase_order.purchase_order_id, amount  , from_ledger_id, trans_date, remarks, purchase_order_ref , (SELECT GROUP_CONCAT(item_name) FROM edms_ac_purchase_order_item, edms_inventory_item WHERE edms_ac_purchase_order_item.item_id = edms_inventory_item.item_id AND edms_ac_purchase_order_item.purchase_order_id = edms_ac_purchase_order.purchase_order_id) as item_names
FROM edms_ac_purchase_order
WHERE purchase_order_id NOT IN (SELECT purchase_order_id FROM edms_inventory_jv WHERE purchase_order_id IS NOT NULL)  ";
	if(isset($from) && validateForNull($from))
	$sql=$sql." AND trans_date>='$from' 
		   ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql." AND trans_date<='$to'
		   ";
	if(checkForNumeric($from_ledger))  
	$sql=$sql." AND from_ledger_id=$from_ledger";
	else if(checkForNumeric($from_customer))
	$sql=$sql." AND from_customer_id=$from_customer";  	
	
	 
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	
	if(dbNumRows($result)>0)
	return $resultArray;
	else
	return array();
	
	return array();
}	

function receivedPurchaseOrderReports($from=NULL,$to=NULL,$from_ledger=NULL)
{
	if(substr($from_ledger, 0, 1) == 'L')
	{
		$from_ledger=str_replace('L','',$from_ledger);
		$from_ledger=intval($from_ledger);
		$from_customer="NULL";
		$head_type=getLedgerHeadType($from_ledger);
		}
	else if(substr($from_ledger, 0, 1) == 'C')
	{
		$from_ledger=str_replace('C','',$from_ledger);
		$from_customer=intval($from_ledger);
		$from_ledger="NULL";
		
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
	
	
	$sql="SELECT edms_ac_purchase_order.purchase_order_id, amount  , from_ledger_id, trans_date, remarks, purchase_order_ref , (SELECT GROUP_CONCAT(item_name) FROM edms_ac_purchase_order_item, edms_inventory_item WHERE edms_ac_purchase_order_item.item_id = edms_inventory_item.item_id AND edms_ac_purchase_order_item.purchase_order_id = edms_ac_purchase_order.purchase_order_id) as item_names
FROM edms_ac_purchase_order
WHERE purchase_order_id IN (SELECT purchase_order_id FROM edms_inventory_jv WHERE purchase_order_id IS NOT NULL)  ";
	if(isset($from) && validateForNull($from))
	$sql=$sql." AND trans_date>='$from' 
		   ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql." AND trans_date<='$to'
		   ";
	if(checkForNumeric($from_ledger))  
	$sql=$sql." AND from_ledger_id=$from_ledger";
	else if(checkForNumeric($from_customer))
	$sql=$sql." AND from_customer_id=$from_customer";  	
	
	 
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	
	if(dbNumRows($result)>0)
	return $resultArray;
	else
	return array();
	
	return array();
}	

?>