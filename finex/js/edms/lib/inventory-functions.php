<?php
require_once("cg.php");
require_once("city-functions.php");
require_once("account-ledger-functions.php");
require_once("account-head-functions.php");
require_once("account-payment-functions.php");
require_once("account-receipt-functions.php");
require_once("account-purchase-functions.php");
require_once("account-sales-functions.php");
require_once("account-debit-note-functions.php");
require_once("account-credit-note-functions.php");
require_once("inventory-purchase-functions.php");
require_once("inventory-sales-functions.php");
require_once("account-jv-functions.php");
require_once("tax-functions.php");
require_once("vehicle-functions.php");
require_once("vehicle-model-functions.php");
require_once("account-contra-functions.php");
require_once("account-functions.php");
require_once("inventory-item-functions.php");
require_once("inventory-jv-functions.php");
require_once("account-combined-agency-functions.php");
require_once("common.php");
require_once("bd.php");

function getAllTransactionsForItemIdMonthWise($id,$transaction_type_array=NULL,$from=NULL,$to=NULL,$model=false)
{
	
	$month_year_array=getMonthYearArrayFromDates($from,$to);
	
	$return_array=array();
	foreach($month_year_array as $month_year)
	{
	
		$month=$month_year['month'];
		$year=$month_year['year'];
		$month_year=$month_year['month_year'];
		
		$first_day_next_month = getFirstDayOfNextMonth($month,$year);
		
	if((validateForNull($transaction_type_array) && in_array(1,$transaction_type_array)) || !validateForNull($transaction_type_array))
	{	
	$purchase=getTotalPurchaseForItemIdForMonth($id,$month,$year,$from,$to,$model);
	$purchase_amount = $purchase[0];
	$purchase_rate = round($purchase[1],2);
	$purchase_quantity = $purchase[2];
	}
	
	
	if((validateForNull($transaction_type_array) && in_array(2,$transaction_type_array)) || !validateForNull($transaction_type_array))
	{
	$sale=getTotalSaleForItemIdForMonth($id,$month,$year,$from,$to,$model);
	$sale_amount = $sale[0];
	$sale_rate = round($sale[1],2);
	$sale_quantity = $sale[2];
	}
	
	$closing_balance = getOpeningBalanceForItemForDate($id,$first_day_next_month,$model);
	$closing_balance_amount = round($closing_balance[0],2);
	$closing_balance_rate = round($closing_balance[2],2);
	$closing_balance_quantity = $closing_balance[1];
	
	$net_amount=$purchase_amount-$sale_amount;
	$return_array[$month_year]=array($purchase_amount,$purchase_quantity,$purchase_rate,$sale_amount,$sale_quantity,$sale_rate,$closing_balance_amount,$closing_balance_quantity,$closing_balance_rate,$month,$year);
	
	
	
	}
	return $return_array;	
		
}
	
function getAllTransactionsForItemId($id,$transaction_type_array=NULL,$from=NULL,$to=NULL,$model=false)
{
	
	if((validateForNull($transaction_type_array) && in_array(1,$transaction_type_array)) || !validateForNull($transaction_type_array))
	$payments=getPurchasesForLedgerIdBetweenDates($id,$from,$to);
	
	
	if((validateForNull($transaction_type_array) && in_array(2,$transaction_type_array)) || !validateForNull($transaction_type_array))
	{
	$receipts=getReceiptsForLedgerIdBetweenDates($id,$from,$to);
	}
	
	if(substr($id, 0, 1) == 'L')
	{
		$ledger_id=str_replace('L','',$id);
		$ledger_id=intval($ledger_id);
		$head_type=getLedgerHeadType($ledger_id);
		}
	else if(substr($id, 0, 1) == 'C')
	{
		$head_type=1;
		}	
	
	
	if($head_type==0)
	{
		if((validateForNull($transaction_type_array) && in_array(4,$transaction_type_array)) || !validateForNull($transaction_type_array))
		$contras=getContrasForLedgerIdBetweenDates($id,$from,$to);
		if(!is_array($payments)) $payments=array();
		if(!is_array($receipts)) $receipts=array();
		if(!is_array($contras)) $contras=array();
		$returnArray=array_merge($payments,$receipts,$contras);
		uasort($returnArray,'TransDateComparator');
		
		return array($returnArray,$head_type);
		}
	else if($head_type==1)
	{
		if((validateForNull($transaction_type_array) && in_array(3,$transaction_type_array)) || !validateForNull($transaction_type_array))
		$jvs=getJVsForLedgerIdBetweenDates($id,$from,$to);
		if(!is_array($payments)) $payments=array();
		if(!is_array($receipts)) $receipts=array();
		if(!is_array($contras)) $contras=array();
		$returnArray=array_merge($payments,$receipts,$jvs);
		uasort($returnArray,'TransDateComparator');
		return array($returnArray,$head_type);
		}
	return false;	
		
	}	

