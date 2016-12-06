<?php require_once '../lib/cg.php';
require_once '../lib/bd.php';
require_once '../lib/common.php';
require_once '../lib/inventory-item-functions.php';
require_once '../lib/dictionary-functions.php';
?>
<?php
$oc_id=$_SESSION['edmsAdminSession']['oc_id'];
      
/* $sql = "SELECT edms_inventory_item.item_id , CONCAT(item_name, ' | ' , IF(alias!='',CONCAT(alias,' | '),'') , IF(mrp!='',CONCAT(mrp,' | '),'') , IF(item_code!='',CONCAT(item_code,' | '),''), IF(mfg_item_code!='NA',CONCAT(mfg_item_code,' | '),''), IF(edms_inventory_item.manufacturer_id IS NULL,'',manufacturer_name) ) as full_item_name, SUM(quantity) as sold_qty FROM edms_inventory_item
		  LEFT OUTER JOIN edms_item_manufacturer
		  ON edms_inventory_item.manufacturer_id=edms_item_manufacturer.manufacturer_id 
		  LEFT OUTER JOIN edms_item_type
		  ON edms_inventory_item.item_type_id = edms_item_type.item_type_id
		  LEFT OUTER JOIN edms_ac_sales_item
		  ON edms_inventory_item.item_id = edms_ac_sales_item.item_id
		   WHERE inc_inventory=1 AND (item_name LIKE '%".$_REQUEST['term']."%' OR alias LIKE '%".$_REQUEST['term']."%' OR item_code LIKE '%".$_REQUEST['term']."%' OR mfg_item_code LIKE '%".$_REQUEST['term']."%') GROUP BY item_id ORDER BY sold_qty DESC"; */
		   
if(validateForNull($_REQUEST['term']))
{
$words_string=getsimilarWordsRegExString($_REQUEST['term']);

$sql = "SELECT * FROM (SELECT edms_inventory_item.item_id , CONCAT(item_name, ' | ' , IF(alias!='',CONCAT(alias,' | '),'') , IF(mrp!='',CONCAT(mrp,' | '),'') , IF(item_code!='',CONCAT(item_code,' | '),''), IF(mfg_item_code!='NA',CONCAT(mfg_item_code,' | '),''), IF(edms_inventory_item.manufacturer_id IS NULL,'',manufacturer_name) ) as full_item_name, SUM(quantity) as sold_qty FROM edms_inventory_item
		  LEFT OUTER JOIN edms_item_manufacturer
		  ON edms_inventory_item.manufacturer_id=edms_item_manufacturer.manufacturer_id 
		  LEFT OUTER JOIN edms_item_type
		  ON edms_inventory_item.item_type_id = edms_item_type.item_type_id
		  LEFT OUTER JOIN edms_ac_sales_item
		  ON edms_inventory_item.item_id = edms_ac_sales_item.item_id
		   WHERE our_company_id = $oc_id AND inc_inventory=1 AND (item_name LIKE '%".$_REQUEST['term']."%' OR alias LIKE '%".$_REQUEST['term']."%' OR item_code LIKE '%".$_REQUEST['term']."%' OR mfg_item_code LIKE '%".$_REQUEST['term']."%') GROUP BY item_id ORDER BY sold_qty DESC) temp1 ";

$sql = $sql."UNION SELECT * FROM (SELECT edms_inventory_item.item_id , CONCAT(item_name, ' | ' , IF(alias!='',CONCAT(alias,' | '),'') , IF(mrp!='',CONCAT(mrp,' | '),'') , IF(item_code!='',CONCAT(item_code,' | '),''), IF(mfg_item_code!='NA',CONCAT(mfg_item_code,' | '),''), IF(edms_inventory_item.manufacturer_id IS NULL,'',manufacturer_name) ) as full_item_name, (SELECT SUM(quantity) FROM edms_ac_sales_item WHERE item_id = edms_inventory_item.item_id GROUP BY item_id) as sold_qty FROM edms_inventory_item
		  LEFT OUTER JOIN edms_item_manufacturer
		  ON edms_inventory_item.manufacturer_id=edms_item_manufacturer.manufacturer_id 
		  LEFT OUTER JOIN edms_item_type
		  ON edms_inventory_item.item_type_id = edms_item_type.item_type_id
		 
		   WHERE our_company_id = $oc_id AND inc_inventory=1 AND (item_name REGEXP '".$words_string."' OR alias REGEXP  '".$words_string."' OR item_code REGEXP  '".$words_string."' OR mfg_item_code REGEXP  '".$words_string."') ORDER BY sold_qty DESC) temp2";			   
	   	  
