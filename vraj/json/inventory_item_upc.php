<?php require_once '../lib/cg.php';
require_once '../lib/bd.php';
require_once '../lib/common.php';
require_once '../lib/inventory-item-functions.php';
require_once '../lib/inventory-item-barcode-functions.php';
require_once '../lib/dictionary-functions.php';
?>
<?php
$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
$curent_companny = getCurrentCompanyForUser($admin_id);
$oc_id = $curent_companny[0];
if(validateForNull($_REQUEST['term']))
{
$search=$_REQUEST['term'];
$search = clean_data($search);
$sql = "SELECT * FROM   edms_inventory_item
 WHERE mfg_item_code LIKE '%".$search."%'";		  
$result=dbQuery($sql);
$resultArray=dbResultToArray($result);
foreach ($resultArray as $r) 
{
    $results[] = array('id' => $r['item_id'],'label' => htmlspecialchars_decode($r['mfg_item_code']));
}
echo json_encode($results); 
}

?>