function getAllTransactionsForItemIdForMonth($id,$month,$year,$transaction_type_array=NULL,$from=NULL,$to=NULL,$model=false)
{
	if(checkForNumeric($id))
	{
	if((validateForNull($transaction_type_array) && in_array(1,$transaction_type_array)) || !validateForNull($transaction_type_array))
	$purchases=getPurchasesForItemIdForMonth($id,$month,$year,$from,$to,$model);
	
	
	
	if((validateForNull($transaction_type_array) && in_array(2,$transaction_type_array)) || !validateForNull($transaction_type_array))
	$sales=getSalesForItemIdForMonth($id,$month,$year,$from,$to,$model);
	
	
	
		$returnArray=array_merge($purchases,$sales);
	
		uasort($returnArray,'TransDateComparator');
		return $returnArray;
	}
	return false;	
		
	}		

function getOpeningBalanceForItemForDate($id,$date,$model=false) // return opening balance on date for id, id should start with L or C
{
	if(checkForNumeric($id))
	{	
	$to=getPreviousDate($date); // return d-m-y
	if(!$model)
	{
	$opening_balance_array=getOpeningBalanceForItem($id);	
	$opening_balance_item=$opening_balance_array[0];
	$opening_balance_quantity=$opening_balance_array[1];
	$opening_balance_rate=$opening_balance_array[2];
	}
	else
	{
		
	$opening_balance_array=GetOpeningBalanceAndQuantityForModel($id);

	$opening_balance_item=$opening_balance_array[1];
	$opening_balance_quantity=$opening_balance_array[0];
	$opening_balance_rate=$opening_balance_array[2];
	}
	
    $total_purchase=getTotalPurchaseForItemIdUptoDate($id,$to,$model);
	$total_debit_note = getTotalDebitNoteForItemIdUptoDate($id,$to,$model);		
	$total_sale = getTotalSaleForItemIdUptoDate($id,$to,$model);
	$total_credit_note = getTotalCreditNoteForItemIdUptoDate($id,$to,$model);	
	
	if($total_debit_note==0)
	{
	$total_debit_note_amount = 0;
	$total_debit_note_quantity =0;
	$total_debit_note_avg_rate =0;
	}
	else if(is_array($total_debit_note))
	{
	$total_debit_note_amount = $total_debit_note['total_amount'];
	$total_debit_note_quantity = $total_debit_note['quantity'];
	$total_debit_note_rate = $total_debit_note['avg_rate'];
	}
		
	if($total_purchase==0)
	{
	$total_purchase_amount = 0;
	$total_purchase_quantity =0;
	$total_purchase_avg_rate =0;
	}
	else if(is_array($total_purchase))
	{
	$total_purchase_amount = $total_purchase['total_amount'];
	$total_purchase_quantity = $total_purchase['quantity'];
	$total_purchase_avg_rate = $total_purchase['avg_rate'];
	}
	
	$total_purchase_amount = $total_purchase_amount - $total_debit_note_amount;
	$total_purchase_quantity = $total_purchase_quantity - $total_debit_note_quantity;
	
	
	if(($opening_balance_quantity + $total_purchase_quantity)>0)
	{	
		if(defined(INVENTORY_CAL_METHOD) && INVENTORY_CAL_METHOD==1) // average_method
		{
		$avg_rate = ($total_purchase_amount + $opening_balance_item) / ($opening_balance_quantity + $total_purchase_quantity);
		$avg_rate = round($avg_rate,2);
		}
		else if(defined(INVENTORY_CAL_METHOD) && INVENTORY_CAL_METHOD==2) // fifo
		{
		
		$avg_rate = getPurchaseAvgRateFifoUpToDate($id,$to,$model,$total_purchase,$total_sale);
		$avg_rate = round($avg_rate,2);
		}
		else 
		{
				
		$avg_rate = getPurchaseAvgRateFifoUpToDate($id,$to,$model,$total_purchase,$total_sale);
		$avg_rate = round($avg_rate,2);
		}
		
	}
	else
	$avg_rate=0;
	
	
	if($total_credit_note==0)
	{
	$total_credit_note_amount = 0;
	$total_credit_note_quantity =0;
	$total_credit_note_avg_rate =0;
	}
	else if(is_array($total_credit_note))
	{
	$total_credit_note_amount = $total_credit_note['total_amount'];
	$total_credit_note_quantity = $total_credit_note['quantity'];
	$total_credit_note_rate = $total_credit_note['avg_rate'];
	}
	
	
	if($total_sale==0)
	{
	$total_sale_amount = 0;
	$total_sale_quantity = 0;
	$total_sale_avg_rate = 0;
		
	}
	else if(is_array($total_sale))
	{
	$total_sale_amount = $total_sale['total_amount'];
	$total_sale_quantity = $total_sale['quantity'];
	$total_sale_avg_rate = $total_sale['avg_rate'];
	}
	
	$total_sale_amount = $total_sale_amount - $total_credit_note_amount;
	$total_sale_quantity = $total_sale_quantity - $total_credit_note_quantity;
	$total_sale_avg_rate = $total_sale_amount - $total_sale_quantity;
	
	$net_quantity = 0;
	
	$net_quantity = $opening_balance_quantity + $total_purchase_quantity - $total_sale_quantity;
	
	if($total_purchase_quantity==0 && $net_quantity<0)
	$avg_rate = $total_sale_avg_rate;
	
	$net_amount = $net_quantity * $avg_rate;

	return array($net_amount,$net_quantity,$avg_rate);
	}
}

