<?php 
require_once("cg.php");
require_once("common.php");
require_once("city-functions.php");
require_once("area-functions.php");
require_once("tax-functions.php");
require_once("item-type-functions.php");
require_once("account-head-functions.php");
require_once("customer-functions.php");
require_once("account-period-functions.php");
require_once("account-combined-agency-functions.php");
require_once("our-company-function.php");
require_once("dictionary-functions.php");
require_once("common.php");
require_once("bd.php");


function listInventoryItems(){
	
	try
	{
		$our_company_id=$_SESSION['edmsAdminSession']['oc_id'];
		$sql="SELECT item_id,item_name, alias, item_code, CONCAT(item_name, ' | ' , IF(alias!='',CONCAT(alias,' | '),'')  , IF(mrp!='',CONCAT(mrp,' | '),'') , IF(item_code!='',CONCAT(item_code,' | '),''), IF(mfg_item_code!='NA',CONCAT(mfg_item_code,' | '),''), IF(edms_inventory_item.manufacturer_id IS NULL,'',manufacturer_name) ) as full_item_name  , edms_item_type.item_type_id , IF(edms_inventory_item.item_type_id IS NULL,'NA',item_type) as item_type, min_quantity_purchase,  edms_inventory_item.item_unit_id, unit_name,edms_item_manufacturer.manufacturer_id, IF(edms_inventory_item.manufacturer_id IS NULL,'NA',manufacturer_name) as manufacturer_name, mfg_item_code, dealer_price, mrp, tax_group_id, opening_quantity,opening_rate,opening_godown_id,remarks,  edms_inventory_item.created_by, edms_inventory_item.last_updated_by, edms_inventory_item.date_added, edms_inventory_item.date_modified
		  FROM edms_inventory_item 
		  LEFT OUTER JOIN edms_item_manufacturer
		  ON edms_inventory_item.manufacturer_id=edms_item_manufacturer.manufacturer_id
		  LEFT OUTER JOIN edms_item_type
		  ON edms_inventory_item.item_type_id = edms_item_type.item_type_id
		  LEFT OUTER JOIN edms_item_unit
		  ON edms_inventory_item.item_unit_id = edms_item_unit.item_unit_id WHERE inc_inventory=1 AND our_company_id = $our_company_id";
		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		
		if(dbNumRows($result)>0)
		return $resultArray; 
		else
		return false;
	}
	catch(Exception $e)
	{
	}
	
}

function getItemIdFromFullItemName($item_full_name)
{
	$item_full_name = clean_data($item_full_name);
	
	if(validateForNull($item_full_name))
	{
		$sql="SELECT item_id, TRIM(CONCAT(item_name, ' | ' , IF(alias!='',CONCAT(alias,' | '),'') , IF(mrp!='',CONCAT(mrp,' | '),'') , IF(item_code!='',CONCAT(item_code,' | '),''), IF(mfg_item_code!='NA',CONCAT(mfg_item_code,' | '),''), IF(edms_inventory_item.manufacturer_id IS NULL,'',manufacturer_name))) as full_item_name 
		  FROM edms_inventory_item LEFT OUTER JOIN edms_item_manufacturer
		  ON edms_inventory_item.manufacturer_id=edms_item_manufacturer.manufacturer_id
		  LEFT OUTER JOIN edms_item_type
		  ON edms_inventory_item.item_type_id = edms_item_type.item_type_id HAVING full_item_name = '$item_full_name' ";
		 
		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		
		if(dbNumRows($result)>0)
		return $resultArray[0][0]; 
		else
		return false;
	}
	return false;
	
}

function getFullItemNameFromItemId($item_id)
{

	if(checkForNumeric($item_id))
	{
		$sql="SELECT  CONCAT(item_name, ' | ' , IF(alias!='',CONCAT(alias,' | '),'')  , IF(mrp!='',CONCAT(mrp,' | '),'') , IF(item_code!='',CONCAT(item_code,' | '),''), IF(mfg_item_code!='NA',CONCAT(mfg_item_code,' | '),''), IF(edms_inventory_item.manufacturer_id IS NULL,'',manufacturer_name) ) as full_item_name 
		  FROM edms_inventory_item LEFT OUTER JOIN edms_item_manufacturer
		  ON edms_inventory_item.manufacturer_id=edms_item_manufacturer.manufacturer_id
		  LEFT OUTER JOIN edms_item_type
		  ON edms_inventory_item.item_type_id = edms_item_type.item_type_id WHERE item_id = $item_id";
		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		
		if(dbNumRows($result)>0)
		return $resultArray[0][0]; 
		else
		return false;
	}
	return false;
	
}


