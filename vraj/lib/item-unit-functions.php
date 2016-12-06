<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("inventory-item-functions.php");
require_once("common.php");
require_once("bd.php");
		
function listItemUnits(){
	
	try
	{
		$sql="SELECT item_unit_id, unit_name
		      FROM edms_item_unit
			  ORDER BY unit_name";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		return $resultArray;	  
	}
	catch(Exception $e)
	{
	}
	
}	

function getNumberOfItemUnits()
{
	$sql="SELECT count(item_unit_id)
		      FROM edms_item_unit
			  ORDER BY unit_name";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		return $resultArray[0][0];	
	
	}
	
function getBaseUnitForItemId($item_id)
{
	if(checkForNumeric($item_id))
	{
		$sql = "SELECT item_unit_id FROM edms_inventory_item WHERE item_id = $item_id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		return $resultArray[0][0];	
		}
	
}	
	
function getNotAvailableItemUnit()
{
	return insertItemUnit('Not Applicable');
}	
function insertItemUnit($unit_name){
	
	try
	{
		$unit_name=clean_data($unit_name);
		$unit_name = ucwords(strtolower($unit_name));
		$duplicate = checkForDuplicateItemUnit($unit_name);
		if(validateForNull($unit_name) && !$duplicate)
		{
		$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
		$sql="INSERT INTO edms_item_unit
		      (unit_name, created_by, last_updated_by, date_added, date_modified)
			  VALUES
			  ('$unit_name', $admin_id, $admin_id, NOW(), NOW())";
		dbQuery($sql);	  
		return dbInsertId();
		}
		else if(is_numeric($duplicate))
		return $duplicate;
		else
		{
			return "error";
			}
	}
	catch(Exception $e)
	{
	}
	
}	