function getPurchaseAvgRateFifoUpToDate($id,$to,$model=false,$total_purchase=false,$total_sale=false,$godown_id=false) // $total_purchase and $total_debit_note are arrays
{	
	if(!checkForNumeric($godown_id))
	$godown_id=NULL;
	
	if($total_purchase==false)
	$total_purchase = getTotalPurchaseForItemIdUptoDate($id,$to,$model,$godown_id);

	$total_debit_note = getTotalDebitNoteForItemIdUptoDate($id,$to,$model,$godown_id);
	
	$total_debit_jv = getTotalDebitJvForItemIdUptoDate($id,$to,$model,$godown_id);
	
	if(!$model)
	{
	$item_opening_balance = getOpeningBalanceForItem($id);
	$opening_quantity = $item_opening_balance[1];
	}
	else
	{
		
	$model_opening_balance=GetOpeningBalanceAndQuantityForModel($id);	
	$opening_quantity = $model_opening_balance[0];
	$total_opening_balance = $model_opening_balance[1];
	$avg_opening_rate = $model_opening_balance[2];
	}
	if($total_purchase==0 && $total_debit_note==0 && $opening_quantity==0)
	{
	return 0;
	}
	else if(is_array($total_purchase) || is_array($total_debit_note) || is_array($total_debit_jv))
	{
		if(is_array($total_purchase))
		 {	
		$total_purchase_amount = $total_purchase['total_amount'];
		$total_purchase_quantity = $total_purchase['quantity'];
		$total_purchase_avg_rate = $total_purchase['avg_rate'];
		 }
		 else
		 {
		$total_purchase_amount = 0;
		$total_purchase_quantity = 0;
		$total_purchase_avg_rate = 0;
		 }

		if(is_array($total_debit_note))
		 {	
		$total_debit_note_amount = $total_debit_note['total_amount'];
		$total_debit_note_quantity = $total_debit_note['quantity'];
		$total_debit_note_avg_rate = $total_debit_note['avg_rate'];
		 }
		 else
		 {
		$total_debit_note_amount = 0;
		$total_debit_note_quantity = 0;
		$total_debit_note_avg_rate = 0;
		 }

		if(is_array($total_debit_jv))
		 {	
		$total_debit_jv_amount = $total_debit_jv['total_amount'];
		$total_debit_jv_quantity = $total_debit_jv['quantity'];
		$total_debit_jv_avg_rate = $total_debit_jv['avg_rate'];
		 }
		 else
		 {
		$total_debit_jv_amount = 0;
		$total_debit_jv_quantity = 0;
		$total_debit_jv_avg_rate = 0;
		 }
		
	}
	else 
	{
		$total_purchase_amount = 0;
		$total_purchase_quantity = 0;
		$total_purchase_avg_rate = 0;
		$total_debit_note_amount = 0;
		$total_debit_note_quantity = 0;
		$total_debit_note_avg_rate = 0;
		$total_debit_jv_amount = 0;
		$total_debit_jv_quantity = 0;
		$total_debit_jv_avg_rate = 0;
	}
	if(!$total_sale)
	$total_sale = getTotalSaleForItemIdUptoDate($id,$to,$model,$godown_id);
	$total_credit_note = getTotalCreditNoteForItemIdUptoDate($id,$to,$model,$godown_id);
	$total_credit_jv = getTotalCreditJvForItemIdUptoDate($id,$to,$model,$godown_id);
	if($total_sale==0)
	{
	$total_sale_amount = 0;
	$total_sale_quantity = 0;
	$total_sale_avg_rate = 0;
	}
	else if(is_array($total_sale))
	{
	$total_sale_amount = $total_sale['total_amount'];
	$total_sale_quantity = $total_sale['quantity'];
	$total_sale_avg_rate = $total_sale['avg_rate'];
	}
	
	if($total_credit_note==0)
	{
	$total_credit_note_amount = 0;
	$total_credit_note_quantity = 0;
	$total_credit_note_avg_rate = 0;
	}
	else if(is_array($total_credit_note))
	{
	$total_credit_note_amount = $total_credit_note['total_amount'];
	$total_credit_note_quantity = $total_credit_note['quantity'];
	$total_credit_note_avg_rate = $total_credit_note['avg_rate'];
	}
	
	if($total_credit_jv==0)
	{
	$total_credit_jv_amount = 0;
	$total_credit_jv_quantity = 0;
	$total_credit_jv_avg_rate = 0;
	}
	else if(is_array($total_credit_jv))
	{
	$total_credit_jv_amount = $total_credit_jv['total_amount'];
	$total_credit_jv_quantity = $total_credit_jv['quantity'];
	$total_credit_jv_avg_rate = $total_credit_jv['avg_rate'];
	}
	
	$difference_quantity = $opening_quantity + $total_purchase_quantity + $total_debit_jv_quantity - $total_sale_quantity - $total_debit_note_quantity + $total_credit_note_quantity - $total_credit_jv_quantity;
	
	
	if($difference_quantity>0 && !$model)
	{
		$sql="SELECT id, type, quantity, net_amount, rate , trans_date, date_added, @t := @t + quantity as total_quantity
  FROM
(
   SELECT purchase_item_id as id, 0 AS type, edms_ac_purchase_item.quantity AS quantity , edms_ac_purchase_item.net_amount AS  net_amount , edms_ac_purchase_item.net_amount/edms_ac_purchase_item.quantity AS  rate , edms_ac_purchase.trans_date AS trans_date ,edms_ac_purchase.date_added as  date_added 
    FROM  edms_ac_purchase_item  INNER JOIN edms_ac_purchase ON edms_ac_purchase_item.purchase_id = edms_ac_purchase.purchase_id ";
	
	$sql=$sql."	 WHERE item_id = $id AND trans_date <= '$to'
	UNION ALL
	SELECT debit_note_item_id as id, 1 AS type, -edms_ac_debit_note_item.quantity AS quantity , -edms_ac_debit_note_item.net_amount AS  net_amount , edms_ac_debit_note_item.net_amount/edms_ac_debit_note_item.quantity AS  rate , edms_ac_debit_note.trans_date AS trans_date ,edms_ac_debit_note.date_added as  date_added 
    FROM  edms_ac_debit_note_item INNER JOIN edms_ac_debit_note ON edms_ac_debit_note_item.debit_note_id = edms_ac_debit_note.debit_note_id  WHERE item_id = $id AND trans_date <= '$to'
	UNION ALL
	SELECT inventory_item_jv_id as id, 3 AS type, edms_inventory_item_jv.quantity AS quantity , edms_inventory_item_jv.amount AS  net_amount , edms_inventory_item_jv.amount/edms_inventory_item_jv.quantity AS  rate , edms_inventory_jv.trans_date AS trans_date ,edms_inventory_jv.date_added as  date_added 
    FROM  edms_inventory_item_jv INNER JOIN edms_inventory_jv ON edms_inventory_item_jv.inventory_jv_id = edms_inventory_jv.inventory_jv_id  WHERE item_id = $id AND trans_date <= '$to'
	UNION ALL
	SELECT item_id as id, 2 AS type, opening_quantity as quantity, opening_quantity*opening_rate AS net_amount, opening_rate as rate,
	'1970-01-01' as trans_date, date_added
	FROM edms_inventory_item WHERE item_id = $id
	 ORDER BY trans_date DESC, date_added DESC
) q CROSS JOIN (SELECT @t := 0) i";
	
 	$result = dbQuery($sql);
	$resultArray = dbResultToArray($result);
	
    if(dbNumRows($result)>0)
	{
		$total_purchase_net_amount=0;
		$total_purchase_quantity=0;
		$i=1;
		foreach($resultArray as $purchase)
		{
			
			$purchase_net_amount= $purchase['net_amount'];
			$purchase_quantity = $purchase['quantity'];
			$purchase_rate = $purchase['rate'];
			$total_quanity = $purchase['total_quantity'];
				
			if($total_quanity>$difference_quantity)
			{
				$last_diffrenece_quantity = $difference_quantity - $total_purchase_quantity;
				$purchase_net_amount = $purchase_rate * $last_diffrenece_quantity;
			}
			
			$total_purchase_net_amount = $total_purchase_net_amount + $purchase_net_amount;
			$total_purchase_quantity = $total_purchase_quantity + $purchase_quantity;
			
			if($total_quanity>$difference_quantity)
			{
				break;
			}
		}
		
		return round($total_purchase_net_amount/$difference_quantity,2);
	}
	}
	else if($difference_quantity>0 && $model && checkForNumeric($id))
	{
		$sql="SELECT edms_vehicle.vehicle_id, vehicle_engine_no, vehicle_chasis_no, is_purchased, basic_price, trans_date
  FROM edms_vehicle INNER JOIN edms_ac_purchase_vehicle ON edms_vehicle.vehicle_id = edms_ac_purchase_vehicle.vehicle_id INNER JOIN edms_ac_purchase ON edms_ac_purchase.purchase_id = edms_ac_purchase_vehicle.purchase_id WHERE is_purchased=1 AND model_id = $id AND trans_date<='$to' ";
		

 	$result = dbQuery($sql);
	$resultArray = dbResultToArray($result);
    if(dbNumRows($result)>0)
	{
		$total_purchase_net_amount=$total_opening_balance;
		if(!checkForNumeric($total_purchase_net_amount))
		$total_purchase_net_amount=0;
		
		$total_quantity = $opening_quantity;
		if(!checkForNumeric($total_quantity))
		$total_quantity=0;
		
		$i=1;
		foreach($resultArray as $purchase)
		{
			$purchase_net_amount= $purchase['basic_price'];
			$total_purchase_net_amount = $total_purchase_net_amount + $purchase_net_amount;
			$total_quantity = $total_quantity + 1;
			
		}
		return round($total_purchase_net_amount/$total_quantity,2);
	}
	else
	{
		return round($total_opening_balance/$opening_quantity,2);
		
	}
	}
	else
	return 0;
	
}

