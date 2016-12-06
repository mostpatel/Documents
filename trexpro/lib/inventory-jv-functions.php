<?php 
require_once("cg.php");
require_once("city-functions.php");
require_once("image-functions.php");
require_once("inventory-item-functions.php");
require_once("item-type-functions.php");
require_once("item-manufacturer-functions.php");
require_once("tax-functions.php");
require_once("godown-functions.php");
require_once("account-ledger-functions.php");
require_once("account-sales-functions.php");
require_once("inventory-sales-functions.php");
require_once("nonstock-sales-functions.php");
require_once("tax-functions.php");
require_once("our-company-function.php");
require_once("common.php");
require_once("bd.php");

function checkForJvItemsInArray($item_id_array,$rate_array,$quantity_array,$godown_id_array)
{
	$total_amount=0;
	$has_items=false;
	if(is_array($item_id_array) && count($item_id_array)>0)
	{
		for($i=0;$i<count($item_id_array);$i++)
		{
			$item_id=$item_id_array[$i];
			$rate=$rate_array[$i];
			$quantity=$quantity_array[$i];
			$godown_id=$godown_id_array[$i];
			
			if(checkForNumeric($item_id,$rate,$quantity,$godown_id) && $item_id>0 && $rate>=0 && $quantity>0 && $godown_id>0)
			{
				$amount = ($rate)*$quantity;
				
				$total_amount = $total_amount + $amount;
			
				$has_items = $total_amount;
			}	
			
		}
				
	}
	return $has_items;
	
	}


function insertInventoryJV($item_id_array,$rate_array,$quantity_array,$item_id_cd_array,$rate_cd_array,$quantity_cd_array,$trans_date,$remarks,$godown_id_array,$godown_id_cd_array)
{
	
	$item_id_array = ConvertItemNameArrayInToIdArray($item_id_array);
	$item_id_cd_array = ConvertItemNameArrayInToIdArray($item_id_cd_array);
	$nett_amount_debit = checkForJvItemsInArray($item_id_array,$rate_array,$quantity_array,$godown_id_array);
	$nett_amount_credit = checkForJvItemsInArray($item_id_cd_array,$rate_cd_array,$quantity_cd_array,$godown_id_cd_array);
	
	
	
	
	if(($nett_amount_debit && checkForNumeric($nett_amount_debit) && $nett_amount_debit>=0) || ($nett_amount_credit && checkForNumeric($nett_amount_credit) && $nett_amount_credit>=0) && validateForNull($trans_date))
	{
	
	$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
	
	$oc_id =$_SESSION['edmsAdminSession']['oc_id'];
		if(isset($trans_date) && validateForNull($trans_date))
			{
		    $trans_date = str_replace('/', '-', $trans_date);
			$trans_date=date('Y-m-d',strtotime($trans_date));
			}
	
	$sql="INSERT INTO edms_inventory_jv(trans_date,remarks,oc_id,created_by,last_updated_by,date_added,date_modified) VALUES ('$trans_date','$remarks',$oc_id,$admin_id,$admin_id,NOW(),NOW())";
	
	$result = dbQuery($sql);
	$inventory_jv_id = dbInsertId();
	if(checkForNumeric($inventory_jv_id))
	{
	
	if($nett_amount_debit && checkForNumeric($nett_amount_debit) && $nett_amount_debit>=0)	
	insertInventoryItemsToJV($item_id_array,$rate_array,$quantity_array,$inventory_jv_id,$godown_id_array,1);
	if($nett_amount_credit && checkForNumeric($nett_amount_credit) && $nett_amount_credit>=0)
	insertInventoryItemsToJV($item_id_cd_array,$rate_cd_array,$quantity_cd_array,$inventory_jv_id,$godown_id_cd_array,0);
	}
	else
	return "error";
	
	return $sales_id;
	}
	return "error";
}	