function listInventoryItemsForStockCalculation(){
	
	try
	{
		$our_company_id=$_SESSION['edmsAdminSession']['oc_id'];
	
		$sql="SELECT edms_inventory_item.item_id,item_name, alias, item_code, CONCAT(item_name, ' | ' , IF(alias!='',CONCAT(alias,' | '),'')  , IF(mrp!='',CONCAT(mrp,' | '),'') , IF(item_code!='',CONCAT(item_code,' | '),''), IF(mfg_item_code!='NA',CONCAT(mfg_item_code,' | '),''), IF(edms_inventory_item.manufacturer_id IS NULL,'',manufacturer_name) ) as full_item_name  , edms_item_type.item_type_id , IF(edms_inventory_item.item_type_id IS NULL,'NA',item_type) as item_type, min_quantity_purchase,  item_unit_id, edms_item_manufacturer.manufacturer_id, IF(edms_inventory_item.manufacturer_id IS NULL,'NA',manufacturer_name) as manufacturer_name, mfg_item_code, dealer_price, mrp, tax_group_id, opening_quantity,opening_rate,opening_godown_id,remarks,  edms_inventory_item.created_by, edms_inventory_item.last_updated_by, edms_inventory_item.date_added, edms_inventory_item.date_modified, SUM(edms_ac_purchase_item.net_amount) as total_purchase_amount, SUM(edms_ac_debit_note_item.net_amount) as total_debit_note_amount,  SUM(edms_ac_sales_item.net_amount) as total_sales_amount ,  SUM(edms_ac_credit_note_item.net_amount) as total_credit_note_amount 
		  FROM edms_inventory_item LEFT OUTER JOIN edms_item_manufacturer
		  ON edms_inventory_item.manufacturer_id=edms_item_manufacturer.manufacturer_id
		  LEFT OUTER JOIN edms_item_type
		  ON edms_inventory_item.item_type_id = edms_item_type.item_type_id
		  LEFT OUTER JOIN edms_ac_purchase_item
		  ON edms_inventory_item.item_id = edms_ac_purchase_item.item_id
		  LEFT OUTER JOIN edms_ac_debit_note_item
		  ON edms_inventory_item.item_id = edms_ac_debit_note_item.item_id
		   LEFT OUTER JOIN edms_ac_sales_item
		  ON edms_inventory_item.item_id = edms_ac_sales_item.item_id
		   LEFT OUTER JOIN edms_ac_credit_note_item
		  ON edms_inventory_item.item_id = edms_ac_credit_note_item.item_id WHERE inc_inventory=1 AND our_company_id = $our_company_id  GROUP BY edms_inventory_item.item_id HAVING (opening_quantity>0 OR total_purchase_amount >0 OR total_debit_note_amount >0 OR total_sales_amount>0 OR  total_credit_note_amount >0 )";
		 
		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		
		if(dbNumRows($result)>0)
		return $resultArray; 
		else
		return false;
	}
	catch(Exception $e)
	{
	}
	
}

function listNonStockItems(){
	
	try
	{
		$our_company_id=$_SESSION['edmsAdminSession']['oc_id'];
		$sql="SELECT item_id,item_name, alias, item_code, CONCAT(item_name, ' | ' , IF(alias!='',CONCAT(alias,' | '),'') , IF(mrp!='',CONCAT(mrp,' | '),'') , IF(item_code!='',CONCAT(item_code,' | '),''), IF(mfg_item_code!='NA',CONCAT(mfg_item_code,' | '),''), IF(edms_inventory_item.manufacturer_id IS NULL,'',manufacturer_name) ) as full_item_name  , edms_item_type.item_type_id , IF(edms_inventory_item.item_type_id IS NULL,'NA',item_type) as item_type, min_quantity_purchase,  item_unit_id, edms_item_manufacturer.manufacturer_id, IF(edms_inventory_item.manufacturer_id IS NULL,'NA',manufacturer_name) as manufacturer_name, mfg_item_code, dealer_price, mrp, tax_group_id, opening_quantity,opening_rate,opening_godown_id,remarks,  edms_inventory_item.created_by, edms_inventory_item.last_updated_by, edms_inventory_item.date_added, edms_inventory_item.date_modified
		  FROM edms_inventory_item LEFT OUTER JOIN edms_item_manufacturer
		  ON edms_inventory_item.manufacturer_id=edms_item_manufacturer.manufacturer_id
		  LEFT OUTER JOIN edms_item_type
		  ON edms_inventory_item.item_type_id = edms_item_type.item_type_id WHERE inc_inventory=0 AND our_company_id = $our_company_id";
		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		
		if(dbNumRows($result)>0)
		return $resultArray; 
		else
		return false;
	}
	catch(Exception $e)
	{
	}
	
}