function getRemainingQuanityForItemForDate($id,$godown_id,$model=false,$date=false) // return opening balance on date for id, id should start with L or C
{
	if(checkForNumeric($id,$godown_id))
	{
	if(!$date)
	$date=date('d/m/Y',strtotime(getTodaysDate()));
		
	$to=$date; // return d-m-y
	
	
	$opening_balance_array=getOpeningBalanceForItem($id);	
	$opening_balance_item=$opening_balance_array[0];
	$opening_balance_quantity=$opening_balance_array[1];
	$opening_balance_rate=$opening_balance_array[2];
	$opening_balance_godown_id=$opening_balance_array[3];
	
	
	
    $total_purchase=getTotalPurchaseForItemIdUptoDate($id,$to,$model,$godown_id);
	$total_debit_jv = getTotalDebitJvForItemIdUptoDate($id,$to,$model,$godown_id);
	$total_debit_note = getTotalDebitNoteForItemIdUptoDate($id,$to,$model,$godown_id);
	if($total_purchase==0)
	{
	$total_purchase_amount = 0;
	$total_purchase_quantity =0;
	$total_purchase_avg_rate =0;
	}
	else if(is_array($total_purchase))
	{
	$total_purchase_amount = $total_purchase['total_amount'];
	$total_purchase_quantity = $total_purchase['quantity'];
	$total_purchase_avg_rate = $total_purchase['avg_rate'];
	}
	if($total_debit_jv==0)
	{
	$total_debit_jv_amount = 0;
	$total_debit_jv_quantity =0;
	$total_debit_jv_avg_rate =0;
	}
	else if(is_array($total_debit_jv))
	{
	$total_debit_jv_amount = $total_debit_note['total_amount'];
	$total_debit_jv_quantity = $total_debit_note['quantity'];
	$total_debit_jv_avg_rate = $total_debit_note['avg_rate'];
	}
	
	if($total_debit_note==0)
	{
	$total_debit_note_amount = 0;
	$total_debit_note_quantity =0;
	$total_debit_note_avg_rate =0;
	}
	else if(is_array($total_debit_note))
	{
	$total_debit_note_amount = $total_debit_note['total_amount'];
	$total_debit_note_quantity = $total_debit_note['quantity'];
	$total_debit_note_avg_rate = $total_debit_note['avg_rate'];
	}
	
	$total_credit_note = getTotalCreditNoteForItemIdUptoDate($id,$to,$model,$godown_id);
	if($total_credit_note==0)
	{
	$total_credit_note_amount = 0;
	$total_credit_note_quantity =0;
	$total_credit_note_avg_rate =0;
	}
	else if(is_array($total_credit_note))
	{
	$total_credit_note_amount = $total_credit_note['total_amount'];
	$total_credit_note_quantity = $total_credit_note['quantity'];
	$total_credit_note_avg_rate = $total_credit_note['avg_rate'];
	}
	
	if(($opening_balance_quantity + $total_purchase_quantity + $total_debit_jv_quantity + $total_credit_note_quantity)>0)
	{
	$avg_rate = ($total_purchase_amount + $opening_balance_item + $total_debit_jv_amount + $total_credit_note_amount) / ($opening_balance_quantity + $total_purchase_quantity + $total_debit_jv_quantity + $total_credit_note_quantity);

	$avg_rate = round($avg_rate,2);
	}
	
	else
	$avg_rate=0;
	
	$total_sale = getTotalSaleForItemIdUptoDate($id,$to,$model,$godown_id);
	$total_credit_jv = getTotalCreditJvForItemIdUptoDate($id,$to,$model,$godown_id);
	
	
	if($total_sale==0)
	{
	$total_sale_amount = 0;
	$total_sale_quantity = 0;
	$total_sale_avg_rate = 0;
		
	}
	else if(is_array($total_sale))
	{
	$total_sale_amount = $total_sale['total_amount'];
	$total_sale_quantity = $total_sale['quantity'];
	$total_sale_avg_rate = $total_sale['avg_rate'];
	}
	
	if($total_credit_jv==0)
	{
	$total_credit_jv_amount = 0;
	$total_credit_jv_quantity =0;
	$total_credit_jv_avg_rate =0;
	}
	else if(is_array($total_credit_jv))
	{
	$total_credit_jv_amount = $total_credit_jv['total_amount'];
	$total_credit_jv_quantity = $total_credit_jv['quantity'];
	$total_credit_jv_avg_rate = $total_credit_jv['avg_rate'];
	}
	
	if(($total_sale_quantity + $total_credit_jv_quantity + $total_debit_note_quantity)>0)
	{
	$sale_avg_rate = ($total_sale_amount +  $total_credit_jv_amount + $total_debit_note_amount) / ($total_sale_quantity + $total_credit_jv_quantity + $total_debit_note_quantity);

	$sale_avg_rate = round($avg_rate,2);
	}
	
	$net_quantity = 0;
	if($opening_balance_godown_id==$godown_id)
	$net_quantity = $opening_balance_quantity + $total_purchase_quantity + $total_debit_jv_quantity - $total_debit_note_quantity - $total_sale_quantity - $total_credit_jv_quantity + $total_credit_note_quantity;
	else
	$net_quantity = $total_purchase_quantity + $total_debit_jv_quantity - $total_debit_note_quantity - $total_sale_quantity - $total_credit_jv_quantity + $total_credit_note_quantity;
	
	if($total_purchase_quantity==0 && $net_quantity<0)
	$avg_rate = $sale_avg_rate;

	$net_amount = $net_quantity * $avg_rate;
	return $net_quantity;
	}
}