function updateInventoryNonStockItemJV($inventory_jv_id,$item_id_array,$rate_array,$quantity_array,$item_id_cd_array,$rate_cd_array,$quantity_cd_array,$trans_date,$remarks,$godown_id_array,$godown_id_cd_array)
{
	
	if(checkForNumeric($inventory_jv_id))
	{
	
	$item_id_array = ConvertItemNameArrayInToIdArray($item_id_array);
	$item_id_cd_array = ConvertItemNameArrayInToIdArray($item_id_cd_array);
	$nett_amount_debit = checkForJvItemsInArray($item_id_array,$rate_array,$quantity_array,$godown_id_array);
	$nett_amount_credit = checkForJvItemsInArray($item_id_cd_array,$rate_cd_array,$quantity_cd_array,$godown_id_cd_array);
	
	if(($nett_amount_debit && checkForNumeric($nett_amount_debit) && $nett_amount_debit>=0) || ($nett_amount_credit && checkForNumeric($nett_amount_credit) && $nett_amount_credit>=0) && validateForNull($trans_date) && checkForNumeric($inventory_jv_id))
	{
		
	$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
	
	$oc_id =$_SESSION['edmsAdminSession']['oc_id'];
		if(isset($trans_date) && validateForNull($trans_date))
			{
		    $trans_date = str_replace('/', '-', $trans_date);
			$trans_date=date('Y-m-d',strtotime($trans_date));
			}	
	
	$sql="UPDATE edms_inventory_jv SET trans_date = '$trans_date' , remarks = '$remarks', last_updated_by = $admin_id, date_modified = NOW() WHERE inventory_jv_id = $inventory_jv_id";	
	$result = dbQuery($sql);
	
	deleteInventoryItemsForJv($inventory_jv_id);	
	
    if($nett_amount_debit && checkForNumeric($nett_amount_debit) && $nett_amount_debit>=0)	
	insertInventoryItemsToJV($item_id_array,$rate_array,$quantity_array,$inventory_jv_id,$godown_id_array,1);
	if($nett_amount_credit && checkForNumeric($nett_amount_credit) && $nett_amount_credit>=0)
	insertInventoryItemsToJV($item_id_cd_array,$rate_cd_array,$quantity_cd_array,$inventory_jv_id,$godown_id_cd_array,0);

	return "success";
	
	}
	return "error";
		
		
	}
}

function insertInventoryItemToJV($item_id,$rate,$quantity,$inventory_jv_id,$godown_id,$type=0) // type 0=debit , 1=credit
{
	if(!validateForNull($type))
	$type=0;
	if(checkForNumeric($item_id,$rate,$quantity,$inventory_jv_id,$godown_id,$type) && $type>=0 && $godown_id>0 && $item_id>0 && $rate>=0 && $quantity>0)
			{
				
				$amount = $quantity * $rate;
				
	
				$admin_id=$_SESSION['edmsAdminSession']['admin_id'];
				
				
				$sql="INSERT INTO edms_inventory_item_jv (item_id,rate,quantity,amount,inventory_jv_id,godown_id,created_by,last_updated_by,date_added,date_modified,type) VALUES ($item_id,$rate,$quantity,$amount,$inventory_jv_id,$godown_id,$admin_id,$admin_id,NOW(),NOW(),$type)";
				dbQuery($sql);
				return dbInsertId();
			}	
		return false;	
	
}

function deleteInventoryItemsForJv($inventory_jv_id)
{
	if(checkForNumeric($inventory_jv_id))
	{
		$sql="DELETE FROM edms_inventory_item_jv WHERE inventory_jv_id = $inventory_jv_id";
		dbQuery($sql);
		return "success";
	}	
	return "error";
}

function insertInventoryItemsToJV($item_id_array,$rate_array,$quantity_array,$inventory_jv_id,$godown_id_array,$type=0)
{
	
	if(!validateForNull($type))
	$type=0;
	
	if(is_array($item_id_array) && count($item_id_array)>0)
	{
		for($i=0;$i<count($item_id_array);$i++)
		{
			$item_id=$item_id_array[$i];
			$rate=$rate_array[$i];
			$quantity=$quantity_array[$i];
			$godown_id = $godown_id_array[$i];
			
			
			if(checkForNumeric($item_id,$rate,$quantity,$inventory_jv_id,$godown_id,$type) && $item_id>0 && $rate>=0 && $quantity>0)
			{
				
				$sales_item_id=insertInventoryItemToJV($item_id,$rate,$quantity,$inventory_jv_id,$godown_id,$type);
				
				
			}	
			
		}
		return "success";
				
	}
	
	return "error";
	
}

function deleteInventoryJv($inventory_jv_id)
{
	if(checkForNumeric($inventory_jv_id))
	{
		$sql="DELETE FROM edms_inventory_jv WHERE inventory_jv_id = $inventory_jv_id";
		dbQuery($sql);
		return "success";
	}
	return "error";
}
function getInventoryItemForJvId($inventory_jv_id)
{
	if(checkForNumeric($inventory_jv_id))
	{
		$return_array = array();
		$sql="SELECT edms_inventory_item_jv.inventory_item_jv_id,item_id,rate,quantity,amount,godown_id,edms_inventory_item_jv.inventory_jv_id,godown_id,edms_inventory_jv.created_by,edms_inventory_jv.last_updated_by,edms_inventory_jv.date_added,edms_inventory_jv.date_modified,type FROM edms_inventory_jv , edms_inventory_item_jv WHERE edms_inventory_item_jv.inventory_jv_id = edms_inventory_jv.inventory_jv_id AND  edms_inventory_jv.inventory_jv_id = $inventory_jv_id";	
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
			
			return $resultArray;
		}
	}
	
}	