function generateItemCode()
{
	$our_company_id=$_SESSION['edmsAdminSession']['oc_id'];
	$item_counter=getItemCodeCounterForOCID($our_company_id);
	
	$item_code = ITEM_CODE_PREFIX.$item_counter;
	
	if(checkforDuplicateItemCode($item_code))
	{
		incrementItemCodeCounterForOCID($our_company_id);
		return generateItemCode();
	}
	else
	return $item_code;
}

function checkforDuplicateItemCode($item_code,$id=false)
{
	if(validateForNull($item_code))
	{
	$our_company_id=$_SESSION['edmsAdminSession']['oc_id'];	
	$sql="SELECT item_code FROM edms_inventory_item WHERE item_code = '$item_code' AND our_company_id = $our_company_id";
	if($id && checkForNumeric($id))
	$sql=$sql." AND item_id != $id";
	$result = dbQuery($sql);
	if(dbNumRows($result)>0)
	return true;
	else
	return false;
	}
	return false;
}

function checkforDuplicateMFGItemCode($mfg_item_code,$manufacturer_id)
{
	if($mfg_item_code=="NA" || $mfg_item_code=="na" || $mfg_item_code=="Na")
	return false;
	if(validateForNull($mfg_item_code) && checkForNumeric($manufacturer_id))
	{ 
	$our_company_id=$_SESSION['edmsAdminSession']['oc_id'];
	$sql="SELECT mfg_item_code FROM edms_inventory_item WHERE mfg_item_code = '$mfg_item_code' AND manufacturer_id = $manufacturer_id AND our_company_id = $our_company_id";
	$result = dbQuery($sql);
	if(dbNumRows($result)>0)
	return true;
	}
	return false;
}

function insertInventoryItem($name,$alias,$item_code,$item_unit_id,$manufacturer_id,$mfg_item_code,$dealer_price,$mrp,$opening_quantity,$opening_rate,$remarks,$item_type_id,$min_quantity,$opening_godown_id,$tax_group_id){
	
	try
	{
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$our_company_id=$_SESSION['edmsAdminSession']['oc_id'];
		
		
	/*	if(!(checkForNumeric($oc_id)))
		{		
			$current_company=getCurrentCompanyForUser($admin_id);
			if($current_company[1]==0) // if current company is our company
			{
			$oc_id=$current_company[0];	
			}
			else if($current_company[1]==2) // if combined agency
			{
				$agency_oc_ids=getAgencyOCForCombinedAgency($current_company[0]);
				if(checkForNumeric($agency_oc_ids[1][0]))
				{	
				$oc_id=$agency_oc_ids[1][0];
				}
			}	
		} */
		$name=clean_data($name);
		$name = ucwords(strtolower($name));
		$alias=clean_data($alias);
		$alias = ucwords(strtolower($alias));
		$item_code = strtoupper(clean_data($item_code));
		$item_unit_id=clean_data($item_unit_id);
		$manufacturer_id = clean_data($manufacturer_id);
		$mfg_item_code = clean_data($mfg_item_code);
		$dealer_price = clean_data($dealer_price);
		$mrp = clean_data($mrp);
		$opening_quantity = clean_data($opening_quantity);
		$opening_rate = clean_data($opening_rate);
		$opening_godown_id = clean_data($opening_godown_id);
		$tax_group_id = clean_data($tax_group_id);
		
		if(!checkForNumeric($opening_quantity))
		$opening_quantity=0;
		
		if(!checkForNumeric($tax_group_id) || (checkForNumeric($tax_group_id) && $tax_group_id<=0))
		$tax_group_id="NULL";
		
		if(!checkForNumeric($opening_rate))
		$opening_rate=0;
		
		if(!checkForNumeric($dealer_price))
		$dealer_price=0;
		
		if(!checkForNumeric($min_quantity))
		$min_quantity=0;
		
		if(!checkForNumeric($mrp))
		$mrp=0;
		
		if(!checkForNumeric($manufacturer_id) || $manufacturer_id==-1)
		$manufacturer_id='NULL';
		
		if(!checkForNumeric($item_unit_id) || $item_unit_id==-1)
		$item_unit_id='NULL';
		
		
		
		if(!validateForNull($alias))
		{
			$alias="";
		}
		if(!validateForNull($opening_godown_id))
		{
			$opening_godown_id="NULL";
		}
		if(!validateForNull($item_code))
		{
		$item_code=generateItemCode();
		}
		if(!validateForNull($mfg_item_code))
		{
			$mfg_item_code='NA';
		}	
				
		if(!validateForNull($remarks))
		{
			$remarks="NA";
		}	
			
		$item_code =strtoupper($item_code);	
		
		
		if(validateForNull($name,$item_code,$item_type_id,$tax_group_id,$opening_godown_id) && checkForNumeric($min_quantity,$item_type_id) && !checkforDuplicateItemCode($item_code) && !checkforDuplicateMFGItemCode($mfg_item_code,$manufacturer_id) && $item_type_id>0)
			{
			
		
			$sql="INSERT INTO edms_inventory_item
					(item_name, alias, item_code,  item_unit_id, item_type_id, min_quantity_purchase, manufacturer_id, mfg_item_code, dealer_price, mrp,tax_group_id, opening_quantity,opening_rate,opening_godown_id, our_company_id, remarks,  created_by, last_updated_by, date_added, date_modified)
					VALUES
					('$name','$alias','$item_code',$item_unit_id,$item_type_id, $min_quantity,$manufacturer_id,'$mfg_item_code',$dealer_price,$mrp,$tax_group_id,$opening_quantity,$opening_rate, $opening_godown_id,$our_company_id,'$remarks',$admin_id,$admin_id,NOW(),NOW())";
			
			dbQuery($sql);
			$item_id=dbInsertId();
			insertDictionaryWords($name);
			return $item_id;
			}
		else 
		{
			return "error";
			}	
	}
	catch(Exception $e)
	{
	}
	
}

