<?php require_once '../lib/cg.php';
?>
<?php
$oc_id=$_SESSION['edmsAdminSession']['oc_id'];

$regno=$_REQUEST['term'];   
$sql = "SELECT trip_memo_no FROM edms_trip_memo,edms_ac_ledgers WHERE our_company_id=$oc_id AND trip_memo_no LIKE '%".$regno."%' 
       AND edms_ac_ledgers.ledger_id=edms_trip_memo.to_branch_ledger_id";
$result=dbQuery($sql);
$resultArray=dbResultToArray($result);
foreach ($resultArray as $r) 
{
    $results[] = array('label' => $r['trip_memo_no']);
}


	
echo json_encode($results);
?>