function deleteItemUnit($id){
	
	try
	{
		if(checkForNumeric($id) && !checkIfItemUnitInUse($id))
		{
		$sql="DELETE FROM edms_item_unit
		      WHERE item_unit_id=$id";
		dbQuery($sql);
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

function updateItemUnit($id,$type){
	
	try
	{
		$type=clean_data($type);
		$type = ucwords(strtolower($type));
		if(checkForNumeric($id) && validateForNull($type) && !checkForDuplicateItemUnit($type,$id))
		{
			
		$sql="UPDATE edms_item_unit
		      SET unit_name='$type'
			  WHERE item_unit_id=$id";
		dbQuery($sql);	  
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

function getItemUnitById($id){
	
	try
	{
		$sql="SELECT item_unit_id, unit_name
		      FROM edms_item_unit
			  WHERE item_unit_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0];	 
	}
	catch(Exception $e)
	{
	}
	
}	
function getItemUnitNameById($id){
	
	try
	{
		$sql="SELECT item_unit_id, unit_name
		      FROM edms_item_unit
			  WHERE item_unit_id=$id";
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][1];	 
	}
	catch(Exception $e)
	{
	}
	
}

function getUnitsForItemId($id)
{
	if(checkForNumeric($id))
	{
		$sql="SELECT edms_rel_item_unit.item_unit_id, unit_name FROM edms_rel_item_unit, edms_item_unit WHERE item_id = $id AND edms_rel_item_unit.item_unit_id = edms_item_unit.item_unit_id
		UNION 
		SELECT edms_rel_item_unit.base_unit_id, unit_name FROM edms_rel_item_unit, edms_item_unit WHERE item_id = $id AND edms_rel_item_unit.base_unit_id = edms_item_unit.item_unit_id
		";
		
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		
		if(dbNumRows($result)>0)
		return $resultArray;	
		else
		{
			$item=getInventoryItemById($id);
			$item_unit = getItemUnitById($item['item_unit_id']);
			$result=array();
			$result[]=$item_unit;
			return $result;
		} 
	}
}	

function checkForDuplicateItemUnit($unit_name,$id=false)
{
	    if(validateForNull($unit_name))
		{
		$sql="SELECT item_unit_id
		      FROM edms_item_unit
			  WHERE unit_name='$unit_name'";
		if($id==false)
		$sql=$sql."";
		else
		$sql=$sql." AND item_unit_id!=$id";		  	  
		$result=dbQuery($sql);
		$resultArray=dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0];
		else
		return false;
		}
	}	
function checkIfItemUnitInUse($id)
{
	if(checkForNumeric($id))
	{
	$sql="SELECT item_unit_id FROM
			edms_inventory_item
			WHERE item_unit_id=$id";
	$result=dbQuery($sql);
	if(dbNumRows($result)>0)
	return true;
	else
	return false;		
	}
	
}

function insertRelItemUnitForItemArray($item_id_array,$base_unit_id,$unit_id,$rel)
{
	foreach($item_id_array as $item_id)
	{	
		insertRelItemUnitForItem($item_id,$base_unit_id,$unit_id,$rel);
	}
	return "success";
	
}


function insertRelItemUnitForItem($item_id,$base_unit_id,$unit_id,$rel)
{
	$duplicate = checkForDuplicateRelItemUnit($item_id,$base_unit_id,$unit_id);
	
	if(checkForNumeric($item_id,$base_unit_id,$unit_id,$rel) && $item_id>0 && $base_unit_id>0 && $unit_id>0 && $rel>0 && !$duplicate && $base_unit_id!=$unit_id)
	{
		$sql="INSERT INTO edms_rel_item_unit (item_id,base_unit_id,item_unit_id,base_unit_rel) VALUES ($item_id,$base_unit_id,$unit_id,$rel)";
		dbQuery($sql);
		return dbInsertId();
	}
	else if($duplicate)
	{
		$in_use = checkforRelItemUnitInUse($item_id,$unit_id);
		
		if(!$in_use)
		{
		  	updateRelItemUnitForItem($duplicate,$item_id,$base_unit_id,$unit_id,$rel);
			
		}
		
	}
	return false;
}
function updateRelItemUnitForItem($rel_item_unit_id,$item_id,$base_unit_id,$unit_id,$rel)
{
	
	$duplicate = checkForDuplicateRelItemUnit($item_id,$base_unit_id,$unit_id,$rel_item_unit_id);
	$in_use = checkforRelItemUnitInUse($item_id,$unit_id);
	
	if(checkForNumeric($rel_item_unit_id,$item_id,$base_unit_id,$unit_id,$rel) && $item_id>0 && $base_unit_id>0 && $unit_id>0 && $rel>0 && !$duplicate && !$in_use && $base_unit_id!=$unit_id)
	{
		$sql="UPDATE edms_rel_item_unit SET base_unit_rel = $rel WHERE item_id = $item_id AND base_unit_id = $base_unit_id AND item_unit_id = $unit_id AND rel_item_unit_id = $rel_item_unit_id";
		dbQuery($sql);
		return "success";
	}
	return false;
}
 
function checkForDuplicateRelItemUnit($item_id,$base_unit_id,$unit_id,$rel_item_unit_id=false)
{
	if(checkForNumeric($item_id,$base_unit_id,$unit_id) && $item_id>0 && $base_unit_id>0 && $unit_id>0)
	{
		$sql="SELECT rel_item_unit_id FROM edms_rel_item_unit WHERE item_id = $item_id AND base_unit_id = $base_unit_id AND item_unit_id = $unit_id";
		if($rel_item_unit_id && checkForNumeric($rel_item_unit_id) && $rel_item_unit_id>0)
		$sql=$sql." AND rel_item_unit_id!=$rel_item_unit_id";
		
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0];
		else
		return false;
		
	}
	return false;
	
}

function checkforRelItemUnitInUse($item_id,$unit_id)
{
	if(checkForNumeric($item_id,$unit_id))
	{
		$sql="SELECT trans_item_unit_id FROM edms_ac_trans_item_unit WHERE item_id = $item_id AND item_unit_id = $unit_id";
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		return $resultArray[0][0];
		else
		return false;
	}
	return false;
}

function insertTransItemUnit($purchase_item_id,$sales_item_id,$credit_note_item_id,$debit_note_item_id,$inventory_item_jv_id,$item_unit_id,$item_id,$or_rate,$or_qty)
{
 	if((checkForNumeric($purchase_item_id) || checkForNumeric($sales_item_id) || checkForNumeric($credit_note_item_id) || checkForNumeric($debit_note_item_id) || checkForNumeric($inventory_item_jv_id)) && checkForNumeric($item_unit_id,$item_id,$or_qty,$or_rate))	
	{
		
		if(!checkForNumeric($purchase_item_id))
		$purchase_item_id="NULL";
		if(!checkForNumeric($sales_item_id))
		$sales_item_id="NULL";
		if(!checkForNumeric($credit_note_item_id))
		$credit_note_item_id="NULL";
		if(!checkForNumeric($debit_note_item_id))
		$debit_note_item_id="NULL";
		if(!checkForNumeric($inventory_item_jv_id))
		$inventory_item_jv_id="NULL";
		
		$sql="INSERT INTO edms_ac_trans_item_unit (purchase_item_id, sales_item_id, credit_note_item_id, debit_note_item_id, inventory_item_jv_id,item_unit_id,item_id,rate,quantity) VALUES ($purchase_item_id,$sales_item_id,$credit_note_item_id,$debit_note_item_id,$inventory_item_jv_id,$item_unit_id,$item_id,$or_rate,$or_qty)";
		dbQuery($sql);
		return dbInsertId();
	}
}

function getTransItemUnitBySalesItemId($sales_item_id)
{
	if(checkForNumeric($sales_item_id))
	{
		$sql="SELECT sales_item_id, rate,quantity,item_id, edms_ac_trans_item_unit.item_unit_id,unit_name FROM edms_ac_trans_item_unit INNER JOIN edms_item_unit ON edms_item_unit.item_unit_id = edms_ac_trans_item_unit.item_unit_id WHERE sales_item_id = $sales_item_id";
		$result=dbQuery($sql);
		$resultArray =dbResultToArray($result);
		return $resultArray[0];
		
	}
	return false;
}

function getTransItemUnitByCreditNoteItemId($credit_note_item_id)
{
	if(checkForNumeric($credit_note_item_id))
	{
		$sql="SELECT credit_note_item_id, rate,quantity,item_id, edms_ac_trans_item_unit.item_unit_id,unit_name FROM edms_ac_trans_item_unit INNER JOIN edms_item_unit ON edms_item_unit.item_unit_id = edms_ac_trans_item_unit.item_unit_id WHERE credit_note_item_id = $credit_note_item_id";
		$result=dbQuery($sql);
		$resultArray =dbResultToArray($result);
		return $resultArray[0];
		
	}
	return false;
}

function getTransItemUnitByDebitNoteItemId($debit_note_item_id)
{
	if(checkForNumeric($debit_note_item_id))
	{
		$sql="SELECT debit_note_item_id, rate,quantity,item_id, edms_ac_trans_item_unit.item_unit_id,unit_name FROM edms_ac_trans_item_unit INNER JOIN edms_item_unit ON edms_item_unit.item_unit_id = edms_ac_trans_item_unit.item_unit_id WHERE debit_note_item_id = $debit_note_item_id";
		$result=dbQuery($sql);
		$resultArray =dbResultToArray($result);
		return $resultArray[0];
		
	}
	return false;
}


function getTransItemUnitByPurchaseItemId($purchase_item_id)
{
	if(checkForNumeric($purchase_item_id))
	{
		$sql="SELECT purchase_item_id, rate,quantity,item_id, edms_ac_trans_item_unit.item_unit_id,unit_name FROM edms_ac_trans_item_unit INNER JOIN edms_item_unit ON edms_item_unit.item_unit_id = edms_ac_trans_item_unit.item_unit_id WHERE purchase_item_id = $purchase_item_id";
		$result=dbQuery($sql);
		$resultArray =dbResultToArray($result);
		return $resultArray[0];
		
	}
	return false;
}


function ConvertRateAndQuantityForItemAndItemUnitId($rate,$quantity,$item_id,$unit_id)
{
	
	if(checkForNumeric($rate,$quantity,$item_id,$unit_id))
	{
		$base_unit_id = getBaseUnitForItemId($item_id);
		if($unit_id!=$base_unit_id)
		{
		$sql="SELECT * FROM edms_rel_item_unit WHERE item_id = $item_id and item_unit_id = $unit_id";
		$result=dbQuery($sql);
		
		$resultArray =dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
			$base_unit_rel = $resultArray[0]['base_unit_rel'];
			if($rate>0)
			$base_rate = $rate / $base_unit_rel;
			else
			$base_rate=0;
			$base_qty = $quantity * $base_unit_rel;
			return array($base_rate,$base_qty);
		}
		}
		else return array($rate,$quantity);
	}
	else return array($rate,$quantity);
}



	
?>