function getDebitInventoryItemForJvId($inventory_jv_id)
{
	if(checkForNumeric($inventory_jv_id))
	{
		$return_array = array();
		$sql="SELECT edms_inventory_item_jv.inventory_item_jv_id,item_id,rate,quantity,amount,godown_id,edms_inventory_item_jv.inventory_jv_id,godown_id,edms_inventory_jv.created_by,edms_inventory_jv.last_updated_by,edms_inventory_jv.date_added,edms_inventory_jv.date_modified,type FROM edms_inventory_jv , edms_inventory_item_jv WHERE edms_inventory_item_jv.inventory_jv_id = edms_inventory_jv.inventory_jv_id AND  edms_inventory_jv.inventory_jv_id = $inventory_jv_id AND type=0";	
	
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
			
			return $resultArray;
		}
	}
	
}	


function getCreditInventoryItemForJvId($inventory_jv_id)
{
	if(checkForNumeric($inventory_jv_id))
	{
		$return_array = array();
		$sql="SELECT edms_inventory_item_jv.inventory_item_jv_id,item_id,rate,quantity,amount,godown_id,edms_inventory_item_jv.inventory_jv_id,edms_inventory_jv.created_by,edms_inventory_jv.last_updated_by,edms_inventory_jv.date_added,edms_inventory_jv.date_modified,type FROM edms_inventory_jv , edms_inventory_item_jv WHERE edms_inventory_item_jv.inventory_jv_id = edms_inventory_jv.inventory_jv_id AND  edms_inventory_jv.inventory_jv_id = $inventory_jv_id AND type=1";	
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
			
			return $resultArray;
		}
	}
	
}	

function getInventoryJVById($inventory_jv_id)
{
	if(checkForNumeric($inventory_jv_id))
	{
		$sql="SELECT inventory_jv_id, trans_date, remarks FROM edms_inventory_jv WHERE inventory_jv_id = $inventory_jv_id";
		$result = dbQuery($sql);
		$resultArray = dbResultToArray($result);
		if(dbNumRows($result)>0)
		{
			return $resultArray[0];
		}
	}
	
}

function getTotalDebitJvForItemIdUptoDate($item_id,$to=NULL,$model=false,$godown_id=NULL) // ledgers without cash and banks, ledger_id should start with l for ledger and c for customer
{
	
if(!$model)
{	

if(isset($to) && validateForNull($to))
{
	$to = str_replace('/', '-', $to);
		$to=date('Y-m-d',strtotime($to));
	}
	
	if(checkForNumeric($item_id))
	{
	$sql="SELECT edms_inventory_item_jv.item_id,SUM(amount) as total_amount, SUM(quantity) as quantity, SUM(amount)/SUM(quantity) as avg_rate
			  FROM edms_inventory_jv, edms_inventory_item_jv WHERE type = 0 AND  edms_inventory_item_jv.inventory_jv_id = edms_inventory_jv.inventory_jv_id AND ";
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(validateForNull($godown_id) && checkForNumeric($godown_id))
	$sql=$sql." godown_id = $godown_id AND ";	  
	if(!$model)	  
	$sql=$sql." item_id=$item_id GROUP BY item_id";
	else if($model)
	$sql=$sql." model_id=$item_id GROUP BY model_id";

	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray[0];
	else
	return 0; 	
	}
	return 0;
	}
	
	return 0;	
}
function getTotalCreditJvForItemIdUptoDate($item_id,$to=NULL,$model=false,$godown_id=NULL) // ledgers without cash and banks, ledger_id should start with l for ledger and c for customer
{
if(!$model)
{	
if(isset($to) && validateForNull($to))
{
	$to = str_replace('/', '-', $to);
		$to=date('Y-m-d',strtotime($to));
	}
	
	if(checkForNumeric($item_id))
	{
	$sql="SELECT edms_inventory_item_jv.item_id,SUM(amount) as total_amount, SUM(quantity) as quantity, SUM(amount)/SUM(quantity) as avg_rate
			  FROM edms_inventory_jv, edms_inventory_item_jv WHERE type = 1 AND  edms_inventory_item_jv.inventory_jv_id = edms_inventory_jv.inventory_jv_id AND ";
	if(isset($from) && validateForNull($from))
	$sql=$sql."trans_date>='$from' 
		  AND ";
	if(isset($to) && validateForNull($to))  
	$sql=$sql."trans_date<='$to'
		  AND ";
	if(validateForNull($godown_id) && checkForNumeric($godown_id))
	$sql=$sql." godown_id = $godown_id AND ";	  
	if(!$model)	  
	$sql=$sql." item_id=$item_id GROUP BY item_id";
	else if($model)
	$sql=$sql." model_id=$item_id GROUP BY model_id";
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	return $resultArray[0];
	else
	return 0; 	
	}
	return 0;
	}	
}
?>