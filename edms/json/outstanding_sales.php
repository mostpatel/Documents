<?php require_once '../lib/cg.php';
require_once '../lib/common.php';
require_once '../lib/account-sales-functions.php';
require_once '../lib/account-ledger-functions.php';
require_once '../lib/account-receipt-functions.php';
require_once '../lib/report-functions.php';
?>
<?php

$ledger_customer_id=$_GET['cid'];
$up_to_date=$_GET['trans_date'];
if(isset($_GET['type']))
{
$ledger_id_type = $_GET['type'];

$ledger_customer_id=getLedgerIdsArrayForLedgerNameId($ledger_customer_id);

}
if(isset($_GET['lid']))
{
	$receipt_id = $_GET['lid'];
	$parent_id=getParentIdForReceiptId($receipt_id);
	$receipt_ids = getReceiptIdsForParentReceiptId($parent_id);
}
else
$receipt_id=NULL;
if(isset($_GET['oc_id']) && checkForNumeric($_GET['oc_id']))
$sales=generalSalesReportsForLedgerForOc($ledger_customer_id,$_GET['oc_id'],NULL,$up_to_date,NULL,NULL,NULL,1,$receipt_ids);
else
$sales=generalSalesReports($ledger_customer_id,NULL,$up_to_date,NULL,NULL,NULL,1,$receipt_ids);

$str="";
foreach ($sales as $s) 
{
	
	$label = $s['invoice_no']." ".date("d/m/Y",strtotime($s['trans_date']))." ".$s['outstanding_amount']." Rs";
	$str=$str . "\"$s[sales_id]\"".",". "\"$label\"".",";
   
}
$str=substr($str,0,(strLen($str)-1)); // Removing the last char , from the string
echo "new Array($str)";

?>