function getFirstPageClosingStockSheet($to)
{
	$to=getNextDate($to); // return Y-m-d	
			
	$items=listInventoryItems();
	$models = listVehicleModels();
	
    $return_array=array();
	$i=0;
	$pl_sheet_balance=0;
	foreach($items as $ite)
	{
		
		$item_id=$ite['item_id'];
		
		$item = getInventoryItemById($item_id);
		
		$closing_balance_item_array = getOpeningBalanceForItemForDate($item_id,$to);	
			
		$opening_balance_item=$closing_balance_item_array[0];
		$opening_balance_quantity=$closing_balance_item_array[1];
		$opening_balance_rate=$closing_balance_item_array[2];
	
		$return_array[$i]['item_id']=$item_id;
		$return_array[$i]['item_type']=0; // item
		$return_array[$i]['item_name']=$item['item_name'];
		$return_array[$i]['closing_balance']=$opening_balance_item;
		$return_array[$i]['closing_quantity']=$opening_balance_quantity;
		$return_array[$i]['closing_rate']=$opening_balance_rate;
		$i++;
	}
	
	foreach($models as $model)
	{
		
		$item_id=$model['model_id'];
		
		$item = getVehicleModelById($item_id);
		
		$closing_balance_item_array = getOpeningBalanceForItemForDate($item_id,$to,true);	
			
		$opening_balance_item=$closing_balance_item_array[0];
		$opening_balance_quantity=$closing_balance_item_array[1];
		$opening_balance_rate=$closing_balance_item_array[2];
	
		$return_array[$i]['item_id']=$item_id;
		$return_array[$i]['item_type']=1; // vehicle
		$return_array[$i]['item_name']=$item['model_name'];
		$return_array[$i]['closing_balance']=$opening_balance_item;
		$return_array[$i]['closing_quantity']=$opening_balance_quantity;
		$return_array[$i]['closing_rate']=$opening_balance_rate;
		$i++;
	}
	
	return $return_array;
}