function deleteInventoryItem($item_id)
{
	
	if(checkForNumeric($item_id) && !checkifItemInUse($item_id))
	{
		$sql="DELETE FROM edms_inventory_item WHERE item_id=$item_id";
		dbQuery($sql);
		return "success";
		}
}
	
function checkifItemInUse($item_id)
{
	if(checkForNumeric($item_id))
	{
		$sql="SELECT item_id FROM edms_ac_purchase_item WHERE item_id = $item_id";
		$result=dbQuery($sql);
		if(dbNumRows($result)>0)
		return true;
		
		$sql="SELECT item_id FROM edms_ac_sales_item WHERE item_id = $item_id";
		$result=dbQuery($sql);
		if(dbNumRows($result)>0)
		return true;
		
		$sql="SELECT item_id FROM edms_ac_debit_note_item WHERE item_id = $item_id";
		$result=dbQuery($sql);
		if(dbNumRows($result)>0)
		return true;
		
		$sql="SELECT item_id FROM edms_ac_credit_note_item WHERE item_id = $item_id";
		$result=dbQuery($sql);
		if(dbNumRows($result)>0)
		return true;
		
		
		return false;
		
	}	
}		

function updateInventoryItem($id,$name,$alias,$item_code,$item_unit_id,$manufacturer_id,$mfg_item_code,$dealer_price,$mrp,$opening_quantity,$opening_rate,$remarks,$item_type_id,$min_quantity,$opening_godown_id,$tax_group_id){
	
	try
	{
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$our_company_id=$_SESSION['edmsAdminSession']['oc_id'];
		
		
	/*	if(!(checkForNumeric($oc_id)))
		{		
			$current_company=getCurrentCompanyForUser($admin_id);
			if($current_company[1]==0) // if current company is our company
			{
			$oc_id=$current_company[0];	
			}
			else if($current_company[1]==2) // if combined agency
			{
				$agency_oc_ids=getAgencyOCForCombinedAgency($current_company[0]);
				if(checkForNumeric($agency_oc_ids[1][0]))
				{	
				$oc_id=$agency_oc_ids[1][0];
				}
			}	
		} */
		$name=clean_data($name);
		$name = ucwords(strtolower($name));
		$alias=clean_data($alias);
		$alias = ucwords(strtolower($alias));
		$item_code = strtoupper(clean_data($item_code));
		$item_unit_id=clean_data($item_unit_id);
		$manufacturer_id = clean_data($manufacturer_id);
		$mfg_item_code = clean_data($mfg_item_code);
		$dealer_price = clean_data($dealer_price);
		$mrp = clean_data($mrp);
		$opening_quantity = clean_data($opening_quantity);
		$opening_rate = clean_data($opening_rate);
		$opening_godown_id = clean_data($opening_godown_id);
		
		$tax_group_id = clean_data($tax_group_id);
		
		if(!checkForNumeric($opening_quantity))
		$opening_quantity=0;
		
		if(!checkForNumeric($tax_group_id) || (checkForNumeric($tax_group_id) && $tax_group_id<=0))
		$tax_group_id="NULL";
		
		if(!checkForNumeric($opening_rate))
		$opening_rate=0;
		
		if(!checkForNumeric($dealer_price))
		$dealer_price=0;
		
		if(!checkForNumeric($mrp))
		$mrp=0;
		
		if(!checkForNumeric($min_quantity))
		$min_quantity=0;
		
		if(!checkForNumeric($manufacturer_id) || $manufacturer_id==-1)
		$manufacturer_id='NULL';
		
		if(!checkForNumeric($item_unit_id) || $item_unit_id==-1)
		$item_unit_id='NULL';
		
		if(!checkForNumeric($item_type_id) || $item_type_id==-1)
		$item_type_id='NULL';
		
		if(!validateForNull($alias))
		{
			$alias="";
		}
		if(!validateForNull($opening_godown_id))
		{
			$opening_godown_id="NULL";
		}
		if(!validateForNull($item_code))
		{
		$item_code=generateItemCode();
		}
		
		if(!validateForNull($mfg_item_code))
		{
			$mfg_item_code='NA';
			}	
		
		if(!validateForNull($remarks))
		{
			$remarks="NA";
			}
			
			
		if(validateForNull($name,$item_code,$item_type_id,$tax_group_id,$opening_godown_id) && checkForNumeric($min_quantity,$id) && !checkforDuplicateItemCode($item_code,$id))
			{
			
			$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
			$sql="UPDATE edms_inventory_item
					SET item_name = '$name', alias = '$alias', item_code='$item_code', item_unit_id = $item_unit_id, manufacturer_id = $manufacturer_id, mfg_item_code= '$mfg_item_code' , dealer_price=$dealer_price, mrp=$mrp, tax_group_id= $tax_group_id,opening_quantity=$opening_quantity,opening_rate=$opening_rate, opening_godown_id = $opening_godown_id, remarks='$remarks' , item_type_id = $item_type_id, min_quantity_purchase = $min_quantity,last_updated_by=$admin_id, date_modified=NOW()
					WHERE item_id=$id";
				
			dbQuery($sql);
		    insertDictionaryWords($name);
			return "success";
			}
		else
		{
			return "error";
			}	
		
	}
	catch(Exception $e)
	{
	}
	
}	

