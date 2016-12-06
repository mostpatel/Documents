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
$term_array = explode(" | ",$_REQUEST['term']);	
$_REQUEST['term'] = $term_array[0];
$trans_date = $term_array[1];
$stock_type = $term_array[2];

if($stock_type==0)
$stock_string ="1,3,5,7";
else if($stock_type==1)
$stock_string = "2,4,6,8";

$words_string=getsimilarWordsRegExString($_REQUEST['term']);
	if(isset($trans_date) && validateForNull($trans_date))
			{
		    $trans_date = str_replace('/', '-', $trans_date);
			$trans_date=date('Y-m-d',strtotime($trans_date));
			}	
$sql = "SELECT * FROM (SELECT edms_inventory_item.item_id , CONCAT(item_name, ' | ' , IF(alias!='',CONCAT(alias,' | '),'') , IF(mrp!='',CONCAT(mrp,' | '),'') , IF(item_code!='',CONCAT(item_code,' | '),''), IF(mfg_item_code!='NA',CONCAT(mfg_item_code,' | '),''), IF(edms_inventory_item.manufacturer_id IS NULL,'',manufacturer_name) ) as full_item_name, SUM(quantity) as sold_qty FROM edms_inventory_item
		  LEFT OUTER JOIN edms_item_manufacturer
		  ON edms_inventory_item.manufacturer_id=edms_item_manufacturer.manufacturer_id 
		  LEFT OUTER JOIN edms_item_type
		  ON edms_inventory_item.item_type_id = edms_item_type.item_type_id
		  LEFT OUTER JOIN edms_ac_sales_item
		  ON edms_inventory_item.item_id = edms_ac_sales_item.item_id
		   WHERE  ";
		   if($stock_type<2)
		   $sql=$sql." use_barcode=0 AND ";
		    if(defined('CUSTOMER_MULTI_COMPANY') && CUSTOMER_MULTI_COMPANY==0)
	  $sql=$sql."  our_company_id=$oc_id AND  ";
	  $sql=$sql." inc_inventory=1 AND (item_name LIKE '%".$_REQUEST['term']."%' OR alias LIKE '%".$_REQUEST['term']."%' OR item_code LIKE '%".$_REQUEST['term']."%' OR mfg_item_code LIKE '%".$_REQUEST['term']."%') GROUP BY item_id ORDER BY sold_qty DESC) temp1 ";
if(defined('ITEM_NAME_CORRECTION') && ITEM_NAME_CORRECTION==1)
{
$sql = $sql."UNION SELECT * FROM (SELECT edms_inventory_item.item_id , CONCAT(item_name, ' | ' , IF(alias!='',CONCAT(alias,' | '),'') , IF(mrp!='',CONCAT(mrp,' | '),'') , IF(item_code!='',CONCAT(item_code,' | '),''), IF(mfg_item_code!='NA',CONCAT(mfg_item_code,' | '),''), IF(edms_inventory_item.manufacturer_id IS NULL,'',manufacturer_name) ) as full_item_name, (SELECT SUM(quantity) FROM edms_ac_sales_item WHERE item_id = edms_inventory_item.item_id GROUP BY item_id) as sold_qty FROM edms_inventory_item
		  LEFT OUTER JOIN edms_item_manufacturer
		  ON edms_inventory_item.manufacturer_id=edms_item_manufacturer.manufacturer_id 
		  LEFT OUTER JOIN edms_item_type
		  ON edms_inventory_item.item_type_id = edms_item_type.item_type_id
		 
		   WHERE ";
		   if($stock_type<2)
		   $sql=$sql." use_barcode=0 AND ";
		    if(defined('CUSTOMER_MULTI_COMPANY') && CUSTOMER_MULTI_COMPANY==0)
	  $sql=$sql."  our_company_id=$oc_id AND  ";
	  $sql=$sql."  inc_inventory=1 AND (item_name REGEXP '".$words_string."' OR alias REGEXP  '".$words_string."' OR item_code REGEXP  '".$words_string."' OR mfg_item_code REGEXP  '".$words_string."') ORDER BY sold_qty DESC) temp2";			   
}
if($stock_type<2)
{
$sql=$sql." UNION ALL SELECT item_id,full_item_name,trans_type as sold_qty FROM (SELECT  edms_inventory_item.item_id , CONCAT(item_name, ' | ' , IF(alias!='',CONCAT(alias,' | '),'') , IF(mrp!='',CONCAT(mrp,' | '),'') , IF(item_code!='',CONCAT(item_code,' | '),''), IF(mfg_item_code!='NA',CONCAT(mfg_item_code,' | '),''), IF(edms_inventory_item.manufacturer_id IS NULL,'',CONCAT(manufacturer_name,' | ')), barcode) as full_item_name, ( SELECT trans_type FROM edms_barcode_transactions as inner_barcode_table WHERE edms_barcode_transactions.item_id = inner_barcode_table.item_id AND inner_barcode_table.barcode = edms_barcode_transactions.barcode  AND inner_barcode_table.oc_id = $oc_id  ORDER BY inner_barcode_table.trans_date DESC,inner_barcode_table.trans_date_added DESC LIMIT 0,1)  as  trans_type, MAX(trans_date) as trans_date, oc_id  FROM edms_barcode_transactions  INNER JOIN edms_inventory_item ON edms_inventory_item.item_id = edms_barcode_transactions.item_id LEFT OUTER JOIN edms_item_manufacturer
		  ON edms_inventory_item.manufacturer_id=edms_item_manufacturer.manufacturer_id  WHERE   oc_id = $oc_id AND (item_name LIKE '%".$_REQUEST['term']."%' OR alias LIKE '%".$_REQUEST['term']."%' OR item_code LIKE '%".$_REQUEST['term']."%' OR mfg_item_code LIKE '%".$_REQUEST['term']."%' OR barcode LIKE '%".$_REQUEST['term']."%') GROUP BY barcode,item_id HAVING trans_date <= '$trans_date 23:59:59' ORDER BY trans_date DESC,trans_date_added DESC) trans_table HAVING sold_qty IN ($stock_string)";
}

$result=dbQuery($sql);

$resultArray=dbResultToArray($result);
foreach ($resultArray as $r) 
{
    $results[] = array('id' => $r['item_id'],'label' => htmlspecialchars_decode($r['full_item_name']));
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