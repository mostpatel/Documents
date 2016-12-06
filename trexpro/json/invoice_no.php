<?php require_once '../lib/cg.php';
?>
<?php
$oc_id=$_SESSION['edmsAdminSession']['oc_id'];
$regno=$_REQUEST['term'];   
$sql = "SELECT invoice_no FROM edms_trip_memo,edms_ac_ledgers,edms_invoice,edms_invoice_trip_memo WHERE our_company_id=$oc_id AND invoice_no LIKE '%".$regno."%' 
       AND edms_ac_ledgers.ledger_id=edms_trip_memo.to_branch_ledger_id  AND edms_trip_memo.trip_memo_id=edms_invoice_trip_memo.trip_memo_id GROUP BY edms_invoice.invoice_id";
$result=dbQuery($sql);
$resultArray=dbResultToArray($result);
foreach ($resultArray as $r) 
{
    $results[] = array('label' => $r['invoice_no']);
}


	
echo json_encode($results);
?>