function getClosingStockForDate($to)
{
	$to=getNextDate($to); // return Y-m-d	
	
	$closing_balance=0;		
	$items=listInventoryItemsForStockCalculation();
	
	$models = listVehicleModels();
	
    $return_array=array();
	$i=0;
	$pl_sheet_balance=0;
	foreach($items as $ite)
	{
		
		$item_id=$ite['item_id'];

		$closing_balance_item_array = getOpeningBalanceForItemForDate($item_id,$to);	
		
		$opening_balance_item=$closing_balance_item_array[0];
		$opening_balance_quantity=$closing_balance_item_array[1];
		$opening_balance_rate=$closing_balance_item_array[2];
		
		$closing_balance = $closing_balance + $opening_balance_item;
		$return_array[$i]['item_id']=$item_id;
		$return_array[$i]['item_name']=$ite['item_name'];
		$return_array[$i]['closing_balance']=$opening_balance_item;
		$return_array[$i]['closing_quantity']=$opening_balance_quantity;
		$return_array[$i]['closing_rate']=$opening_balance_rate;

		$i++; 
	}
		
	foreach($models as $model)
	{
		
		$item_id=$model['model_id'];
		
		$item = getVehicleModelById($item_id);
	
		$closing_balance_item_array = getOpeningBalanceForItemForDate($item_id,$to,true);	
		
		$opening_balance_item=$closing_balance_item_array[0];
		$opening_balance_quantity=$closing_balance_item_array[1];
		$opening_balance_rate=$closing_balance_item_array[2];
	
	   $closing_balance = $closing_balance + $opening_balance_item;
		$return_array[$i]['item_id']=$item_id;
		$return_array[$i]['item_type']=1; // vehicle
		$return_array[$i]['item_name']=$item['model_name'];
		$return_array[$i]['closing_balance']=$opening_balance_item;
		$return_array[$i]['closing_quantity']=$opening_balance_quantity;
		$return_array[$i]['closing_rate']=$opening_balance_rate;
		
		$i++;
	} 
	
	return $closing_balance;
}


