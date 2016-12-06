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
 $sql = "SELECT edms_inventory_item.item_id , CONCAT(item_name, ' | ' , IF(alias!='',CONCAT(alias,' | '),'') , IF(mrp!='',CONCAT(mrp,' | '),'') , IF(item_code!='',CONCAT(item_code,' | '),''), IF(mfg_item_code!='NA',CONCAT(mfg_item_code,' | '),''), IF(edms_inventory_item.manufacturer_id IS NULL,'',manufacturer_name) ) as full_item_name FROM edms_inventory_item
		  LEFT OUTER JOIN edms_item_manufacturer
		  ON edms_inventory_item.manufacturer_id=edms_item_manufacturer.manufacturer_id 
		  LEFT OUTER JOIN edms_item_type
		  ON edms_inventory_item.item_type_id = edms_item_type.item_type_id
		  
		   WHERE inc_inventory=1 AND (item_name LIKE '%".$_REQUEST['term']."%' OR alias LIKE '%".$_REQUEST['term']."%' OR item_code LIKE '%".$_REQUEST['term']."%' OR mfg_item_code LIKE '%".$_REQUEST['term']."%') GROUP BY item_id ORDER BY item_name "; 
		   
$result=dbQuery($sql);
$resultArray=dbResultToArray($result);
foreach ($resultArray as $r) 
{
    $results[] = array('label' => htmlspecialchars_decode(utf8_encode($r['full_item_name'])));
}
echo json_encode($results); 

?>