function getInventoryItemById($id){
	
	try
	{
		
		$sql="SELECT item_id,item_name, alias, item_code, edms_item_type.item_type_id , IF(edms_inventory_item.item_type_id IS NULL,'NA',item_type) as item_type, min_quantity_purchase,  item_unit_id, edms_item_manufacturer.manufacturer_id, IF(edms_inventory_item.manufacturer_id IS NULL,'NA',manufacturer_name) as manufacturer_name, mfg_item_code, dealer_price, mrp, tax_group_id, opening_quantity,opening_rate,opening_godown_id, our_company_id,remarks,  edms_inventory_item.created_by, edms_inventory_item.last_updated_by, edms_inventory_item.date_added, edms_inventory_item.date_modified
		  FROM edms_inventory_item LEFT OUTER JOIN edms_item_manufacturer
		  ON edms_inventory_item.manufacturer_id=edms_item_manufacturer.manufacturer_id
		  LEFT OUTER JOIN edms_item_type
		  ON edms_inventory_item.item_type_id = edms_item_type.item_type_id WHERE item_id=$id";
		  
		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0]; 
		else
		return false;
	}
	catch(Exception $e)
	{
	}
	
}

function getItemNameFromItemId($id)
{
try
	{
		$sql="SELECT  item_name
		  FROM edms_inventory_item
		  WHERE item_id=$id";
		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0]; 
		else
		return false;
	}
	catch(Exception $e)
	{
	}	
}