function getClosingStockForPeriod($from,$to)
{
	$to=getNextDate($to); // return Y-m-d	
	if(isset($from) && validateForNull($from))
{
	    $from = str_replace('/', '-', $from);
		$from=date('Y-m-d',strtotime($from));
	}
	$closing_balance=0;		
	$items=listInventoryItemsForStockCalculation();
	
	$models = listVehicleModels();
	
    $return_array=array();
	$i=0;
	$pl_sheet_balance=0;
	foreach($items as $ite)
	{
		
		$item_id=$ite['item_id'];

		$closing_balance_item_array = getOpeningBalanceForItemForDate($item_id,$to);	
		$opening_balance_item_array = getOpeningBalanceForItemForDate($item_id,$from);
		$closing_balance_item=$closing_balance_item_array[0];
		$closing_balance_quantity=$closing_balance_item_array[1];
		$closing_balance_rate=$closing_balance_item_array[2];
		
		$opening_balance_item=$closing_balance_item_array[0];
		$opening_balance_quantity=$closing_balance_item_array[1];
		$opening_balance_rate=$closing_balance_item_array[2];
		
		$closing_balance = $closing_balance + $opening_balance_item;
		$return_array[$i]['item_id']=$item_id;
		$return_array[$i]['item_name']=$ite['item_name'];
		$return_array[$i]['closing_balance']=$closing_balance_item;
		$return_array[$i]['closing_quantity']=$closing_balance_quantity;
		$return_array[$i]['closing_rate']=$closing_balance_rate;
		
		$return_array[$i]['opening_balance']=$opening_balance_item;
		$return_array[$i]['opening_quantity']=$opening_balance_quantity;
		$return_array[$i]['opening_rate']=$opening_balance_rate;

		$i++; 
	}
		
	foreach($models as $model)
	{
		
		$item_id=$model['model_id'];
		
		$item = getVehicleModelById($item_id);
	
		$closing_balance_item_array = getOpeningBalanceForItemForDate($item_id,$to,true);	
		
		$opening_balance_item=$closing_balance_item_array[0];
		$opening_balance_quantity=$closing_balance_item_array[1];
		$opening_balance_rate=$closing_balance_item_array[2];
	
	   $closing_balance = $closing_balance + $opening_balance_item;
		$return_array[$i]['item_id']=$item_id;
		$return_array[$i]['item_type']=1; // vehicle
		$return_array[$i]['item_name']=$item['model_name'];
		$return_array[$i]['closing_balance']=$opening_balance_item;
		$return_array[$i]['closing_quantity']=$opening_balance_quantity;
		$return_array[$i]['closing_rate']=$opening_balance_rate;
		
		$i++;
	} 
	
	return $closing_balance;
}