$result=dbQuery($sql);
$resultArray=dbResultToArray($result);
foreach ($resultArray as $r) 
{
    $results[] = array('id' => $r['item_id'],'label' => $r['full_item_name']);
}
echo json_encode($results); 
}
/*<?php require_once '../lib/cg.php';
require_once '../lib/bd.php';
require_once '../lib/common.php';
require_once '../lib/inventory-item-functions.php';
require_once '../lib/dictionary-functions.php';
?>
<?php
$oc_id=$_SESSION['edmsAdminSession']['oc_id'];
      
/* $sql = "SELECT edms_inventory_item.item_id , CONCAT(item_name, ' | ' , IF(alias!='',CONCAT(alias,' | '),'') , IF(mrp!='',CONCAT(mrp,' | '),'') , IF(item_code!='',CONCAT(item_code,' | '),''), IF(mfg_item_code!='NA',CONCAT(mfg_item_code,' | '),''), IF(edms_inventory_item.manufacturer_id IS NULL,'',manufacturer_name) ) as full_item_name, SUM(quantity) as sold_qty FROM edms_inventory_item
		  LEFT OUTER JOIN edms_item_manufacturer
		  ON edms_inventory_item.manufacturer_id=edms_item_manufacturer.manufacturer_id 
		  LEFT OUTER JOIN edms_item_type
		  ON edms_inventory_item.item_type_id = edms_item_type.item_type_id
		  LEFT OUTER JOIN edms_ac_sales_item
		  ON edms_inventory_item.item_id = edms_ac_sales_item.item_id
		   WHERE inc_inventory=1 AND (item_name LIKE '%".$_REQUEST['term']."%' OR alias LIKE '%".$_REQUEST['term']."%' OR item_code LIKE '%".$_REQUEST['term']."%' OR mfg_item_code LIKE '%".$_REQUEST['term']."%') GROUP BY item_id ORDER BY sold_qty DESC"; 
$_REQUEST['term']="quarter pi";		   
if(validateForNull($_REQUEST['term']))
{

    $words_string = '';
	$words_array = explode(' ',$_REQUEST['term']);
 for($i=0;$i<count($words_array);$i++)
			{
				$word = $words_array[$i];
				
				if(validateForNull($word) &&  strlen($word)>=3 && !checkForNumeric($word))
				{
						$soundex=getSoundexStringForWords($word);
						$words_string.$words_string=".*(SELECT GROUP_CONCAT(word SEPARATOR '|') FROM edms_dictionary_item WHERE soundex IN ($soundex)).*";
				}
				else
				{
					   $words_string=$words_string.".*($word).*";
				}
			}
			echo $words_string;
$sql = "SELECT * FROM (SELECT edms_inventory_item.item_id , CONCAT(item_name, ' | ' , IF(alias!='',CONCAT(alias,' | '),'') , IF(mrp!='',CONCAT(mrp,' | '),'') , IF(item_code!='',CONCAT(item_code,' | '),''), IF(mfg_item_code!='NA',CONCAT(mfg_item_code,' | '),''), IF(edms_inventory_item.manufacturer_id IS NULL,'',manufacturer_name) ) as full_item_name, SUM(quantity) as sold_qty FROM edms_inventory_item
		  LEFT OUTER JOIN edms_item_manufacturer
		  ON edms_inventory_item.manufacturer_id=edms_item_manufacturer.manufacturer_id 
		  LEFT OUTER JOIN edms_item_type
		  ON edms_inventory_item.item_type_id = edms_item_type.item_type_id
		  LEFT OUTER JOIN edms_ac_sales_item
		  ON edms_inventory_item.item_id = edms_ac_sales_item.item_id
		   WHERE inc_inventory=1 AND (item_name LIKE '%".$_REQUEST['term']."%' OR alias LIKE '%".$_REQUEST['term']."%' OR item_code LIKE '%".$_REQUEST['term']."%' OR mfg_item_code LIKE '%".$_REQUEST['term']."%') GROUP BY item_id ORDER BY sold_qty DESC) temp1 ";

$sql = $sql."UNION SELECT * FROM (SELECT edms_inventory_item.item_id , CONCAT(item_name, ' | ' , IF(alias!='',CONCAT(alias,' | '),'') , IF(mrp!='',CONCAT(mrp,' | '),'') , IF(item_code!='',CONCAT(item_code,' | '),''), IF(mfg_item_code!='NA',CONCAT(mfg_item_code,' | '),''), IF(edms_inventory_item.manufacturer_id IS NULL,'',manufacturer_name) ) as full_item_name, (SELECT SUM(quantity) FROM edms_ac_sales_item WHERE item_id = edms_inventory_item.item_id GROUP BY item_id) as sold_qty FROM edms_inventory_item
		  LEFT OUTER JOIN edms_item_manufacturer
		  ON edms_inventory_item.manufacturer_id=edms_item_manufacturer.manufacturer_id 
		  LEFT OUTER JOIN edms_item_type
		  ON edms_inventory_item.item_type_id = edms_item_type.item_type_id
		 
		   WHERE inc_inventory=1 AND (item_name REGEXP '".$words_string."' OR alias REGEXP  '".$words_string."' OR item_code REGEXP  '".$words_string."' OR mfg_item_code REGEXP  '".$words_string."') ORDER BY sold_qty DESC) temp2";			   
	   	  
$result=dbQuery($sql);
$resultArray=dbResultToArray($result);
foreach ($resultArray as $r) 
{
    $results[] = array('id' => $r['item_id'],'label' => $r['full_item_name']);
}
echo json_encode($results); 
}
?> */
?>