function getMFgCodeFromItemId($id)
{
try
	{
		$sql="SELECT  mfg_item_code
		  FROM edms_inventory_item
		  WHERE item_id=$id";
		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0]; 
		else
		return false;
	}
	catch(Exception $e)
	{
	}	
}

function getItemIdFromMfgCOde($mfg_item_code,$mfg_id)
{
try
	{if(validateForNull($mfg_item_code))
	{
		$sql="SELECT  item_id
		  FROM edms_inventory_item
		  WHERE mfg_item_code='$mfg_item_code' AND manufacturer_id = $mfg_id";
		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0]; 
		else
		return false;
	}
	return false;
	}
	catch(Exception $e)
	{
	}	
}
	
function getItemCodeFromItemId($id)
{
try
	{
		$sql="SELECT  item_code
		  FROM edms_inventory_item
		  WHERE item_id=$id";
		$result=dbQuery($sql);	 
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0]; 
		else
		return false;
	}
	catch(Exception $e)
	{
	}	
}
		
function getOpeningBalanceForItem($id) // returns array 1: opening balance, 2: balance type credit(1) or debit(0)
{
	
		if(checkForNumeric($id))
		{
		$ledger=getInventoryItemById($id);
		return array($ledger['opening_quantity']*$ledger['opening_rate'],$ledger['opening_quantity'],$ledger['opening_rate'],$ledger['opening_godown_id']);
		}
		return false;
	
	}
	
function getOpeningBalanceForItemArray($id_array) // returns array 1: opening balance, 2: balance type credit(1) or debit(0)
{
	if(is_array($id_array) && checkForNumeric($id_array[0]))
	{
		$ids=implode(',',$id_array);
		$sql="SELECT SUM(opening_rate*opening_quantity) AS opening_balance
		      FROM edms_inventory_item
			  WHERE item_id IN (".$ids.")";
		$result=dbQuery($sql);
	    $result_array=dbResultToArray($result);
		}
		return $result_array[0][0];
			
}

function updateItemNames()
{
	$items=listInventoryItems();
	foreach($items as $item)
	{
		$item_name = $item['item_name'];
		$item_id = $item['item_id'];
		$replace_array=array("&amp;","-","quot;","(",")","[","]");
		$item_name=str_replace($replace_array," ",$item_name);
		$sql="UPDATE edms_inventory_item SET item_name = '$item_name' WHERE item_id = $item_id";
		dbQuery();
	}
}		

function mergeMultipleItemsIntoOne($item_id_array,$new_item_id)
{
	$item_id_string = implode(",",$item_id_array);
	if(count($item_id_array)>1 && checkForNumeric($new_item_id))
	{
		$sql="UPDATE edms_ac_debit_note_item SET item_id  = $new_item_id WHERE item_id IN ($item_id_string)";
		dbQuery($sql);
		
		$sql="UPDATE edms_ac_credit_note_item SET item_id  = $new_item_id WHERE item_id IN ($item_id_string)";
		dbQuery($sql);
		$sql="UPDATE edms_ac_purchase_item SET item_id  = $new_item_id WHERE item_id IN ($item_id_string)";
		dbQuery($sql);
		$sql="UPDATE edms_ac_sales_item SET item_id  = $new_item_id WHERE item_id IN ($item_id_string)";
		dbQuery($sql);
		$total_opening_balance = 0;
		$total_opening_quantity = 0;
		
		
		foreach($item_id_array as $item_id)
		{
			$opening_balance_array=getOpeningBalanceForItem($item_id);
			$total_opening_balance = $total_opening_balance + $opening_balance_array[0];
			$total_opening_quantity = $total_opening_quantity + $opening_balance_array[1];
		}
		
		if(!in_array($new_item_id,$item_id_array))
		{
			$opening_balance_array=getOpeningBalanceForItem($new_item_id);
			$total_opening_balance = $total_opening_balance + $opening_balance_array[0];
			$total_opening_quantity = $total_opening_quantity + $opening_balance_array[1];
			
		}
		
		$total_opening_rate = $total_opening_balance / $total_opening_quantity;
		if($total_opening_rate<0)
		$total_opening_rate = -$total_opening_rate;
		
		$sql="UPDATE edms_inventory_item SET opening_quantity = $total_opening_quantity , opening_rate = $total_opening_rate WHERE item_id = $new_item_id";
		dbQuery($sql);
		
		foreach($item_id_array as $item_id)
		{
			if($item_id!=$new_item_id)
			deleteInventoryItem($item_id);
			
		}
	return "success";
	}
	
	
}
					
?>