function getClosingStockForDateItemwise($to)
{
	$to=getNextDate($to); // return Y-m-d	
	
	$closing_balance=0;		
	$items=listInventoryItemsForStockCalculation();
	
	$models = listVehicleModels();
	
    $return_array=array();
	$i=0;
	$pl_sheet_balance=0;
	foreach($items as $ite)
	{
		
		$item_id=$ite['item_id'];

		$closing_balance_item_array = getOpeningBalanceForItemForDate($item_id,$to);	
		
		$opening_balance_item=$closing_balance_item_array[0];
		$opening_balance_quantity=$closing_balance_item_array[1];
		$opening_balance_rate=$closing_balance_item_array[2];
		
		$closing_balance = $closing_balance + $opening_balance_item;
		$return_array[$i]['item_id']=$item_id;
		$return_array[$i]['item_name']=$ite['item_name'];
		$return_array[$i]['item_code']=$ite['item_code'];
			$return_array[$i]['mfg_item_code']=$ite['mfg_item_code'];
		$return_array[$i]['closing_balance']=$opening_balance_item;
		$return_array[$i]['closing_quantity']=$opening_balance_quantity;
		$return_array[$i]['closing_rate']=$opening_balance_rate;

		$i++; 
	}
		
	foreach($models as $model)
	{
		
		$item_id=$model['model_id'];
		
		$item = getVehicleModelById($item_id);
	
		$closing_balance_item_array = getOpeningBalanceForItemForDate($item_id,$to,true);	
		
		$opening_balance_item=$closing_balance_item_array[0];
		$opening_balance_quantity=$closing_balance_item_array[1];
		$opening_balance_rate=$closing_balance_item_array[2];
	
	   $closing_balance = $closing_balance + $opening_balance_item;
		$return_array[$i]['item_id']=$item_id;
		$return_array[$i]['item_type']=1; // vehicle
		$return_array[$i]['item_name']=$item['model_name'];
		$return_array[$i]['closing_balance']=$opening_balance_item;
		$return_array[$i]['closing_quantity']=$opening_balance_quantity;
		$return_array[$i]['closing_rate']=$opening_balance_rate;
		
		$i++;
	} 
	
	return $return_array;
}

function getTotalDebitCreditQtyForItemIdForPeriod($item_id,$from,$to,$model=NULL)
{
	if(isset($from) && validateForNull($from))
    {
	$from = str_replace('/', '-', $from);
	$from = date('Y-m-d',strtotime($from));
	}
	
	if(isset($to) && validateForNull($to))
	{
	$to = str_replace('/', '-', $to);
	$to=date('Y-m-d',strtotime($to));
	}

	$sql="SELECT edms_ac_purchase_item.purchase_item_id as id,SUM(net_amount) as total_amount, SUM(quantity) as quantity, SUM(net_amount)/SUM(quantity) as avg_rate
			  FROM edms_ac_purchase_item, edms_ac_purchase WHERE edms_ac_purchase_item.purchase_id = edms_ac_purchase.purchase_id AND ";
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
	
	$sql=$sql." UNION ALL SELECT edms_ac_credit_note_item.credit_note_item_id as id,SUM(net_amount) as total_amount, SUM(quantity) as quantity, SUM(net_amount)/SUM(quantity) as avg_rate
			  FROM edms_ac_credit_note_item, edms_ac_credit_note WHERE edms_ac_credit_note_item.credit_note_id = edms_ac_credit_note.credit_note_id AND ";
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
	
	if(!$model)
	{
		$sql=$sql." UNION ALL SELECT edms_inventory_item_jv.item_id as id,SUM(amount) as total_amount, SUM(quantity) as quantity, SUM(amount)/SUM(quantity) as avg_rate
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
		
	}
	$total_debit_qty=0;
	
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	if(dbNumRows($result)>0)
	{
		foreach($resultArray as $re)
		{
			$quantity = $re['quantity'];
			$total_debit_qty =  $total_debit_qty + $quantity;
		}
	}
	
	
	$sql="SELECT edms_ac_sales_item.sales_item_id as id,SUM(net_amount) as total_amount, SUM(quantity) as quantity, SUM(net_amount)/SUM(quantity) as avg_rate
			  FROM edms_ac_sales_item, edms_ac_sales WHERE edms_ac_sales_item.sales_id = edms_ac_sales.sales_id AND ";
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
	
	$sql=$sql." UNION ALL SELECT edms_ac_debit_note_item.debit_note_item_id as id,SUM(net_amount) as total_amount, SUM(quantity) as quantity, SUM(net_amount)/SUM(quantity) as avg_rate
			  FROM edms_ac_debit_note_item, edms_ac_debit_note WHERE edms_ac_debit_note_item.debit_note_id = edms_ac_debit_note.debit_note_id AND ";
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
	
	if(!$model)
	{
		$sql=$sql." UNION ALL SELECT edms_inventory_item_jv.item_id as id,SUM(amount) as total_amount, SUM(quantity) as quantity, SUM(amount)/SUM(quantity) as avg_rate
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
		
	}
	$total_credit_qty=0;
	
	$result=dbQuery($sql);
	$resultArray=dbResultToArray($result);
	
	if(dbNumRows($result)>0)
	{
		foreach($resultArray as $re)
		{
			$quantity = $re['quantity'];
			$total_credit_qty =  $total_credit_qty + $quantity;
		}
	} 	
	
	return array($total_debit_qty,$total_credit_qty);
}
?>