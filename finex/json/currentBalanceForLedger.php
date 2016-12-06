<?php require_once '../lib/cg.php';
require_once '../lib/common.php';
require_once '../lib/file-functions.php';
require_once '../lib/account-functions.php';
?>
<?php
$oc_id=$_SESSION['adminSession']['oc_id'];
$ledger_id = $_GET['id'];
if(is_numeric($ledger_id))
$ledger_id = "L".$ledger_id;
$date = $_GET['date'];
$amount = $_GET['amount'];
$debitcredit = $_GET['dc'];
if(!is_numeric($amount))
$amount=0;


$file_no=clean_data($file_no);  
$date=getNextDate($date);
$net_amount=getOpeningBalanceForLedgerForDate($ledger_id,date('d/m/Y',strtotime($date)));

if($net_amount<0 && $debitcredit==0)
$net_amount = $net_amount + $amount;
else if($net_amount<0 && $debitcredit==1)
$net_amount = $net_amount - $amount;
else if($net_amount>0 && $debitcredit==0)
$net_amount = $net_amount + $amount;
else if($net_amount>0 && $debitcredit==1)
$net_amount = $net_amount - $amount;
if($net_amount<0)
{
echo -$net_amount." CR"; 
}
else
{
echo $net_amount." DR"; 	
}
?>